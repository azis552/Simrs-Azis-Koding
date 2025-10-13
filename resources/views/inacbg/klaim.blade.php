@extends('template.master')

@section('content')
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {

            table.table-sm th,
            table.table-sm td {
                font-size: 13px;
                white-space: nowrap;
            }

            .card-body {
                padding: 10px;
            }

            select.form-control {
                font-size: 14px;
            }
        }
    </style>
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
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                        {{-- Data Pasien   --}}
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>No. Registrasi</label>
                                                <input type="text" class="form-control" value="{{ $pasien->no_rawat }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>No. RM</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $pasien->no_rkm_medis }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Nama Pasien</label>
                                                <input type="text" class="form-control" value="{{ $pasien->nm_pasien }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Jenis Kelamin</label>
                                                <input type="text" class="form-control" value="{{ $pasien->jk }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>No Peserta</label>
                                                <input type="text" class="form-control" value="{{ $pasien->no_peserta }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tanggal Lahir</label>
                                                <input type="text" class="form-control" value="{{ $pasien->tgl_lahir }}"
                                                    readonly>
                                            </div>

                                        </div>

                                        {{-- Tabs --}}
                                        <ul class="nav nav-tabs mb-3" id="klaimTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link {{ @$log == null ? 'active' : '' }}"
                                                    data-bs-toggle="tab" href="#dataKlaim" role="tab">Data Klaim</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ @$log->status == 'proses klaim' ? 'active' : '' }}"
                                                    data-bs-toggle="tab" href="#diagnosa" role="tab">Diagnosa & Prosedur
                                                    IDRG</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#inacbgimport"
                                                    role="tab">Import INA-CBG</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#inacbg" role="tab">Data
                                                    INA-CBG</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            {{-- Tab 1: Data Klaim --}}
                                            <div class="tab-pane fade {{ @$log == null ? 'show active' : '' }}"
                                                id="dataKlaim" role="tabpanel">
                                                <h3 class="mb-4">Form Klaim E-Klaim (Set Claim Data)</h3>
                                                @php
                                                    $isReadonly = !empty($log);
                                                @endphp


                                                @if ($isReadonly)
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            document.querySelectorAll('#form-claim input, #form-claim select, #form-claim textarea').forEach(
                                                                function(el) {
                                                                    el.setAttribute('readonly', true);
                                                                    el.setAttribute('disabled', true); // supaya select juga tidak bisa diubah
                                                                });
                                                        });
                                                    </script>
                                                @endif
                                                <form action="{{ route('inacbg-ranap.store') }}" id="form-claim"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="no_rawat" value="{{ $pasien->no_rawat }}">
                                                    <input type="hidden" name="nomor_rm"
                                                        value="{{ $pasien->no_rkm_medis }}">
                                                    <input type="hidden" name="nama_pasien"
                                                        value="{{ $pasien->nm_pasien }}">
                                                    <input type="hidden" name="gender"
                                                        value="{{ $pasien->jk == 'L' ? '1' : '2' }}">
                                                    <input type="hidden" name="tgl_lahir"
                                                        value="{{ $pasien->tgl_lahir }}">
                                                    {{-- ==================== DATA UTAMA ==================== --}}
                                                    <h5>🧾 Data Utama</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Nomor SEP</label>
                                                            <input type="text" name="nomor_sep"
                                                                value="{{ @$log->nomor_sep ?? ($sep->no_sep ?? '') }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Nomor Kartu</label>
                                                            <input type="text" name="nomor_kartu"
                                                                value="{{ @$log->nomor_kartu ?? ($pasien->no_peserta ?? '') }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Nama Dokter</label>
                                                            <input type="text" name="nama_dokter"
                                                                value="{{ @$log->nama_dokter ?? ($sep->nmdpdjp ?? '') }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        @php
                                                            // ambil data dari log jika ada, kalau tidak ambil dari pasien
                                                            $tglMasuk =
                                                                $log->tgl_masuk ??
                                                                $pasien->tgl_masuk . ' ' . $pasien->jam_masuk;
                                                            $tglPulang =
                                                                $log->tgl_pulang ??
                                                                $pasien->tgl_keluar . ' ' . $pasien->jam_keluar;

                                                            // tentukan apakah semua field form perlu readonly
                                                            $isReadonly =
                                                                !empty($log->tgl_masuk) && !empty($log->tgl_pulang);
                                                        @endphp

                                                        <div class="col-md-4">
                                                            <label>Tanggal Masuk</label>
                                                            <input type="datetime-local" name="tgl_masuk"
                                                                class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($tglMasuk)->format('Y-m-d\TH:i') }}"
                                                                {{ $isReadonly ? 'readonly' : '' }}>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Tanggal Pulang</label>
                                                            <input type="datetime-local" name="tgl_pulang"
                                                                class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($tglPulang)->format('Y-m-d\TH:i') }}"
                                                                {{ $isReadonly ? 'readonly' : '' }}>
                                                        </div>

                                                        @php
                                                            $asalRujukan =
                                                                $log->cara_masuk ?? ($sep->asal_rujukan ?? '');
                                                        @endphp
                                                        <div class="col-md-4">
                                                            <label>Cara Masuk</label>
                                                            <select name="cara_masuk" class="form-control">
                                                                <option value="gp"
                                                                    {{ $asalRujukan == 'gp' || $asalRujukan == '1. Faskes 1' ? 'selected' : '' }}>
                                                                    Rujukan FKTP
                                                                </option>

                                                                <option value="hosp-trans"
                                                                    {{ $asalRujukan == 'hosp-trans' || $asalRujukan == '2. Faskes 2(RS)' ? 'selected' : '' }}>
                                                                    Rujukan FKRTL
                                                                </option>
                                                                <option value="mp">Rujukan Spesialis</option>
                                                                <option value="outp">Dari Rawat Jalan</option>
                                                                <option value="emd">Dari IGD</option>
                                                                <option value="born">Lahir di RS</option>
                                                                <option value="other">Lain-lain</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== DATA RAWAT ==================== --}}
                                                    <h5 class="mt-4">🏥 Data Rawat</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Jenis Rawat</label>
                                                            <select name="jenis_rawat" class="form-control">
                                                                <option value="1"
                                                                    {{ @$log->jenis_rawat || @$sep->jnspelayanan == '1' ? 'selected' : '' }}>
                                                                    Rawat
                                                                    Inap</option>
                                                                <option value="2"
                                                                    {{ @$log->jenis_rawat || @$sep->jnspelayanan == '2' ? 'selected' : '' }}>
                                                                    Rawat
                                                                    Jalan</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Kelas Rawat</label>
                                                            <select name="kelas_rawat" class="form-control">
                                                                <option value="1"
                                                                    {{ @$log->kelas_rawat || @$sep->klsrawat == '1' ? 'selected' : '' }}>
                                                                    Kelas 1
                                                                </option>
                                                                <option value="2"
                                                                    {{ @$log->kelas_rawat || @$sep->klsrawat == '2' ? 'selected' : '' }}>
                                                                    Kelas 2
                                                                </option>
                                                                <option value="3"
                                                                    {{ @$log->kelas_rawat || @$sep->klsrawat == '3' ? 'selected' : '' }}>
                                                                    Kelas 3
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Status Pulang</label>
                                                            <select name="discharge_status" class="form-control">
                                                                <option value="1"
                                                                    {{ @$log->discharge_status || $pasien->cara_pulang == 'Atas Persetujuan Dokter' ? 'selected' : '' }}>
                                                                    Atas Persetujuan Dokter</option>
                                                                <option value="2"
                                                                    {{ @$log->discharge_status || $pasien->cara_pulang == 'Rujuk' ? 'selected' : '' }}>
                                                                    Dirujuk</option>
                                                                <option value="3"
                                                                    {{ @$log->discharge_status || $pasien->cara_pulang == 'Atas Permintaan Sendiri' ? 'selected' : '' }}>
                                                                    Atas Permintaan Sendiri</option>
                                                                <option value="4"
                                                                    {{ @$log->discharge_status || $pasien->cara_pulang == 'Meninggal' ? 'selected' : '' }}>
                                                                    Meninggal</option>
                                                                <option value="5"
                                                                    {{ @$log->discharge_status || $pasien->cara_pulang == 'Lain-lain' ? 'selected' : '' }}>
                                                                    Lain-lain</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== TAMBAHAN ==================== --}}
                                                    <h5 class="mt-4">🧮 Data Tambahan</h5>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>ADL Sub Acute</label>
                                                            <input type="number" name="adl_sub_acute"
                                                                value="{{ $log->adl_sub_acute ?? '0' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ADL Chronic</label>
                                                            <input type="number" name="adl_chronic"
                                                                value="{{ $log->adl_chronic ?? '0' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ICU Indikator</label>
                                                            <select name="icu_indikator" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->icu_indikator ? 'selected' : '' }}>Tidak
                                                                </option>
                                                                <option value="1"
                                                                    {{ @$log->icu_indikator ? 'selected' : '' }}>Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ICU Lama Rawat (LOS)</label>
                                                            <input type="number" name="icu_los"
                                                                value="{{ $log->icu_los ?? '0' }}" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Upgrade Class</label>
                                                            <select name="upgrade_class_ind" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->upgrade_class_ind == 0 || @$sep->klsnaik == null ? 'selected' : '' }}>
                                                                    Tidak
                                                                </option>
                                                                <option value="1"
                                                                    {{ @$log->upgrade_class_ind == 1 || @$sep->klsnaik != null ? 'selected' : '' }}>
                                                                    Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Lama Hari Naik Kelas</label>
                                                            <input type="number" name="upgrade_class_los"
                                                                value="{{ $log->upgrade_class_los ?? ($sep->klsnaik ?? '0') }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Persentase Biaya Tambahan</label>
                                                            <input type="number" name="add_payment_pct"
                                                                value="{{ $log->add_payment_pct ?? '0' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Berat Lahir (gram)</label>
                                                            <input type="number" name="birth_weight"
                                                                value="{{ $log->birth_weight ?? ($bayi->berat_lahir ?? '0') }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    {{-- ==================== TEKANAN DARAH ==================== --}}
                                                    @php
                                                        $tensi = explode('/', $pemeriksaan->tensi ?? '/');
                                                    @endphp
                                                    <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <label>Sistole</label>
                                                            <input type="number" name="sistole"
                                                                value="{{ $log->sistole ?? ($tensi[0] ?? '0') }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Diastole</label>
                                                            <input type="number" name="diastole"
                                                                value="{{ $log->diastole ?? ($tensi[1] ?? '0') }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Dializer Single Use</label>
                                                            <select name="dializer_single_use" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->dializer_single_use == 0 ? 'selected' : '' }}>
                                                                    Tidak</option>
                                                                <option value="1"
                                                                    {{ @$log->dializer_single_use == 1 ? 'selected' : '' }}>
                                                                    Ya</option>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Pasien TB</label>
                                                            <select name="tb_indikator" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->tb_indikator == 0 ? 'selected' : '' }}>
                                                                    Bukan
                                                                    TB</option>
                                                                <option value="1"
                                                                    {{ @$log->tb_indikator == 1 ? 'selected' : '' }}>
                                                                    Pasien
                                                                    TB</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== COVID SECTION ==================== --}}
                                                    <h5 class="mt-4">🦠 Data Covid</h5>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>Pemulasaraan Jenazah</label>
                                                            <select name="pemulasaraan_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->pemulasaraan_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak</option>
                                                                <option value="1"
                                                                    {{ @$log->pemulasaraan_jenazah == 1 ? 'selected' : '' }}>
                                                                    Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Kantong Jenazah</label>
                                                            <select name="kantong_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->kantong_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak</option>
                                                                <option value="1"
                                                                    {{ @$log->kantong_jenazah == 1 ? 'selected' : '' }}>
                                                                    Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Peti Jenazah</label>
                                                            <select name="peti_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->peti_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak
                                                                </option>
                                                                <option value="1"
                                                                    {{ @$log->peti_jenazah == 1 ? 'selected' : '' }}>Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Desinfektan Jenazah</label>
                                                            <select name="desinfektan_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->desinfektan_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak</option>
                                                                <option value="1"
                                                                    {{ @$log->desinfektan_jenazah == 1 ? 'selected' : '' }}>
                                                                    Ya</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Mobil Jenazah</label>
                                                            <select name="mobil_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->mobil_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak
                                                                </option>
                                                                <option value="1"
                                                                    {{ @$log->mobil_jenazah == 1 ? 'selected' : '' }}>Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Desinfektan Mobil Jenazah</label>
                                                            <select name="desinfektan_mobil_jenazah" class="form-control">
                                                                <option value="0"
                                                                    {{ @$log->desinfektan_mobil_jenazah == 0 ? 'selected' : '' }}>
                                                                    Tidak</option>
                                                                <option value="1"
                                                                    {{ @$log->desinfektan_mobil_jenazah == 1 ? 'selected' : '' }}>
                                                                    Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Status COVID</label>
                                                            <select name="covid19_status_cd" class="form-control">
                                                                <option value="">Pilih Status</option>
                                                                <option value="1"
                                                                    {{ @$log->covid19_status_cd == 1 ? 'selected' : '' }}>
                                                                    ODP</option>
                                                                <option value="2"
                                                                    {{ @$log->covid19_status_cd == 2 ? 'selected' : '' }}>
                                                                    PDP</option>
                                                                <option value="3"
                                                                    {{ @$log->covid19_status_cd == 3 ? 'selected' : '' }}>
                                                                    Terkonfirmasi</option>
                                                                <option value="4"
                                                                    {{ @$log->covid19_status_cd == 4 ? 'selected' : '' }}>
                                                                    Suspek</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Nomor Kartu T</label>
                                                            <select name="nomor_kartu_t" class="form-control">
                                                                <option value="nik"
                                                                    {{ @$log->nomor_kartu_t == 'nik' ? 'selected' : '' }}>
                                                                    NIK</option>
                                                                <option value="paspor"
                                                                    {{ @$log->nomor_kartu_t == 'paspor' ? 'selected' : '' }}>
                                                                    Paspor</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== TARIF RS ==================== --}}
                                                    @php
                                                        $tarifLog = is_string(@$log->tarif_rs)
                                                            ? json_decode(@$log->tarif_rs, true)
                                                            : @$log->tarif_rs;
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
                                                            @php
                                                                $totalBhpGabungan =
                                                                    ($obatbhpalkes['total_bhp'] ?? 0) +
                                                                    ($rekap['Bmhp'] ?? 0);
                                                            @endphp
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
                                                        <div class="col-md-3">
                                                            <label><strong>Total Semua Tarif RS</strong></label>
                                                            <input type="text" id="total_semua_tarif"
                                                                class="form-control rupiah" value="0" readonly>
                                                        </div>

                                                    </div>

                                                    {{-- ==================== DATA LAIN ==================== --}}
                                                    <h5 class="mt-4">📋 Lain-lain</h5>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Episodes</label>
                                                            <input type="text" name="episodes"
                                                                value="{{ $log->episodes ?? '{}' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Payor ID</label>
                                                            <input type="text" name="payor_id"
                                                                value="{{ $log->payor_id ?? '3' }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Payor Code</label>
                                                            <input type="text" name="payor_cd"
                                                                value="{{ $log->payor_cd ?? 'JKN' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Kode Tarif</label>
                                                            <input type="text" name="kode_tarif" value="DS"
                                                                class="form-control" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Coder NIK</label>
                                                            <input type="text" name="coder_nik"
                                                                value="{{ $log->coder_nik ?? ($coder->no_ktp ?? '') }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    {{-- ==================== SUBMIT ==================== --}}
                                                    @if ($isReadonly)
                                                        <div class="alert alert-info mt-4">
                                                            Data klaim sudah dikirim , tidak dapat diubah.
                                                        </div>
                                                    @else
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-success">
                                                                Simpan Data Klaim
                                                            </button>

                                                        </div>
                                                    @endif

                                                </form>
                                                @if ($isReadonly)
                                                    <form action="{{ route('inacbg.hapusklaim') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="no_rawat"
                                                            value="{{ $pasien->no_rawat ?? '' }}">
                                                        <input type="hidden" name="nomor_sep"
                                                            value="{{ @$log->nomor_sep ?? '' }}">
                                                        <input type="hidden" name="coder_nik"
                                                            value="{{ @$log->coder_nik ?? '' }}">
                                                        <button type="submit" class="btn btn-danger">Hapus Klaim</button>
                                                    </form>
                                                @endif

                                            </div>
                                            <div class="tab-pane fade {{ @$log->status == 'proses klaim' ? 'active show' : '' }}"
                                                id="diagnosa" role="tabpanel">
                                                <div class="row">
                                                    <!-- Diagnosa -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Diagnosa IDRG (ICD-10)</h5>
                                                                <select id="diagnosa_idrg" class="form-control"
                                                                    {{ $log->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>

                                                                <div class="table-responsive mt-2">
                                                                    <table id="tabel_diagnosa"
                                                                        class="table table-bordered table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Kode</th>
                                                                                <th>Deskripsi</th>
                                                                                <th>Status</th>
                                                                                <th>Hapus</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>

                                                                <button class="btn {{ $log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="diagnosa-idrg-simpan"
                                                                    {{ $log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Prosedur -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Prosedur IDRG (ICD-9-CM)</h5>
                                                                <select id="prosedur_idrg" class="form-control"
                                                                    {{ $log->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>

                                                                <div class="table-responsive mt-2">
                                                                    <table id="tabel_prosedur"
                                                                        class="table table-bordered table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Kode</th>
                                                                                <th>Deskripsi</th>
                                                                                <th>Qty</th>
                                                                                <th>Status</th>
                                                                                <th>Hapus</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>

                                                                <button class="btn {{ $log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="prosedur-idrg-simpan"
                                                                    {{ $log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <button id="btnGroupingIdrg" class="btn {{ $log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
                                                        {{ $log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Proses
                                                        Grouping
                                                        iDRG</button>

                                                    @php
                                                        $response = json_decode(
                                                            $log->response_grouping_idrg ?? '{}',
                                                            true,
                                                        );
                                                        $response_idrg = $response['response_idrg'] ?? null;
                                                    @endphp


                                                </div>
                                                {{-- ========================= --}}
                                                {{-- Hasil Grouping iDRG --}}
                                                {{-- ========================= --}}
                                                @if ($response_idrg)
                                                    <table class="table table-bordered mt-3">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th colspan="3">Hasil Grouping iDRG</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><b>MDC</b></td>
                                                                <td>{{ $response_idrg['mdc_description'] ?? '-' }}</td>
                                                                <td class="text-center">
                                                                    {{ $response_idrg['mdc_number'] ?? '-' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>DRG</b></td>
                                                                <td>{{ $response_idrg['drg_description'] ?? '-' }}</td>
                                                                <td class="text-center">
                                                                    {{ $response_idrg['drg_code'] ?? '-' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    {{-- Tombol Final iDRG --}}
                                                    <button id="btnFinalIdrg" class="btn {{ $log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} mt-2"
                                                        {{ $log->response_grouping_idrg == null ? 'disabled' : '' }}>
                                                        ✔ Final IDRG
                                                    </button>
                                                @else
                                                    <div class="alert alert-warning mt-3">
                                                        Belum ada hasil grouping iDRG tersimpan
                                                    </div>
                                                @endif

                                                {{-- ========================= --}}
                                                {{-- Tempat Hasil Final IDRG --}}
                                                {{-- ========================= --}}
                                                <div id="hasil_final_idrg" class="mt-4">
                                                    @php
                                                        $final = json_decode(
                                                            $log->response_idrg_grouper_final ?? '{}',
                                                            true,
                                                        );
                                                    @endphp

                                                    @if (!empty($final))
                                                        <div class="alert alert-success">
                                                            <b>Final IDRG</b>
                                                        </div>

                                                        <button id="btnReeditIdrg" class="btn btn-warning">
                                                            ✎ Re-edit iDRG
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="inacbgimport" role="tabpanel">
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

@section('script')
    <script>
        $(document).ready(function() {
            // Tombol Re-edit IDRG
            $('#btnReeditIdrg').click(function() {
                let nomor_sep = '{{ $log->nomor_sep ?? '' }}';

                if (!nomor_sep) {
                    Swal.fire('Error', 'Nomor SEP tidak ditemukan', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Yakin ingin Re-edit?',
                    text: 'Proses ini akan menghapus hasil Final IDRG sebelumnya.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    // 🔹 1. Panggil endpoint idrg_grouper_reedit
                    $.ajax({
                        url: '/idrg-grouper-reedit',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            metadata: {
                                method: 'idrg_grouper_reedit'
                            },
                            data: {
                                nomor_sep: nomor_sep
                            }
                        }),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Re-edit response:', response);

                            // 🔹 2. Jika sukses dari WS, hapus final IDRG di DB
                            if (response.metadata && response.metadata.code == 200) {
                                $.ajax({
                                    url: '/hapus-final-idrg',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        nomor_sep: nomor_sep
                                    },
                                    success: function(res) {
                                        Swal.fire('Berhasil',
                                                'Final IDRG berhasil dihapus, dan data siap diedit ulang.',
                                                'success')
                                            .then(() => location.reload());
                                    }
                                });
                            } else {
                                Swal.fire('Gagal', response.metadata?.message ||
                                    'Re-edit gagal', 'error');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Terjadi kesalahan saat Re-edit',
                                'error');
                        }
                    });
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#btnGroupingIdrg').click(function() {
                let nomor_sep = '{{ @$log->nomor_sep ?? '' }}';

                $.ajax({
                    url: '/grouping-idrg',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        metadata: {
                            method: 'grouper',
                            stage: '1',
                            grouper: 'idrg'
                        },
                        data: {
                            nomor_sep: nomor_sep
                        }
                    }),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);

                        // ✅ Simpan hasil ke tabel log
                        $.ajax({
                            url: '/save-grouping-idrg-log',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                nomor_sep: nomor_sep,
                                response_grouping_idrg: JSON.stringify(response)
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: 'Data grouping tersimpan ke log',
                                    timer: 2000
                                });
                            }
                        });

                        // ✅ Tampilkan hasil grouping di tampilan
                        if (response.data) {
                            $('#hasil_grouping').html(`
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center">Hasil Grouping iDRG</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>MDC</b></td>
                                    <td>${response.data.mdc_name ?? '-'}</td>
                                    <td>${response.data.mdc_code ?? '-'}</td>
                                </tr>
                                <tr>
                                    <td><b>DRG</b></td>
                                    <td>${response.data.drg_name ?? '-'}</td>
                                    <td>${response.data.drg_code ?? '-'}</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-success">✔ Final IDRG</button>
                    `);
                        } else {
                            $('#hasil_grouping').html(
                                '<div class="alert alert-warning">Data IDRG tidak ditemukan</div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        let msg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                    }
                });
            });
            // ------------------- FINAL IDRG -------------------
            $(document).on('click', '#btnFinalIdrg', function() {
                let nomor_sep = '{{ @$log->nomor_sep ?? '' }}';
                let log_response_idrg_grouper_final = '{{ $log->response_idrg_grouper_final ?? '' }}';
                if (log_response_idrg_grouper_final) {
                    return Swal.fire('Info', 'Final IDRG sudah diproses sebelumnya', 'info');
                }
                if (!nomor_sep) return Swal.fire('Nomor SEP kosong!');

                $.ajax({
                    url: '/final-idrg',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        metadata: {
                            method: 'idrg_grouper_final'
                        },
                        data: {
                            nomor_sep: nomor_sep
                        }
                    }),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Proses Final IDRG...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        // Simpan hasil ke log
                        $.post('/save-final-idrg-log', {
                            _token: '{{ csrf_token() }}',
                            nomor_sep: nomor_sep,
                            response_idrg_grouper_final: JSON.stringify(response)
                        });

                        Swal.fire('Sukses', 'Final IDRG tersimpan di log', 'success').then(
                            () => {
                                location.reload();
                            });
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire('Error', xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                            'error');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            // ---------- Variabel global ----------
            window.diagnosaList = [];
            window.prosedurList = [];

            // ---------- Inisialisasi Select2 ----------
            initSelect2('#diagnosa_idrg', '/api/icd10', 'tabel_diagnosa', true);
            initSelect2('#prosedur_idrg', '/api/icd9', 'tabel_prosedur', false);

            // ---------- FUNGSI UTAMA ----------
            function initSelect2(selector, url, tableId, isDiagnosa) {
                $(selector).select2({
                    placeholder: 'Cari kode atau deskripsi...',
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: data => ({
                            results: data
                        })
                    },
                    templateResult: function(item) {
                        if (!item.id) return item.text;
                        return $('<div>')
                            .append($('<b>').text(item.code))
                            .append(' — ' + item.description);
                    },
                    templateSelection: item => item.text,
                    multiple: true
                });

                // Saat memilih item
                $(selector).on('select2:select', function(e) {
                    let data = e.params.data;
                    let list = isDiagnosa ? diagnosaList : prosedurList;

                    // Validasi Diagnosa Primer
                    if (isDiagnosa && list.length === 0 && (data.validcode != 1 || data.accpdx !== 'Y')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak dapat dijadikan Primer',
                            text: 'Diagnosa ini tidak valid sebagai primer (validcode!=1 atau accpdx!=Y)',
                            timer: 2500
                        });
                        $(selector).val($(selector).val().filter(v => v !== data.id)).trigger('change');
                        return;
                    }

                    // Cegah duplikat
                    if (list.some(d => d.code === data.code)) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Data sudah ada',
                            text: 'Data ini sudah ditambahkan sebelumnya.',
                            timer: 2000
                        });
                        $(selector).val($(selector).val().filter(v => v !== data.id)).trigger('change');
                        return;
                    }

                    // Tambah item baru
                    let item = {
                        code: data.code,
                        desc: data.description,
                        status: isDiagnosa && list.length === 0 ? 'Primer' : 'Sekunder'
                    };

                    if (!isDiagnosa) item.qty = 1;

                    list.push(item);
                    if (isDiagnosa) diagnosaList = list;
                    else prosedurList = list;

                    renderTable(list, '#' + tableId, isDiagnosa);
                });

                // Saat unselect item
                $(selector).on('select2:unselect', function(e) {
                    let id = e.params.data.id;
                    let list = isDiagnosa ? diagnosaList : prosedurList;

                    list = list.filter(d => d.code !== id);
                    if (isDiagnosa) {
                        diagnosaList = list;
                        if (diagnosaList.length > 0) {
                            diagnosaList[0].status = 'Primer';
                            diagnosaList.slice(1).forEach(d => d.status = 'Sekunder');
                        }
                    } else {
                        prosedurList = list;
                    }

                    renderTable(list, '#' + tableId, isDiagnosa);
                });
            }

            // ---------- Render Tabel ----------
            function renderTable(list, tableId, isDiagnosa) {
                let tbody = $(tableId + ' tbody');
                tbody.empty();
                list.forEach((d, i) => {
                    tbody.append(`
                    <tr>
                        <td>${i + 1}</td>
                        <td>${d.code}</td>
                        <td>${d.desc}</td>
                        ${!isDiagnosa
                            ? `<td><input type="number" min="1" class="form-control form-control-sm qty-input"
                                                                                data-code="${d.code}" value="${d.qty}" style="width:80px"></td>` : ''
                        }
                        <td>${d.status}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="hapusItem('${d.code}', '${tableId.replace('#','')}')">X</button>
                        </td>
                    </tr>
                `);
                });
            }

            // ---------- Update Qty ----------
            $(document).on('change', '.qty-input', function() {
                const code = $(this).data('code');
                const qty = parseInt($(this).val()) || 1;
                const item = prosedurList.find(p => p.code === code);
                if (item) item.qty = qty;
            });

            // ---------- Hapus Item ----------
            window.hapusItem = function(code, table) {
                let selector = table === 'tabel_diagnosa' ? '#diagnosa_idrg' : '#prosedur_idrg';
                let list = table === 'tabel_diagnosa' ? diagnosaList : prosedurList;

                list = list.filter(d => d.code !== code);
                if (table === 'tabel_diagnosa') {
                    diagnosaList = list;
                    if (diagnosaList.length > 0) {
                        diagnosaList[0].status = 'Primer';
                        diagnosaList.slice(1).forEach(d => d.status = 'Sekunder');
                    }
                } else {
                    prosedurList = list;
                }

                $(selector).val($(selector).val().filter(v => v !== code)).trigger('change');
                renderTable(list, '#' + table, table === 'tabel_diagnosa');
            };

            // ---------- Load Data dari Log ----------
            let diagnosaLog = @json($log->diagnosa_idrg ?? '');
            let prosedurLog = @json($log->procedure_idrg ?? '');

            // Diagnosa
            if (diagnosaLog && diagnosaLog.expanded) {
                diagnosaList = diagnosaLog.expanded.map((d, i) => ({
                    code: d.code,
                    desc: d.display,
                    status: i === 0 ? 'Primer' : 'Sekunder'
                }));
                renderTable(diagnosaList, '#tabel_diagnosa', true);
            }

            // Prosedur
            if (prosedurLog && prosedurLog.expanded) {
                prosedurList = prosedurLog.expanded.map((d) => ({
                    code: d.code,
                    desc: d.display,
                    qty: d.multiplicity || 1,
                    status: 'Primer' // atau 'Sekunder' sesuai log kamu
                }));
                renderTable(prosedurList, '#tabel_prosedur', false);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rupiahInputs = document.querySelectorAll('.rupiah');

            rupiahInputs.forEach(input => {
                // format awal
                input.value = formatRupiah(input.value);

                input.addEventListener('input', function(e) {
                    const value = this.value.replace(/[^\d]/g, '');
                    this.value = formatRupiah(value);
                });

                // sebelum dikirim, ubah jadi angka murni
                input.form?.addEventListener('submit', function() {
                    rupiahInputs.forEach(i => {
                        i.value = i.value.replace(/[^\d]/g, '');
                    });
                });
            });

            function formatRupiah(angka) {
                if (!angka) return '';
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.rupiah');
            const totalInput = document.getElementById('total_semua_tarif');

            // Fungsi untuk hapus format rupiah dan ubah ke angka
            function parseRupiah(str) {
                return parseFloat(str.replace(/[^0-9,-]/g, '')) || 0;
            }

            // Fungsi untuk format angka ke rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            // Fungsi hitung total semua input
            function hitungTotal() {
                let total = 0;
                inputs.forEach(input => {
                    if (input !== totalInput) {
                        total += parseRupiah(input.value);
                    }
                });
                totalInput.value = formatRupiah(total);
            }

            // Jalankan saat halaman dimuat
            hitungTotal();

            // Tambahkan event listener ke semua input
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Reformat nilai rupiah saat diketik
                    const angka = parseRupiah(this.value);
                    this.value = formatRupiah(angka);
                    hitungTotal();
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let nomor_sep = "{{ @$log->nomor_sep }}";

            // === SIMPAN DIAGNOSA ===
            $('#diagnosa-idrg-simpan').on('click', function() {
                let diagnosaCodes = [];
                $('#tabel_diagnosa tbody tr').each(function() {
                    let kode = $(this).find('td:eq(1)').text();
                    if (kode) diagnosaCodes.push(kode);
                });

                let payload = {
                    metadata: {
                        method: "idrg_diagnosa_set",
                        nomor_sep: nomor_sep
                    },
                    data: {
                        diagnosa: diagnosaCodes.join('#')
                    }
                };

                $.ajax({
                    url: '/api/eklaim/idrg-diagnosa-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Diagnosa berhasil disimpan', 'success');

                        // Ambil hasil dari respon eksternal
                        let stringData = res.data;
                        // Kirim ke Laravel untuk update kolom diagnosa_idrg
                        $.ajax({
                            url: '/idrg/update-log',
                            type: 'POST',
                            data: {
                                nomor_sep: nomor_sep,
                                field: 'diagnosa_idrg',
                                value: stringData,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res2) {
                                console.log('Update diagnosa_idrg sukses:', res2);
                            },
                            error: function(xhr) {
                                console.error('Gagal update log:', xhr
                                    .responseText);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data', 'error');
                        console.error(xhr.responseText);
                    }
                });
            });


            // === SIMPAN PROSEDUR ===
            $('#prosedur-idrg-simpan').on('click', function() {
                let procedureList = [];
                $('#tabel_prosedur tbody tr').each(function() {
                    let kode = $(this).find('td:eq(1)').text();
                    let qty = $(this).find('td:eq(3) input').val() || '1';
                    if (kode) procedureList.push(`${kode}+${qty}`);
                });

                let payload = {
                    metadata: {
                        method: "idrg_procedure_set",
                        nomor_sep: nomor_sep
                    },
                    data: {
                        procedure: procedureList.join('#')
                    }
                };
                $.ajax({
                    url: '/api/eklaim/idrg-procedure-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Prosedur berhasil disimpan', 'success');

                        // Ambil hasil string dari respon eksternal
                        let stringData = res.data;

                        // Kirim ke Laravel untuk update kolom procedure_idrg
                        $.ajax({
                            url: '/idrg/update-log',
                            type: 'POST',
                            data: {
                                nomor_sep: nomor_sep,
                                field: 'procedure_idrg',
                                value: stringData,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res2) {
                                console.log('Update procedure_idrg sukses:', res2);
                            },
                            error: function(xhr) {
                                console.error('Gagal update log:', xhr
                                    .responseText);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data', 'error');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
