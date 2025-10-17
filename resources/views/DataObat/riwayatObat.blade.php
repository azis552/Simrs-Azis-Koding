@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        <div class="card">
                            <div class="card-block">
                                <h4>Riwayat Stok - {{ $barang->nama_brng }} ({{ $barang->kode_brng }})</h4>
                                {{-- 🔢 Tabel Data --}}
                                {{-- Filter Tanggal --}}
                                <form method="GET" action="{{ route('stok.riwayat', $barang->kode_brng) }}" class="mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="date" name="tgl_awal" class="form-control"
                                                value="{{ $tgl_awal }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" name="tgl_akhir" class="form-control"
                                                value="{{ $tgl_akhir }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            @if ($tgl_awal && $tgl_akhir)
                                                <a href="{{ route('stok.riwayat.cetak', ['kode_brng' => $barang->kode_brng, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                                    target="_blank" class="btn btn-success">
                                                    Cetak
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <div class="dt-responsive table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jam</th>
                                                <th>Stok Awal</th>
                                                <th>Masuk</th>
                                                <th>Keluar</th>
                                                <th>Stok Akhir</th>
                                                <th>Petugas</th>
                                                <th>Bangsal</th>
                                                <th>Status</th>
                                                <th>No Batch</th>
                                                <th>No Faktur</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($riwayat as $r)
                                                <tr>
                                                    <td>{{ $r->tanggal }}</td>
                                                    <td>{{ $r->jam }}</td>
                                                    <td>{{ $r->stok_awal }}</td>
                                                    <td>{{ $r->masuk }}</td>
                                                    <td>{{ $r->keluar }}</td>
                                                    <td>{{ $r->stok_akhir }}</td>
                                                    <td>{{ $r->petugas }}</td>
                                                    <td>{{ $r->kd_bangsal }}</td>
                                                    <td>{{ $r->status }}</td>
                                                    <td>{{ $r->no_batch }}</td>
                                                    <td>{{ $r->no_faktur }}</td>
                                                    <td>{{ $r->keterangan }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">Belum ada riwayat stok</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-center">
                                        {{ $riwayat->links('pagination::bootstrap-4') }}
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
