{{-- resources/views/inacbg/partials/klaim-form-lainlain.blade.php --}}
<h5 class="mt-4">📋 Lain-lain</h5>
<div class="row mt-3">
    <div class="col-md-3">
        <label>Episodes</label>
        <input type="text" name="episodes"
            value="{{ optional($log)->episodes ?? '{}' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Payor ID</label>
        <input type="text" name="payor_id"
            value="{{ optional($log)->payor_id ?? '3' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Payor Code</label>
        <input type="text" name="payor_cd"
            value="{{ optional($log)->payor_cd ?? 'JKN' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Kode Tarif</label>
        <input type="text" name="kode_tarif" value="AP" class="form-control" readonly>
    </div>
    <div class="col-md-3">
        <label>Coder NIK</label>
        <input type="text" name="coder_nik"
            value="{{ optional($log)->coder_nik ?? (optional($coder)->no_ktp ?? '') }}"
            class="form-control">
    </div>
</div>
