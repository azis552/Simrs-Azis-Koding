@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        <div class="card">
                            <div class="card-block">
                                <h4 class="sub-title">Data Rawat Jalan</h4>

                                {{-- 🔍 Form Pencarian --}}
                                <form method="GET" action="{{ route('inacbg-rajal.index') }}" class="mb-3">
                                    <div class="form-row align-items-center">
                                        <div class="col">
                                            <input type="text" name="key" value="{{ request('key') }}"
                                                class="form-control" placeholder="Cari Nama Pasien / No Rawat / No RM">
                                        </div>

                                        <div class="col-auto">
                                            <select name="order" class="form-control">
                                                <option value="reg_periksa.tgl_registrasi DESC"
                                                    {{ request('order') == 'reg_periksa.tgl_registrasi DESC' ? 'selected' : '' }}>
                                                    Tanggal Registrasi ↓
                                                </option>
                                                <option value="reg_periksa.tgl_registrasi ASC"
                                                    {{ request('order') == 'reg_periksa.tgl_registrasi ASC' ? 'selected' : '' }}>
                                                    Tanggal Registrasi ↑
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> Cari
                                            </button>
                                            <a href="{{ route('inacbg-rajal.index') }}" class="btn btn-secondary">
                                                <i class="fa fa-refresh"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </form>

                                {{-- 🔢 Tabel Data --}}
                                <div class="dt-responsive table-responsive">
                                    <table class="table table-striped table-bordered nowrap">
                                        <thead class="thead-light">
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>No. Rawat</th>
                                                <th>Aksi</th>
                                                <th>No. RM</th>
                                                <th>Nama Pasien</th>
                                                <th>Alamat</th>
                                                <th>Poliklinik</th>
                                                <th>Dokter</th>
                                                <th>Tgl Registrasi</th>
                                                <th>Jam</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $row)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                                                    </td>
                                                    <td>{{ $row->no_rawat }}</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('inacbg-rajal.show') }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="no_rawat" value="{{ $row->no_rawat }}">
                                                            <button type="submit" class="btn btn-sm btn-info">
                                                                <i class="fa fa-folder-open"></i> Klaim
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>{{ $row->no_rkm_medis }}</td>
                                                    <td>{{ $row->nm_pasien }}</td>
                                                    <td>{{ $row->alamat ?? '-' }}</td>
                                                    <td>{{ $row->nm_poli ?? '-' }}</td>
                                                    <td>{{ $row->nm_dokter ?? '-' }}</td>
                                                    <td>{{ $row->tgl_registrasi }}</td>
                                                    <td>{{ $row->jam_reg ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">
                                                        <i class="fa fa-info-circle"></i> Data tidak ditemukan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-center">
                                        {{ $data->links('pagination::bootstrap-4') }}
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
