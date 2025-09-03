@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">

                    <div class="page-body">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="sub-title">Data Obat</h4>
                                <!-- Button trigger modal -->


                                <!-- Modal -->
                                <div class="modal fade " id="staticBackdrop" data-backdrop="static" data-keyboard="false"
                                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Telaah Resep dan Telaah
                                                    Obat Serta Konseling</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('telaah.store') }}" method="POST">
                                                    @csrf
                                                    <div class="row" >
                                                        <label for="" class="col-sm-3 col-form-label">No Resep</label>
                                                        <input type="text" readonly class="form-control mr-2 ml-2 mb-2" name="noresep" id="noresep">
                                                        <div class="col">

                                                            <h6>Telaah Resep</h6>
                                                            <hr>

                                                            <div class="form-row">
                                                                <div class="col">
                                                                    <label for="">Kejelasan Tulisan Resep</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="kejelasanTulisanResep">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Berat Badan (Pasien Anak)</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="beratbadan">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Nama, Bentuk, Kekuatan jumlah
                                                                        obat,
                                                                        aturan pakai</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="identitasObat">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Obat</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="tepatObat">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Dosis</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="tepatDosis">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Rute</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="tepatRute">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Waktu</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="tepatWaktu">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Duplikasi</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="duplikasi">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Alergi</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="alergi">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Interaksi Obat</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="interaksiObat">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Kontra Indikasi Lainnya</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="kontraIndikasiLainnya">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <h6>Konseling</h6>
                                                            <hr>
                                                            <div class="form-row">
                                                                <div class="col">
                                                                    <label for="">Poli Farmasi</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="polifarmasi">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Obat Luar</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="obatluar">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Alat Khusus</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="alatkhusus">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Antibiotik</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="antibiotik">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Prn</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="pm">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Efek Samping Obat</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control" name="efeksamping">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Indeks Terapi Sempit</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="indeksterapisempit">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Interaksi Obat-Obat</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="interaksiobatKonseling">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Interaksi Obat-Makanan</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="interaksiobatmakanan">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Obat</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="tepatObatKonseling">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Informasi</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="tepatInformasiKonseling">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row mt-2">
                                                                <div class="col">
                                                                    <label for="">Tepat Dokumentasi</label>
                                                                </div>
                                                                <div class="col-3" style="margin-right: 20%">
                                                                    <Select class="form-control"
                                                                        name="tepatDokumentasiKonseling">
                                                                        <option value="Ya">Ya</option>
                                                                        <option value="Tidak">Tidak</option>
                                                                    </Select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6>Telaah Obat</h6>
                                                    <hr>
                                                    <div class="form-row mt-2">
                                                        <div class="col">
                                                            <label for="">Kesesuaian Identitas Pasien Dengan
                                                                Resep</label>
                                                        </div>
                                                        <div class="col-3" style="margin-right: 20%">
                                                            <Select class="form-control"
                                                                name="kesesuaianIdentitasPasienResep">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row mt-2">
                                                        <div class="col">
                                                            <label for="">Nama Obat Resep</label>
                                                        </div>
                                                        <div class="col-3" style="margin-right: 20%">
                                                            <Select class="form-control" name="namaObatResep">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row mt-2">
                                                        <div class="col">
                                                            <label for="">Dosis Dan Jumlah Obat dengan
                                                                Resep</label>
                                                        </div>
                                                        <div class="col-3" style="margin-right: 20%">
                                                            <Select class="form-control" name="dosisResep">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row mt-2">
                                                        <div class="col">
                                                            <label for="">Rute /Cara Pemberian</label>
                                                        </div>
                                                        <div class="col-3" style="margin-right: 20%">
                                                            <Select class="form-control" name="ruteCaraResep">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row mt-2">
                                                        <div class="col">
                                                            <label for="">Waktu Pemberian dengan Resep</label>
                                                        </div>
                                                        <div class="col-3" style="margin-right: 20%">
                                                            <Select class="form-control" name="waktuResep">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="dt-responsive table-responsive">
                                    <form action="{{ route('obats.search') }}" method="post">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="text" name="search" class="form-control" placeholder="Search"
                                                aria-label="Username" aria-describedby="basic-addon1">

                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Resep</th>
                                                <th>No Rawat</th>
                                                <th>Pasien</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obats as $obat)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $obat->no_resep }}</td>
                                                    <td>{{ $obat->no_rawat }}</td>
                                                    <td>{{ $obat->nm_pasien }}</td>
                                                    <td>
                                                        @if ($obat->telaah == null)
                                                            <button type="button" class="btn btn-warning mb-3 btn-telaah"
                                                                data-toggle="modal" id="telaah"
                                                                data-noresep="{{ $obat->no_resep }}"
                                                                data-target="#staticBackdrop">
                                                                Telaah Resep
                                                            </button>
                                                        @else
                                                            <form action="{{ route('obats.destroy', $obat->no_resep) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="{{ route('obats.show', $obat->no_resep) }}" class="btn btn-primary mb-3" target="_blank" >Print Telaah</a>
                                                                <button type="submit" class="btn btn-danger mb-3">Hapus Telaah</button>
                                                            </form>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            {{-- Tombol Sebelumnya --}}
                                            @if ($obats->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">Sebelumnya</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $obats->previousPageUrl() }}"
                                                        rel="prev">Sebelumnya</a>
                                                </li>
                                            @endif

                                            {{-- Nomor Halaman --}}
                                            @foreach ($obats->links()->elements[0] as $page => $url)
                                                <li class="page-item {{ $page == $obats->currentPage() ? 'active' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endforeach

                                            {{-- Tombol Selanjutnya --}}
                                            @if ($obats->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $obats->nextPageUrl() }}"
                                                        rel="next">Selanjutnya</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">Selanjutnya</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="styleSelector">

                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-telaah', function() {
            var no_resep = $(this).data('noresep');
            $('#noresep').val(no_resep);
        });
    });
    // coba update
</script>
@endsection

