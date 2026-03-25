{{-- resources/views/inacbg/partials/klaim-form-tarif.blade.php --}}
@php
    $tarifLog        = is_string(optional($log)->tarif_rs) ? json_decode(optional($log)->tarif_rs, true) : (optional($log)->tarif_rs ?? []);
    $totalBhpGabungan = (optional($obatbhpalkes)['total_bhp'] ?? 0) + (optional($rekap)['Bmhp'] ?? 0);
@endphp

<h5 class="mt-4">💰 Tarif RS</h5>
<div class="row">
    <div class="row ml-2 mr-2">
        <div class="col-md-3">
            <label>Prosedur Non Bedah</label>
            <input type="text" name="tarif_rs[prosedur_non_bedah]"
                value="{{ $tarifLog['prosedur_non_bedah'] ?? ($rekap['Prosedur_non_bedah'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Prosedur Bedah</label>
            <input type="text" name="tarif_rs[prosedur_bedah]"
                value="{{ $tarifLog['prosedur_bedah'] ?? ($rekap['Prosedur_bedah'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Konsultasi</label>
            <input type="text" name="tarif_rs[konsultasi]"
                value="{{ $tarifLog['konsultasi'] ?? ($rekap['Konsultasi'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Tenaga Ahli</label>
            <input type="text" name="tarif_rs[tenaga_ahli]"
                value="{{ $tarifLog['tenaga_ahli'] ?? ($rekap['Tenaga_ahli'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Keperawatan</label>
            <input type="text" name="tarif_rs[keperawatan]"
                value="{{ $tarifLog['keperawatan'] ?? ($rekap['Keperawatan'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Penunjang</label>
            <input type="text" name="tarif_rs[penunjang]"
                value="{{ $tarifLog['penunjang'] ?? ($rekap['Penunjang'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Radiologi</label>
            <input type="text" name="tarif_rs[radiologi]"
                value="{{ $tarifLog['radiologi'] ?? ($rekap['Radiologi'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Laboratorium</label>
            <input type="text" name="tarif_rs[laboratorium]"
                value="{{ $tarifLog['laboratorium'] ?? ($rekap['Laboratorium'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Pelayanan Darah</label>
            <input type="text" name="tarif_rs[pelayanan_darah]"
                value="{{ $tarifLog['pelayanan_darah'] ?? 0 }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Rehabilitasi</label>
            <input type="text" name="tarif_rs[rehabilitasi]"
                value="{{ $tarifLog['rehabilitasi'] ?? 0 }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Kamar</label>
            <input type="text" name="tarif_rs[kamar]"
                value="{{ $tarifLog['kamar'] ?? ($totalKamar ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Rawat Intensif</label>
            <input type="text" name="tarif_rs[rawat_intensif]"
                value="{{ $tarifLog['rawat_intensif'] ?? 0 }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Obat</label>
            <input type="text" name="tarif_rs[obat]"
                value="{{ $tarifLog['obat'] ?? ($obatbhpalkes['total_obat'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Obat Kronis</label>
            <input type="text" name="tarif_rs[obat_kronis]"
                value="{{ $tarifLog['obat_kronis'] ?? 0 }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Obat Kemoterapi</label>
            <input type="text" name="tarif_rs[obat_kemoterapi]"
                value="{{ $tarifLog['obat_kemoterapi'] ?? 0 }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Alkes</label>
            <input type="text" name="tarif_rs[alkes]"
                value="{{ $tarifLog['alkes'] ?? ($obatbhpalkes['total_alkes'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>BMHP</label>
            <input type="text" name="tarif_rs[bmhp]"
                value="{{ $tarifLog['bmhp'] ?? $totalBhpGabungan }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Sewa Alat</label>
            <input type="text" name="tarif_rs[sewa_alat]"
                value="{{ $tarifLog['sewa_alat'] ?? ($rekap['sewa_alat'] ?? 0) }}"
                class="form-control rupiah">
        </div>
        <div class="col-md-3">
            <label>Tarif Poli Eks</label>
            <input type="text" name="tarif_rs[tarif_poli_eks]"
                value="{{ $tarifLog['tarif_poli_eks'] ?? 0 }}"
                class="form-control rupiah">
        </div>
    </div>

    <div class="col-md-3 mt-2">
        <label><strong>Total Semua Tarif RS</strong></label>
        <input type="text" id="total_semua_tarif" class="form-control rupiah" value="0" readonly>
    </div>
</div>
