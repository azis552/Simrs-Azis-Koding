@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        <div class="card">
                            <div class="card-block">
                                <h4 class="sub-title">Stok Obat</h4>

                                {{-- 🔍 Form Pencarian --}}
                                <form method="GET" action="{{ route('obats.stokObat') }}" class="mb-3">
                                    <div class="form-row">
                                        {{-- Dropdown Jenis --}}
                                        <div class="col">
                                            <select name="nmjns" class="form-control">
                                                <option value="">-- Semua Jenis --</option>
                                                @foreach ($jenisList as $j)
                                                    <option value="{{ $j->nama }}"
                                                        {{ $nmjns == $j->nama ? 'selected' : '' }}>
                                                        {{ $j->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Dropdown Kategori --}}
                                        <div class="col">
                                            <select name="nmkategori" class="form-control">
                                                <option value="">-- Semua Kategori --</option>
                                                @foreach ($kategoriList as $k)
                                                    <option value="{{ $k->nama }}"
                                                        {{ $nmkategori == $k->nama ? 'selected' : '' }}>
                                                        {{ $k->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Dropdown Golongan --}}
                                        <div class="col">
                                            <select name="nmgolongan" class="form-control">
                                                <option value="">-- Semua Golongan --</option>
                                                @foreach ($golonganList as $g)
                                                    <option value="{{ $g->nama }}"
                                                        {{ $nmgolongan == $g->nama ? 'selected' : '' }}>
                                                        {{ $g->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Input teks pencarian obat --}}
                                        <div class="col">
                                            <input type="text" name="tcari" value="{{ $tcari }}"
                                                class="form-control" placeholder="Cari kode/nama obat">
                                        </div>

                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                            <a href="{{ route('obats.stokObat') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>


                                {{-- 🔢 Tabel Data --}}
                                <div class="dt-responsive table-responsive">
                                    <table class="table table-striped table-bordered nowrap">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Brg</th>
                                                <th>Nama Brg</th>
                                                <th>Satuan</th>
                                                <th>Harga Dasar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $obat)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                                                    </td>
                                                    <td> <a href="{{ route('stok.riwayat', $obat->kode_brng) }}">{{ $obat->kode_brng }}</a> </td>
                                                    <td>{{ $obat->nama_brng }}</td>
                                                    <td>{{ $obat->kode_sat }}</td>
                                                    <td>{{ number_format($obat->dasar, 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
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
