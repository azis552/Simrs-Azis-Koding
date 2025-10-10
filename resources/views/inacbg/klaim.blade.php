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
                                                <a class="nav-link active" data-bs-toggle="tab" href="#dataKlaim"
                                                    role="tab">Data Klaim</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#diagnosa"
                                                    role="tab">Diagnosa & Prosedur IDRG</a>
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
                                            <div class="tab-pane fade show active" id="dataKlaim" role="tabpanel">
                                                <h3 class="mb-4">Form Klaim E-Klaim (Set Claim Data)</h3>

                                                <form action="" method="POST">
                                                    @csrf

                                                    {{-- ==================== DATA UTAMA ==================== --}}
                                                    <h5>🧾 Data Utama</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Nomor SEP</label>
                                                            <input type="text" name="nomor_sep"
                                                                value="{{ $sep->no_sep ?? '' }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Nomor Kartu</label>
                                                            <input type="text" name="nomor_kartu"
                                                                value="{{ $pasien->no_peserta }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Nama Dokter</label>
                                                            <input type="text" name="nama_dokter"
                                                                value="{{ $sep->nmdpdjp ?? '' }}" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <label>Tanggal Masuk</label>
                                                            <input type="datetime-local" name="tgl_masuk"
                                                                class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($pasien->tgl_masuk . ' ' . $pasien->jam_masuk)->format('Y-m-d H:i') }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Tanggal Pulang</label>
                                                            <input type="datetime-local" name="tgl_pulang"
                                                                class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($pasien->tgl_keluar . ' ' . $pasien->jam_keluar)->format('Y-m-d H:i') }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Cara Masuk</label>
                                                            <select name="cara_masuk" class="form-control">
                                                                <option value="gp"
                                                                    {{ $sep->asal_rujukan == '1. Faskes 1' ? 'selected' : '' }}>
                                                                    Rujukan FKTP</option>
                                                                <option value="hosp-trans"
                                                                    {{ $sep->asal_rujukan == '2. Faskes 2(RS)' ? 'selected' : '' }}>
                                                                    Rujukan FKRTL</option>
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
                                                                    {{ $sep->jnspelayanan == '1' ? 'selected' : '' }}>Rawat
                                                                    Inap</option>
                                                                <option value="2"
                                                                    {{ $sep->jnspelayanan == '2' ? 'selected' : '' }}>Rawat
                                                                    Jalan</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Kelas Rawat</label>
                                                            <select name="kelas_rawat" class="form-control">
                                                                <option value="1"
                                                                    {{ $sep->klsrawat == '1' ? 'selected' : '' }}>Kelas 1
                                                                </option>
                                                                <option value="2"
                                                                    {{ $sep->klsrawat == '2' ? 'selected' : '' }}>Kelas 2
                                                                </option>
                                                                <option value="3"
                                                                    {{ $sep->klsrawat == '3' ? 'selected' : '' }}>Kelas 3
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Status Pulang</label>
                                                            <select name="discharge_status" class="form-control">
                                                                <option value="1"
                                                                    {{ $pasien->cara_pulang == 'Atas Persetujuan Dokter' ? 'selected' : '' }}>
                                                                    Atas Persetujuan Dokter</option>
                                                                <option value="2"
                                                                    {{ $pasien->cara_pulang == 'Rujuk' ? 'selected' : '' }}>
                                                                    Dirujuk</option>
                                                                <option value="3"
                                                                    {{ $pasien->cara_pulang == 'Atas Permintaan Sendiri' ? 'selected' : '' }}>
                                                                    Atas Permintaan Sendiri</option>
                                                                <option value="4"
                                                                    {{ $pasien->cara_pulang == 'Meninggal' ? 'selected' : '' }}>
                                                                    Meninggal</option>
                                                                <option value="5"
                                                                    {{ $pasien->cara_pulang == 'Lain-lain' ? 'selected' : '' }}>
                                                                    Lain-lain</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== TAMBAHAN ==================== --}}
                                                    <h5 class="mt-4">🧮 Data Tambahan</h5>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>ADL Sub Acute</label>
                                                            <input type="number" name="adl_sub_acute" value="0"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ADL Chronic</label>
                                                            <input type="number" name="adl_chronic" value="0"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ICU Indikator</label>
                                                            <select name="icu_indikator" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ICU Lama Rawat (LOS)</label>
                                                            <input type="number" name="icu_los" value="0"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Upgrade Class</label>
                                                            <select name="upgrade_class_ind" class="form-control">
                                                                <option value="0"
                                                                    {{ $sep->klsnaik == '0' ? 'selected' : '' }}>Tidak
                                                                </option>
                                                                <option value="1"
                                                                    {{ $sep->klsnaik == '1' ? 'selected' : '' }}>Ya
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Lama Hari Naik Kelas</label>
                                                            <input type="number" name="upgrade_class_los"
                                                                value="{{ $sep->klsnaik == null ? '0' : $pasien->lama }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Persentase Biaya Tambahan</label>
                                                            <input type="number" name="add_payment_pct"
                                                                value="{{ $sep->klsnaik == null ? '' : '10' }}"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Berat Lahir (gram)</label>
                                                            <input type="number" name="birth_weight"
                                                                value="{{ $bayi->berat_lahir ?? '' }}"
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
                                                                value="{{ $tensi[0] ?? '0' }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Diastole</label>
                                                            <input type="number" name="diastole"
                                                                value="{{ $tensi[1] ?? '0' }}" class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Dializer Single Use</label>
                                                            <select name="dializer_single_use" class="form-control">
                                                                <option value="1">Ya</option>
                                                                <option value="0" selected>Tidak</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Pasien TB</label>
                                                            <select name="tb_indikator" class="form-control">
                                                                <option value="0" selected>Bukan TB</option>
                                                                <option value="1">Pasien TB</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== COVID SECTION ==================== --}}
                                                    <h5 class="mt-4">🦠 Data Covid</h5>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>Pemulasaraan Jenazah</label>
                                                            <select name="pemulasaraan_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Kantong Jenazah</label>
                                                            <select name="kantong_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Peti Jenazah</label>
                                                            <select name="peti_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Desinfektan Jenazah</label>
                                                            <select name="desinfektan_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Mobil Jenazah</label>
                                                            <select name="mobil_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Desinfektan Mobil Jenazah</label>
                                                            <select name="desinfektan_mobil_jenazah" class="form-control">
                                                                <option value="0" selected>Tidak</option>
                                                                <option value="1">Ya</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Status COVID</label>
                                                            <select name="covid19_status_cd" class="form-control">
                                                                <option value="">Pilih Status</option>
                                                                <option value="1">ODP</option>
                                                                <option value="2">PDP</option>
                                                                <option value="3">Terkonfirmasi</option>
                                                                <option value="4">Suspek</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Nomor Kartu T</label>
                                                            <select name="nomor_kartu_t" class="form-control">
                                                                <option value="nik" selected>NIK</option>
                                                                <option value="paspor">Paspor</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- ==================== TARIF RS ==================== --}}
                                                    <h5 class="mt-4">💰 Tarif RS</h5>
                                                    <div class="row">
                                                        @php
                                                            $tarif = [
                                                                'prosedur_non_bedah',
                                                                'prosedur_bedah',
                                                                'konsultasi',
                                                                'tenaga_ahli',
                                                                'keperawatan',
                                                                'penunjang',
                                                                'radiologi',
                                                                'laboratorium',
                                                                'pelayanan_darah',
                                                                'rehabilitasi',
                                                                'kamar',
                                                                'rawat_intensif',
                                                                'obat',
                                                                'obat_kronis',
                                                                'obat_kemoterapi',
                                                                'alkes',
                                                                'bmhp',
                                                                'sewa_alat',
                                                                'tarif_poli_eks',
                                                            ];
                                                        @endphp
                                                        @foreach ($tarif as $key)
                                                            <div class="col-md-3">
                                                                <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                                                <input type="number"
                                                                    name="tarif_rs[{{ $key }}]" value="100000"
                                                                    class="form-control">
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    {{-- ==================== DATA LAIN ==================== --}}
                                                    <h5 class="mt-4">📋 Lain-lain</h5>

                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label>Episodes</label>
                                                            <input type="text" name="episodes" value=""
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Payor ID</label>
                                                            <input type="text" name="payor_id" value="3"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Payor Code</label>
                                                            <input type="text" name="payor_cd" value="JKN"
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
                                                                value="{{ $coder->no_ktp ?? '' }}" class="form-control">
                                                        </div>
                                                    </div>

                                                    {{-- ==================== SUBMIT ==================== --}}
                                                    <div class="mt-4">
                                                        <button type="submit" class="btn btn-success">
                                                            Simpan Data Klaim
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>

                                            <div class="tab-pane fade" id="diagnosa" role="tabpanel">
                                                <div class="row">
                                                    <!-- Diagnosa -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Diagnosa IDRG (ICD-10)</h5>
                                                                <select id="diagnosa_idrg" class="form-control"
                                                                    multiple="multiple" style="width: 100%"></select>

                                                                <table class="table table-bordered mt-3"
                                                                    id="tabel_diagnosa">
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
                                                        </div>
                                                    </div>

                                                    <!-- Prosedur -->
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5>Prosedur IDRG (ICD-9-CM)</h5>
                                                                <select id="prosedur_idrg" class="form-control"
                                                                    multiple="multiple" style="width: 100%"></select>

                                                                <table class="table table-bordered mt-3"
                                                                    id="tabel_prosedur">
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
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mt-3 text-end">
                                                        <button class="btn btn-success">Grouping IDRG</button>
                                                    </div>
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
    $(document).ready(function () {

        // ---------- Diagnosa ----------
        window.diagnosaList = [];
        initSelect2('#diagnosa_idrg', '/api/icd10', 'tabel_diagnosa', true);

        // ---------- Prosedur ----------
        window.prosedurList = [];
        initSelect2('#prosedur_idrg', '/api/icd9', 'tabel_prosedur', false);

        // ---------- FUNGSI UTAMA ----------
        function initSelect2(selector, url, tableId, isDiagnosa) {
            $(selector).select2({
                placeholder: 'Cari kode atau deskripsi...',
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({ results: data })
                },
                templateResult: item => {
                    if (!item.id) return item.text;
                    return $('<div><b>' + item.code + '</b> — ' + item.description + '</div>');
                },
                templateSelection: item => item.text,
                multiple: true
            });

            // Saat memilih
            $(selector).on('select2:select', function (e) {
                let data = e.params.data;
                let list = isDiagnosa ? diagnosaList : prosedurList;

                // 🔍 Validasi diagnosa primer
                if (isDiagnosa && list.length === 0 && (data.validcode != 1 || data.accpdx !== 'Y')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak dapat dijadikan Primer',
                        text: 'Diagnosa ini tidak valid sebagai primer (validcode!=1 atau accpdx!=Y)',
                        timer: 2500
                    });
                    // batalkan pilihan
                    const current = $(selector).val() || [];
                    $(selector).val(current.filter(v => v !== data.id)).trigger('change');
                    return;
                }

                // 🔁 Cek duplikat
                if (list.some(d => d.code === data.code)) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Diagnosa sudah ada',
                        text: 'Data ini sudah ditambahkan sebelumnya.',
                        timer: 2000
                    });
                    const current = $(selector).val() || [];
                    $(selector).val(current.filter(v => v !== data.id)).trigger('change');
                    return;
                }

                // Tambah ke list
                let status = list.length === 0 ? 'Primer' : 'Sekunder';
                list.push({
                    code: data.code,
                    desc: data.description,
                    status: status
                });

                if (isDiagnosa) diagnosaList = list;
                else prosedurList = list;

                renderTable(list, '#' + tableId);
            });

            // Saat unselect
            $(selector).on('select2:unselect', function (e) {
                let id = e.params.data.id;
                let list = isDiagnosa ? diagnosaList : prosedurList;

                list = list.filter(d => d.code !== id);
                if (isDiagnosa) {
                    diagnosaList = list;
                    if (diagnosaList.length > 0) {
                        diagnosaList[0].status = 'Primer';
                        for (let i = 1; i < diagnosaList.length; i++) diagnosaList[i].status = 'Sekunder';
                    }
                } else {
                    prosedurList = list;
                }

                renderTable(list, '#' + tableId);
            });
        }

        // ---------- Render Tabel ----------
        function renderTable(list, tableId) {
            let tbody = $(tableId + ' tbody');
            tbody.empty();
            list.forEach((d, i) => {
                tbody.append(`
                    <tr>
                        <td>${i + 1}</td>
                        <td>${d.code}</td>
                        <td>${d.desc}</td>
                        <td>${d.status}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="hapusItem('${d.code}', '${tableId.replace('#','')}')">X</button>
                        </td>
                    </tr>
                `);
            });
        }

        // ---------- Fungsi Hapus ----------
        window.hapusItem = function (code, table) {
            let selector = table === 'tabel_diagnosa' ? '#diagnosa_idrg' : '#prosedur_idrg';
            let list = table === 'tabel_diagnosa' ? diagnosaList : prosedurList;

            // hapus dari list global
            list = list.filter(d => d.code !== code);
            if (table === 'tabel_diagnosa') {
                diagnosaList = list;
                if (diagnosaList.length > 0) {
                    diagnosaList[0].status = 'Primer';
                    for (let i = 1; i < diagnosaList.length; i++) diagnosaList[i].status = 'Sekunder';
                }
            } else {
                prosedurList = list;
            }

            // update Select2 (hapus dari value)
            const current = $(selector).val() || [];
            const newVals = current.filter(v => v !== code);
            $(selector).val(newVals).trigger('change');

            renderTable(list, '#' + table);
        };
    });
</script>
@endsection
