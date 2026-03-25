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
                                    <h4>E-Klaim Rawat Inap</h4>
                                    <p class="text-muted mb-3">Daftar pasien rawat inap yang siap diproses klaim</p>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    {{-- Form Pencarian --}}
                                    <form method="GET" action="{{ route('inacbg-ranap.index') }}" class="mb-3">
                                        <div class="input-group">
                                            <input type="text" name="key" class="form-control"
                                                placeholder="Cari no. rawat, nama pasien, bangsal, diagnosa..."
                                                value="{{ request('key') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-search"></i> Cari
                                                </button>
                                                @if(request('key'))
                                                    <a href="{{ route('inacbg-ranap.index') }}" class="btn btn-secondary">
                                                        <i class="fa fa-times"></i> Reset
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </form>

                                    {{-- Tabel Data --}}
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Aksi</th>
                                                    <th>#</th>
                                                    <th>
                                                        <a href="{{ request()->fullUrlWithQuery(['order' => 'kamar_inap.no_rawat ASC']) }}">
                                                            No. Rawat
                                                        </a>
                                                    </th>
                                                    <th>No. RM</th>
                                                    <th>
                                                        <a href="{{ request()->fullUrlWithQuery(['order' => 'pasien.nm_pasien ASC']) }}">
                                                            Nama Pasien
                                                        </a>
                                                    </th>
                                                    <th>Dokter</th>
                                                    <th>Kamar / Bangsal</th>
                                                    <th>
                                                        <a href="{{ request()->fullUrlWithQuery(['order' => 'kamar_inap.tgl_masuk ASC']) }}">
                                                            Tgl Masuk
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a href="{{ request()->fullUrlWithQuery(['order' => 'kamar_inap.tgl_keluar ASC']) }}">
                                                            Tgl Keluar
                                                        </a>
                                                    </th>
                                                    <th>Lama</th>
                                                    <th>Penjamin</th>
                                                    <th>Status Pulang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data as $index => $row)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('inacbg-ranap.show', ['no_rawat' => $row->no_rawat]) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fa fa-file-medical"></i> Klaim
                                                            </a>
                                                        </td>
                                                        <td>{{ $data->firstItem() + $index }}</td>
                                                        <td>{{ $row->no_rawat }}</td>
                                                        <td>{{ $row->no_rkm_medis }}</td>
                                                        <td>
                                                            <strong>{{ $row->nm_pasien }}</strong><br>
                                                            <small class="text-muted">{{ $row->umur }}</small>
                                                        </td>
                                                        <td>{{ $row->nm_dokter }}</td>
                                                        <td>{{ $row->kamar }}</td>
                                                        <td>{{ $row->tgl_masuk }}</td>
                                                        <td>{{ $row->tgl_keluar ?: '-' }}</td>
                                                        <td>{{ $row->lama }} hari</td>
                                                        <td>{{ $row->png_jawab }}</td>
                                                        <td>
                                                            <span class="badge badge-info">
                                                                {{ $row->stts_pulang }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="12" class="text-center text-muted py-4">
                                                            Tidak ada data pasien ditemukan.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }}
                                            dari {{ $data->total() }} data
                                        </small>
                                        {{ $data->links('pagination::bootstrap-4') }}
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