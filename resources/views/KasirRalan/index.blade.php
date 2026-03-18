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
                            <h4 class="sub-title">Daftar Pasien Rawat Jalan</h4>

                            {{-- Filter --}}
                            <form action="{{ route('kasir.ralan.index') }}" method="GET">
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label>Dari Tanggal</label>
                                        <input type="date" class="form-control"
                                            name="tgl_awal" value="{{ request('tgl_awal', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Sampai Tanggal</label>
                                        <input type="date" class="form-control"
                                            name="tgl_akhir" value="{{ request('tgl_akhir', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Semua</option>
                                            @foreach(['Belum','Sudah','Batal','Dirujuk','Dirawat','Meninggal'] as $s)
                                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                                    {{ $s }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cari</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                name="cari"
                                                placeholder="No.Rawat / No.RM / Nama Pasien..."
                                                value="{{ request('cari') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <a href="{{ route('kasir.ralan.index') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Rawat</th>
                                            <th>No. RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Poliklinik</th>
                                            <th>Dokter</th>
                                            <th>Cara Bayar</th>
                                            <th>Tgl. Daftar</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pasiens as $p)
                                            <tr>
                                                <td>{{ $loop->iteration + ($pasiens->currentPage() - 1) * $pasiens->perPage() }}</td>
                                                <td>{{ $p->no_rawat }}</td>
                                                <td>{{ $p->no_rkm_medis }}</td>
                                                <td>
                                                    {{ $p->nm_pasien }}
                                                    <br>
                                                    <small class="text-muted">{{ $p->umur ?? '-' }}</small>
                                                </td>
                                                <td>{{ $p->nm_poli }}</td>
                                                <td>{{ $p->nm_dokter }}</td>
                                                <td>{{ $p->png_jawab }}</td>
                                                <td>{{ \Carbon\Carbon::parse($p->tgl_registrasi)->format('d/m/Y') }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = match($p->stts) {
                                                            'Sudah'     => 'badge badge-success',
                                                            'Belum'     => 'badge badge-warning',
                                                            'Batal'     => 'badge badge-secondary',
                                                            'Dirujuk'   => 'badge badge-info',
                                                            'Dirawat'   => 'badge badge-primary',
                                                            'Meninggal' => 'badge badge-dark',
                                                            default     => 'badge badge-light'
                                                        };
                                                    @endphp
                                                    <span class="{{ $statusClass }}">{{ $p->stts }}</span>
                                                </td>
                                                <td>
    {{-- Billing --}}
    <a href="{{ route('kasir.ralan.billing', $p->no_rawat) }}"
        class="btn btn-success btn-sm mb-1">
        <i class="feather icon-credit-card"></i> Billing
    </a>

    {{-- Resep Obat --}}
    <a href="{{ route('obat.validasi', $p->no_rawat) }}"
        class="btn btn-primary btn-sm mb-1">
        <i class="feather icon-package"></i> Resep Obat
    </a>

    {{-- Surat Kematian - selalu tampil --}}
    <a href="{{ route('kasir.ralan.surat.kematian', $p->no_rawat) }}"
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

            <h6 class="dropdown-header">Tindakan & Pemeriksaan</h6>
            <a class="dropdown-item" href="{{ route('kasir.ralan.tindakan', $p->no_rawat) }}">
                <i class="feather icon-activity mr-2"></i>Tindakan Rawat Jalan
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.lab', $p->no_rawat) }}">
                <i class="feather icon-thermometer mr-2"></i>Periksa Laborat
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.radiologi', $p->no_rawat) }}">
                <i class="feather icon-radio mr-2"></i>Periksa Radiologi
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.diagnosa', $p->no_rawat) }}">
                <i class="feather icon-clipboard mr-2"></i>Diagnosa Pasien
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.pemberian-obat', $p->no_rawat) }}">
                <i class="feather icon-shopping-bag mr-2"></i>Pemberian Obat
            </a>

            <div class="dropdown-divider"></div>

            <h6 class="dropdown-header">Surat-Surat</h6>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.sakit', $p->no_rawat) }}">
                <i class="feather icon-file-text mr-2"></i>Surat Cuti Sakit
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.sehat', $p->no_rawat) }}">
                <i class="feather icon-file-text mr-2"></i>Surat Keterangan Sehat
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.kontrol', $p->no_rawat) }}">
                <i class="feather icon-calendar mr-2"></i>Surat Kontrol
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.rujukan', $p->no_rawat) }}">
                <i class="feather icon-navigation mr-2"></i>Surat Rujukan
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.persetujuan', $p->no_rawat) }}">
                <i class="feather icon-edit mr-2"></i>Surat Persetujuan Umum
            </a>
            <a class="dropdown-item" href="{{ route('kasir.ralan.surat.pulang-paksa', $p->no_rawat) }}">
                <i class="feather icon-log-out mr-2"></i>Pulang Atas Permintaan
            </a>

            <div class="dropdown-divider"></div>

            <h6 class="dropdown-header">Set Status</h6>
            @foreach([
                ['Sudah',     'Sudah Periksa'],
                ['Belum',     'Belum Periksa'],
                ['Batal',     'Batal Periksa'],
                ['Dirujuk',   'Dirujuk'],
                ['Dirawat',   'Dirawat'],
                ['Meninggal', 'Meninggal'],
            ] as [$val, $label])
                @if($p->stts !== $val)
                    <form action="{{ route('kasir.ralan.status', $p->no_rawat) }}"
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
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    Belum ada data pasien rawat jalan.
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