{{-- resources/views/inacbg/partials/klaim-form-utama.blade.php --}}
<h5>🧾 Data Utama</h5>
<div class="row">
    <div class="col-md-4">
        <label>Nomor SEP</label>
        <input type="text" name="nomor_sep"
            value="{{ optional($log)->nomor_sep ?? (optional($sep)->no_sep ?? '') }}"
            class="form-control">
    </div>
    <div class="col-md-4">
        <label>Nomor Kartu</label>
        <input type="text" name="nomor_kartu"
            value="{{ optional($log)->nomor_kartu ?? (optional($pasien)->no_peserta ?? '') }}"
            class="form-control">
    </div>
    <div class="col-md-4">
        <label>Nama Dokter</label>
        <input type="text" name="nama_dokter"
            value="{{ optional($log)->nama_dokter ?? (optional($sep)->nmdpdjp ?? '') }}"
            class="form-control">
    </div>
</div>

<div class="row mt-3">
    @php
        $tglMasuk  = optional($log)->tgl_masuk  ?? ($pasien->tgl_masuk  . ' ' . $pasien->jam_masuk);
        $tglPulang = optional($log)->tgl_pulang ?? ($pasien->tgl_keluar . ' ' . $pasien->jam_keluar);
        $isReadonlyTgl = !empty(optional($log)->tgl_masuk) && !empty(optional($log)->tgl_pulang);
    @endphp

    <div class="col-md-4">
        <label>Tanggal Masuk</label>
        <input type="datetime-local" name="tgl_masuk" class="form-control"
            value="{{ \Carbon\Carbon::parse($tglMasuk)->format('Y-m-d\TH:i') }}"
            {{ $isReadonlyTgl ? 'readonly' : '' }}>
    </div>
    <div class="col-md-4">
        <label>Tanggal Pulang</label>
        <input type="datetime-local" name="tgl_pulang" class="form-control"
            value="{{ \Carbon\Carbon::parse($tglPulang)->format('Y-m-d\TH:i') }}"
            {{ $isReadonlyTgl ? 'readonly' : '' }}>
    </div>
    <div class="col-md-4">
        <label>Cara Masuk</label>
        @php
            $asalRujukan = optional($log)->cara_masuk ?? (optional($sep)->asal_rujukan ?? '');
            $cara_masuk  = match($asalRujukan) {
                'gp', '1. Faskes 1'      => 'gp',
                'hosp-trans', '2. Faskes 2(RS)' => 'hosp-trans',
                'mp'    => 'mp',
                'outp'  => 'outp',
                'emd'   => 'emd',
                'born'  => 'born',
                'other' => 'other',
                default => '',
            };
        @endphp
        <select name="cara_masuk" class="form-control">
            <option value="gp"         {{ $cara_masuk == 'gp'         ? 'selected' : '' }}>Rujukan FKTP</option>
            <option value="hosp-trans" {{ $cara_masuk == 'hosp-trans' ? 'selected' : '' }}>Rujukan FKRTL</option>
            <option value="mp"         {{ $cara_masuk == 'mp'         ? 'selected' : '' }}>Rujukan Spesialis</option>
            <option value="outp"       {{ $cara_masuk == 'outp'       ? 'selected' : '' }}>Dari Rawat Jalan</option>
            <option value="emd"        {{ $cara_masuk == 'emd'        ? 'selected' : '' }}>Dari IGD</option>
            <option value="born"       {{ $cara_masuk == 'born'       ? 'selected' : '' }}>Lahir di RS</option>
            <option value="other"      {{ $cara_masuk == 'other'      ? 'selected' : '' }}>Lain-lain</option>
        </select>
    </div>
</div>
