/**
 * public/js/eklaim/klaim-actions.js
 * Optimasi:
 * 1. Fungsi swal() helper — tidak tulis ulang config berulang
 * 2. ajaxPost() helper — tidak tulis ulang $.ajax berulang
 * 3. Tombol re-enable otomatis jika request gagal
 * 4. Konfirmasi sebelum aksi destruktif (re-edit, hapus) konsisten pakai Swal
 * 5. Import INACBG: cek status_cd sebelum proses
 */
(function ($) {
    'use strict';

    const BASE_URL  = window.baseUrl  || '';
    const NOMOR_SEP = window.nomorSep || '';
    const CODER_NIK = window.coderNik || '';

    // ── Helper: loading swal ──
    function swalLoading(text) {
        Swal.fire({ title: 'Memproses...', text: text || '', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    }

    function toast(icon, title, text) {
        Swal.fire({ icon, title, text: text || '', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    }

    // ── Helper: ajax POST JSON ──
    function ajaxPost(url, data, onSuccess, onError) {
        return $.ajax({
            url: BASE_URL + url, type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data)
        })
        .done(onSuccess)
        .fail(onError || function (xhr) {
            Swal.close();
            toast('error', 'Koneksi gagal', xhr.responseJSON?.message || 'Tidak dapat terhubung ke server.');
        });
    }

    // ── Helper: simpan log ke DB ──
    function saveLog(field, value, callback) {
        $.post(BASE_URL + '/idrg/update-log', {
            _token: window.csrfToken, nomor_sep: NOMOR_SEP, field, value
        }).done(callback || function () {});
    }

    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window.csrfToken } });

        // Tampilkan tombol send jika sudah ada claim final
        if (window.hasClaimFinal) $('#btnSendClaim').show();

        // ================================================================
        // PRINT CLAIM
        // ================================================================
        $('#btnPrintClaim').on('click', function () {
            const sep = $(this).data('sep');
            if (!sep) return toast('warning', 'Nomor SEP tidak ditemukan');
            const btn = $(this).prop('disabled', true).text('Mencetak...');
            $.ajax({
                url: BASE_URL + '/api/eklaim/claim-print', method: 'POST',
                xhrFields: { responseType: 'blob' },
                data: { 'metadata[method]': 'claim_print', 'data[nomor_sep]': sep },
                success: blob => window.open(URL.createObjectURL(blob), '_blank'),
                error:   ()   => toast('error', 'Gagal mencetak klaim'),
                complete: ()  => btn.prop('disabled', false).html('<i class="fa fa-print"></i> Print Claim')
            });
        });

        // ================================================================
        // GROUPING IDRG
        // ================================================================
        $('#btnGroupingIdrg').on('click', function () {
            const btn = $(this).prop('disabled', true);
            swalLoading('Memproses Grouping IDRG...');
            ajaxPost('/grouping-idrg',
                { metadata: { method: 'grouper', stage: '1', grouper: 'idrg' }, data: { nomor_sep: NOMOR_SEP } },
                function (response) {
                    $.post(BASE_URL + '/save-grouping-idrg-log', {
                        _token: window.csrfToken, nomor_sep: NOMOR_SEP,
                        response_grouping_idrg: JSON.stringify(response)
                    }).done(() => {
                        Swal.fire({ icon: 'success', title: 'Grouping IDRG Berhasil', timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    });
                },
                function (xhr) { Swal.close(); btn.prop('disabled', false); toast('error', 'Grouping gagal', xhr.responseJSON?.message); }
            );
        });

        // ================================================================
        // FINAL IDRG
        // ================================================================
        $(document).on('click', '#btnFinalIdrg', function () {
            const btn = $(this).prop('disabled', true);
            // Cek apakah grouping sudah valid sebelum finalisasi
            ajaxPost('/api/eklaim/get-claim-data',
                { metadata: { method: 'get_claim_data' }, data: { nomor_sep: NOMOR_SEP } },
                function (response) {
                    const idrg = response.response?.data?.grouper?.response_idrg || {};
                    if (!idrg.mdc_number || !idrg.drg_code) {
                        btn.prop('disabled', false);
                        $.post(BASE_URL + '/delete-response-grouping-idrg', { nomor_sep: NOMOR_SEP });
                        return toast('warning', 'IDRG belum di-grouping', 'Lakukan grouping terlebih dahulu.');
                    }
                    swalLoading('Memproses Final IDRG...');
                    ajaxPost('/final-idrg',
                        { metadata: { method: 'idrg_grouper_final' }, data: { nomor_sep: NOMOR_SEP } },
                        function (res) {
                            if (res.metadata?.code !== 200) {
                                btn.prop('disabled', false); Swal.close();
                                return toast('error', 'Final IDRG gagal', res.metadata?.message);
                            }
                            $.post(BASE_URL + '/save-final-idrg-log', {
                                _token: window.csrfToken, nomor_sep: NOMOR_SEP,
                                response_idrg_grouper_final: JSON.stringify(res)
                            }).done(() => {
                                Swal.fire({ icon: 'success', title: 'Final IDRG Berhasil!', timer: 1500, showConfirmButton: false })
                                    .then(() => location.reload());
                            });
                        },
                        function (xhr) { Swal.close(); btn.prop('disabled', false); toast('error', 'Final IDRG gagal', xhr.responseJSON?.message); }
                    );
                }
            );
        });

        // ================================================================
        // RE-EDIT IDRG
        // ================================================================
        $('#btnReeditIdrg').on('click', function () {
            Swal.fire({ title: 'Re-edit IDRG?', text: 'Hasil Final IDRG akan dihapus.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
            .then(result => {
                if (!result.isConfirmed) return;
                swalLoading('Memproses re-edit...');
                ajaxPost('/idrg-grouper-reedit',
                    { metadata: { method: 'idrg_grouper_reedit' }, data: { nomor_sep: NOMOR_SEP } },
                    function (response) {
                        if (response.metadata?.code != 200) {
                            Swal.close(); return toast('error', 'Re-edit gagal', response.metadata?.message);
                        }
                        $.post(BASE_URL + '/hapus-final-idrg', { _token: window.csrfToken, nomor_sep: NOMOR_SEP })
                            .done(() => { Swal.fire({ icon: 'success', title: 'Re-edit Berhasil', timer: 1500, showConfirmButton: false }).then(() => location.reload()); });
                    }
                );
            });
        });

        // ================================================================
        // IMPORT INACBG
        // ================================================================
        $('#btnImportInacbg').on('click', function () {
            const btn = $(this).prop('disabled', true);
            // Cek status IDRG final sebelum import
            ajaxPost('/api/eklaim/get-claim-data',
                { metadata: { method: 'get_claim_data' }, data: { nomor_sep: NOMOR_SEP } },
                function (response) {
                    const idrg = response.response?.data?.grouper?.response_idrg || {};
                    if (idrg.status_cd === 'normal' || !idrg.mdc_number || !idrg.drg_code) {
                        btn.prop('disabled', false);
                        const msg = idrg.status_cd === 'normal' ? 'IDRG belum di-finalkan' : 'IDRG belum di-grouping';
                        return toast('warning', msg, 'Selesaikan proses IDRG terlebih dahulu.');
                    }
                    Swal.fire({ title: 'Import iDRG → INA-CBG?', text: 'Pastikan Final IDRG sudah benar.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Lanjutkan', cancelButtonText: 'Batal' })
                    .then(result => {
                        if (!result.isConfirmed) { btn.prop('disabled', false); return; }
                        swalLoading('Mengimpor data...');
                        ajaxPost('/api/eklaim/idrg-to-inacbg-import',
                            { metadata: { method: 'idrg_to_inacbg_import' }, data: { nomor_sep: NOMOR_SEP } },
                            function (res) {
                                if (res.metadata?.code !== 200) {
                                    btn.prop('disabled', false); Swal.close();
                                    return toast('error', 'Import gagal', res.metadata?.message);
                                }
                                swalLoading('Menyimpan hasil import...');
                                $.ajax({
                                    url: BASE_URL + '/inacbg/import/save-log', type: 'POST',
                                    data: { nomor_sep: NOMOR_SEP, response_inacbg_import: JSON.stringify(res) }
                                }).done(() => {
                                    Swal.fire({ icon: 'success', title: 'Import Berhasil!', timer: 1500, showConfirmButton: false })
                                        .then(() => location.reload());
                                }).fail(() => { btn.prop('disabled', false); toast('error', 'Gagal simpan log import'); });
                            },
                            function () { btn.prop('disabled', false); }
                        );
                    });
                },
                function () { btn.prop('disabled', false); toast('error', 'Gagal mengambil data klaim'); }
            );
        });

        // ================================================================
        // GROUPING INACBG — ditangani di grouping-inacbg.js
        // FINAL INACBG
        // ================================================================
        $(document).on('click', '#btnFinalInacbg', function () {
            const btn      = $(this).prop('disabled', true).text('Mengirim...');
            const nomorSep = btn.data('nomor-sep') || NOMOR_SEP;
            swalLoading('Mengirim Final INA-CBG...');
            $.ajax({
                url: BASE_URL + '/api/eklaim/final-inacbg', type: 'POST',
                data: { _token: window.csrfToken, metadata: { method: 'inacbg_grouper_final' }, data: { nomor_sep: nomorSep } }
            })
            .done(response => {
                $.post(BASE_URL + '/save-final-inacbg-log', {
                    _token: window.csrfToken, nomor_sep: nomorSep,
                    response_inacbg_final: JSON.stringify(response)
                }).done(() => {
                    Swal.fire({ icon: 'success', title: 'Final INA-CBG Berhasil!', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                });
            })
            .fail(xhr => { Swal.close(); btn.prop('disabled', false).text('Grouping Final INA-CBG'); toast('error', 'Gagal Final INA-CBG', xhr.responseJSON?.message); });
        });

        // ================================================================
        // RE-EDIT INACBG
        // ================================================================
        $('#btnReeditInacbg').on('click', function () {
            const btn = $(this).prop('disabled', true).text('Memproses...');
            $.post(BASE_URL + '/grouping-inacbg-reedit-final', { _token: window.csrfToken, nomor_sep: NOMOR_SEP })
                .done(res => {
                    if (res.status === 'success') {
                        toast('success', 'Re-edit berhasil');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        btn.prop('disabled', false).text('Re-edit INA-CBG');
                        toast('error', res.message || 'Re-edit gagal');
                    }
                })
                .fail(() => { btn.prop('disabled', false).text('Re-edit INA-CBG'); toast('error', 'Koneksi gagal'); });
        });

        // ================================================================
        // CLAIM FINAL INA-CBG
        // ================================================================
        $('#btnClaimFinal').on('click', function () {
            const btn = $(this);
            Swal.fire({ title: 'Kirim Claim Final?', text: 'Pastikan semua data sudah benar.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, kirim', cancelButtonText: 'Batal' })
            .then(result => {
                if (!result.isConfirmed) return;
                btn.prop('disabled', true);
                swalLoading('Mengirim Claim Final...');
                ajaxPost('/api/eklaim/claim-final',
                    { metadata: { method: 'claim_final' }, data: { nomor_sep: NOMOR_SEP, coder_nik: CODER_NIK } },
                    function (response) {
                        if (response.metadata?.code !== 200) {
                            btn.prop('disabled', false); Swal.close();
                            return toast('error', 'Claim Final gagal', response.metadata?.message);
                        }
                        $('#cardClaimFinal').show();
                        $('#resultClaimFinal').text(JSON.stringify(response, null, 2));
                        $.post(BASE_URL + '/save-claim-final-log', {
                            _token: window.csrfToken, nomor_sep: NOMOR_SEP,
                            response_claim_final: JSON.stringify(response)
                        }).done(() => {
                            Swal.fire({ icon: 'success', title: 'Claim Final Berhasil!', timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                        }).fail(() => toast('warning', 'Claim final berhasil, tapi gagal simpan log'));
                    },
                    function () { btn.prop('disabled', false); }
                );
            });
        });

        // ================================================================
        // RE-EDIT CLAIM
        // ================================================================
        $('#btnReeditClaim').on('click', function () {
            Swal.fire({ title: 'Re-edit Claim?', text: 'Hasil Claim Final akan dihapus.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
            .then(result => {
                if (!result.isConfirmed) return;
                swalLoading('Memproses re-edit...');
                $.post(BASE_URL + '/reedit-claim', { _token: window.csrfToken, nomor_sep: NOMOR_SEP })
                    .done(res => {
                        Swal.close();
                        if (res.status === 'success') {
                            toast('success', 'Re-edit berhasil');
                            setTimeout(() => location.reload(), 1000);
                        } else toast('error', res.message || 'Re-edit gagal');
                    })
                    .fail(() => { Swal.close(); toast('error', 'Koneksi gagal'); });
            });
        });

        // ================================================================
        // KIRIM CLAIM INDIVIDUAL
        // ================================================================
        $('#btnSendClaim').on('click', function () {
            const btn = $(this);
            Swal.fire({ title: 'Kirim Claim ke e-Klaim?', text: 'Pastikan Claim Final sudah benar.', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Kirim', cancelButtonText: 'Batal' })
            .then(result => {
                if (!result.isConfirmed) return;
                btn.prop('disabled', true);
                swalLoading('Mengirim klaim...');
                ajaxPost('/api/eklaim/claim-send',
                    { metadata: { method: 'send_claim_individual' }, data: { nomor_sep: NOMOR_SEP } },
                    function (response) {
                        if (response.metadata?.code !== 200) {
                            btn.prop('disabled', false); Swal.close();
                            return toast('error', 'Kirim klaim gagal', response.metadata?.message);
                        }
                        swalLoading('Menyimpan log...');
                        $.post(BASE_URL + '/log/claim-send/save', {
                            _token: window.csrfToken, nomor_sep: NOMOR_SEP,
                            response_send_claim_individual: JSON.stringify(response.response)
                        })
                        .done(res => {
                            Swal.fire({ icon: 'success', title: 'Klaim Terkirim!', text: res.message || '', timer: 2000, showConfirmButton: false })
                                .then(() => location.reload());
                        })
                        .fail(() => { Swal.close(); toast('warning', 'Klaim terkirim, log gagal disimpan'); });
                    },
                    function () { btn.prop('disabled', false); }
                );
            });
        });

    });

})(jQuery);