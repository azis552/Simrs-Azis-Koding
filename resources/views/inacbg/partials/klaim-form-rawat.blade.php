{{-- resources/views/inacbg/partials/klaim-form-rawat.blade.php --}}
<h5 class="mt-4">🏥 Data Rawat</h5>
<div class="row">
    <div class="col-md-4">
        <label>Jenis Rawat</label>
        @php $jenis_rawat = optional($log)->jenis_rawat ?? (optional($sep)->jnspelayanan ?? ''); @endphp
        <select name="jenis_rawat" class="form-control">
            <option value="1" {{ $jenis_rawat == '1' ? 'selected' : '' }}>Rawat Inap</option>
            <option value="2" {{ $jenis_rawat == '2' ? 'selected' : '' }}>Rawat Jalan</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Kelas Rawat</label>
        @php $kelas_rawat = optional($log)->kelas_rawat ?? (optional($sep)->klsrawat ?? ''); @endphp
        <select name="kelas_rawat" class="form-control">
            <option value="1" {{ $kelas_rawat == '1' ? 'selected' : '' }}>Kelas 1</option>
            <option value="2" {{ $kelas_rawat == '2' ? 'selected' : '' }}>Kelas 2</option>
            <option value="3" {{ $kelas_rawat == '3' ? 'selected' : '' }}>Kelas 3</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Status Pulang</label>
        @php
            $discharge_status = optional($log)->discharge_status ?? match($pasien->cara_pulang ?? '') {
                'Atas Persetujuan Dokter' => '1',
                'Rujuk'                  => '2',
                'Atas Permintaan Sendiri' => '3',
                'Meninggal'              => '4',
                'Lain-lain'              => '5',
                default                  => '',
            };
        @endphp
        <select name="discharge_status" class="form-control">
            <option value="1" {{ $discharge_status == '1' ? 'selected' : '' }}>Atas Persetujuan Dokter</option>
            <option value="2" {{ $discharge_status == '2' ? 'selected' : '' }}>Dirujuk</option>
            <option value="3" {{ $discharge_status == '3' ? 'selected' : '' }}>Atas Permintaan Sendiri</option>
            <option value="4" {{ $discharge_status == '4' ? 'selected' : '' }}>Meninggal</option>
            <option value="5" {{ $discharge_status == '5' ? 'selected' : '' }}>Lain-lain</option>
        </select>
    </div>
</div>
