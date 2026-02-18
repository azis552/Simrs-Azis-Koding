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

        /* Gaya untuk input Select2 */
        .select2-container .select2-selection--single {
            background-color: white !important;
            /* Ganti background menjadi putih */
            color: black !important;
            /* Warna teks hitam */
            border: 1px solid black !important;
            /* Border hitam */
            border-radius: 4px;
            /* Radius border agar lebih rapi */
            height: 38px;
            /* Sesuaikan tinggi input */
        }

        /* Gaya untuk dropdown Select2 */
        .select2-container .select2-dropdown {
            background-color: white !important;
            /* Ganti background dropdown menjadi putih */
            color: black !important;
            /* Warna teks hitam */
            border: 1px solid black !important;
            /* Border hitam pada dropdown */
            border-radius: 4px;
        }

        /* Gaya untuk pilihan dropdown */
        .select2-container .select2-results__option {
            color: black !important;
            /* Warna teks pilihan dropdown */
        }

        /* Gaya untuk placeholder */
        .select2-container .select2-selection__placeholder {
            color: black !important;
            /* Ubah warna placeholder menjadi hitam */
            font-weight: normal !important;
            /* Gaya font normal pada placeholder */
        }

        /* Gaya untuk teks yang dipilih */
        .select2-container .select2-selection__rendered {
            color: black !important;
            /* Warna teks yang dipilih */
            background-color: white !important;
            /* Background putih pada teks yang dipilih */
        }
    </style>
    <script>
        window.isFinalIdrg = {{ @$log->response_idrg_grouper_final != null ? 'true' : 'false' }};
        window.isFinalInacbg  = {{ @$log->response_inacbg_final != null ? 'true' : 'false' }};
    </script>

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
                                                        data-bs-toggle="tab" href="#inacbgimport" role="tab">Import
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

                                                            <select name="kelas_rawat" class="form-control">
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
                                                            <input type="text" name="kode_tarif" value="AP"
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
                                                            <button type="submit" class="btn btn-pr">
                                                                Simpan Data Klaim
                                                            </button>

                                                        </div>
                                                    @endif

                                                </form>
                                                @if ($isReadonly)
                                                    <form action="{{ route('inacbg.hapusklaimrajal') }}" method="POST">
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
                                                            <tr>
                                                                <td><b>Cost Weight</b></td>
                                                                <td colspan="2">
                                                                    {{ $response_idrg['cost_weight'] ?? '0.00' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>NBR</b></td>
                                                                <td colspan="2">{{ $response_idrg['nbr'] ?? '-' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td><b>Status</b></td>
                                                                <td colspan="2">
                                                                    {{ $response_idrg['status_cd'] ?? '-' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    {{-- Tombol Final iDRG: hanya tampil jika mdc_number ≠ 36 --}}
                                                    @if ($response_idrg['mdc_number'] != '36')
                                                        <button id="btnFinalIdrg"
                                                            class="btn {{ @$log->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }} mt-2"
                                                            {{ $log->response_grouping_idrg == null ? 'disabled' : '' }}>
                                                            ✔ Final IDRG
                                                        </button>
                                                    @else
                                                        <div class="alert alert-danger mt-2">
                                                            ⚠️ Tidak dapat memfinalkan karena kode MDC 36 (Ungroupable or
                                                            Unrelated)
                                                        </div>
                                                    @endif
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
                                                        @if (empty($log->response_send_claim_individual))
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
                                                        <button id="btnImportInacbg"
                                                            {{ @$log->response_inacbg_final != null ? 'disabled' : '' }}
                                                            class="btn btn-primary btn-lg">
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
                                                @if (@$log->response_inacbg_import != null || @$log->procedure_inacbg != null)
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
                                                                    <p>{{ $log->response_claim_final }}</p>

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
                                                                            @if (empty($log->response_send_claim_individual))
                                                                                <button id="btnReeditClaim"
                                                                                    class="btn btn-warning">
                                                                                    <i class="fa fa-refresh"></i> Re-edit
                                                                                    Claim
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
                                                                    <button id="btnFinalInacbg" class="btn btn-warning"
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
                url: '{{ url('') }}/api/eklaim/claim-print',
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
                        url: '{{ url('') }}/api/eklaim/claim-send',
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

                            // ✅ Cek berdasarkan metadata.code === 200
                            if (response.metadata?.code !== 200) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mengirim Klaim',
                                    text: response.metadata?.message ||
                                        'Terjadi kesalahan saat mengirim klaim.'
                                });
                                return;
                            }

                            // === Simpan Log ke Database ===
                            $.ajax({
                                url: '{{ url('') }}/log/claim-send/save-rajal',
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
                                        title: '✅ Klaim Terkirim!',
                                        text: res.message ||
                                            'Klaim berhasil dikirim dan log disimpan.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(() => location.reload(),
                                        2000);
                                },
                                error: function() {
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Klaim Terkirim, Log Gagal Disimpan',
                                        text: 'Klaim berhasil dikirim, tetapi log gagal disimpan.'
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
                url: '{{ url('') }}/reedit-claim-rajal',
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
                        url: '{{ url('') }}/api/eklaim/claim-final',
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
                                url: '{{ url('') }}/save-claim-final-log-rajal',
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
                                    setTimeout(() => location.reload(),
                                        2000);
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

            $(this).prop('disabled', true).text('Memproses Re-edit...');

            $.ajax({
                url: '{{ url('') }}/grouping-inacbg-reedit-final-rajal',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nomor_sep: nomor_sep
                },
                success: function(res) {
                    $('#btnReeditInacbg').prop('disabled', false).text('Re-edit INA-CBG');

                    if (res.status === 'success') {

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
                    url: '{{ url('') }}/api/eklaim/final-inacbg', // <---- langsung pakai URL
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


                        // === 2. Simpan hasil ke log (save-final-inacbg-log) ===
                        $.ajax({
                            url: '{{ url('') }}/save-final-inacbg-log-rajal', // <---- langsung pakai URL
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                nomor_sep: nomorSep,
                                response_inacbg_final: JSON.stringify(response)
                            },
                            success: function(res) {

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



            // --- Auto-load hasil Stage 2 dari log, jika ada ---
            // Cek apakah Stage 2 ada
            if (existingStage2) {
                try {
                    const parsedStage2 = typeof existingStage2 === 'string' ? JSON.parse(existingStage2) :
                        existingStage2;

                    if (parsedStage2) {
                        // Gunakan data Stage 2 jika ada
                        renderGroupingResult(parsedStage2);
                    } else {
                        // Jika Stage 2 tidak ada datanya
                        console.warn("Data Stage 2 tidak ditemukan.");
                    }
                } catch (e) {
                    console.warn('Gagal parse response_inacbg_stage2:', e);
                }
            }
            // --- Jika Stage 2 tidak ada, fallback ke Stage 1 ---
            else if (existingStage1) {
                try {
                    const parsedStage1 = typeof existingStage1 === 'string' ? JSON.parse(existingStage1) :
                        existingStage1;

                    if (parsedStage1) {
                        // Gunakan data Stage 1 jika Stage 2 tidak ada
                        renderGroupingResult(parsedStage1);
                    } else {
                        console.warn("Data Stage 1 tidak ditemukan.");
                    }
                } catch (e) {
                    console.warn('Gagal parse response_inacbg_stage1:', e);
                }
            } else {
                console.warn('Tidak ada data Stage 1 atau Stage 2.');
            }


            // --- Tombol grouping stage 1 ---
            $('#btnGroupingInacbg').on('click', function() {
                $(this).prop('disabled', true).text('Processing...');

                $.ajax({
                    url: '{{ url('') }}/api/eklaim/grouping-inacbg-stage-1',
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
                            renderGroupingResult(response);

                            // --- Simpan log stage 1 (full JSON) ---
                            $.post('{{ url('') }}/save-grouping-inacbg-stage1-log-rajal', {
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
           // Variabel untuk menyimpan kode dan tarif yang dipilih


function renderGroupingResult(response_inacbg) {
    const cbg = response_inacbg.response_inacbg.cbg || {};
    const tarif = parseInt(response_inacbg.response_inacbg.tariff || 0).toLocaleString('id-ID');
    const specialCmgOptions = response_inacbg.special_cmg_option || [];
    const specialCmg = response_inacbg.response_inacbg.special_cmg || [];
let selectedSpecialCmgs = {
    specialProcedure: { code: '', tariff: 0 },
    specialProsthesis: { code: '', tariff: 0 },
    specialInvestigation: { code: '', tariff: 0 },
    specialDrug: { code: '', tariff: 0 }
};
    let html = `
    <div class="card shadow-sm border p-3" style="color:#000;">
        <h5 class="mb-3" style="font-weight:600;">
            <i class="bi bi-hospital"></i> Hasil Grouping INA-CBG
        </h5>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <tr>
                    <td style="font-weight: bold;" class="w-25">Info</td>
                    <td colspan="3">${response_inacbg.response_inacbg.inacbg_version}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;" class="w-25">Kelas Rawat</td>
                    <td colspan="3">${response_inacbg.response_inacbg.kelas}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Group</td>
                    <td colspan="1">${cbg.description || 'N/A'}</td>
                    <td> ${cbg.code || 'N/A'}</td>
                    <td>Rp ${parseInt(response_inacbg.response_inacbg.base_tariff || 0).toLocaleString('id-ID')} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sub Acute</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Chronic</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                    <td colspan="1">-</td>
                </tr>

                <!-- Special Procedure -->
                <tr>
                    <td style="font-weight: bold;">Special Procedure</td>
                    <td>
                        <select class="form-control" id="specialProcedureSelect" onchange="updateSelectedCmg('specialProcedure')" ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}>
                            <option value="None">None</option>`;

    // Menambahkan data Special Procedure
    specialCmgOptions.forEach(option => {
        if (option.type === "Special Procedure") {
            // Mencari nilai tarif dan kode berdasarkan deskripsi
            const matchingCmg = specialCmg.find(cmg => cmg.description.toLowerCase() === option.description.toLowerCase());
            const isSelected = matchingCmg ? true : false;
            if (isSelected) {
            selectedSpecialCmgs.specialProcedure = isSelected ? { code: matchingCmg.code, tariff: matchingCmg.tariff } : { code: '', tariff: 0 };
            }
            html += `
                <option value="${option.code}" data-tarif="${option.tariff}" ${isSelected ? 'selected' : ''}>
                    ${option.description}
                </option>`;
        }
    });

    html += `</select></td>
                    <td id="specialProcedureCode">${selectedSpecialCmgs.specialProcedure.code}</td>
                    <td id="specialProcedurePrice">Rp ${selectedSpecialCmgs.specialProcedure.tariff.toLocaleString('id-ID')}</td>
                </tr>

                <!-- Special Prosthesis -->
                <tr>
                    <td style="font-weight: bold;">Special Prosthesis</td>
                    <td>
                        <select class="form-control" id="specialProsthesisSelect" onchange="updateSelectedCmg('specialProsthesis')" ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}>
                            <option value="None">None</option>`;

    // Menambahkan data Special Prosthesis
    specialCmgOptions.forEach(option => {
        if (option.type === "Special Prosthesis") {
            // Mencari nilai tarif dan kode berdasarkan deskripsi
            const matchingCmg = specialCmg.find(cmg => cmg.description.toLowerCase() === option.description.toLowerCase());
            const isSelected = matchingCmg ? true : false;
            if (isSelected) {
                selectedSpecialCmgs.specialProsthesis = isSelected ? { code: matchingCmg.code, tariff: matchingCmg.tariff } : { code: '', tariff: 0 };
            }
            html += `
                <option value="${option.code}" data-tarif="${option.tariff}" ${isSelected ? 'selected' : ''}>
                    ${option.description}
                </option>`;
        }
    });

    html += `</select></td>
                    <td id="specialProsthesisCode">${selectedSpecialCmgs.specialProsthesis.code}</td>
                    <td id="specialProsthesisPrice">Rp ${selectedSpecialCmgs.specialProsthesis.tariff.toLocaleString('id-ID')}</td>
                </tr>

                <!-- Special Investigation Section -->
                <tr>
                    <td style="font-weight: bold;">Special Investigation</td>
                    <td>
                        <select class="form-control" id="specialInvestigationSelect" onchange="updateSelectedCmg('specialInvestigation')" ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}>
                            <option value="None">None</option>`;

    // Menambahkan data Special Investigation
    specialCmgOptions.forEach(option => {
        if (option.type === "Special Investigation") {
            html += `
                <option value="${option.code}" data-kode="${option.code}">
                    ${option.description}
                </option>`;
        }
    });

    html += `</select></td>
                    <td id="specialInvestigationCode">${selectedSpecialCmgs.specialInvestigation.code}</td>
                    <td id="specialInvestigationPrice">Rp ${selectedSpecialCmgs.specialInvestigation.tariff.toLocaleString('id-ID')}</td>
                </tr>

                <!-- Special Drug Section -->
                <tr>
                    <td style="font-weight: bold;">Special Drug</td>
                    <td>
                        <select class="form-control" id="specialDrugSelect" onchange="updateSelectedCmg('specialDrug')" ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}>
                            <option value="None">None</option>`;

    // Menambahkan data Special Drug
    specialCmgOptions.forEach(option => {
        if (option.type === "Special Drug") {
            html += `
                <option value="${option.code}" data-kode="${option.code}">
                    ${option.description}
                </option>`;
        }
    });

    html += `</select></td>
                    <td id="specialDrugCode">${selectedSpecialCmgs.specialDrug.code}</td>
                    <td id="specialDrugPrice">Rp ${selectedSpecialCmgs.specialDrug.tariff.toLocaleString('id-ID')}</td>
                </tr>

                <tr>
                    <td style="font-weight: bold;">Total Klaim</td>
                    <td></td>
                    <td></td>
                    <td colspan="1" id="totalKlaim">Rp ${tarif}</td>
                </tr>
            </table>
        </div>
    </div>`;

    $('#groupingInacbgResult').html(html);
}

// Fungsi untuk memperbarui pilihan yang dipilih dan menyimpan ke dalam variabel
function updateSelectedCmg(type) {
    const selectedSelect = document.getElementById(`${type}Select`);
    const selectedOption = selectedSelect.options[selectedSelect.selectedIndex];
    
    // Ambil nilai kode dan tarif yang dipilih
    const selectedCode = selectedOption.value;
    const selectedTariff = selectedOption.getAttribute('data-tarif');

    // Update variabel sesuai dengan tipe (specialProcedure, specialProsthesis, specialInvestigation, specialDrug)
    selectedSpecialCmgs[type] = { code: selectedCode, tariff: parseInt(selectedTariff) || 0 };

    // Perbarui tampilan harga dan kode
    document.getElementById(`${type}Code`).innerHTML = selectedSpecialCmgs[type].code;
    document.getElementById(`${type}Price`).innerHTML = `Rp ${selectedSpecialCmgs[type].tariff.toLocaleString('id-ID')}`;

    // Menghitung total klaim
    const total = Object.values(selectedSpecialCmgs).reduce((acc, cmg) => acc + cmg.tariff, parseInt($('#totalKlaim').text().replace('Rp ', '').replace(',', '') || 0));

    // Update total klaim
    document.getElementById('totalKlaim').innerHTML = `Rp ${total.toLocaleString('id-ID')}`;
}



            // Fungsi untuk mengirimkan request AJAX dengan special_cmg yang dipilih
            function sendGroupingStage2Request() {
                const nomor_sep = '{{ @$log->nomor_sep }}';

                // Ambil nilai dari dropdown dan simpan dalam array
                const selectedSpecialCmgs = [];

                // Ambil nilai dari dropdown Special Procedure
                const specialProcedureSelect = document.getElementById('specialProcedureSelect');
                const selectedSpecialProcedure = specialProcedureSelect.value;
                if (selectedSpecialProcedure !== "None") {
                    selectedSpecialCmgs.push(selectedSpecialProcedure);
                }

                // Ambil nilai dari dropdown Special Prosthesis
                const specialProsthesisSelect = document.getElementById('specialProsthesisSelect');
                const selectedSpecialProsthesis = specialProsthesisSelect.value;
                if (selectedSpecialProsthesis !== "None") {
                    selectedSpecialCmgs.push(selectedSpecialProsthesis);
                }

                // Ambil nilai dari dropdown Special Investigation
                const specialInvestigationSelect = document.getElementById('specialInvestigationSelect');
                const selectedSpecialInvestigation = specialInvestigationSelect.value;
                if (selectedSpecialInvestigation !== "None") {
                    selectedSpecialCmgs.push(selectedSpecialInvestigation);
                }

                // Ambil nilai dari dropdown Special Drug
                const specialDrugSelect = document.getElementById('specialDrugSelect');
                const selectedSpecialDrug = specialDrugSelect.value;
                if (selectedSpecialDrug !== "None") {
                    selectedSpecialCmgs.push(selectedSpecialDrug);
                }



                // Gabungkan semua pilihan special_cmg dengan tanda #
                const specialCmg = selectedSpecialCmgs.join('#'); // Misalnya, "RR04#YY01"

                // Menyiapkan data untuk dikirim ke server
                const data = {
                    metadata: {
                        method: 'grouper',
                        stage: '2',
                        grouper: 'inacbg',
                        special_cmg: specialCmg // Menggunakan special_cmg yang digabungkan
                    },
                    data: {
                        nomor_sep: nomor_sep,
                        special_cmg: specialCmg // Mengirimkan special_cmg yang digabungkan ke API
                    }
                };

                // Mengirim request menggunakan AJAX
                $.ajax({
                    url: '{{ url('') }}/api/eklaim/grouping-inacbg-stage-2', // URL untuk API
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify(data), // Mengirimkan data dalam format JSON
                    success: function(response) {
                        // Proses hasil dari API
                        if (response && response.response_inacbg) {
                            console.log('Hasil Grouping Stage 2:', response);

                            if (response.response_inacbg) {


                                // --- Simpan log stage 1 (full JSON) ---
                                $.post('{{ url('') }}/save-grouping-inacbg-stage2-log-rajal', {
                                    _token: '{{ csrf_token() }}',
                                    nomor_sep: nomor_sep,
                                    response_inacbg_stage2: JSON.stringify(response)
                                });
                                location.reload();
                            } else {
                                $('#groupingInacbgResult').html(
                                    '<div class="alert alert-danger">Tidak ada hasil grouping.</div>'
                                );
                            }



                            // Tampilkan hasil grouping atau lakukan aksi lain
                            alert("Proses Grouping Stage 2 berhasil!");
                        } else {
                            alert("Gagal memproses Stage 2!");
                        }
                    },
                    error: function(err) {
                        console.error('Error dalam pengiriman Stage 2:', err.responseJSON || err);
                        alert('Terjadi kesalahan dalam proses Grouping Stage 2!');
                    }
                });
            }

            // Event listener untuk menangani perubahan pada dropdown
            document.getElementById('specialProcedureSelect').addEventListener('change', function() {
                sendGroupingStage2Request();
            });

            document.getElementById('specialProsthesisSelect').addEventListener('change', function() {
                sendGroupingStage2Request();
            });

            document.getElementById('specialInvestigationSelect').addEventListener('change', function() {
                sendGroupingStage2Request();
            });

            document.getElementById('specialDrugSelect').addEventListener('change', function() {
                sendGroupingStage2Request();
            });


        });
    </script>





    {{-- end grouping inacbg --}}
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
                        url: '{{ url('') }}/idrg-grouper-reedit-rajal',
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
                                    url: '{{ url('') }}/hapus-final-idrg-rajal',
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
                    url: '{{ url('') }}/grouping-idrg-rajal',
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
                            url: '{{ url('') }}/save-grouping-idrg-log-rajal',
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
                    url: '{{ url('') }}/final-idrg-rajal',
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
                                url: '{{ url('') }}/save-final-idrg-log-rajal',
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
    {{--  idrg diagnosa dan procedure --}}
    <script>
        const logDiagnosa = @json($log->diagnosa_idrg ?? null);
        const logProsedur = @json($log->procedure_idrg ?? null);
    </script>
    {{-- diagnosa & prosedur idrg --}}
    <script>
        $(document).ready(function() {

            // =====================================================
            // 🔹 SETUP CSRF TOKEN GLOBAL
            // =====================================================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // =====================================================
            // 🔹 VARIABEL GLOBAL
            // =====================================================
            let diagnosaList = [];
            let prosedurList = [];
            const updateLogURL = "{{ url('') }}/idrg/update-log-rajal";

            // =====================================================
            // 🔹 LOAD DATA DARI LOG DATABASE
            // =====================================================
            function loadFromLog() {
                if (logDiagnosa) {
                    try {
                        const parsed = typeof logDiagnosa === 'string' ? JSON.parse(logDiagnosa) : logDiagnosa;
                        const expanded = parsed.expanded || [];
                        diagnosaList = expanded.map((d, i) => ({
                            code: d.code,
                            desc: d.display || d.description,
                            status: i === 0 ? 'Primer' : 'Sekunder'
                        }));
                        renderTable(diagnosaList, '#tabel_diagnosa', true);
                    } catch (e) {
                        console.warn("⚠️ Gagal parsing logDiagnosa:", e);
                    }
                }

                if (logProsedur) {
                    try {
                        const parsed = typeof logProsedur === 'string' ?
                            JSON.parse(logProsedur) :
                            logProsedur;

                        const expanded = parsed.expanded || [];

                        prosedurList = expanded.map((d, i) => ({
                            code: d.code,
                            desc: d.display || d.description,
                            qty: d.multiplicity ? Number(d.multiplicity) : 1,
                            status: i === 0 ? 'Primer' : 'Sekunder'
                        }));

                        renderTable(prosedurList, '#tabel_prosedur', false);

                    } catch (e) {
                        console.warn("⚠️ Gagal parsing logProsedur:", e);
                    }

                }
            }
            loadFromLog();

            // =====================================================
            // 🔹 INISIALISASI SELECT2
            // =====================================================
            initSelect2('#diagnosa_idrg', "{{ url('/api/icd10_idrg') }}", '#tabel_diagnosa', true);
            initSelect2('#prosedur_idrg', "{{ url('/api/icd9_idrg') }}", '#tabel_prosedur', false);

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
                    templateResult: item => {
                        if (!item.id) return item.text;
                        return $('<div>').append($('<b>').text(item.code)).append(' — ' + item
                            .description);
                    },
                    templateSelection: item => item.text,
                    // Nonaktifkan multiple selection
                    multiple: false,
                    bootstrap4: true,
                });

                // Reset select2 setelah pemilihan item
                $(selector).on('select2:select', function(e) {
                    // Reset input setelah memilih
                    $(selector).val(null).trigger('change');
                });
                // Saat pilih item
                $(selector).on('select2:select', function(e) {
                    const data = e.params.data;
                    let list = isDiagnosa ? diagnosaList : prosedurList;

                    if (list.length === 0 && (data.validcode != 1 || data.accpdx !== 'Y')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak valid sebagai Primer',
                            timer: 2500
                        });
                        $(this).val(null).trigger('change');
                        return;
                    }

                    // 🔴 CEK DUPLIKASI → HANYA UNTUK DIAGNOSA
                    if (isDiagnosa && list.some(d => d.code === data.code)) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Diagnosa sudah ada',
                            timer: 1500
                        });
                        $(this).val(null).trigger('change');
                        return;
                    }

                    const item = {
                        code: data.code,
                        desc: data.description,
                        status: list.length === 0 ? 'Primer' : 'Sekunder',
                        qty: isDiagnosa ? undefined : 1
                    };
                    list.push(item);

                    if (isDiagnosa) diagnosaList = list;
                    else prosedurList = list;

                    renderTable(list, tableId, isDiagnosa);
                    sendToEklaim(isDiagnosa);
                });

                // Saat unselect
                $(selector).on('select2:unselect', function(e) {
                    const id = e.params.data.id;
                    let list = isDiagnosa;
                    list = list.filter(d => d.code !== id);

                    if (isDiagnosa) diagnosaList = list;

                    renderTable(list, tableId, isDiagnosa);
                    sendToEklaim(isDiagnosa);
                });
            }

            function parseProcedureResponse(res) {
                return res.expanded.map(item => ({
                    code: item.code,
                    desc: item.display,
                    qty: item.multiplicity ? Number(item.multiplicity) : 1,
                    status: item.validcode === "1" ? "Valid" : "Tidak Valid"
                }));
            }
            // =====================================================
            // 🔹 RENDER TABEL
            // =====================================================
            function renderTable(list, tableId, isDiagnosa) {
                const tbody = $(tableId + ' tbody');
                tbody.empty();
                if (!list.length) {
                    tbody.append(`<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>`);
                    return;
                }
                list.forEach((d, i) => {
                    tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${d.code}</td>
                    <td>${d.desc}</td>
                    ${!isDiagnosa ? `<td><input type="number" min="1" value="${d.qty}" data-code="${d.code}" class="form-control form-control-sm qty-input" style="width:80px"  ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}></td>` : ''}
                    <td>${d.status}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="hapusItem('${d.code}', ${isDiagnosa}, $(this).closest('tr').index())" ${(typeof isFinalIdrg !== 'undefined' && isFinalIdrg) ? 'disabled' : ''}>X</button></td>

                </tr>
            `);
                });
            }

            // =====================================================
            // 🔹 HAPUS ITEM
            // =====================================================
            window.hapusItem = function(code, isDiagnosa, rowIndex) {
                let list;

                // Pilih list yang sesuai, apakah itu diagnosa atau prosedur
                if (isDiagnosa) {
                    list = diagnosaList;
                } else {
                    list = prosedurList;
                }

                // Pastikan kita hanya menghapus item yang ada pada baris yang dipilih
                const item = list[rowIndex];

                if (!item || item.code !== code) {
                    console.warn('Item yang ingin dihapus tidak ditemukan di baris yang dipilih:', code);
                    return;
                }

                // Menghapus item berdasarkan baris yang dipilih
                list.splice(rowIndex, 1);

                // Memperbarui list yang sesuai setelah item dihapus
                if (isDiagnosa) {
                    diagnosaList = list; // Update diagnosaList jika ini diagnosa
                } else {
                    prosedurList = list; // Update prosedurList jika ini prosedur
                }

                const selector = isDiagnosa ? '#diagnosa_idrg' : '#prosedur_idrg';
                // Memperbarui nilai selector dengan menghapus 'code' dari value yang ada
                $(selector).val((($(selector).val()) || []).filter(v => v !== code)).trigger('change');

                // Render ulang tabel dengan data yang telah diupdate
                renderTable(list, isDiagnosa ? '#tabel_diagnosa' : '#tabel_prosedur', isDiagnosa);

                // Kirimkan data terbaru ke eklaim setelah pembaruan
                if (isDiagnosa) {
                    sendToEklaim(true); // Jika diagnosa yang dihapus, kirim data diagnosa
                } else {
                    sendProcedureToEklaim(); // Jika prosedur yang dihapus, kirim data prosedur
                }
            };





            // =====================================================
            // 🔹 KIRIM KE EKLAIM
            // =====================================================
            async function sendToEklaim(isDiagnosa) {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                if (!nomor_sep) return;

                const endpoint = isDiagnosa ?
                    "{{ url('') }}/api/eklaim/idrg-diagnosa-set" :
                    "{{ url('') }}/api/eklaim/idrg-procedure-set";

                const list = isDiagnosa ? diagnosaList : prosedurList;
                const codes = list.map(d => d.code).join('#') || '#';

                const payload = {
                    metadata: {
                        method: isDiagnosa ? 'idrg_diagnosa_set' : 'idrg_procedure_set',
                        nomor_sep
                    },
                    data: {
                        [isDiagnosa ? 'diagnosa' : 'procedure']: codes
                    }
                };

                try {
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    if (res.metadata?.code === 200 || res.code === 200) {
                        await getFromEklaim(isDiagnosa);
                    }
                } catch (err) {
                    console.error("❌ Error kirim:", err);
                }
            }

            async function sendProcedureToEklaim() {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                if (!nomor_sep) return;

                const endpoint = "{{ url('') }}/api/eklaim/idrg-procedure-set";

                // 🔥 FORMAT RESMI INA-CBG
                const procedure = prosedurList
                    .map((p) =>
                        `${p.code}+${p.qty}`) // Pastikan qty sesuai dengan prosedur berdasarkan urutan index
                    .join('#') || '#'; // Gabungkan dengan '#' sebagai pemisah antar prosedur

                const payload = {
                    metadata: {
                        method: 'idrg_procedure_set',
                        nomor_sep
                    },
                    data: {
                        procedure
                    }
                };

                console.log('📤 PAYLOAD PROCEDURE', payload);

                try {
                    // Mengirimkan payload ke endpoint API
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    console.log('✅ RESPONSE SET PROCEDURE', res);

                    // ✅ JIKA SUKSES → LANGSUNG GET ULANG DATA PROCEDURE
                    if (res?.metadata?.code === 200 || res?.code === 200) {
                        await getFromEklaim(false); // false = procedure
                    }

                } catch (err) {
                    console.error('❌ Gagal kirim procedure:', err);
                }
            }


            // =====================================================
            // 🔹 UPDATE QTY
            // =====================================================
            $(document).on('input change', '.qty-input', function() {
                const code = $(this).data('code').toString().trim(); // Ambil kode dari data-attribute
                const qty = Math.max(parseInt($(this).val()) || 1,
                    1); // Ambil nilai qty dan pastikan minimal 1

                // Cari item yang sesuai berdasarkan kode dan pastikan untuk mengupdate qty yang sesuai
                const index = $(this).closest('tr').index(); // Ambil index dari baris input yang diubah
                const item = prosedurList[index]; // Dapatkan item yang sesuai berdasarkan index baris

                if (!item) {
                    console.warn('PROCEDURE TIDAK DITEMUKAN:', code, prosedurList);
                    return;
                }

                item.qty = qty; // Update qty sesuai dengan item yang diubah

                console.log('UPDATE QTY', code, qty, 'Index:', index);
                sendProcedureToEklaim(); // Kirim data setelah update qty
            });



            // =====================================================
            // 🔹 GET DARI EKLAIM & SIMPAN LOG
            // =====================================================
            async function getFromEklaim(isDiagnosa) {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                const endpoint = isDiagnosa ?
                    "{{ url('') }}/api/eklaim/idrg-diagnosa-get" :
                    "{{ url('') }}/api/eklaim/idrg-procedure-get";

                const payload = {
                    metadata: {
                        method: isDiagnosa ? 'idrg_diagnosa_get' : 'idrg_procedure_get'
                    },
                    data: {
                        nomor_sep
                    }
                };

                try {
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    const resultData = res.data || res.response?.data || null;

                    if (resultData) {
                        await $.ajax({
                            url: updateLogURL,
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                nomor_sep,
                                field: isDiagnosa ? 'diagnosa_idrg' : 'procedure_idrg',
                                value: JSON.stringify(resultData)
                            }
                        });
                        console.log("💾 Log diperbarui:", resultData);
                    }
                } catch (err) {
                    console.error("❌ Gagal GET dari e-Klaim:", err);
                }
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
                    url: '{{ url('') }}/api/eklaim/idrg-diagnosa-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Diagnosa berhasil disimpan', 'success');

                        // Ambil hasil dari respon eksternal
                        let stringData = res.data;
                        // Kirim ke Laravel untuk update kolom diagnosa_idrg
                        $.ajax({
                            url: '{{ url('') }}/idrg/update-log-rajal',
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
                    url: '{{ url('') }}/api/eklaim/idrg-procedure-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Prosedur berhasil disimpan', 'success');

                        // Ambil hasil string dari respon eksternal
                        let stringData = res.data;

                        // Kirim ke Laravel untuk update kolom procedure_idrg
                        $.ajax({
                            url: '{{ url('') }}/idrg/update-log-rajal',
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
    {{-- inacbg --}}

    <script>
        const logDiagnosaInacbg = @json($log->diagnosa_inacbg ?? null);
        const logProsedurInacbg = @json($log->procedure_inacbg ?? null);
    </script>

    <script>
        $(document).ready(function() {

            // =====================================================
            // 🔹 SETUP CSRF TOKEN GLOBAL
            // =====================================================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // =====================================================
            // 🔹 VARIABEL GLOBAL
            // =====================================================
            let diagnosaListInacbg = [];
            let prosedurListInacbg = [];
            const updateLogURLInacbg = "{{ url('') }}/idrg/update-log-rajal";

            // =====================================================
            // 🔹 LOAD DATA DARI LOG DATABASE
            // =====================================================
            function loadFromLogInacbg() {
                if (logDiagnosaInacbg) {
                    try {
                        const parsed = typeof logDiagnosaInacbg === 'string' ? JSON.parse(logDiagnosaInacbg) :
                            logDiagnosaInacbg;
                        const expanded = parsed.expanded || [];
                        diagnosaListInacbg = expanded.map((d, i) => ({
                            code: d.code,
                            desc: d.display || d.description,
                            status: i === 0 ? 'Primer' : 'Sekunder',
                            metadata: d.metadata // Menyimpan metadata untuk keperluan pengecekan
                        }));
                        renderTableinacbg(diagnosaListInacbg, '#tabel_diagnosa_inacbg', true);
                    } catch (e) {
                        console.warn("⚠️ Gagal parsing logDiagnosaInacbg:", e);
                    }
                }

                if (logProsedurInacbg) {
                    try {
                        const parsed = typeof logProsedurInacbg === 'string' ? JSON.parse(logProsedurInacbg) :
                            logProsedurInacbg;
                        const expanded = parsed.expanded || [];

                        prosedurListInacbg = expanded.map((d, i) => ({
                            code: d.code,
                            desc: d.display || d.description,
                            qty: d.multiplicity ? Number(d.multiplicity) : 1,
                            status: i === 0 ? 'Primer' : 'Sekunder',
                            metadata: d.metadata // Menyimpan metadata untuk keperluan pengecekan
                        }));

                        renderTableinacbg(prosedurListInacbg, '#tabel_prosedur_inacbg', false);

                    } catch (e) {
                        console.warn("⚠️ Gagal parsing logProsedur:", e);
                    }
                }
            }
            loadFromLogInacbg();

            function renderTableinacbg(list, tableId, isDiagnosa) {
                const tbody = $(tableId + ' tbody');
                tbody.empty();

                if (!list.length) {
                    tbody.append(`<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>`);
                    return;
                }

                list.forEach((d, i) => {
                    let alertMessage = "";
                    // Menambahkan pesan alert jika metadata menunjukkan "IM tidak berlaku"
                    if (d.metadata && d.metadata.message === "IM tidak berlaku") {
                        alertMessage =
                            `<span class="text-danger">⚠️ ${d.metadata.message}</span>`; // Menampilkan peringatan dengan warna merah
                    }

                    tbody.append(`
            <tr>
                <td>${i + 1}</td>
                <td>${d.code}</td>
                <td>${d.desc} ${alertMessage}</td> <!-- Menambahkan alert di samping deskripsi -->
                <td>${d.status}</td>
                <td><button class="btn btn-danger btn-sm" onclick="hapusIteminacbg('${d.code}', ${isDiagnosa}, $(this).closest('tr').index())" ${(typeof isFinalInacbg !== 'undefined' && isFinalInacbg) ? 'disabled' : ''}>X</button></td>
            </tr>
        `);
                });
            }


            // =====================================================
            // 🔹 INISIALISASI SELECT2
            // =====================================================
            initSelect2inacbg('#diagnosa_inacbg', "{{ url('/api/icd10') }}", '#tabel_diagnosa_inacbg', true);
            initSelect2inacbg('#prosedur_inacbg', "{{ url('/api/icd9') }}", '#tabel_prosedur_inacbg', false);

            function initSelect2inacbg(selector, url, tableId, isDiagnosa) {
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
                    templateResult: item => {
                        if (!item.id) return item.text;
                        return $('<div>').append($('<b>').text(item.code)).append(' — ' + item
                            .description);
                    },
                    templateSelection: item => item.text,
                    // Nonaktifkan multiple selection
                    multiple: false,
                    bootstrap4: true,
                });

                // Reset select2 setelah pemilihan item
                $(selector).on('select2:select', function(e) {
                    // Reset input setelah memilih
                    $(selector).val(null).trigger('change');
                });
                // Saat pilih item
                $(selector).on('select2:select', function(e) {
                    const data = e.params.data;
                    let list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;

                    // if (list.length === 0 && (data.validcode != 1 || data.accpdx !== 'Y')) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Tidak valid sebagai Primer',
                    //         timer: 2500
                    //     });
                    //     $(this).val(null).trigger('change');
                    //     return;
                    // }

                    // 🔴 CEK DUPLIKASI → HANYA UNTUK DIAGNOSA
                    if (isDiagnosa && list.some(d => d.code === data.code)) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Diagnosa sudah ada',
                            timer: 1500
                        });
                        $(this).val(null).trigger('change');
                        return;
                    }

                    const item = {
                        code: data.code,
                        desc: data.description,
                        status: list.length === 0 ? 'Primer' : 'Sekunder',
                        qty: isDiagnosa ? undefined : 1
                    };
                    list.push(item);

                    if (isDiagnosa) diagnosaListInacbg = list;
                    else prosedurListInacbg = list;

                    renderTableinacbg(list, tableId, isDiagnosa);
                    sendToEklaiminacbg(isDiagnosa);
                });

                // Saat unselect
                $(selector).on('select2:unselect', function(e) {
                    const id = e.params.data.id;
                    let list = isDiagnosa;
                    list = list.filter(d => d.code !== id);

                    if (isDiagnosa) diagnosaListInacbg = list;

                    renderTableinacbg(list, tableId, isDiagnosa);
                    sendToEklaiminacbg(isDiagnosa);
                });
            }

            function parseProcedureResponse(res) {
                return res.expanded.map(item => ({
                    code: item.code,
                    desc: item.display,
                    qty: item.multiplicity ? Number(item.multiplicity) : 1,
                    status: item.validcode === "1" ? "Valid" : "Tidak Valid"
                }));
            }


            // =====================================================
            // 🔹 HAPUS ITEM
            // =====================================================
            window.hapusIteminacbg = function(code, isDiagnosa, rowIndex) {
                let list;

                // Pilih list yang sesuai, apakah itu diagnosa atau prosedur
                if (isDiagnosa) {
                    list = diagnosaListInacbg;
                } else {
                    list = prosedurListInacbg;
                }

                // Pastikan kita hanya menghapus item yang ada pada baris yang dipilih
                const item = list[rowIndex];

                if (!item || item.code !== code) {
                    console.warn('Item yang ingin dihapus tidak ditemukan di baris yang dipilih:', code);
                    return;
                }

                // Menghapus item berdasarkan baris yang dipilih
                list.splice(rowIndex, 1);

                // Memperbarui list yang sesuai setelah item dihapus
                if (isDiagnosa) {
                    diagnosaListInacbg = list; // Update diagnosaListInacbg jika ini diagnosa
                } else {
                    prosedurListInacbg = list; // Update prosedurListInacbg jika ini prosedur
                }

                const selector = isDiagnosa ? '#diagnosa_inacbg' : '#prosedur_inacbg';
                // Memperbarui nilai selector dengan menghapus 'code' dari value yang ada
                $(selector).val((($(selector).val()) || []).filter(v => v !== code)).trigger('change');

                // Render ulang tabel dengan data yang telah diupdate
                renderTableinacbg(list, isDiagnosa ? '#tabel_diagnosa_inacbg' : '#tabel_prosedur_inacbg',
                    isDiagnosa);

                // Kirimkan data terbaru ke eklaim setelah pembaruan
                if (isDiagnosa) {
                    sendToEklaiminacbg(true); // Jika diagnosa yang dihapus, kirim data diagnosa
                } else {
                    sendProcedureToEklaiminacbg(); // Jika prosedur yang dihapus, kirim data prosedur
                }
            };





            // =====================================================
            // 🔹 KIRIM KE EKLAIM
            // =====================================================
            async function sendToEklaiminacbg(isDiagnosa) {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                if (!nomor_sep) return;

                const endpoint = isDiagnosa ?
                    "{{ url('') }}/api/eklaim/inacbg-diagnosa-set" :
                    "{{ url('') }}/api/eklaim/inacbg-procedure-set";

                const list = isDiagnosa ? diagnosaListInacbg : prosedurListInacbg;
                const codes = list.map(d => d.code).join('#') || '#';

                const payload = {
                    metadata: {
                        method: isDiagnosa ? 'inacbg_diagnosa_set' : 'inacbg_procedure_set',
                        nomor_sep
                    },
                    data: {
                        [isDiagnosa ? 'diagnosa' : 'procedure']: codes
                    }
                };

                try {
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    if (res.metadata?.code === 200 || res.code === 200) {
                        await getFromEklaiminacbg(isDiagnosa);
                    }
                } catch (err) {
                    console.error("❌ Error kirim:", err);
                }
            }

            async function sendProcedureToEklaiminacbg() {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                if (!nomor_sep) return;

                const endpoint = "{{ url('') }}/api/eklaim/inacbg-procedure-set";

                // 🔥 FORMAT RESMI INA-CBG TANPA qty
                const procedure = prosedurListInacbg
                    .map((p) => p.code) // Hanya ambil kode prosedur tanpa qty
                    .join('#') || '#'; // Gabungkan dengan '#' sebagai pemisah antar prosedur

                const payload = {
                    metadata: {
                        method: 'inacbg_procedure_set',
                        nomor_sep
                    },
                    data: {
                        procedure
                    }
                };

                console.log('📤 PAYLOAD PROCEDURE', payload);

                try {
                    // Mengirimkan payload ke endpoint API
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    console.log('✅ RESPONSE SET PROCEDURE', res);

                    // ✅ JIKA SUKSES → LANGSUNG GET ULANG DATA PROCEDURE
                    if (res?.metadata?.code === 200 || res?.code === 200) {
                        await getFromEklaiminacbg(false); // false = procedure
                    }

                } catch (err) {
                    console.error('❌ Gagal kirim procedure:', err);
                }
            }
            // =====================================================
            // 🔹 GET DARI EKLAIM & SIMPAN LOG
            // =====================================================
            async function getFromEklaiminacbg(isDiagnosa) {
                const nomor_sep = $('input[name="nomor_sep"]').val()?.trim();
                const endpoint = isDiagnosa ?
                    "{{ url('') }}/api/eklaim/inacbg-diagnosa-get" :
                    "{{ url('') }}/api/eklaim/inacbg-procedure-get";

                const payload = {
                    metadata: {
                        method: isDiagnosa ? 'inacbg_diagnosa_get' : 'inacbg_procedure_get'
                    },
                    data: {
                        nomor_sep
                    }
                };

                try {
                    const res = await $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json'
                    });

                    const resultData = res.data || res.response?.data || null;

                    if (resultData) {
                        await $.ajax({
                            url: updateLogURLInacbg,
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                nomor_sep,
                                field: isDiagnosa ? 'diagnosa_inacbg' : 'procedure_inacbg',
                                value: JSON.stringify(resultData)
                            }
                        });
                        console.log("💾 Log diperbarui:", resultData);
                    }
                } catch (err) {
                    console.error("❌ Gagal GET dari e-Klaim:", err);
                }
            }

        });
    </script>
    {{-- import inacbg --}}
    <script>
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
                    url: '{{ url('') }}/api/eklaim/idrg-to-inacbg-import',
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
                            url: '{{ url('') }}/inacbg/import/save-log-rajal',
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
    </script>






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
                    url: '{{ url('') }}/api/eklaim/inacbg-diagnosa-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Diagnosa INA-CBG berhasil disimpan', 'success');

                        // Ambil hasil string dari response eksternal
                        let stringData = res.data;

                        // Kirim ke Laravel untuk update log
                        $.ajax({
                            url: '{{ url('') }}/idrg/update-log-rajal',
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
                    url: '{{ url('') }}/api/eklaim/inacbg-procedure-set',
                    type: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    success: function(res) {
                        Swal.fire('Sukses', 'Prosedur INA-CBG berhasil disimpan', 'success');

                        let stringData = res.data;

                        $.ajax({
                            url: '{{ url('') }}/idrg/update-log-rajal',
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
