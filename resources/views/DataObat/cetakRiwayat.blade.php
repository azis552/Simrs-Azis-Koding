<!DOCTYPE html>
<html>
<head>
    <title>Cetak Riwayat - {{ $barang->nama_brng }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        h4 { margin: 0; }
    </style>
</head>
<body onload="window.print()">
    <h4>Laporan Riwayat Stok</h4>
    <p>
        Obat: <b>{{ $barang->nama_brng }}</b> ({{ $barang->kode_brng }}) <br>
        Periode: {{ $tgl_awal ?? '-' }} s/d {{ $tgl_akhir ?? '-' }}
    </p>
    <table>
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
                <th>No Batch</th>
                <th>No Faktur</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $r)
            <tr>
                <td>{{ $r->tanggal }}</td>
                <td>{{ $r->jam }}</td>
                <td>{{ $r->stok_awal }}</td>
                <td>{{ $r->masuk }}</td>
                <td>{{ $r->keluar }}</td>
                <td>{{ $r->stok_akhir }}</td>
                <td>{{ $r->petugas }}</td>
                <td>{{ $r->kd_bangsal }}</td>
                <td>{{ $r->no_batch }}</td>
                <td>{{ $r->no_faktur }}</td>
                <td>{{ $r->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
