/**
 * public/js/eklaim/inacbg-diagnosa-prosedur.js
 * Optimasi sama dengan idrg-diagnosa-prosedur.js:
 * debounce, request queue, toast, badge status, minimumInputLength
 */
(function ($) {
    'use strict';

    const BASE_URL       = window.baseUrl  || '';
    const NOMOR_SEP      = window.nomorSep || '';
    const UPDATE_LOG_URL = BASE_URL + '/idrg/update-log';

    let diagnosaListInacbg = [];
    let prosedurListInacbg = [];
    let pendingDiagnosa    = null;
    let pendingProsedur    = null;

    function setLoading(tableId, on) {
        $(tableId).closest('.table-responsive').css('opacity', on ? '0.5' : '1');
    }

    function toast(icon, title, text) {
        Swal.fire({ icon, title, text: text || '', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
    }

    // ── Load dari log ──
    function loadFromLogInacbg() {
        if (window.logDiagnosaInacbg) {
            try {
                const p = typeof window.logDiagnosaInacbg === 'string' ? JSON.parse(window.logDiagnosaInacbg) : window.logDiagnosaInacbg;
                diagnosaListInacbg = (p.expanded || []).map((d, i) => ({ code: d.code, desc: d.display || d.description, validcode: d.validcode, status: i === 0 ? 'Primer' : 'Sekunder', metadata: d.metadata }));
                renderTableInacbg(diagnosaListInacbg, '#tabel_diagnosa_inacbg', true);
            } catch (e) { console.warn('Parse logDiagnosaInacbg:', e); }
        }
        if (window.logProsedurInacbg) {
            try {
                const p = typeof window.logProsedurInacbg === 'string' ? JSON.parse(window.logProsedurInacbg) : window.logProsedurInacbg;
                prosedurListInacbg = (p.expanded || []).map((d, i) => ({ code: d.code, desc: d.display || d.description, validcode: d.validcode, qty: d.multiplicity ? Number(d.multiplicity) : 1, status: i === 0 ? 'Primer' : 'Sekunder', metadata: d.metadata }));
                renderTableInacbg(prosedurListInacbg, '#tabel_prosedur_inacbg', false);
            } catch (e) { console.warn('Parse logProsedurInacbg:', e); }
        }
    }

    // ── Select2 ──
    function initSelect2Inacbg(selector, url, tableId, isDiagnosa) {
        $(selector).select2({
            placeholder: isDiagnosa ? 'Ketik min. 2 karakter...' : 'Ketik kode/nama prosedur...',
            minimumInputLength: 2,
            ajax: { url, dataType: 'json', delay: 300, data: p => ({ q: p.term }), processResults: d => ({ results: d }) },
            templateResult:    item => item.id ? $('<span>').append($('<b>').text(item.code)).append(' — ' + item.description) : item.text,
            templateSelection: item => item.text,
            multiple: false, bootstrap4: true
        });

        $(selector).on('select2:select', function (e) {
            $(selector).val(null).trigger('change');
            const data = e.params.data;
            const list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;

            if (list.length === 0 && data.validcode != 1) return toast('warning', 'Kode tidak valid');
            if (list.some(d => d.code === data.code)) return toast('info', isDiagnosa ? 'Diagnosa sudah ada' : 'Prosedur sudah ada');

            list.push({ code: data.code, desc: data.description, validcode: data.validcode, status: list.length === 0 ? 'Primer' : 'Sekunder', qty: isDiagnosa ? undefined : 1 });
            if (isDiagnosa) diagnosaListInacbg = list; else prosedurListInacbg = list;
            renderTableInacbg(list, tableId, isDiagnosa);
            isDiagnosa ? syncDiagnosaInacbg() : syncProsedurInacbg();
        });
    }

    // ── Render tabel ──
    function renderTableInacbg(list, tableId, isDiagnosa) {
        const tbody  = $(tableId + ' tbody');
        const locked = window.isFinalInacbg === true;
        tbody.empty();
        if (!list.length) {
            tbody.append(`<tr><td colspan="5" class="text-center text-muted py-3">Belum ada data</td></tr>`);
            return;
        }
        list.forEach((d, i) => {
            const alertMsg = d.metadata?.message === 'IM tidak berlaku'
                ? `<br><small class="text-danger"><i class="fa fa-exclamation-triangle"></i> ${d.metadata.message}</small>` : '';
            tbody.append(`
                <tr ${d.metadata?.message ? 'class="table-warning"' : ''}>
                    <td>${i + 1}</td>
                    <td><code>${d.code}</code></td>
                    <td>${d.desc}${alertMsg}</td>
                    <td><span class="badge ${i === 0 ? 'badge-primary' : 'badge-secondary'}">${d.status}</span></td>
                    <td><button class="btn btn-danger btn-sm btn-hapus-inacbg" data-code="${d.code}" data-index="${i}" data-is-diagnosa="${isDiagnosa}" ${locked ? 'disabled' : ''}><i class="fa fa-times"></i></button></td>
                </tr>`);
        });
    }

    // ── Hapus ──
    $(document).on('click', '.btn-hapus-inacbg', function () {
        hapusItemInacbg($(this).data('code').toString(), $(this).data('is-diagnosa') === true || $(this).data('is-diagnosa') === 'true', parseInt($(this).data('index')));
    });

    function hapusItemInacbg(code, isDiagnosa, rowIndex) {
        let list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;
        if (!list[rowIndex] || list[rowIndex].code !== code) return;

        const doDelete = () => {
            list.splice(rowIndex, 1);
            list.forEach((item, i) => { item.status = i === 0 ? 'Primer' : 'Sekunder'; });
            if (isDiagnosa) diagnosaListInacbg = list; else prosedurListInacbg = list;
            renderTableInacbg(list, isDiagnosa ? '#tabel_diagnosa_inacbg' : '#tabel_prosedur_inacbg', isDiagnosa);
            isDiagnosa ? syncDiagnosaInacbg() : syncProsedurInacbg();
        };

        if (list.length === 1 || rowIndex > 0) { doDelete(); return; }

        $.get(isDiagnosa ? BASE_URL + '/api/icd10' : BASE_URL + '/api/icd9', { q: list[1].code })
            .done(res => {
                const found = (res || []).find(r => r.code === list[1].code);
                if (!found || found.validcode != 1) return toast('warning', 'Kode berikutnya tidak valid sebagai primer');
                doDelete();
            })
            .fail(() => toast('error', 'Gagal validasi kode'));
    }

    // ── Sync + request queue ──
    function syncDiagnosaInacbg() {
        if (!NOMOR_SEP) return;
        if (pendingDiagnosa) pendingDiagnosa.abort();
        const payload = { metadata: { method: 'inacbg_diagnosa_set', nomor_sep: NOMOR_SEP }, data: { diagnosa: diagnosaListInacbg.map(d => d.code).join('#') || '#' } };
        setLoading('#tabel_diagnosa_inacbg', true);
        pendingDiagnosa = $.ajax({ url: BASE_URL + '/api/eklaim/inacbg-diagnosa-set', method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => { if (res.metadata?.code === 200 || res.code === 200) saveLogInacbg('diagnosa_inacbg'); })
            .fail(xhr => { if (xhr.statusText !== 'abort') toast('error', 'Gagal menyimpan diagnosa'); })
            .always(() => { setLoading('#tabel_diagnosa_inacbg', false); pendingDiagnosa = null; });
    }

    function syncProsedurInacbg() {
        if (!NOMOR_SEP) return;
        if (pendingProsedur) pendingProsedur.abort();
        const payload = { metadata: { method: 'inacbg_procedure_set', nomor_sep: NOMOR_SEP }, data: { procedure: prosedurListInacbg.map(p => `${p.code}+${p.qty}`).join('#') || '#' } };
        setLoading('#tabel_prosedur_inacbg', true);
        pendingProsedur = $.ajax({ url: BASE_URL + '/api/eklaim/inacbg-procedure-set', method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => { if (res?.metadata?.code === 200 || res?.code === 200) saveLogInacbg('procedure_inacbg'); })
            .fail(xhr => { if (xhr.statusText !== 'abort') toast('error', 'Gagal menyimpan prosedur'); })
            .always(() => { setLoading('#tabel_prosedur_inacbg', false); pendingProsedur = null; });
    }

    function saveLogInacbg(field) {
        const isD     = field === 'diagnosa_inacbg';
        const method  = isD ? 'inacbg_diagnosa_get' : 'inacbg_procedure_get';
        const payload = { metadata: { method }, data: { nomor_sep: NOMOR_SEP } };
        $.ajax({ url: BASE_URL + '/api/eklaim/' + (isD ? 'inacbg-diagnosa-get' : 'inacbg-procedure-get'), method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => {
                const val = res.data || res.response?.data || null;
                if (val) $.post(UPDATE_LOG_URL, { _token: window.csrfToken, nomor_sep: NOMOR_SEP, field, value: JSON.stringify(val) });
            });
    }

    // ── Init ──
    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window.csrfToken } });
        loadFromLogInacbg();
        initSelect2Inacbg('#diagnosa_inacbg', BASE_URL + '/api/icd10', '#tabel_diagnosa_inacbg', true);
        initSelect2Inacbg('#prosedur_inacbg', BASE_URL + '/api/icd9',  '#tabel_prosedur_inacbg', false);
    });

})(jQuery);