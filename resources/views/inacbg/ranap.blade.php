@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        <div class="card">
                            <div class="card-block">
                                <h4 class="sub-title">Data Rawat Inap</h4>

                                {{-- 🔍 Form Pencarian --}}
                                <form method="GET" action="{{ route('inacbg-ranap.index') }}" class="mb-3">
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" name="key" value="{{ request('key') }}"
                                                class="form-control" placeholder="Cari Nama Pasien / No Rawat / No RM">
                                        </div>

                                        <div class="col-auto">
                                            <select name="order" class="form-control">
                                                <option value="kamar_inap.tgl_masuk DESC"
                                                    {{ request('order') == 'kamar_inap.tgl_masuk DESC' ? 'selected' : '' }}>
                                                    Tanggal Masuk ↓
                                                </option>
                                                <option value="kamar_inap.tgl_masuk ASC"
                                                    {{ request('order') == 'kamar_inap.tgl_masuk ASC' ? 'selected' : '' }}>
                                                    Tanggal Masuk ↑
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                            <a href="{{ route('inacbg-ranap.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>

                                {{-- 🔢 Tabel Data --}}
                                <div class="dt-responsive table-responsive">
                                    <table class="table table-striped table-bordered nowrap">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>No Rawat</th>
                                                <th>Aksi</th>
                                                <th>No RM</th>
                                                <th>Status Pulang</th>
                                                <th>Nama Pasien</th>
                                                <th>Alamat</th>
                                                <th>Kamar</th>
                                                <th>Dokter</th>
                                                <th>Diagnosa Awal</th>
                                                <th>Tgl Masuk</th>
                                                <th>Tgl Keluar</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                                                    </td>
                                                    <td>{{ $row->no_rawat }}</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('inacbg-ranap.show') }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="no_rawat"
                                                                value="{{ $row->no_rawat }}">
                                                            <button type="submit" class="btn btn-sm btn-info">
                                                                <i class="fa fa-folder-open"></i> Klaim
                                                            </button>
                                                        </form>
                                                    </td>

                                                    <td>{{ $row->no_rkm_medis }}</td>
                                                    <td>{{ $row->stts_pulang }}</td>
                                                    <td>{{ $row->nm_pasien }}</td>
                                                    <td>{{ $row->alamat }}</td>
                                                    <td>{{ $row->kamar }}</td>
                                                    <td>{{ $row->nm_dokter }}</td>
                                                    <td>{{ $row->diagnosa_awal }}</td>
                                                    <td>{{ $row->tgl_masuk }} {{ $row->jam_masuk }}</td>
                                                    <td>{{ $row->tgl_keluar }} {{ $row->jam_keluar }}</td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">Data tidak ditemukan</td>
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
