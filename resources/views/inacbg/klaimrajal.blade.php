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
                                            @if (@$log->status == 'proses final idrg')
                                                <li class="nav-item">
                                                    <a class="nav-link {{ @$log->status == 'proses final idrg' ? 'active' : 'hidden' }}"
                                                        data-bs-toggle="tab" href="#inacbgimport" role="tab">
                                                        INA-CBG</a>
                                                </li>
                                            @endif

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
                                                            document.querySelectorAll('#form-claim input, #form-claim select, #form-claim textarea')
                                                                .forEach(function(el) {
                                                                    if (el.tagName === 'SELECT') {
                                                                        el.setAttribute('disabled', true);
                                                                    } else {
                                                                        el.setAttribute('readonly', true);
                                                                    }
                                                                });
                                                        });
                                                    </script>
                                                @endif
                                                <form action="{{ route('inacbg-rajal.store') }}" id="form-claim"
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
                                                                $pasien->tgl_registrasi . ' ' . $pasien->jam_reg;
                                                            $tglPulang =
                                                                $log->tgl_pulang ??
                                                                $pasien->tgl_registrasi . ' ' . $pasien->jam_reg;

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
                                                            @php
                                                                // Tentukan asal rujukan berdasar logika data
                                                                $cara_masuk = match ($asalRujukan ?? '') {
                                                                    'gp', '1. Faskes 1' => 'gp',
                                                                    'hosp-trans', '2. Faskes 2(RS)' => 'hosp-trans',
                                                                    'mp' => 'mp',
                                                                    'outp' => 'outp',
                                                                    'emd' => 'emd',
                                                                    'born' => 'born',
                                                                    'other' => 'other',
                                                                    default => '',
                                                                };
                                                            @endphp

                                                            <select name="cara_masuk" class="form-control">
                                                                <option value="gp"
                                                                    {{ $cara_masuk == 'gp' ? 'selected' : '' }}>Rujukan
                                                                    FKTP</option>
                                                                <option value="hosp-trans"
                                                                    {{ $cara_masuk == 'hosp-trans' ? 'selected' : '' }}>
                                                                    Rujukan FKRTL</option>
                                                                <option value="mp"
                                                                    {{ $cara_masuk == 'mp' ? 'selected' : '' }}>Rujukan
                                                                    Spesialis</option>
                                                                <option value="outp"
                                                                    {{ $cara_masuk == 'outp' ? 'selected' : '' }}>Dari
                                                                    Rawat Jalan</option>
                                                                <option value="emd"
                                                                    {{ $cara_masuk == 'emd' ? 'selected' : '' }}>Dari IGD
                                                                </option>
                                                                <option value="born"
                                                                    {{ $cara_masuk == 'born' ? 'selected' : '' }}>Lahir di
                                                                    RS</option>
                                                                <option value="other"
                                                                    {{ $cara_masuk == 'other' ? 'selected' : '' }}>
                                                                    Lain-lain</option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    {{-- ==================== DATA RAWAT ==================== --}}
                                                    <h5 class="mt-4">🏥 Data Rawat</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Jenis Rawat</label>
                                                            @php
                                                                // Ambil nilai jenis rawat: pakai dari log kalau ada, kalau tidak dari SEP
                                                                $jenis_rawat =
                                                                    $log->jenis_rawat ?? ($sep->jnspelayanan ?? '');
                                                            @endphp

                                                            <select name="jenis_rawat" class="form-control">
                                                                <option value="1"
                                                                    {{ $jenis_rawat == '1' ? 'selected' : '' }}>Rawat Inap
                                                                </option>
                                                                <option value="2"
                                                                    {{ $jenis_rawat == '2' ? 'selected' : '' }}>Rawat Jalan
                                                                </option>
                                                            </select>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Kelas Rawat</label>
                                                            @php
                                                                $kelas_rawat =
                                                                    $log->kelas_rawat ?? ($sep->klsrawat ?? '');
                                                            @endphp

                                                            <select name="kelas_rawat" class="form-control"
                                                                >
                                                                <option value="1"
                                                                    {{ $kelas_rawat == '1' ? 'selected' : '' }}>Kelas 1
                                                                </option>
                                                                <option value="2"
                                                                    {{ $kelas_rawat == '2' ? 'selected' : '' }}>Kelas 2
                                                                </option>
                                                                <option value="3"
                                                                    {{ $kelas_rawat == '3' ? 'selected' : '' }}>Kelas 3
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Status Pulang</label>
                                                            @php
                                                                // Ambil nilai discharge status: prioritas dari log, jika tidak ada gunakan data pasien
                                                                $discharge_status =
                                                                    $log->discharge_status ??
                                                                    match ($pasien->cara_pulang ?? '') {
                                                                        'Atas Persetujuan Dokter' => '1',
                                                                        'Rujuk' => '2',
                                                                        'Atas Permintaan Sendiri' => '3',
                                                                        'Meninggal' => '4',
                                                                        'Lain-lain' => '5',
                                                                        default => '',
                                                                    };
                                                            @endphp

                                                            <select name="discharge_status" class="form-control">
                                                                <option value="1"
                                                                    {{ $discharge_status == '1' ? 'selected' : '' }}>Atas
                                                                    Persetujuan Dokter</option>
                                                                <option value="2"
                                                                    {{ $discharge_status == '2' ? 'selected' : '' }}>
                                                                    Dirujuk</option>
                                                                <option value="3"
                                                                    {{ $discharge_status == '3' ? 'selected' : '' }}>Atas
                                                                    Permintaan Sendiri</option>
                                                                <option value="4"
                                                                    {{ $discharge_status == '4' ? 'selected' : '' }}>
                                                                    Meninggal</option>
                                                                <option value="5"
                                                                    {{ $discharge_status == '5' ? 'selected' : '' }}>
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
                                                                    {{ @$log->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>

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

                                                                <button
                                                                    class="btn {{ @$log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="diagnosa-idrg-simpan"
                                                                    {{ @$log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Prosedur -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Prosedur IDRG (ICD-9-CM)</h5>
                                                                <select id="prosedur_idrg" class="form-control"
                                                                    {{ @$log->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>

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

                                                                <button
                                                                    class="btn {{ @$log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="prosedur-idrg-simpan"
                                                                    {{ @$log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <button id="btnGroupingIdrg"
                                                        class="btn {{ @$log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
                                                        {{ @$log->response_idrg_grouper_final != null ? 'disabled' : '' }}>Proses
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
                                                    <button id="btnFinalIdrg"
                                                        class="btn {{ @$log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} mt-2"
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
                                                        @if (empty($log->response_send_claim_individual ))
                                                            <button id="btnReeditIdrg" class="btn btn-warning">
                                                            ✎ Re-edit iDRG
                                                        </button>
                                                        @endif
                                                        
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="tab-pane fade {{ @$log->status == 'proses final idrg' ? 'active show' : 'hidden' }}"
                                                id="inacbgimport" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-12 text-center mb-3">
                                                        <button id="btnImportInacbg" class="btn btn-primary btn-lg">
                                                            <i class="fas fa-exchange-alt"></i> Import iDRG → INA-CBG
                                                        </button>
                                                    </div>
                                                    <!-- Diagnosa -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Diagnosa INACBG (ICD-10)</h5>
                                                                <select id="diagnosa_inacbg" class="form-control"
                                                                    {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}></select>

                                                                <div class="table-responsive mt-2">
                                                                    <table id="tabel_diagnosa_inacbg"
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

                                                                <button
                                                                    class="btn {{ @$log->response_inacbg_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="diagnosa-inacbg-simpan"
                                                                    {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}>
                                                                    Simpan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Prosedur -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Prosedur INACBG (ICD-9-CM)</h5>
                                                                <select id="prosedur_inacbg" class="form-control"
                                                                    {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}></select>

                                                                <div class="table-responsive mt-2">
                                                                    <table id="tabel_prosedur_inacbg"
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


                                                                <button
                                                                    class="btn {{ @$log->response_inacbg_final != null ? 'btn-disabled' : 'btn-primary' }} btn-sm mt-2"
                                                                    id="prosedur-inacbg-simpan"
                                                                    {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}>
                                                                    Simpan
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div id="hasil_import_inacbg" class="mt-3"></div>
                                                </div>
                                                @if (@$log->response_inacbg_import != null && @$log->procedure_inacbg != null)
                                                    <button
                                                        class="btn btn-primary {{ @$log->response_inacbg_final != null ? 'btn-disabled' : '' }}"
                                                        {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}
                                                        id="btnGroupingInacbg">Grouping
                                                        INA-CBG</button>
                                                    <div id="groupingInacbgResult" class="mt-3"></div>

                                                    @if (isset($log->response_inacbg_stage1))
                                                        @php
                                                            $response = json_decode(
                                                                @$log->response_inacbg_stage1,
                                                                true,
                                                            );
                                                            $code = $response['response_inacbg']['cbg']['code'] ?? '';
                                                            $awal = strtoupper(substr($code, 0, 1));

                                                        @endphp

                                                        <div class="mt-3">
                                                            @if ($awal !== 'X')
                                                                @php
                                                                    $hasFinal = !empty($log->response_inacbg_final);
                                                                @endphp
                                                                @if ($hasFinal)

                                                                    <div class="mt-3 text-center">
                                                                        @if (@$log->response_claim_final == null)
                                                                            <button class="btn btn-warning"
                                                                                id="btnReeditInacbg">
                                                                                <i class="bi bi-arrow-repeat"></i> Re-edit
                                                                                INA-CBG
                                                                            </button>
                                                                            <button id="btnClaimFinal"
                                                                                class="btn btn-success">
                                                                                <i class="fas fa-flag-checkered"></i> Claim
                                                                                Final INA-CBG
                                                                            </button>
                                                                        @endif
                                                                        @if (!empty($log->response_claim_final))
                                                                            @if (empty($log->response_send_claim_individual ))
                                                                                
                                                                            
                                                                            <button id="btnReeditClaim"
                                                                                class="btn btn-warning">
                                                                                <i class="fa fa-refresh"></i> Re-edit Claim
                                                                            </button>
                                                                            @endif
                                                                            <!-- Tombol kirim klaim -->
                                                                            <button id="btnSendClaim"
                                                                                class="btn btn-success"
                                                                                style="display:none;">
                                                                                <i class="fa fa-paper-plane"></i> Kirim
                                                                                Claim Individual
                                                                            </button>
                                                                            <button class="btn btn-danger"
                                                                                id="btnPrintClaim"
                                                                                data-sep="{{ @$log->nomor_sep ?? '' }}">
                                                                                🖨️ Print Claim
                                                                            </button>
                                                                        @endif
                                                                    </div>

                                                                    <!-- Card untuk hasil Claim Final -->
                                                                    <div id="cardClaimFinal" class="card mt-4"
                                                                        style="display: none;">
                                                                        <div class="card-header bg-success text-white">
                                                                            <strong>Hasil Claim Final INA-CBG</strong>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <pre id="resultClaimFinal" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <button id="btnFinalInacbg" class="btn btn-success"
                                                                        data-nomor-sep="{{ @$log->nomor_sep }}">
                                                                        Grouping Final INA-CBG
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <p class="text-danger">Final tidak tersedia untuk code
                                                                    diawali "{{ $awal }}"</p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif





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
        $('#btnPrintClaim').on('click', function() {
            const nomor_sep = $(this).data('sep');

            if (!nomor_sep) {
                alert('Nomor SEP tidak ditemukan.');
                return;
            }

            // Tampilkan loading kecil
            $(this).prop('disabled', true).text('Mencetak...');

            // Kirim request ke backend
            $.ajax({
                url: '/api/eklaim/claim-print',
                method: 'POST',
                xhrFields: {
                    responseType: 'blob' // penting untuk PDF!
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'metadata[method]': 'claim_print',
                    'data[nomor_sep]': nomor_sep
                },
                success: function(blob) {
                    // Buat URL dari blob PDF
                    const fileURL = URL.createObjectURL(blob);
                    window.open(fileURL, '_blank'); // tampilkan di tab baru
                },
                error: function(xhr) {
                    alert('Gagal mencetak klaim.\n' + xhr.responseText);
                },
                complete: function() {
                    $('#btnPrintClaim').prop('disabled', false).text('🖨️ Print Claim');
                }
            });
        });
    </script>
    {{-- send klaim --}}
    <script>
        $(document).ready(function() {
            const nomor_sep = '{{ @$log->nomor_sep }}';

            // Tampilkan tombol hanya jika ada hasil Claim Final
            @if (!empty($log->response_claim_final))
                $('#btnSendClaim').show();
            @endif

            // Klik tombol kirim klaim
            $('#btnSendClaim').on('click', function() {
                Swal.fire({
                    title: 'Kirim Claim ke e-Klaim?',
                    text: 'Pastikan hasil claim final sudah benar sebelum dikirim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (!res.isConfirmed) return;

                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mengirim klaim ke e-Klaim...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // === Kirim ke e-Klaim ===
                    $.ajax({
                        url: '/api/eklaim/claim-send',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            metadata: {
                                method: 'send_claim_individual'
                            },
                            data: {
                                nomor_sep: nomor_sep
                            }
                        }),
                        success: function(response) {
                            Swal.close();
                            console.log('Response send_claim_individual:', response);

                            if (response.status !== 'success') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mengirim Claim',
                                    text: response.message ||
                                        'Terjadi kesalahan saat mengirim klaim.'
                                });
                                return;
                            }

                            // === Simpan Log ke Database ===
                            $.ajax({
                                url: '/log/claim-send/save',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    nomor_sep: nomor_sep,
                                    response_send_claim_individual: JSON
                                        .stringify(response.response)
                                },
                                beforeSend: function() {
                                    Swal.fire({
                                        title: 'Menyimpan Log...',
                                        text: 'Menyimpan hasil kirim klaim...',
                                        allowOutsideClick: false,
                                        didOpen: () => Swal
                                            .showLoading()
                                    });
                                },
                                success: function(res) {
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Klaim Terkirim!',
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(() => location.reload(),
                                        2000);
                                },
                                error: function() {
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Simpan Log',
                                        text: 'Klaim berhasil dikirim, tapi gagal menyimpan log.'
                                    });
                                }
                            });
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Server',
                                text: 'Tidak dapat terhubung ke server.'
                            });
                        }
                    });
                });
            });
        });
    </script>
    {{-- reedit klaim --}}
    <script>
        $('#btnReeditClaim').on('click', function() {
            let nomor_sep = "{{ @$log->nomor_sep }}";

            if (!nomor_sep) {
                alert('Nomor SEP tidak ditemukan!');
                return;
            }

            if (!confirm('Yakin ingin melakukan re-edit claim untuk SEP ini?')) return;

            $.ajax({
                url: '/reedit-claim',
                type: 'POST',
                data: {

                    nomor_sep: nomor_sep,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.message);
                        console.log(res.response);
                        location.reload();
                    } else {
                        alert(res.message || 'Re-edit claim gagal.');
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON?.message);
                }
            });
        });
    </script>
    {{-- claim final  --}}
    <script>
        $(document).ready(function() {
            $('#btnClaimFinal').on('click', function() {
                const nomor_sep = '{{ @$log->nomor_sep }}';
                const coder_nik = '{{ @$log->coder_nik }}';
                const btn = $(this);

                Swal.fire({
                    title: 'Kirim Claim Final?',
                    text: 'Pastikan semua data sudah benar sebelum finalisasi klaim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    btn.prop('disabled', true);

                    $.ajax({
                        url: '/api/eklaim/claim-final',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            metadata: {
                                method: 'claim_final'
                            },
                            data: {
                                nomor_sep: nomor_sep,
                                coder_nik: coder_nik
                            }
                        }),
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mengirim data Claim Final ke e-Klaim...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.metadata?.code !== 200) {
                                btn.prop('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Claim Final',
                                    text: response.metadata?.message ||
                                        'Terjadi kesalahan pada proses klaim.'
                                });
                                return;
                            }

                            // ✅ Tampilkan hasil Claim Final
                            $('#cardClaimFinal').show();
                            $('#resultClaimFinal').text(JSON.stringify(response, null,
                                2));

                            // ✅ Simpan hasil ke log
                            $.ajax({
                                url: '/save-claim-final-log-rajal',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    nomor_sep: nomor_sep,
                                    response_claim_final: JSON.stringify(
                                        response)
                                },
                                success: function(saveRes) {
                                    console.log('Save Claim Final Log:',
                                        saveRes);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Claim Final Berhasil',
                                        text: 'Hasil Claim Final berhasil disimpan ke log.'
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Claim Final Berhasil',
                                        text: 'Claim Final berhasil, tetapi gagal menyimpan log.'
                                    });
                                },
                                complete: function() {
                                    btn.prop('disabled', false);
                                }
                            });
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Koneksi Gagal',
                                text: 'Tidak dapat terhubung ke server e-Klaim.'
                            });
                        }
                    });
                });
            });
        });
    </script>
    {{-- Re-edit Inacbg Final --}}
    <script>
        $('#btnReeditInacbg').on('click', function() {
            const nomor_sep = '{{ @$log->nomor_sep }}';

            if (!confirm('Yakin ingin melakukan Re-edit INA-CBG? Semua hasil grouping akan dihapus.')) return;

            $(this).prop('disabled', true).text('Memproses Re-edit...');

            $.ajax({
                url: '/grouping-inacbg-reedit-final-rajal',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nomor_sep: nomor_sep
                },
                success: function(res) {
                    $('#btnReeditInacbg').prop('disabled', false).text('Re-edit INA-CBG');

                    if (res.status === 'success') {
                        alert(res.message);
                        $('#groupingInacbgResult').html('');
                        $('#stage2Result').html('');
                        location.reload();
                        if (res.response && res.response.response_inacbg) {
                            const cbg = res.response.response_inacbg.cbg || {};
                            const tarif = parseInt(res.response.response_inacbg.tariff || 0)
                                .toLocaleString('id-ID');

                            $('#groupingInacbgResult').html(`
                        <div class="card border-info shadow-sm p-3 mt-3">
                            <h6 class="text-info">
                                <i class="bi bi-arrow-repeat"></i> Hasil Re-edit INA-CBG
                            </h6>
                            <table class="table table-sm table-hover mt-2">
                                <tr><td width="35%">Kode CBG</td><td><b>${cbg.code}</b></td></tr>
                                <tr><td>Deskripsi</td><td>${cbg.description}</td></tr>
                                <tr><td>Tarif</td><td><b>Rp ${tarif}</b></td></tr>
                            </table>
                        </div>
                    `);
                        }
                    } else {
                        alert(res.message || 'Gagal melakukan Re-edit INA-CBG.');
                    }
                },
                error: function(err) {
                    console.error('Reedit error:', err);
                    $('#btnReeditInacbg').prop('disabled', false).text('Re-edit INA-CBG');
                    alert('Terjadi kesalahan saat Re-edit INA-CBG.');
                }
            });
        });
    </script>

    {{-- final inacbg --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '#btnFinalInacbg', function() {
                const btn = $(this);
                const nomorSep = btn.data('nomor-sep');

                if (!nomorSep) {
                    alert("Nomor SEP tidak ditemukan!");
                    return;
                }

                btn.prop('disabled', true).text('Mengirim ke e-Klaim...');

                // === 1. Kirim ke e-Klaim (final-inacbg) ===
                $.ajax({
                    url: '/api/eklaim/final-inacbg', // <---- langsung pakai URL
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        metadata: {
                            method: 'inacbg_grouper_final'
                        },
                        data: {
                            nomor_sep: nomorSep
                        }
                    },
                    success: function(response) {
                        console.log('Response e-Klaim:', response);

                        // tampilkan hasil dulu
                        alert('Proses final berhasil dari e-Klaim. Akan disimpan ke log.');

                        // === 2. Simpan hasil ke log (save-final-inacbg-log) ===
                        $.ajax({
                            url: '/save-final-inacbg-log-rajal', // <---- langsung pakai URL
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                nomor_sep: nomorSep,
                                response_inacbg_final: JSON.stringify(response)
                            },
                            success: function(res) {
                                alert(res.message);
                                location.reload();
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                alert('Gagal menyimpan hasil final ke log.');
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Gagal mengirim ke e-Klaim.');
                        btn.prop('disabled', false).text('Final IDRG');
                    }
                });
            });
        });
    </script>
    {{-- grouping inacbg --}}
    <script>
        $(document).ready(function() {
            const nomor_sep = '{{ @$log->nomor_sep }}';
            const existingStage1 = @json($log->response_inacbg_stage1 ?? null);
            const existingStage2 = @json($log->response_inacbg_stage2 ?? null);

            let selectedCmgCode = null;

            // --- Auto-load Stage 2 (cek CMG yang dipakai) ---
            if (existingStage2) {
                try {
                    const parsed2 = typeof existingStage2 === 'string' ? JSON.parse(existingStage2) :
                        existingStage2;
                    if (parsed2.response_inacbg) {
                        renderStage2Result(parsed2.response_inacbg);
                        // Ambil kode CMG terakhir dari log stage2
                        if (parsed2.metadata && parsed2.metadata.special_cmg) {
                            selectedCmgCode = parsed2.metadata.special_cmg;
                        } else if (parsed2.data && parsed2.data.special_cmg) {
                            selectedCmgCode = parsed2.data.special_cmg;
                        }
                    }
                } catch (e) {
                    console.warn('Gagal parse response_inacbg_stage2:', e);
                }
            }

            // --- Auto-load hasil Stage 1 dari log ---
            if (existingStage1) {
                try {
                    const parsed = typeof existingStage1 === 'string' ? JSON.parse(existingStage1) : existingStage1;
                    if (parsed.response_inacbg) {
                        renderGroupingResult(parsed.response_inacbg, parsed.special_cmg_option || []);
                    }
                } catch (e) {
                    console.warn('Gagal parse response_inacbg_stage1:', e);
                }
            }

            // --- Tombol grouping stage 1 ---
            $('#btnGroupingInacbg').on('click', function() {
                $(this).prop('disabled', true).text('Processing...');

                $.ajax({
                    url: '/api/eklaim/grouping-inacbg-stage-1',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        metadata: {
                            method: 'grouper',
                            stage: '1',
                            grouper: 'inacbg'
                        },
                        data: {
                            nomor_sep: nomor_sep
                        }
                    }),
                    success: function(response) {
                        $('#btnGroupingInacbg').prop('disabled', false).text(
                            'Grouping INA-CBG');

                        if (response.response_inacbg) {
                            renderGroupingResult(response.response_inacbg, response
                                .special_cmg_option || []);

                            // --- Simpan log stage 1 (full JSON) ---
                            $.post('/save-grouping-inacbg-stage1-log-rajal', {
                                _token: '{{ csrf_token() }}',
                                nomor_sep: nomor_sep,
                                response_inacbg_stage1: JSON.stringify(response)
                            });
                            location.reload();
                        } else {
                            $('#groupingInacbgResult').html(
                                '<div class="alert alert-danger">Tidak ada hasil grouping.</div>'
                            );
                        }
                    },
                    error: function(err) {
                        console.error('Error detail:', err.responseJSON || err);
                        $('#btnGroupingInacbg').prop('disabled', false).text(
                            'Grouping INA-CBG');
                        alert('Terjadi kesalahan pada proses Grouping INA-CBG.');
                    }
                });
            });

            // --- Render Stage 1 result ---
            function renderGroupingResult(response_inacbg, specialOptions) {
                const cbg = response_inacbg.cbg || {};
                const tarif = parseInt(response_inacbg.tariff || 0).toLocaleString('id-ID');
                const version = response_inacbg.inacbg_version || '-';

                let html = `
            <div class="card shadow-sm border p-3" style="color:#000;">
                <h5 class="mb-3" style="font-weight:600;">
                    <i class="bi bi-hospital"></i> Hasil Grouping INA-CBG (Stage 1)
                </h5>
                <table class="table table-sm table-striped">
                    <tr><td width="35%">Kode CBG</td><td><b>${cbg.code || '-'}</b></td></tr>
                    <tr><td>Deskripsi</td><td>${cbg.description || '-'}</td></tr>
                    <tr><td>Tarif</td><td><b>Rp ${tarif}</b></td></tr>
                    <tr><td>Versi INA-CBG</td><td>${version}</td></tr>
                </table>
        `;

                if (specialOptions && specialOptions.length > 0) {
                    html += `
                <div class="mt-3 p-3 border rounded bg-light" style="color:#000;">
                    <h6><i class="bi bi-stars"></i> Special CMG Ditemukan</h6>
                    <p class="text-muted small">Silakan pilih salah satu opsi berikut untuk melanjutkan Grouping Stage 2:</p>
                    <select id="specialCmgSelect" class="form-control mt-2">
                        <option value="">-- Pilih Special CMG --</option>`;
                    specialOptions.forEach(opt => {
                        const selected = (opt.code === selectedCmgCode) ? 'selected' : '';
                        html += `<option value="${opt.code}" data-field="${opt.type}" ${selected}>
                            ${opt.code} - ${opt.description} (${opt.type})
                        </option>`;
                    });
                    html += `
                    </select>
                    <button id="btnCancelCmg" class="btn btn-outline-danger btn-sm mt-2">Batal Pilih CMG</button>
                </div>
                <div id="stage2Result" class="mt-3"></div>
            `;
                }

                html += `</div>`;
                $('#groupingInacbgResult').html(html);

                // Jika sudah ada hasil stage 2 dari log, tampilkan ulang
                if (existingStage2) {
                    const parsed2 = typeof existingStage2 === 'string' ? JSON.parse(existingStage2) :
                        existingStage2;
                    if (parsed2.response_inacbg) {
                        renderStage2Result(parsed2.response_inacbg);
                    }
                }
            }

            // --- Render Stage 2 result ---
            function renderStage2Result(response_inacbg) {
                const cbg = response_inacbg.cbg || {};
                const tarif = parseInt(response_inacbg.tariff || 0).toLocaleString('id-ID');

                const html = `
            <div class="card border shadow-sm p-3 mt-3" style="color:#000;">
                <h6 style="font-weight:600;">
                    <i class="bi bi-cpu"></i> Hasil Grouping Stage 2 (Special CMG)
                </h6>
                <table class="table table-sm table-hover mt-2">
                    <tr><td width="35%">Kode CBG</td><td><b>${cbg.code}</b></td></tr>
                    <tr><td>Deskripsi</td><td>${cbg.description}</td></tr>
                    <tr><td>Tarif Akhir</td><td><b>Rp ${tarif}</b></td></tr>
                </table>
                <span class="badge bg-secondary text-light">Special CMG Applied</span>
            </div>
        `;
                $('#stage2Result').html(html);
            }

            // --- Event Stage 2 grouping ---
            $(document).on('change', '#specialCmgSelect', function() {
                const code = $(this).val();
                if (!code) {
                    $('#stage2Result').html('');
                    return;
                }

                $('#stage2Result').html('<div class="text-muted">Memproses Stage 2...</div>');

                $.ajax({
                    url: '/api/eklaim/grouping-inacbg-stage-2',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        metadata: {
                            method: 'grouper',
                            stage: '2',
                            grouper: 'inacbg',
                            special_cmg: code
                        },
                        data: {
                            nomor_sep: nomor_sep,
                            special_cmg: code
                        }
                    }),
                    success: function(res) {
                        if (res.response_inacbg) {
                            renderStage2Result(res.response_inacbg);

                            // --- Simpan log Stage 2 (full JSON) ---
                            $.post('/save-grouping-inacbg-stage2-log-rajal', {
                                _token: '{{ csrf_token() }}',
                                nomor_sep: nomor_sep,
                                response_inacbg_stage2: JSON.stringify(res)
                            });

                            selectedCmgCode = code; // simpan CMG terakhir
                            location.reload();
                        } else {
                            $('#stage2Result').html(
                                '<div class="alert alert-warning">Gagal memproses Stage 2.</div>'
                            );
                        }
                    },
                    error: function(err) {
                        console.error('Stage 2 error:', err.responseJSON || err);
                        $('#stage2Result').html(
                            '<div class="alert alert-danger">Terjadi kesalahan pada proses Stage 2.</div>'
                        );
                    }
                });
            });

            // --- Tombol batal pilih CMG ---
            $(document).on('click', '#btnCancelCmg', function() {
                if (!confirm('Yakin ingin membatalkan CMG?')) return;
                $('#specialCmgSelect').val('');
                $('#stage2Result').html('<div class="text-muted">CMG dibatalkan...</div>');

                // Update log: hapus response_inacbg_stage2
                $.ajax({
                    url: '/save-grouping-inacbg-stage2-log-rajal',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nomor_sep: nomor_sep,
                        response_inacbg_stage2: null
                    },
                    success: function() {
                        $('#stage2Result').html(
                            '<div class="alert alert-secondary">CMG dibatalkan dan log diperbarui.</div>'
                        );
                        selectedCmgCode = null;
                    }
                });
            });
        });
    </script>





    {{-- end grouping inacbg --}}

    {{-- Re-edit IDRG --}}
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
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Memproses Re-edit...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            console.log('Re-edit response:', response);

                            // 🔹 2. Jika sukses dari WS, hapus final IDRG di DB
                            if (response.metadata && response.metadata.code == 200) {
                                $.ajax({
                                    url: '/hapus-final-idrg-rajal',
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

    {{-- grouping idrg dan final --}}
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
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses Grouping IDRG...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        console.log(response);

                        $.ajax({
                            url: '/save-grouping-idrg-log-rajal',
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
                                    timer: 2000,
                                    showConfirmButton: false,
                                    didClose: () => {
                                        // Reload setelah alert tertutup
                                        location.reload();
                                    }
                                });
                            }
                        });



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

                if (!nomor_sep) {
                    return Swal.fire('Peringatan', 'Nomor SEP kosong!', 'warning');
                }

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
                            title: 'Memproses Final IDRG...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.metadata && response.metadata.code === 200) {
                            // Simpan log ke database tanpa reload
                            $.ajax({
                                url: '/save-final-idrg-log-rajal',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    nomor_sep: nomor_sep,
                                    response_idrg_grouper_final: JSON.stringify(
                                        response)
                                },
                                success: function(res) {
                                    // Tampilkan hasil ke halaman tanpa reload
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Final IDRG Berhasil',
                                        text: 'Data Final IDRG berhasil diproses dan disimpan.'
                                    });

                                    // Tambahkan info hasil di area tertentu (jika ada div hasil)
                                    $('#hasil_final_idrg').html(`
                            <div class="alert alert-success mt-3">
                                <b>Final IDRG Sukses</b><br>
                                <small>Kode IDRG: ${response.data?.idrg_code || '-'}</small><br>
                                <small>Deskripsi: ${response.data?.description || '-'}</small><br>
                                <small>Tarif INA-CBG: Rp ${(response.data?.cbg_tarif || 0).toLocaleString('id-ID')}</small>
                            </div>
                        `);

                                    window.location.reload();
                                },
                                error: function() {
                                    Swal.fire('Error',
                                        'Gagal menyimpan log ke database',
                                        'error');
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.metadata?.message ||
                                    'Proses Final IDRG gagal.'
                            });
                        }
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

    {{-- diagnosa & prosedur idrg --}}
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

    {{-- rupiah --}}
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

    {{-- total semua tarif --}}
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

    {{-- simpan diagnosa & prosedur idrg --}}
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
                            url: '/idrg/update-log-rajal',
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
                            url: '/idrg/update-log-rajal',
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
    {{-- diagnosa & prosedur inacbg dan import --}}
    <script>
        $(document).ready(function() {

            // ---------- Variabel global ----------
            window.diagnosaListInacbg = [];
            window.prosedurListInacbg = [];
            window.diagnosaCodes = []; // daftar kode diagnosa aktif

            // ---------- Fungsi Update Diagnosa Codes ----------
            function updateDiagnosaCodes() {
                window.diagnosaCodes = diagnosaListInacbg.map(d => d.code);
                console.log('Diagnosa codes terbaru:', diagnosaCodes);
            }

            // ---------- Inisialisasi Select2 ----------
            initSelect2('#diagnosa_inacbg', '/api/icd10', 'tabel_diagnosa_inacbg', true);
            initSelect2('#prosedur_inacbg', '/api/icd9', 'tabel_prosedur_inacbg', false);

            // ---------- Fungsi Select2 ----------
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

                // Saat pilih item
                $(selector).on('select2:select', function(e) {
                    let data = e.params.data;
                    let list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;

                    // ---------- Validasi Diagnosa Primer ----------
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

                    // ---------- Cegah duplikat ----------
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

                    // ---------- Deteksi IM tidak berlaku ----------
                    if (data.im == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            html: `
                            <div style="text-align:left">
                                <b>${isDiagnosa ? 'Diagnosa' : 'Prosedur'}:</b><br>
                                ${data.code} - ${data.description}<br>
                                <span style="color:red;font-weight:bold;">(IM tidak berlaku pada item ini)</span>
                            </div>
                        `,
                            confirmButtonText: 'OK'
                        });
                    }

                    // ---------- Tambah item baru ----------
                    let item = {
                        code: data.code,
                        desc: data.description,
                        status: isDiagnosa && list.length === 0 ? 'Primer' : 'Sekunder',
                        im: data.im || 0
                    };

                    if (!isDiagnosa) item.qty = 1;

                    list.push(item);
                    if (isDiagnosa) diagnosaListInacbg = list;
                    else prosedurListInacbg = list;

                    renderTable(list, '#' + tableId, isDiagnosa);
                    if (isDiagnosa) updateDiagnosaCodes(); // <- update otomatis
                });

                // Saat unselect item
                $(selector).on('select2:unselect', function(e) {
                    let id = e.params.data.id;
                    let list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;

                    list = list.filter(d => d.code !== id);
                    if (isDiagnosa) {
                        diagnosaListInacbg = list;
                        if (diagnosaListInacbg.length > 0) {
                            diagnosaListInacbg[0].status = 'Primer';
                            diagnosaListInacbg.slice(1).forEach(d => d.status = 'Sekunder');
                        }
                    } else {
                        prosedurListInacbg = list;
                    }

                    renderTable(list, '#' + tableId, isDiagnosa);
                    if (isDiagnosa) updateDiagnosaCodes();
                });
            }

            // ---------- Render Tabel ----------
            function renderTable(list, tableId, isDiagnosa) {
                let tbody = $(tableId + ' tbody');
                tbody.empty();

                list.forEach((d, i) => {
                    const rowClass = d.error_no ? 'table-danger' : '';
                    const warningMsg = d.im == 1 ?
                        `<br><span class="text-danger fw-bold">⚠ IM tidak berlaku</span>` :
                        (d.error_no ? `<br><span class="text-danger">[${d.error_no}] ${d.message}</span>` :
                            '');

                    tbody.append(`
                    <tr class="fade-highlight ${rowClass}">
                        <td>${i + 1}</td>
                        <td>${d.code}</td>
                        <td>${d.desc}${warningMsg}</td>
                        <td>${d.status}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="hapusItemInacbg('${d.code}', '${tableId.replace('#','')}')">X</button>
                        </td>
                    </tr>
                `);
                });
            }

            // ---------- Hapus Item ----------
            window.hapusItemInacbg = function(code, table) {
                let selector = table === 'tabel_diagnosa_inacbg' ? '#diagnosa_inacbg' : '#prosedur_inacbg';
                let list = table === 'tabel_diagnosa_inacbg' ? diagnosaListInacbg : prosedurListInacbg;

                // Hapus dari array
                list = list.filter(d => d.code !== code);

                // Update primer-sekunder
                if (table === 'tabel_diagnosa_inacbg') {
                    diagnosaListInacbg = list;
                    if (diagnosaListInacbg.length > 0) {
                        diagnosaListInacbg[0].status = 'Primer';
                        diagnosaListInacbg.slice(1).forEach(d => d.status = 'Sekunder');
                    }
                } else {
                    prosedurListInacbg = list;
                }

                // Hapus dari select2
                $(selector).val($(selector).val().filter(v => v !== code)).trigger('change');

                // Render ulang tabel
                renderTable(list, '#' + table, table === 'tabel_diagnosa_inacbg');

                // Update daftar kode diagnosa
                if (table === 'tabel_diagnosa_inacbg') updateDiagnosaCodes();
            };

            // ---------- Load Data dari Log ----------
            let diagnosaLog = @json($log->diagnosa_inacbg ?? '');
            let prosedurLog = @json($log->procedure_inacbg ?? '');
            let imInvalidItems = [];

            if (diagnosaLog && diagnosaLog.expanded) {
                diagnosaListInacbg = diagnosaLog.expanded.map((d, i) => {
                    const message = d.metadata?.message || '';
                    const imInvalid = d.validcode == 0 || message.includes('IM tidak berlaku');
                    if (imInvalid) {
                        imInvalidItems.push({
                            jenis: 'Diagnosa',
                            code: d.code,
                            desc: d.display,
                            message: message || 'IM tidak berlaku'
                        });
                    }
                    return {
                        code: d.code,
                        desc: d.display + (imInvalid ? ' ⚠ IM tidak berlaku' : ''),
                        status: i === 0 ? 'Primer' : 'Sekunder',
                        im: imInvalid ? 1 : 0
                    };
                });

                renderTable(diagnosaListInacbg, '#tabel_diagnosa_inacbg', true);
                updateDiagnosaCodes();
            }

            if (prosedurLog && prosedurLog.expanded) {
                prosedurListInacbg = prosedurLog.expanded.map((d) => {
                    const message = d.metadata?.message || '';
                    const imInvalid = d.validcode == 0 || message.includes('IM tidak berlaku');
                    if (imInvalid) {
                        imInvalidItems.push({
                            jenis: 'Prosedur',
                            code: d.code,
                            desc: d.display,
                            message: message || 'IM tidak berlaku'
                        });
                    }
                    return {
                        code: d.code,
                        desc: d.display + (imInvalid ? ' ⚠ IM tidak berlaku' : ''),
                        status: 'Primer',
                        im: imInvalid ? 1 : 0
                    };
                });

                renderTable(prosedurListInacbg, '#tabel_prosedur_inacbg', false);
            }

            // ---------- Gabungan Alert IM Tidak Berlaku ----------
            if (imInvalidItems.length > 0) {
                let htmlList = imInvalidItems.map(d =>
                    `<b>${d.jenis}</b>: ${d.code} - ${d.desc}<br>[400] ${d.message}`
                ).join('<br><br>');

                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian: Terdapat Kode dengan IM Tidak Berlaku',
                    html: `
                    Terjadi kesalahan pada item berikut:<br><br>
                    ${htmlList}
                    <br><br><b>Periksa kembali kode sebelum klaim dikirim.</b>
                `,
                    confirmButtonText: 'Mengerti'
                });
            }

            // ---------- Tombol Import ----------
            $('#btnImportInacbg').on('click', function() {
                const nomor_sep = '{{ @$log->nomor_sep }}';
                const btn = $(this);
                btn.prop('disabled', true);

                Swal.fire({
                    title: 'Proses Import iDRG → INA-CBG?',
                    text: "Pastikan hasil Final IDRG sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        btn.prop('disabled', false);
                        return;
                    }

                    $.ajax({
                        url: '/api/eklaim/idrg-to-inacbg-import',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            metadata: {
                                method: 'idrg_to_inacbg_import'
                            },
                            data: {
                                nomor_sep: nomor_sep
                            }
                        }),
                        beforeSend: () => {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mengimpor data iDRG ke INA-CBG...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            console.log('Response INA-CBG Import:', response);

                            if (response.metadata?.code !== 200) {
                                btn.prop('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Import',
                                    text: response.metadata?.message ||
                                        'Terjadi kesalahan saat proses import.'
                                });
                                return;
                            }

                            // === Simpan log hasil import ===
                            $.ajax({
                                url: '/inacbg/import/save-log-rajal',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    nomor_sep: nomor_sep,
                                    response_inacbg_import: JSON.stringify(
                                        response)
                                },
                                beforeSend: () => {
                                    Swal.fire({
                                        title: 'Menyimpan...',
                                        text: 'Menyimpan hasil import ke log...',
                                        allowOutsideClick: false,
                                        didOpen: () => Swal
                                            .showLoading()
                                    });
                                },
                                success: function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Import Selesai',
                                        text: 'Data berhasil diimport dan disimpan. Halaman akan dimuat ulang.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(() => location.reload(),
                                        2000);
                                },
                                error: function() {
                                    btn.prop('disabled', false);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Simpan Log',
                                        text: 'Import berhasil, tapi gagal menyimpan log.'
                                    });
                                }
                            });
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Server',
                                text: 'Tidak dapat terhubung ke server.'
                            });
                        }
                    });
                });
            });

        });
    </script>





    {{-- simpan diagnosa & prosedur inacbg --}}
    <script>
        $(document).ready(function() {
            let nomor_sep = "{{ @$log->nomor_sep }}";

            // === SIMPAN DIAGNOSA (INA-CBG) ===
            $('#diagnosa-inacbg-simpan').on('click', function() {
                let diagnosaCodes = [];
                $('#tabel_diagnosa_inacbg tbody tr').each(function() {
                    let kode = $(this).find('td:eq(1)').text();
                    if (kode) diagnosaCodes.push(kode);
                });
                alert(diagnosaCodes.join('#'));

                let payload = {
                    metadata: {
                        method: "inacbg_diagnosa_set",
                        nomor_sep: nomor_sep
                    },
                    data: {
                        diagnosa: diagnosaCodes.join('#')
                    }
                };

                $.ajax({
                    url: '/api/eklaim/inacbg-diagnosa-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Diagnosa INA-CBG berhasil disimpan', 'success');

                        // Ambil hasil string dari response eksternal
                        let stringData = res.data;

                        // Kirim ke Laravel untuk update log
                        $.ajax({
                            url: '/idrg/update-log',
                            type: 'POST',
                            data: {
                                nomor_sep: nomor_sep,
                                field: 'diagnosa_inacbg',
                                value: stringData,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res2) {
                                console.log('Update diagnosa_inacbg sukses:', res2);
                            },
                            error: function(xhr) {
                                console.error('Gagal update log:', xhr
                                    .responseText);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan diagnosa',
                            'error');
                        console.error(xhr.responseText);
                    }
                });
            });

            // === SIMPAN PROSEDUR (INA-CBG) ===
            $('#prosedur-inacbg-simpan').on('click', function() {
                let procedureList = [];
                $('#tabel_prosedur_inacbg tbody tr').each(function() {
                    let kode = $(this).find('td:eq(1)').text();
                    let qty = $(this).find('td:eq(3) input').val() || '1';
                    if (kode) procedureList.push(`${kode}+${qty}`);
                });

                let payload = {
                    metadata: {
                        method: "inacbg_procedure_set",
                        nomor_sep: nomor_sep
                    },
                    data: {
                        procedure: procedureList.join('#')
                    }
                };

                $.ajax({
                    url: '/api/eklaim/inacbg-procedure-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Prosedur INA-CBG berhasil disimpan', 'success');

                        let stringData = res.data;

                        $.ajax({
                            url: '/idrg/update-log',
                            type: 'POST',
                            data: {
                                nomor_sep: nomor_sep,
                                field: 'procedure_inacbg',
                                value: stringData,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res2) {
                                console.log('Update procedure_inacbg sukses:',
                                    res2);
                            },
                            error: function(xhr) {
                                console.error('Gagal update log:', xhr
                                    .responseText);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan prosedur',
                            'error');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
