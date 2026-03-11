@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        <div class="card">
                            <div class="card-block">
                                <h4>Resep Obat</h4>
                                <div class="dt-responsive table-responsive">
                                    <form action="{{ route('obats.updatejam', $resep_obat->no_resep) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col">
                                                <label for="">No Resep</label>
                                                <input type="text" class="form-control" value="{{ $resep_obat->no_resep }}"
                                                    readonly>
                                            </div>
                                            <div class="col">
                                                <label for="">Tanggal Perawatan</label>
                                                <input type="hidden" name="tgl_perawatan_old" value="{{ $resep_obat->tgl_perawatan }}">
                                                <input type="date" class="form-control"
                                                    value="{{ $resep_obat->tgl_perawatan }}" name="tanggal">
                                            </div>
                                            <div class="col">
                                                <label for="">Jam</label>
                                                <input type="hidden" name="jam_old" value="{{ $resep_obat->jam }}">
                                                <input type="time " step="1" class="form-control"
                                                    value="{{ $resep_obat->jam }}" name="jam">
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Ubah Tanggal /Jam</button>
                                    </form>
                                    <h4>Tambah Obat</h4>
                                    <form action="">
                                        <div class="row">
                                            <div class="col">
                                                <label>Nama Obat</label>
                                                <select name="kode_brng" id="obat-select" class="form-select">
                                                    <option value="">Pilih Obat</option>
                                                    @foreach ($obats as $o)
                                                        <option value="{{ $o->kode_brng }}">{{ $o->nama_brng }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="">Jumlah</label>
                                                <input type="number" class="form-control">
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Tambah Obat</button>
                                    </form>
                                    <h4>Data Obat</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Obat</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($obats as $r)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $r->nama_brng }}</td>
                                                    <td>{{ $r->jml }}</td>
                                                    <td>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="text-center">Belum ada riwayat stok</td>
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