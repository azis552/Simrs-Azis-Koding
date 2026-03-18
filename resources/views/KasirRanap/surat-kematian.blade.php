@extends('template.master')

@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <div class="card">
                        <div class="card-block">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="sub-title">Surat Keterangan Kematian - Rawat Inap</h4>
                                <a href="{{ route('kasir.ranap.index') }}" class="btn btn-secondary">
                                    <i class="feather icon-arrow-left"></i> Kembali
                                </a>
                            </div>

                            {{-- Info Pasien (readonly) --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. Rawat</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->no_rawat }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. RM</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->no_rkm_medis }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Pasien</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->nm_pasien }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tempat / Tgl Lahir</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->tmp_lahir }}, {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d/m/Y') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Umur</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->umur }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->alamat_lengkap }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kamar / Bangsal</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->kamar }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tgl. Masuk</label>
                                        <input type="text" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($pasien->tgl_masuk)->format('d/m/Y') }} {{ $pasien->jam_masuk }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dokter Pemeriksa</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->nm_dokter }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input type="text" class="form-control"
                                            value="{{ $pasien->stts_pulang }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Diagnosa / Penyebab Kematian</label>
                                        <input type="text" class="form-control"
                                            value="{{ $diagnosa ? $diagnosa->kd_penyakit . ' - ' . $diagnosa->nm_penyakit : '-' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- Form Cetak --}}
                            <form action="{{ route('kasir.ranap.surat.kematian.cetak', $pasien->no_rawat) }}"
                                method="POST" target="_blank">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nomor Surat</label>
                                            <input type="text" class="form-control"
                                                name="nomor_surat"
                                                placeholder="Contoh: 001/SK/RS/2026"
                                                value="{{ old('nomor_surat', $pasienMati->nosurat ?? '') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Kematian</label>
                                            <input type="date" class="form-control"
                                                name="tgl_meninggal"
                                                value="{{ old('tgl_meninggal', $pasienMati->tanggal ?? $pasien->tgl_masuk) }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jam Kematian</label>
                                            <input type="time" step="1" class="form-control"
                                                name="jam_meninggal"
                                                value="{{ old('jam_meninggal', $pasienMati->jam ?? $pasien->jam_masuk) }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Keterangan Tambahan (opsional)</label>
                                            <textarea class="form-control" name="keterangan" rows="2"
                                                placeholder="Keterangan tambahan...">{{ old('keterangan', $pasienMati->keterangan ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-dark">
                                            <i class="feather icon-printer"></i> Cetak Surat Kematian
                                        </button>
                                        <a href="{{ route('kasir.ranap.index') }}" class="btn btn-secondary ml-2">
                                            <i class="feather icon-x"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection