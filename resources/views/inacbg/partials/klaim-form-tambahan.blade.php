{{-- resources/views/inacbg/partials/klaim-form-tambahan.blade.php --}}
<h5 class="mt-4">🧮 Data Tambahan</h5>
<div class="row">
    <div class="col-md-3">
        <label>ADL Sub Acute</label>
        <input type="number" name="adl_sub_acute"
            value="{{ optional($log)->adl_sub_acute ?? '0' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>ADL Chronic</label>
        <input type="number" name="adl_chronic"
            value="{{ optional($log)->adl_chronic ?? '0' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>ICU Indikator</label>
        {{-- BUG #1 FIX: == 0 dan == 1, bukan truthy --}}
        <select name="icu_indikator" class="form-control">
            <option value="0" {{ optional($log)->icu_indikator == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->icu_indikator == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>ICU Lama Rawat (LOS)</label>
        <input type="number" name="icu_los"
            value="{{ optional($log)->icu_los ?? '0' }}" class="form-control">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <label>Upgrade Class</label>
        <select name="upgrade_class_ind" class="form-control">
            <option value="0" {{ optional($log)->upgrade_class_ind == 0 || optional($sep)->klsnaik == null ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->upgrade_class_ind == 1 || optional($sep)->klsnaik != null ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Lama Hari Naik Kelas</label>
        <input type="number" name="upgrade_class_los"
            value="{{ optional($log)->upgrade_class_los ?? (optional($sep)->klsnaik ?? '0') }}"
            class="form-control">
    </div>
    <div class="col-md-3">
        <label>Persentase Biaya Tambahan</label>
        <input type="number" name="add_payment_pct"
            value="{{ optional($log)->add_payment_pct ?? '0' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Berat Lahir (gram)</label>
        <input type="number" name="birth_weight"
            value="{{ optional($log)->birth_weight ?? (optional($bayi)->berat_lahir ?? '0') }}"
            class="form-control">
    </div>
</div>

{{-- Tekanan Darah --}}
@php $tensi = explode('/', optional($pemeriksaan)->tensi ?? '/'); @endphp
<div class="row mt-3">
    <div class="col-md-2">
        <label>Sistole</label>
        <input type="number" name="sistole"
            value="{{ optional($log)->sistole ?? ($tensi[0] ?? '0') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label>Diastole</label>
        <input type="number" name="diastole"
            value="{{ optional($log)->diastole ?? ($tensi[1] ?? '0') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Dializer Single Use</label>
        <select name="dializer_single_use" class="form-control">
            <option value="0" {{ optional($log)->dializer_single_use == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->dializer_single_use == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Pasien TB</label>
        <select name="tb_indikator" class="form-control">
            <option value="0" {{ optional($log)->tb_indikator == 0 ? 'selected' : '' }}>Bukan TB</option>
            <option value="1" {{ optional($log)->tb_indikator == 1 ? 'selected' : '' }}>Pasien TB</option>
        </select>
    </div>
</div>

{{-- COVID --}}
<h5 class="mt-4">🦠 Data Covid</h5>
<div class="row">
    <div class="col-md-3">
        <label>Pemulasaraan Jenazah</label>
        <select name="pemulasaraan_jenazah" class="form-control">
            <option value="0" {{ optional($log)->pemulasaraan_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->pemulasaraan_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Kantong Jenazah</label>
        <select name="kantong_jenazah" class="form-control">
            <option value="0" {{ optional($log)->kantong_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->kantong_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Peti Jenazah</label>
        <select name="peti_jenazah" class="form-control">
            <option value="0" {{ optional($log)->peti_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->peti_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Desinfektan Jenazah</label>
        <select name="desinfektan_jenazah" class="form-control">
            <option value="0" {{ optional($log)->desinfektan_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->desinfektan_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <label>Mobil Jenazah</label>
        <select name="mobil_jenazah" class="form-control">
            <option value="0" {{ optional($log)->mobil_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->mobil_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Desinfektan Mobil Jenazah</label>
        <select name="desinfektan_mobil_jenazah" class="form-control">
            <option value="0" {{ optional($log)->desinfektan_mobil_jenazah == 0 ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ optional($log)->desinfektan_mobil_jenazah == 1 ? 'selected' : '' }}>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Status COVID</label>
        <select name="covid19_status_cd" class="form-control">
            <option value="">Pilih Status</option>
            <option value="1" {{ optional($log)->covid19_status_cd == 1 ? 'selected' : '' }}>ODP</option>
            <option value="2" {{ optional($log)->covid19_status_cd == 2 ? 'selected' : '' }}>PDP</option>
            <option value="3" {{ optional($log)->covid19_status_cd == 3 ? 'selected' : '' }}>Terkonfirmasi</option>
            <option value="4" {{ optional($log)->covid19_status_cd == 4 ? 'selected' : '' }}>Suspek</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Nomor Kartu T</label>
        <select name="nomor_kartu_t" class="form-control">
            <option value="nik"    {{ optional($log)->nomor_kartu_t == 'nik'    ? 'selected' : '' }}>NIK</option>
            <option value="paspor" {{ optional($log)->nomor_kartu_t == 'paspor' ? 'selected' : '' }}>Paspor</option>
        </select>
    </div>
</div>
