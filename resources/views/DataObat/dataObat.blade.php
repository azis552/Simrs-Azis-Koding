@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        {{-- Alert --}}
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-block">

                                <h4 class="mb-4">
                                    <i class="bi bi-file-text me-2"></i>Detail Resep Obat
                                </h4>

                                {{-- Form Ubah Tanggal / Jam --}}
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <i class="bi bi-clock me-1"></i> Ubah Tanggal / Jam
                                    </h5>
                                    <form action="{{ route('obats.updatejam', $resep_obat->no_resep) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label">No Resep</label>
                                                <input type="text" class="form-control" value="{{ $resep_obat->no_resep }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">No Rawat</label>
                                                <input type="text" class="form-control" value="{{ $resep_obat->no_rawat }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Tanggal Perawatan</label>
                                                <input type="date" class="form-control" name="tanggal" value="{{ $resep_obat->tgl_perawatan }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jam</label>
                                                <input type="time" step="1" class="form-control" name="jam" value="{{ $resep_obat->jam }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <i class="bi bi-save me-1"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <hr class="my-4">

                                {{-- Form Ubah Dokter --}}
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <i class="bi bi-person-badge me-1"></i> Ubah Dokter Peresep
                                    </h5>
                                    <form action="{{ route('obats.updatedokter', $resep_obat->no_resep) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-8">
                                                <label class="form-label">Dokter</label>
                                                <select name="kd_dokter" id="dokter-select" class="form-select" required>
                                                    <option value="">-- Pilih Dokter --</option>
                                                    @foreach ($data_dokter as $d)
                                                        <option value="{{ $d->kd_dokter }}"
                                                            {{ $resep_obat->kd_dokter == $d->kd_dokter ? 'selected' : '' }}>
                                                            {{ $d->nm_dokter }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <i class="bi bi-save me-1"></i> Simpan Dokter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <hr class="my-4">

                                {{-- Form Tambah Obat --}}
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Obat
                                    </h5>
                                    <form action="{{ route('obats.tambah', $resep_obat->no_resep) }}" method="POST">
                                        @csrf
                                        <div class="row g-3 align-items-end">

                                            <div class="col-md-3">
                                                <label class="form-label">Nama Obat</label>
                                                <select name="kode_brng" id="obat-select" class="form-select" required>
                                                    <option value="">Pilih Obat</option>
                                                    @foreach ($data_obat as $o)
                                                        <option value="{{ $o->kode_brng }}">{{ $o->nama_brng }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" name="jml" min="1" value="1" required>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Aturan Pakai</label>
                                                <select name="aturan_pakai" id="aturan-select-tambah" class="form-select">
                                                    <option value="">-- Pilih dari master --</option>
                                                    @foreach ($data_aturan_pakai as $ap)
                                                        <option value="{{ $ap }}">{{ $ap }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    atau ketik manual
                                                    <small class="text-muted">(otomatis simpan ke master)</small>
                                                </label>
                                                <input type="text" class="form-control" name="aturan_manual"
                                                    placeholder="cth: 3 x 1 sesudah makan">
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn btn-success w-100" type="submit">
                                                    <i class="bi bi-plus me-1"></i> Tambah
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <hr class="my-4">

                                {{-- Tabel Data Obat --}}
                                <h5 class="mb-3">
                                    <i class="bi bi-list-ul me-1"></i> Data Obat dalam Resep
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width:45px" class="text-center">No</th>
                                                <th>Nama Obat</th>
                                                <th class="text-center" style="width:130px">Jumlah</th>
                                                <th>Aturan Pakai</th>
                                                <th class="text-center" style="width:70px">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($obats as $r)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $r->nama_brng ?? '-' }}</td>

                                                    {{-- Jumlah + / - --}}
                                                    <td class="text-center p-1">
                                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                                            <form action="{{ route('obats.kurang', [$resep_obat->no_resep, $r->kode_brng]) }}" method="POST" class="m-0">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning btn-sm px-2"
                                                                    onclick="return confirm('Kurangi jumlah {{ addslashes($r->nama_brng) }}?')">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </form>

                                                            <span class="fw-bold mx-1" style="min-width:24px;display:inline-block;text-align:center">
                                                                {{ $r->jml }}
                                                            </span>

                                                            <form action="{{ route('obats.tambah', $resep_obat->no_resep) }}" method="POST" class="m-0">
                                                                @csrf
                                                                <input type="hidden" name="kode_brng" value="{{ $r->kode_brng }}">
                                                                <input type="hidden" name="jml" value="1">
                                                                <input type="hidden" name="aturan_pakai" value="{{ $r->aturan ?? '' }}">
                                                                <input type="hidden" name="aturan_manual" value="">
                                                                <button type="submit" class="btn btn-success btn-sm px-2">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>

                                                    {{-- Aturan Pakai inline edit --}}
                                                    <td class="p-1">
                                                        <form action="{{ route('obats.updateaturan', [$resep_obat->no_resep, $r->kode_brng]) }}"
                                                            method="POST" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="d-flex gap-1 align-items-center">
                                                                <select name="aturan_pakai"
                                                                    class="form-select form-select-sm"
                                                                    style="min-width:180px">
                                                                    <option value="">-- Pilih --</option>
                                                                    @foreach ($data_aturan_pakai as $ap)
                                                                        <option value="{{ $ap }}"
                                                                            @if(($r->aturan ?? '') === $ap) selected @endif>
                                                                            {{ $ap }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <input type="text" name="aturan_manual"
                                                                    class="form-control form-control-sm"
                                                                    style="min-width:150px"
                                                                    placeholder="atau ketik manual..."
                                                                    value="">

                                                                <button type="submit" class="btn btn-primary btn-sm px-2 flex-shrink-0" title="Simpan">
                                                                    <i class="bi bi-save"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </td>

                                                    {{-- Hapus --}}
                                                    <td class="text-center p-1">
                                                        <form action="{{ route('obats.hapus', [$resep_obat->no_resep, $r->kode_brng]) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm px-2"
                                                                onclick="return confirm('Hapus obat {{ addslashes($r->nama_brng) }} dari resep?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        <i class="bi bi-inbox me-2"></i>
                                                        Belum ada obat dalam resep ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
            $('#obat-select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            $('#aturan-select-tambah').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: '-- Pilih dari master --'
            });
            $('#dokter-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Dokter --'
            });

            // Jika dropdown aturan dipilih, kosongkan input manual
            $('#aturan-select-tambah').on('change', function () {
                if ($(this).val()) {
                    $('input[name="aturan_manual"]').first().val('');
                }
            });
            $('input[name="aturan_manual"]').first().on('input', function () {
                if ($(this).val()) {
                    $('#aturan-select-tambah').val('').trigger('change');
                }
            });

            // Saling mengosongkan di tabel
            $('table tbody').on('input', 'input[name="aturan_manual"]', function () {
                if ($(this).val()) {
                    $(this).closest('form').find('select[name="aturan_pakai"]').val('');
                }
            });
            $('table tbody').on('change', 'select[name="aturan_pakai"]', function () {
                if ($(this).val()) {
                    $(this).closest('form').find('input[name="aturan_manual"]').val('');
                }
            });
        });
    </script>
@endsection