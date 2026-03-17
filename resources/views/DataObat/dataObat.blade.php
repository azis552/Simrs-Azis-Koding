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
                <input type="text" class="form-control"
                    value="{{ $resep_obat->no_resep }}" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">No Rawat</label>
                <input type="text" class="form-control"
                    value="{{ $resep_obat->no_rawat }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Perawatan</label>
                <input type="date" class="form-control"
                    name="tanggal" value="{{ $resep_obat->tgl_perawatan }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jam</label>
                <input type="time" step="1" class="form-control"
                    name="jam" value="{{ $resep_obat->jam }}" required>
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

                                {{-- Form Tambah Obat --}}
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Obat
                                    </h5>
                                    <form action="{{ route('obats.tambah', $resep_obat->no_resep) }}" method="POST">
                                        @csrf
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label">Nama Obat</label>
                                                <select name="kode_brng" id="obat-select" class="form-select" required>
                                                    <option value="">Pilih Obat</option>
                                                    @foreach ($data_obat as $o)
                                                        <option value="{{ $o->kode_brng }}">{{ $o->nama_brng }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" name="jml" min="1" value="1"
                                                    required>
                                            </div>
                                            <div class="col-md-3">
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
                                                <th style="width: 50px">No</th>
                                                <th>Nama Obat</th>
                                                <th class="text-center" style="width: 180px">Jumlah</th>
                                                <th class="text-center" style="width: 100px">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($obats as $r)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $r->nama_brng }}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                                            {{-- Tombol Kurang --}}
                                                            <form
                                                                action="{{ route('obats.kurang', [$resep_obat->no_resep, $r->kode_brng]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning btn-sm"
                                                                    title="Kurangi jumlah"
                                                                    onclick="return confirm('Kurangi jumlah {{ addslashes($r->nama_brng) }}?')">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </form>

                                                            {{-- Jumlah --}}
                                                            <span class="fw-bold fs-6 px-2">{{ $r->jml }}</span>

                                                            {{-- Tombol Tambah +1 --}}
                                                            <form action="{{ route('obats.tambah', $resep_obat->no_resep) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="kode_brng"
                                                                    value="{{ $r->kode_brng }}">
                                                                <input type="hidden" name="jml" value="1">
                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                    title="Tambah 1">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <form
                                                            action="{{ route('obats.hapus', [$resep_obat->no_resep, $r->kode_brng]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Hapus obat"
                                                                onclick="return confirm('Hapus obat {{ addslashes($r->nama_brng) }} dari resep?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
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
        });
    </script>
@endsection