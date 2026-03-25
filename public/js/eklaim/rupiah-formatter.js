/**
 * public/js/eklaim/rupiah-formatter.js
 * Formatter input currency rupiah + kalkulasi total tarif RS
 */
(function () {
    'use strict';

    function parseRupiah(str) {
        return parseInt(str.replace(/[^\d]/g, '')) || 0;
    }

    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '';
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function hitungTotal() {
        const inputs   = document.querySelectorAll('.rupiah:not(#total_semua_tarif)');
        const totalEl  = document.getElementById('total_semua_tarif');
        if (!totalEl) return;
        let total = 0;
        inputs.forEach(i => { total += parseRupiah(i.value); });
        totalEl.value = formatRupiah(total);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const inputs  = document.querySelectorAll('.rupiah');
        const form    = document.getElementById('form-claim');

        // Format semua input saat load
        inputs.forEach(function (input) {
            if (input.id !== 'total_semua_tarif') {
                input.value = formatRupiah(parseRupiah(input.value));
            }
            input.addEventListener('input', function () {
                this.value = formatRupiah(parseRupiah(this.value));
                hitungTotal();
            });
        });

        hitungTotal();

        // BUG #7 FIX: satu submit handler, bukan N handler (satu per input)
        if (form) {
            form.addEventListener('submit', function () {
                inputs.forEach(function (i) {
                    i.value = parseRupiah(i.value).toString();
                });
            });
        }
    });
})();
