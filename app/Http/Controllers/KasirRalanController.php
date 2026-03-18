<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class KasirRalanController extends Controller
{
    public function index(Request $request)
    {
        $tgl_awal  = $request->tgl_awal  ?? date('Y-m-d');
        $tgl_akhir = $request->tgl_akhir ?? date('Y-m-d');
        $status    = $request->status;
        $cari      = $request->cari;

        $query = DB::table('reg_periksa as r')
            ->join('dokter as d',      'r.kd_dokter',    '=', 'd.kd_dokter')
            ->join('pasien as p',      'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('poliklinik as po', 'r.kd_poli',      '=', 'po.kd_poli')
            ->join('penjab as pj',     'r.kd_pj',        '=', 'pj.kd_pj')
            ->select(
                'r.no_rawat',
                'r.no_reg',
                'r.no_rkm_medis',
                'r.tgl_registrasi',
                'r.jam_reg',
                'r.stts',
                'r.status_bayar',
                'r.kd_pj',
                'r.kd_poli',
                'r.kd_dokter',
                'p.nm_pasien',
                'p.no_tlp',
                DB::raw("CONCAT(r.umurdaftar, ' ', r.sttsumur) as umur"),
                'd.nm_dokter',
                'po.nm_poli',
                'pj.png_jawab'
            )
            ->where('r.status_lanjut', 'Ralan')
            ->whereBetween('r.tgl_registrasi', [$tgl_awal, $tgl_akhir]);

        if ($status) {
            $query->where('r.stts', $status);
        }

        if ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->where('r.no_rawat',       'like', "%$cari%")
                  ->orWhere('r.no_rkm_medis', 'like', "%$cari%")
                  ->orWhere('p.nm_pasien',    'like', "%$cari%")
                  ->orWhere('d.nm_dokter',    'like', "%$cari%")
                  ->orWhere('po.nm_poli',     'like', "%$cari%");
            });
        }

        $pasiens = $query->orderBy('r.no_rawat', 'desc')->paginate(25);

        return view('KasirRalan.index', compact('pasiens'));
    }

    public function setStatus(Request $request, $no_rawat)
    {
        $request->validate([
            'status' => 'required|in:Sudah,Belum,Batal,Dirujuk,Dirawat,Meninggal,Pulang Paksa',
        ]);

        $pasien = DB::table('reg_periksa')->where('no_rawat', $no_rawat)->first();

        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        DB::table('reg_periksa')
            ->where('no_rawat', $no_rawat)
            ->update(['stts' => $request->status]);

        return redirect()->back()->with('success', "Status pasien berhasil diubah menjadi {$request->status}.");
    }

    public function suratKematian($no_rawat)
    {
        $pasien = DB::table('reg_periksa as r')
            ->join('pasien as p',          'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d',          'r.kd_dokter',    '=', 'd.kd_dokter')
            ->join('poliklinik as po',     'r.kd_poli',      '=', 'po.kd_poli')
            ->join('penjab as pj',         'r.kd_pj',        '=', 'pj.kd_pj')
            ->leftJoin('kelurahan as kel', 'p.kd_kel',       '=', 'kel.kd_kel')
            ->leftJoin('kecamatan as kec', 'p.kd_kec',       '=', 'kec.kd_kec')
            ->leftJoin('kabupaten as kab', 'p.kd_kab',       '=', 'kab.kd_kab')
            ->select(
                'r.no_rawat',
                'r.no_rkm_medis',
                'r.tgl_registrasi',
                'r.jam_reg',
                'r.stts',
                'r.kd_dokter',
                DB::raw("CONCAT(r.umurdaftar, ' ', r.sttsumur) as umur"),
                'p.nm_pasien',
                'p.tgl_lahir',
                'p.tmp_lahir',
                'p.jk',
                'p.agama',
                'p.pekerjaan',
                DB::raw("CONCAT(IFNULL(p.alamat,''), ', ', IFNULL(kel.nm_kel,''), ', ', IFNULL(kec.nm_kec,''), ', ', IFNULL(kab.nm_kab,'')) as alamat_lengkap"),
                'd.nm_dokter',
                'po.nm_poli',
                'pj.png_jawab'
            )
            ->where('r.no_rawat', $no_rawat)
            ->first();

        if (!$pasien) {
            return redirect()->route('kasir.ralan.index')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $diagnosa = DB::table('diagnosa_pasien as dp')
            ->join('penyakit as py', 'dp.kd_penyakit', '=', 'py.kd_penyakit')
            ->select('dp.kd_penyakit', 'py.nm_penyakit')
            ->where('dp.no_rawat', $no_rawat)
            ->where('dp.prioritas', '1')
            ->first();

        $setting = DB::table('setting')->first();

        // Pre-fill dari pasien_mati jika sudah pernah disimpan
        $pasienMati = DB::table('pasien_mati')
            ->where('no_rkm_medis', $pasien->no_rkm_medis)
            ->first();

        return view('KasirRalan.surat-kematian', compact(
            'pasien', 'diagnosa', 'setting', 'pasienMati'
        ));
    }

    public function cetakSuratKematian(Request $request, $no_rawat)
    {
        $request->validate([
            'nomor_surat'   => 'required',
            'tgl_meninggal' => 'required|date',
            'jam_meninggal' => 'required',
        ]);

        $pasien = DB::table('reg_periksa as r')
            ->join('pasien as p',          'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d',          'r.kd_dokter',    '=', 'd.kd_dokter')
            ->join('poliklinik as po',     'r.kd_poli',      '=', 'po.kd_poli')
            ->leftJoin('kelurahan as kel', 'p.kd_kel',       '=', 'kel.kd_kel')
            ->leftJoin('kecamatan as kec', 'p.kd_kec',       '=', 'kec.kd_kec')
            ->leftJoin('kabupaten as kab', 'p.kd_kab',       '=', 'kab.kd_kab')
            ->select(
                'r.no_rawat',
                'r.no_rkm_medis',
                'r.kd_dokter',
                DB::raw("CONCAT(r.umurdaftar, ' ', r.sttsumur) as umur"),
                'p.nm_pasien',
                'p.tgl_lahir',
                'p.tmp_lahir',
                'p.jk',
                DB::raw("CONCAT(IFNULL(p.alamat,''), ', ', IFNULL(kel.nm_kel,''), ', ', IFNULL(kec.nm_kec,''), ', ', IFNULL(kab.nm_kab,'')) as alamat_lengkap"),
                'd.nm_dokter'
            )
            ->where('r.no_rawat', $no_rawat)
            ->first();

        if (!$pasien) {
            return redirect()->route('kasir.ralan.index')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $diagnosa = DB::table('diagnosa_pasien as dp')
            ->join('penyakit as py', 'dp.kd_penyakit', '=', 'py.kd_penyakit')
            ->where('dp.no_rawat', $no_rawat)
            ->where('dp.prioritas', '1')
            ->first();

        $setting = DB::table('setting')->first();

        // Simpan ke tabel pasien_mati
        DB::table('pasien_mati')->updateOrInsert(
            ['no_rkm_medis' => $pasien->no_rkm_medis],
            [
                'tanggal'        => $request->tgl_meninggal,
                'jam'            => $request->jam_meninggal,
                'no_rkm_medis'   => $pasien->no_rkm_medis,
                'keterangan'     => $request->keterangan,
                'temp_meninggal' => 'Rumah Sakit',
                'kd_dokter'      => $pasien->kd_dokter,
                'nosurat'        => $request->nomor_surat,
            ]
        );

        // Logo base64
        $logoBase64 = null;
        if ($setting && $setting->logo) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode($setting->logo);
        }

        // QR Code menggunakan SVG (tidak butuh imagick/gd)
        $qrContent = implode("\n", [
            'RS      : ' . ($setting->nama_instansi ?? ''),
            'Dokter  : ' . $pasien->nm_dokter,
            'Tanggal : ' . Carbon::parse($request->tgl_meninggal)->format('d-m-Y'),
            'Jam     : ' . $request->jam_meninggal,
        ]);

        $renderer = new ImageRenderer(
            new RendererStyle(150),
            new SvgImageBackEnd()
        );
        $writer   = new Writer($renderer);
        $qrSvg    = $writer->writeString($qrContent);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $data = [
            'pasien'        => $pasien,
            'diagnosa'      => $diagnosa,
            'setting'       => $setting,
            'logoBase64'    => $logoBase64,
            'qrBase64'      => $qrBase64,
            'nomor_surat'   => $request->nomor_surat,
            'tgl_meninggal' => Carbon::parse($request->tgl_meninggal)->format('d-m-Y'),
            'jam_meninggal' => $request->jam_meninggal,
            'keterangan'    => $request->keterangan,
            'tgl_surat'     => Carbon::parse($request->tgl_meninggal)->isoFormat('D MMMM Y'),
        ];

        $namaFile = 'surat-kematian-' . str_replace('/', '-', $no_rawat) . '.pdf';

        $pdf = Pdf::loadView('KasirRalan.pdf-surat-kematian', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream($namaFile);
    }
}