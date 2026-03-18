@extends('template.master')

@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">

                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-block">
                            <h4 class="sub-title">Daftar Pasien Rawat Inap</h4>

                            {{-- Filter --}}
                            <form action="{{ route('kasir.ranap.index') }}" method="GET">
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label>Tgl. Masuk Dari</label>
                                        <input type="date" class="form-control"
                                            name="tgl_awal" value="{{ request('tgl_awal') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Sampai</label>
                                        <input type="date" class="form-control"
                                            name="tgl_akhir" value="{{ request('tgl_akhir') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="-" {{ request('status','-') == '-' ? 'selected' : '' }}>Belum Pulang</option>
                                            <option value="Sehat"        {{ request('status') == 'Sehat' ? 'selected' : '' }}>Sehat</option>
                                            <option value="Rujuk"        {{ request('status') == 'Rujuk' ? 'selected' : '' }}>Rujuk</option>
                                            <option value="APS"          {{ request('status') == 'APS' ? 'selected' : '' }}>APS</option>
                                            <option value="Meninggal"    {{ request('status') == 'Meninggal' ? 'selected' : '' }}>Meninggal</option>
                                            <option value="Sembuh"       {{ request('status') == 'Sembuh' ? 'selected' : '' }}>Sembuh</option>
                                            <option value="Membaik"      {{ request('status') == 'Membaik' ? 'selected' : '' }}>Membaik</option>
                                            <option value="Pulang Paksa" {{ request('status') == 'Pulang Paksa' ? 'selected' : '' }}>Pulang Paksa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cari</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                name="cari"
                                                placeholder="No.Rawat / No.RM / Nama / Kamar..."
                                                value="{{ request('cari') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <a href="{{ route('kasir.ranap.index') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>No. Rawat</th>
                                            <th>No. RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Kamar / Bangsal</th>
                                            <th>Dokter</th>
                                            <th>Cara Bayar</th>
                                            <th>Tgl. Masuk</th>
                                            <th>Lama</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pasiens as $p)
                                            <tr>
                                                <td>
                                                    {{-- Surat Kematian selalu tampil --}}
                                                    <a href="{{ route('kasir.ranap.surat.kematian', $p->no_rawat) }}"
                                                        class="btn btn-dark btn-sm mb-1">
                                                        <i class="feather icon-file-text"></i> Surat Kematian
                                                    </a>

                                                    {{-- Dropdown Lainnya --}}
                                                    <div class="btn-group mb-1">
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm dropdown-toggle"
                                                            data-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="feather icon-more-horizontal"></i> Lainnya
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <h6 class="dropdown-header">Set Status Pulang</h6>
                                                            @foreach([
                                                                ['Sehat',        'Sehat'],
                                                                ['Rujuk',        'Rujuk'],
                                                                ['APS',          'APS'],
                                                                ['+',            'Plus (+)'],
                                                                ['Meninggal',    'Meninggal'],
                                                                ['Sembuh',       'Sembuh'],
                                                                ['Membaik',      'Membaik'],
                                                                ['Pulang Paksa', 'Pulang Paksa'],
                                                                ['-',            'Belum Pulang (-)'],
                                                            ] as [$val, $label])
                                                                @if($p->stts_pulang !== $val)
                                                                    <form action="{{ route('kasir.ranap.status', $p->no_rawat) }}"
                                                                        method="POST"
                                                                        @if($val === 'Meninggal')
                                                                            onsubmit="return confirm('Tandai {{ addslashes($p->nm_pasien) }} sebagai Meninggal?')"
                                                                        @endif>
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="status" value="{{ $val }}">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="feather icon-toggle-right mr-2"></i>{{ $label }}
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </td>

                                                <td>{{ $loop->iteration + ($pasiens->currentPage() - 1) * $pasiens->perPage() }}</td>
                                                <td>{{ $p->no_rawat }}</td>
                                                <td>{{ $p->no_rkm_medis }}</td>
                                                <td>
                                                    {{ $p->nm_pasien }}
                                                    <br><small class="text-muted">{{ $p->umur }}</small>
                                                </td>
                                                <td>{{ $p->kamar }}</td>
                                                <td>{{ $p->nm_dokter }}</td>
                                                <td>{{ $p->png_jawab }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($p->tgl_masuk)->format('d/m/Y') }}
                                                    <br><small class="text-muted">{{ $p->jam_masuk }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">{{ $p->lama }} hr</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = match($p->stts_pulang) {
                                                            '-'               => 'badge badge-warning',
                                                            'Sehat','Sembuh'  => 'badge badge-success',
                                                            'Rujuk'           => 'badge badge-info',
                                                            'APS','Pulang Paksa' => 'badge badge-secondary',
                                                            'Meninggal'       => 'badge badge-dark',
                                                            'Membaik'         => 'badge badge-primary',
                                                            default           => 'badge badge-light'
                                                        };
                                                    @endphp
                                                    <span class="{{ $statusClass }}">
                                                        {{ $p->stts_pulang == '-' ? 'Dirawat' : $p->stts_pulang }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    Belum ada data pasien rawat inap.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{-- Pagination --}}
                                <nav>
                                    <ul class="pagination justify-content-center">
                                        @if($pasiens->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">Sebelumnya</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $pasiens->withQueryString()->previousPageUrl() }}"
                                                    rel="prev">Sebelumnya</a>
                                            </li>
                                        @endif

                                        @foreach($pasiens->links()->elements[0] as $page => $url)
                                            <li class="page-item {{ $page == $pasiens->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        @if($pasiens->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $pasiens->withQueryString()->nextPageUrl() }}"
                                                    rel="next">Selanjutnya</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">Selanjutnya</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                                <p class="text-center text-muted">
                                    Menampilkan {{ $pasiens->firstItem() ?? 0 }} - {{ $pasiens->lastItem() ?? 0 }}
                                    dari {{ $pasiens->total() }} data
                                </p>
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