<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
            padding: 1.5cm 2.5cm 2cm 2.5cm;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
            border: none;
        }
        .logo-cell { width: 85px; text-align: center; }
        .logo-cell img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }
        .logo-placeholder {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background-color: #8B6914;
            color: white;
            font-size: 24pt;
            font-weight: bold;
            text-align: center;
            line-height: 75px;
            display: inline-block;
        }
        .info-cell { padding-left: 12px; }
        .rs-name {
            font-size: 15pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .rs-info {
            font-size: 9pt;
            line-height: 1.5;
            color: #333;
        }
        .header-border      { border-top: 3px solid #000; margin-top: 6px; }
        .header-border-thin { border-top: 1px solid #000; margin-top: 2px; }

        .judul { text-align: center; margin: 20px 0 6px; }
        .judul h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .judul .nomor { font-size: 12pt; margin-top: 5px; }

        .body { margin-top: 24px; }
        .body p { font-size: 12pt; margin-bottom: 4px; }

        .field-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 6px 15px;
        }
        .field-table td {
            font-size: 12pt;
            padding: 2px 0;
            border: none;
            vertical-align: top;
        }
        .field-table .lbl { width: 80px; }
        .field-table .sep { width: 12px; }
        .field-table .val {
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
        }

        .keterangan { margin-top: 20px; }
        .keterangan p { font-size: 12pt; line-height: 2; }
        .underline-field {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 95px;
            padding: 0 3px;
        }

        .ttd-wrapper { margin-top: 35px; width: 100%; }
        .ttd-right {
            text-align: center;
            width: 210px;
            float: right;
        }
        .ttd-right p { font-size: 12pt; }
        .ttd-nama {
            font-size: 12pt;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <span class="logo-placeholder">R</span>
                @endif
            </td>
            <td class="info-cell">
                <div class="rs-name">{{ $setting->nama_instansi ?? '' }}</div>
                <div class="rs-info">
                    {{ $setting->alamat_instansi ?? '' }}<br>
                    {{ $setting->kontak ?? '' }}<br>
                    Email : {{ $setting->email ?? '' }}<br>
                    {{ $setting->propinsi ?? '' }}
                </div>
            </td>
        </tr>
    </table>
    <div class="header-border"></div>
    <div class="header-border-thin"></div>

    {{-- JUDUL --}}
    <div class="judul">
        <h2>SURAT KETERANGAN KEMATIAN</h2>
        <p class="nomor">Nomor : {{ $nomor_surat }}</p>
    </div>

    {{-- BODY --}}
    <div class="body">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa :</p>

        <table class="field-table">
            <tr>
                <td class="lbl">Nama</td>
                <td class="sep">:</td>
                <td class="val">{{ $pasien->nm_pasien }}</td>
            </tr>
            <tr>
                <td class="lbl">Umur</td>
                <td class="sep">:</td>
                <td class="val">{{ $pasien->umur }} &nbsp;( {{ $pasien->jk }} )</td>
            </tr>
            <tr>
                <td class="lbl">Alamat</td>
                <td class="sep">:</td>
                <td class="val">{{ $pasien->alamat_lengkap }}</td>
            </tr>
        </table>

        <div class="keterangan">
            <p>
                Telah meninggal dunia pada &nbsp;
                <span class="underline-field">{{ $tgl_meninggal }}</span>
                &nbsp;&nbsp; Jam &nbsp;
                <span class="underline-field">{{ $jam_meninggal }}</span>
            </p>
            <p>di {{ $setting->nama_instansi ?? 'Rumah Sakit' }} dikarenakan Meninggal</p>
            <p>Demikian surat keterangan ini dibuat agar menjadikan maklum dan dapat</p>
            <p>sebagaimana mestinya</p>
            @if(!empty($keterangan))
                <p style="margin-top:6px;">Keterangan : {{ $keterangan }}</p>
            @endif
        </div>
    </div>

    {{-- TTD --}}
    <div class="ttd-wrapper">
        <div class="ttd-right">
            <p>{{ $setting->kabupaten ?? '' }}, {{ $tgl_surat }}</p>
            <p>Dokter Pemeriksa</p>
            <div style="margin: 6px 0;">
                <img src="{{ $qrBase64 }}" alt="QR Code" style="width:80px; height:80px;">
            </div>
            <div class="ttd-nama">{{ $pasien->nm_dokter }}</div>
        </div>
    </div>

</body>
</html>