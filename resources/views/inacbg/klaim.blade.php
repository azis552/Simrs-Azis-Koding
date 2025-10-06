@extends('template.master')

@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    <div class="card">
                        <div class="card-block">
                            <div class="card">
                                <div class="card-body">
                                    <h4>E-Klaim</h4>
                                    <h5 class="mb-3">
                                        [{{ $pasien->no_rkm_medis }}] {{ $pasien->nm_pasien }}
                                    </h5>
                                    {{-- Data Pasien   --}}
                                    <div class="row mb-3" >
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

                                    {{-- Tabs --}}
                                    <ul class="nav nav-tabs mb-3" id="klaimTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#dataKlaim" role="tab">Data Klaim</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#diagnosa" role="tab">Diagnosa & Prosedur</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#rme" role="tab">Berkas RME</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#inacbg" role="tab">Data INA-CBG</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        {{-- Tab 1: Data Klaim --}}
                                        <div class="tab-pane fade show active" id="dataKlaim" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Jenis Rawat</label>
                                                    <input type="text" class="form-control" value="Rawat Inap" readonly>

                                                    <label for="">Tanggal Masuk</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->tgl_masuk }} {{ $pasien->jam_masuk }}" readonly>

                                                    <label for="">Tanggal Pulang</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->tgl_keluar }} {{ $pasien->jam_keluar }}" readonly>

                                                    <label for="">Jenis Rawat</label>
                                                    <select name="jenis_rawat" id="">
                                                        <option value="1">Rawat Inap</option>
                                                        <option value="2">Rawat Jalan</option>
                                                        <option value="3">Rawat IGD</option>
                                                    </select>

                                                    <label for="">Kelas Rawat</label>
                                                    <select name="kelas_rawat" id="">
                                                        <option value="1">Kelas 1</option>
                                                        <option value="2">Kelas 2</option>
                                                        <option value="3">Kelas 3</option>
                                                    </select>

                                                    <label for="">Cara Masuk</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->cara_masuk }}" readonly>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Tanggal Masuk</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->tgl_masuk }} {{ $pasien->jam_masuk }}" readonly>

                                                    <label class="mt-2">Tanggal Pulang</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->tgl_keluar }} {{ $pasien->jam_keluar }}" readonly>

                                                    <label class="mt-2">Cara Masuk</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->cara_masuk }}" readonly>

                                                    <label class="mt-2">Cara Pulang</label>
                                                    <input type="text" class="form-control" value="{{ $pasien->cara_pulang }}" readonly>
                                                </div>
                                            </div>

                                            {{-- Tombol Buat Klaim Baru --}}
                                            <div class="mt-4 text-end">
                                                <form action="" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="no_rawat" value="{{ $pasien->no_rawat }}">
                                                    <input type="hidden" name="no_sep" value="{{ $pasien->no_sep }}">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-file-medical"></i> Buat Klaim Baru
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="diagnosa" role="tabpanel">
                                            <p>Form diagnosa & prosedur akan ditampilkan di sini.</p>
                                        </div>
                                        <div class="tab-pane fade" id="rme" role="tabpanel">
                                            <p>Berkas rekam medis pasien.</p>
                                        </div>
                                        <div class="tab-pane fade" id="inacbg" role="tabpanel">
                                            <p>Data INA-CBG dan hasil grouping.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="styleSelector"></div>
            </div>
        </div>
    </div>
</div>
@endsection
