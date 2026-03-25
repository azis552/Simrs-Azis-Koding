{{-- resources/views/inacbg/partials/klaim-pasien-info.blade.php --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label>No. Registrasi</label>
        <input type="text" class="form-control" value="{{ $pasien->no_rawat }}" readonly>
    </div>
    <div class="col-md-6">
        <label>No. RM</label>
        <input type="text" class="form-control" value="{{ $pasien->no_rkm_medis }}" readonly>
    </div>
    <div class="col-md-6">
        <label>Nama Pasien</label>
        <input type="text" class="form-control" value="{{ $pasien->nm_pasien }}" readonly>
    </div>
    <div class="col-md-6">
        <label>Jenis Kelamin</label>
        <input type="text" class="form-control" value="{{ $pasien->jk }}" readonly>
    </div>
    <div class="col-md-6">
        <label>No Peserta</label>
        <input type="text" class="form-control" value="{{ $pasien->no_peserta }}" readonly>
    </div>
    <div class="col-md-6">
        <label>Tanggal Lahir</label>
        <input type="text" class="form-control" value="{{ $pasien->tgl_lahir }}" readonly>
    </div>
</div>
