/**
 * public/js/eklaim/grouping-inacbg.js
 * Grouping INA-CBG stage 1 & 2, special CMG selection
 *
 * Bug fixes applied:
 *   #5 — event listener dropdown dipasang SETELAH html dirender ke DOM
 *   #6 — total klaim dihitung dari baseTariff, bukan append ke nilai lama
 */
(function ($) {
    'use strict';

    const BASE_URL  = window.baseUrl  || '';
    const NOMOR_SEP = window.nomorSep || '';

    let baseTariff = 0;
    let selectedSpecialCmgs = {
        specialProcedure:     { code: '', tariff: 0 },
        specialProsthesis:    { code: '', tariff: 0 },
        specialInvestigation: { code: '', tariff: 0 },
        specialDrug:          { code: '', tariff: 0 }
    };

    function recalcTotal() {
        return baseTariff + Object.values(selectedSpecialCmgs).reduce((acc, c) => acc + (parseInt(c.tariff) || 0), 0);
    }

    // ---- Render hasil grouping ----
    function renderGroupingResult(response_inacbg) {
        // Reset special CMG state
        selectedSpecialCmgs = { specialProcedure: { code:'', tariff:0 }, specialProsthesis: { code:'', tariff:0 }, specialInvestigation: { code:'', tariff:0 }, specialDrug: { code:'', tariff:0 } };

        const ri              = response_inacbg.response_inacbg;
        const cbg             = ri.cbg || {};
        const specialCmgOpts  = response_inacbg.special_cmg_option || [];
        const specialCmgActive = ri.special_cmg || [];
        const disabled         = (typeof window.isFinalInacbg !== 'undefined' && window.isFinalInacbg) ? 'disabled' : '';

        // BUG #6 FIX: simpan baseTariff dari server supaya total selalu di-reset dari nilai ini
        baseTariff = parseInt(ri.tariff || 0);

        // Pre-populate selected special CMG dari data aktif server
        const typeMap = { specialProcedure: 'Special Procedure', specialProsthesis: 'Special Prosthesis', specialInvestigation: 'Special Investigation', specialDrug: 'Special Drug' };
        Object.keys(typeMap).forEach(key => {
            specialCmgOpts.filter(o => o.type === typeMap[key]).forEach(option => {
                const match = specialCmgActive.find(c => c.description.toLowerCase() === option.description.toLowerCase());
                if (match) selectedSpecialCmgs[key] = { code: match.code, tariff: parseInt(match.tariff) || 0 };
            });
        });

        function buildOptions(typeLabel) {
            return specialCmgOpts.filter(o => o.type === typeLabel).map(o => {
                const match    = specialCmgActive.find(c => c.description.toLowerCase() === o.description.toLowerCase());
                const selected = match ? 'selected' : '';
                return `<option value="${o.code}" data-tarif="${o.tariff}" ${selected}>${o.description}</option>`;
            }).join('');
        }

        const html = `
        <div class="card shadow-sm border p-3">
            <h5 class="mb-3" style="font-weight:600;">Hasil Grouping INA-CBG</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <tr><td style="font-weight:bold" class="w-25">Info</td><td colspan="3">${ri.inacbg_version}</td></tr>
                    <tr><td style="font-weight:bold">Kelas Rawat</td><td colspan="3">${ri.kelas}</td></tr>
                    <tr>
                        <td style="font-weight:bold">Group</td>
                        <td>${cbg.description || 'N/A'}</td>
                        <td>${cbg.code || 'N/A'}</td>
                        <td>Rp ${parseInt(ri.base_tariff || 0).toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Special Procedure</td>
                        <td><select class="form-control" id="specialProcedureSelect" ${disabled}><option value="None">None</option>${buildOptions('Special Procedure')}</select></td>
                        <td id="specialProcedureCode">${selectedSpecialCmgs.specialProcedure.code}</td>
                        <td id="specialProcedurePrice">Rp ${selectedSpecialCmgs.specialProcedure.tariff.toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Special Prosthesis</td>
                        <td><select class="form-control" id="specialProsthesisSelect" ${disabled}><option value="None">None</option>${buildOptions('Special Prosthesis')}</select></td>
                        <td id="specialProsthesisCode">${selectedSpecialCmgs.specialProsthesis.code}</td>
                        <td id="specialProsthesisPrice">Rp ${selectedSpecialCmgs.specialProsthesis.tariff.toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Special Investigation</td>
                        <td><select class="form-control" id="specialInvestigationSelect" ${disabled}><option value="None">None</option>${buildOptions('Special Investigation')}</select></td>
                        <td id="specialInvestigationCode">${selectedSpecialCmgs.specialInvestigation.code}</td>
                        <td id="specialInvestigationPrice">Rp ${selectedSpecialCmgs.specialInvestigation.tariff.toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Special Drug</td>
                        <td><select class="form-control" id="specialDrugSelect" ${disabled}><option value="None">None</option>${buildOptions('Special Drug')}</select></td>
                        <td id="specialDrugCode">${selectedSpecialCmgs.specialDrug.code}</td>
                        <td id="specialDrugPrice">Rp ${selectedSpecialCmgs.specialDrug.tariff.toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold">Total Klaim</td>
                        <td></td><td></td>
                        <td id="totalKlaim">Rp ${recalcTotal().toLocaleString('id-ID')}</td>
                    </tr>
                </table>
            </div>
        </div>`;

        $('#groupingInacbgResult').html(html);

        // BUG #5 FIX: pasang event listener SETELAH html dirender, bukan sebelumnya
        ['specialProcedure', 'specialProsthesis', 'specialInvestigation', 'specialDrug'].forEach(type => {
            const el = document.getElementById(type + 'Select');
            if (el) {
                el.addEventListener('change', function () {
                    updateSelectedCmg(type);
                    sendGroupingStage2Request();
                });
            }
        });
    }

    // BUG #6 FIX: total selalu dihitung ulang dari baseTariff
    function updateSelectedCmg(type) {
        const sel    = document.getElementById(type + 'Select');
        if (!sel) return;
        const option = sel.options[sel.selectedIndex];
        const code   = option.value;
        const tariff = parseInt(option.getAttribute('data-tarif')) || 0;

        selectedSpecialCmgs[type] = { code: code === 'None' ? '' : code, tariff: code === 'None' ? 0 : tariff };

        const codeEl  = document.getElementById(type + 'Code');
        const priceEl = document.getElementById(type + 'Price');
        if (codeEl)  codeEl.innerHTML  = selectedSpecialCmgs[type].code;
        if (priceEl) priceEl.innerHTML = 'Rp ' + selectedSpecialCmgs[type].tariff.toLocaleString('id-ID');

        const totalEl = document.getElementById('totalKlaim');
        if (totalEl) totalEl.innerHTML = 'Rp ' + recalcTotal().toLocaleString('id-ID');
    }

    function sendGroupingStage2Request() {
        const cmgParts  = ['specialProcedure', 'specialProsthesis', 'specialInvestigation', 'specialDrug']
            .map(type => selectedSpecialCmgs[type].code)
            .filter(code => code && code !== 'None');
        const specialCmg = cmgParts.join('#');

        $.ajax({
            url: BASE_URL + '/api/eklaim/grouping-inacbg-stage-2', type: 'POST',
            headers: { 'X-CSRF-TOKEN': window.csrfToken }, contentType: 'application/json',
            data: JSON.stringify({ metadata: { method: 'grouper', stage: '2', grouper: 'inacbg', special_cmg: specialCmg }, data: { nomor_sep: NOMOR_SEP, special_cmg: specialCmg } }),
            success: function (response) {
                if (response && response.response_inacbg) {
                    $.post(BASE_URL + '/save-grouping-inacbg-stage2-log', { _token: window.csrfToken, nomor_sep: NOMOR_SEP, response_inacbg_stage2: JSON.stringify(response) }, () => location.reload());
                }
            },
            error: err => console.error('Error Stage 2:', err.responseJSON || err)
        });
    }

    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window.csrfToken } });

        // Load existing stage data
        const stage2 = window.existingStage2;
        const stage1 = window.existingStage1;
        if (stage2) {
            try { renderGroupingResult(typeof stage2 === 'string' ? JSON.parse(stage2) : stage2); } catch (e) { console.warn('Parse stage2 error:', e); }
        } else if (stage1) {
            try { renderGroupingResult(typeof stage1 === 'string' ? JSON.parse(stage1) : stage1); } catch (e) { console.warn('Parse stage1 error:', e); }
        }

        // Tombol grouping stage 1
        $('#btnGroupingInacbg').on('click', function () {
            $(this).prop('disabled', true).text('Processing...');
            $.ajax({
                url: BASE_URL + '/api/eklaim/grouping-inacbg-stage-1', type: 'POST',
                headers: { 'X-CSRF-TOKEN': window.csrfToken }, contentType: 'application/json',
                data: JSON.stringify({ metadata: { method: 'grouper', stage: '1', grouper: 'inacbg' }, data: { nomor_sep: NOMOR_SEP } }),
                success: function (response) {
                    $('#btnGroupingInacbg').prop('disabled', false).text('Grouping INA-CBG');
                    if (response.response_inacbg) {
                        renderGroupingResult(response);
                        $.post(BASE_URL + '/save-grouping-inacbg-stage1-log', { _token: window.csrfToken, nomor_sep: NOMOR_SEP, response_inacbg_stage1: JSON.stringify(response) }, () => location.reload());
                    } else {
                        $('#groupingInacbgResult').html('<div class="alert alert-danger">Tidak ada hasil grouping.</div>');
                    }
                },
                error: function () {
                    $('#btnGroupingInacbg').prop('disabled', false).text('Grouping INA-CBG');
                    alert('Terjadi kesalahan pada proses Grouping INA-CBG.');
                }
            });
        });
    });

})(jQuery);
