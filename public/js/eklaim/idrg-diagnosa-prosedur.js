/**
 * public/js/eklaim/idrg-diagnosa-prosedur.js
 * Optimasi:
 * 1. Debounce qty-input (600ms) — tidak kirim ke server setiap keystroke
 * 2. Request queue — abort request lama jika ada yang baru (cegah race condition)
 * 3. Toast notification ringan — tidak blokir UI
 * 4. Status badge otomatis update setelah hapus
 * 5. minimumInputLength:2 — kurangi request tidak perlu ke API ICD
 */
(function ($) {
    'use strict';

    const BASE_URL       = window.baseUrl  || '';
    const NOMOR_SEP      = window.nomorSep || '';
    const UPDATE_LOG_URL = BASE_URL + '/idrg/update-log';

    let diagnosaList     = [];
    let prosedurList     = [];
    let pendingDiagnosa  = null;
    let pendingProsedur  = null;
    let qtyDebounceTimer = null;

    function setLoading(tableId, on) {
        $(tableId).closest('.table-responsive').css('opacity', on ? '0.5' : '1');
    }

    function toast(icon, title, text) {
        Swal.fire({ icon, title, text: text || '', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
    }

    // ── Load dari log ──
    function loadFromLog() {
        if (window.logDiagnosaIdrg) {
            try {
                const p = typeof window.logDiagnosaIdrg === 'string' ? JSON.parse(window.logDiagnosaIdrg) : window.logDiagnosaIdrg;
                diagnosaList = (p.expanded || []).map((d, i) => ({ code: d.code, desc: d.display || d.description, validcode: d.validcode, accpdx: d.accpdx, status: i === 0 ? 'Primer' : 'Sekunder' }));
                renderTable(diagnosaList, '#tabel_diagnosa', true);
            } catch (e) { console.warn('Parse logDiagnosaIdrg:', e); }
        }
        if (window.logProsedurIdrg) {
            try {
                const p = typeof window.logProsedurIdrg === 'string' ? JSON.parse(window.logProsedurIdrg) : window.logProsedurIdrg;
                prosedurList = (p.expanded || []).map((d, i) => ({ code: d.code, desc: d.display || d.description, validcode: d.validcode, accpdx: d.accpdx, qty: d.multiplicity ? Number(d.multiplicity) : 1, status: i === 0 ? 'Primer' : 'Sekunder' }));
                renderTable(prosedurList, '#tabel_prosedur', false);
            } catch (e) { console.warn('Parse logProsedurIdrg:', e); }
        }
    }

    // ── Select2 ──
    function initSelect2(selector, url, tableId, isDiagnosa) {
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
            const list = isDiagnosa ? diagnosaList : prosedurList;

            if (data.validcode != 1) return toast('warning', 'Kode tidak valid');
            if (list.length === 0 && isDiagnosa && data.accpdx !== 'Y') return toast('warning', 'Tidak bisa sebagai Primer', 'Kode ini tidak dapat digunakan sebagai diagnosa primer.');
            if (list.some(d => d.code === data.code)) return toast('info', isDiagnosa ? 'Diagnosa sudah ada' : 'Prosedur sudah ada');

            list.push({ code: data.code, desc: data.description, validcode: data.validcode, accpdx: data.accpdx, status: list.length === 0 ? 'Primer' : 'Sekunder', qty: isDiagnosa ? undefined : 1 });
            if (isDiagnosa) diagnosaList = list; else prosedurList = list;
            renderTable(list, tableId, isDiagnosa);
            isDiagnosa ? syncDiagnosa() : syncProsedur();
        });
    }

    // ── Render tabel ──
    function renderTable(list, tableId, isDiagnosa) {
        const tbody  = $(tableId + ' tbody');
        const locked = window.isFinalIdrg === true;
        tbody.empty();
        if (!list.length) {
            tbody.append(`<tr><td colspan="${isDiagnosa ? 5 : 6}" class="text-center text-muted py-3">Belum ada data</td></tr>`);
            return;
        }
        list.forEach((d, i) => {
            tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td><code>${d.code}</code></td>
                    <td>${d.desc}</td>
                    ${!isDiagnosa ? `<td><input type="number" min="1" value="${d.qty}" data-code="${d.code}" data-index="${i}" class="form-control form-control-sm qty-input text-center" style="width:70px" ${locked ? 'disabled' : ''}></td>` : ''}
                    <td><span class="badge ${i === 0 ? 'badge-primary' : 'badge-secondary'}">${d.status}</span></td>
                    <td><button class="btn btn-danger btn-sm btn-hapus-idrg" data-code="${d.code}" data-index="${i}" data-is-diagnosa="${isDiagnosa}" ${locked ? 'disabled' : ''}><i class="fa fa-times"></i></button></td>
                </tr>`);
        });
    }

    // ── Hapus ──
    $(document).on('click', '.btn-hapus-idrg', function () {
        hapusItem($(this).data('code').toString(), $(this).data('is-diagnosa') === true || $(this).data('is-diagnosa') === 'true', parseInt($(this).data('index')));
    });

    function hapusItem(code, isDiagnosa, rowIndex) {
        let list = isDiagnosa ? diagnosaList : prosedurList;
        if (!list[rowIndex] || list[rowIndex].code !== code) return;

        const doDelete = () => {
            list.splice(rowIndex, 1);
            list.forEach((item, i) => { item.status = i === 0 ? 'Primer' : 'Sekunder'; });
            if (isDiagnosa) diagnosaList = list; else prosedurList = list;
            renderTable(list, isDiagnosa ? '#tabel_diagnosa' : '#tabel_prosedur', isDiagnosa);
            isDiagnosa ? syncDiagnosa() : syncProsedur();
        };

        if (list.length === 1 || rowIndex === list.length - 1 || rowIndex > 0) { doDelete(); return; }

        // Hapus index 0 diagnosa: validasi item ke-2 layak jadi primer
        $.get(BASE_URL + '/api/icd10_idrg', { q: list[1].code })
            .done(res => {
                const found = (res || []).find(r => r.code === list[1].code);
                if (!found || found.validcode != 1) return toast('warning', 'Kode berikutnya tidak valid sebagai primer');
                if (found.accpdx !== 'Y')           return toast('warning', 'Kode berikutnya tidak bisa sebagai primer');
                doDelete();
            })
            .fail(() => toast('error', 'Gagal validasi kode'));
    }

    // ── Debounce qty ──
    $(document).on('input', '#tabel_prosedur .qty-input', function () {
        const idx = parseInt($(this).data('index'));
        if (prosedurList[idx]) prosedurList[idx].qty = Math.max(parseInt($(this).val()) || 1, 1);
        clearTimeout(qtyDebounceTimer);
        qtyDebounceTimer = setTimeout(syncProsedur, 600);
    });

    // ── Sync + request queue ──
    function syncDiagnosa() {
        if (!NOMOR_SEP) return;
        if (pendingDiagnosa) pendingDiagnosa.abort();
        const payload = { metadata: { method: 'idrg_diagnosa_set', nomor_sep: NOMOR_SEP }, data: { diagnosa: diagnosaList.map(d => d.code).join('#') || '#' } };
        setLoading('#tabel_diagnosa', true);
        pendingDiagnosa = $.ajax({ url: BASE_URL + '/api/eklaim/idrg-diagnosa-set', method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => { if (res.metadata?.code === 200 || res.code === 200) saveLog('diagnosa_idrg'); })
            .fail(xhr => { if (xhr.statusText !== 'abort') toast('error', 'Gagal menyimpan diagnosa'); })
            .always(() => { setLoading('#tabel_diagnosa', false); pendingDiagnosa = null; });
    }

    function syncProsedur() {
        if (!NOMOR_SEP) return;
        if (pendingProsedur) pendingProsedur.abort();
        const payload = { metadata: { method: 'idrg_procedure_set', nomor_sep: NOMOR_SEP }, data: { procedure: prosedurList.map(p => `${p.code}+${p.qty}`).join('#') || '#' } };
        setLoading('#tabel_prosedur', true);
        pendingProsedur = $.ajax({ url: BASE_URL + '/api/eklaim/idrg-procedure-set', method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => { if (res?.metadata?.code === 200 || res?.code === 200) saveLog('procedure_idrg'); })
            .fail(xhr => { if (xhr.statusText !== 'abort') toast('error', 'Gagal menyimpan prosedur'); })
            .always(() => { setLoading('#tabel_prosedur', false); pendingProsedur = null; });
    }

    function saveLog(field) {
        const isD     = field === 'diagnosa_idrg';
        const method  = isD ? 'idrg_diagnosa_get' : 'idrg_procedure_get';
        const payload = { metadata: { method }, data: { nomor_sep: NOMOR_SEP } };
        $.ajax({ url: BASE_URL + '/api/eklaim/' + (isD ? 'idrg-diagnosa-get' : 'idrg-procedure-get'), method: 'POST', data: JSON.stringify(payload), contentType: 'application/json' })
            .done(res => {
                const val = res.data || res.response?.data || null;
                if (val) $.post(UPDATE_LOG_URL, { _token: window.csrfToken, nomor_sep: NOMOR_SEP, field, value: JSON.stringify(val) });
            });
    }

    // ── Init ──
    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window.csrfToken } });
        loadFromLog();
        initSelect2('#diagnosa_idrg', BASE_URL + '/api/icd10_idrg', '#tabel_diagnosa', true);
        initSelect2('#prosedur_idrg', BASE_URL + '/api/icd9_idrg',  '#tabel_prosedur', false);
    });

})(jQuery);