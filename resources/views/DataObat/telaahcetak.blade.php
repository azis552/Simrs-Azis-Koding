<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <div class="row">
        <div class="col">

            <table>
                <tr>
                    <td width="20%" style="text-align: center">
                        <img src="{{ asset('') }}/assets/LOGO.png" width="70%" alt="Logo">
                    </td>
                    <td style="text-align: center; font-family: Arial, sans-serif;">
                        @foreach ($perusahaan as $item)
                            <div style="font-weight: bold; font-size: 18px; margin-bottom: 5px;">
                                {{ $item->nama_instansi }}
                            </div>
                            <div style="font-size: 12px; margin-bottom: 5px;">
                                {{ $item->alamat_instansi }}, {{ $item->kabupaten }}, {{ $item->propinsi }}
                            </div>
                            <div style="font-size: 12px; margin-bottom: 5px;">
                                {{ $item->kontak }}
                            </div>
                            <div style="font-size: 13px; color: rgb(1, 1, 7);">
                                {{ $item->email }}
                            </div>
                        @endforeach
                    </td>

                </tr>
            </table>
            <hr style="border: 1px solid black">
            <style>
                #identitas {
                    font-size: 13px;
                }

                .list {
                    font-size: 12.5px;
                }
            </style>
            <table id="identitas">
                <tr>
                    <td>Nama Pasien</td>
                    <td>:</td>
                    <td>{{ $pasien->nm_pasien }}</td>
                </tr>
                <tr>
                    <td>No RM</td>
                    <td>:</td>
                    <td>{{ $pasien->no_rkm_medis }}</td>
                </tr>
                <tr>
                    <td>No. Rawat</td>
                    <td>:</td>
                    <td>{{ $pasien->no_rawat }}</td>
                </tr>
                <tr>
                    <td>Jenis Pasien</td>
                    <td>:</td>
                    <td>{{ $pasien->png_jawab }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $pasien->alamat }}</td>
                </tr>
                <tr>
                    <td>Pemberi Resep</td>
                    <td>:</td>
                    <td>{{ $pemberiobat->nm_dokter }}</td>
                </tr>
                <tr>
                    <td>No Resep</td>
                    <td>:</td>
                    <td>{{ $pemberiobat->no_resep }}</td>
                </tr>
            </table>

            <div style="text-align: center; font-family: Arial, sans-serif; margin-right: 180px;">
                <b>RESEP</b>
            </div>

            <table class="list" width="100%">
                @foreach ($obat_umum as $item)
                    <tr>
                        <td>R/</td>
                        <td> {{ $item->nama_brng }} </td>
                        <td>{{ $item->jml }} {{ $item->satuan }}</td>

                    </tr>
                    <tr style="border-bottom: 1px solid black; text-align: center">
                        <td colspan="3"> {{ $item->aturan_pakai }}</td>
                    </tr>
                @endforeach
            </table>

            <table width="100%" style="margin-top: 10px" class="list">
                <tr>
                    <td width="70%"></td>
                    <td width="50%">
                        <div style="text-align: center; font-family: Arial, sans-serif;">
                            Kediri , {{ $pemberiobat->tgl_peresepan }}
                            <br>
                            {!! QrCode::size(70)->generate($pemberiobat->nm_dokter . ' | ' . $pemberiobat->tgl_peresepan) !!}
                            <br>
                            <b>{{ $pemberiobat->nm_dokter }}</b>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <div class="col" style="text-align: center; font-family: Arial, sans-serif; font-size: 12px">
            <b>FORM TELAAH RESEP UNIT FARMASI</b>
            <table width="100%" border="1" style="border : 1px solid black">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 45%">Telaah Resep</th>
                        <th>Ya</th>
                        <th>Tidak</th>
                        <th>Ket.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td style="text-align: left">Kejelasan Tulisan Resep</td>
                        <td>{{ $telaah->kejelasanTulisanResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->kejelasanTulisanResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align: left">Berat Badan (Pasien Anak) </td>
                        <td>{{ $telaah->beratbadan == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->beratbadan == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align: left">Nama, Bentuk, Kekuatan, Jumlah Obat, Aturan Pakai</td>
                        <td>{{ $telaah->identitasObat == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->identitasObat == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align: left">Tepat Obat</td>
                        <td>{{ $telaah->tepatObat == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatObat == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td style="text-align: left">Tepat Dosis</td>
                        <td>{{ $telaah->tepatDosis == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatDosis == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td style="text-align: left">Tepat Rute</td>
                        <td>{{ $telaah->tepatRute == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatRute == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td style="text-align: left">Tepat Waktu</td>
                        <td>{{ $telaah->tepatWaktu == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatWaktu == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td style="text-align: left">Duplikasi</td>
                        <td>{{ $telaah->duplikasi == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->duplikasi == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td style="text-align: left">Alergi</td>
                        <td>{{ $telaah->alergi == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->alergi == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td style="text-align: left">Interaksi Obat</td>
                        <td>{{ $telaah->interaksiObat == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->interaksiObat == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td style="text-align: left">Kontra Indikasi Lainnya</td>
                        <td>{{ $telaah->kontraIndikasiLainnya == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->kontraIndikasiLainnya == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th style="text-align: left">Telaah Obat</th>
                        <th>Ya</th>
                        <th>Tidak</th>
                        <th>Ket.</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td style="text-align: left">Kesesuaian Identitas Pasien dengan Resep</td>
                        <td>{{ $telaah->kesesuaianIdentitasPasienResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->kesesuaianIdentitasPasienResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align: left">Nama Obat dengan Resep</td>
                        <td>{{ $telaah->namaObatResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->namaObatResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align: left">Dosis Obat Dengan Resep</td>
                        <td>{{ $telaah->dosisResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->dosisResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align: left">Rute / Cara Pemberian</td>
                        <td>{{ $telaah->ruteCaraResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->ruteCaraResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td style="text-align: left">Waktu Pemberian Dengan Resep</td>
                        <td>{{ $telaah->waktuResep == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->waktuResep == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th style="text-align: left">Konseling</th>
                        <th>Ya</th>
                        <th>Tidak</th>
                        <th>Ket.</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td style="text-align: left">Poli Farmasi</td>
                        <td>{{ $telaah->polifarmasi == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->polifarmasi == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align: left">Obat Luar</td>
                        <td>{{ $telaah->obatluar == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->obatluar == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align: left">Alat Khusus</td>
                        <td>{{ $telaah->alatkhusus == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->alatkhusus == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align: left">Antibiotik</td>
                        <td>{{ $telaah->antibiotik == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->antibiotik == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td style="text-align: left">Prn</td>
                        <td>{{ $telaah->pm == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->pm == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td style="text-align: left">Efek Samping</td>
                        <td>{{ $telaah->efeksamping == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->efeksamping == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td style="text-align: left">Indeks Terapi Sempit</td>
                        <td>{{ $telaah->indeksterapisempit == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->indeksterapisempit == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td style="text-align: left">Interaksi Obat-obat</td>
                        <td>{{ $telaah->interaksiobatKonseling == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->interaksiobatKonseling == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left">Interaksi Obat-Makanan</td>
                        <td>{{ $telaah->interaksiobatmakanan == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->interaksiobatmakanan == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td style="text-align: left">Tepat Obat</td>
                        <td>{{ $telaah->tepatObatKonseling == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatObatKonseling == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td style="text-align: left">Tepat Informasi</td>
                        <td>{{ $telaah->tepatInformasiKonseling == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatInformasiKonseling == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td style="text-align: left">Tepat Dokumentasi</td>
                        <td>{{ $telaah->tepatDokumentasiKonseling == "Ya" ?  "✓" : "" }}</td>
                        <td>{{ $telaah->tepatDokumentasiKonseling == "Tidak" ?  "✓" : "" }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="1" style="border : 1px solid black; margin-top: 5px">
                <tr>
                    <td>Terima</td>
                    <td>Ambil</td>
                    <td>Etiket</td>
                    <td>Periksa</td>
                    <td>Entri</td>
                    <td>KIE</td>
                </tr>
                <tr>
                    <td height="50px"></td>
                    <td height="50px"></td>
                    <td height="50px"></td>
                    <td height="50px"></td>
                    <td height="50px"></td>
                    <td height="50px"></td>
                </tr>
            </table>

            <table style="margin-top: 10px">
                <tr style="border: 1px solid black; text-align: center">
                    <td>
                        <div style="text-align: center; font-family: Arial, sans-serif;">
                            Tanda Tangan Penerima Obat dan Telah Diedukasi.
                            <br>
                            <br>
                            <br>
                            <br>
                            <hr style="border: 1px solid black">
                            <b>{{ $pasien->nm_pasien }}</b>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>
    -->

</html>
