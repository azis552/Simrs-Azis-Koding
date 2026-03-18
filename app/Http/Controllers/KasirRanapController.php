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

class KasirRanapController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? '-';
        $cari   = $request->cari;

        $query = DB::table('kamar_inap as ki')
            ->join('reg_periksa as r',     'ki.no_rawat',    '=', 'r.no_rawat')
            ->join('pasien as p',          'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar as km',          'ki.kd_kamar',    '=', 'km.kd_kamar')
            ->join('bangsal as b',         'km.kd_bangsal',  '=', 'b.kd_bangsal')
            ->join('dokter as d',          'r.kd_dokter',    '=', 'd.kd_dokter')
            ->join('penjab as pj',         'r.kd_pj',        '=', 'pj.kd_pj')
            ->leftJoin('kelurahan as kel', 'p.kd_kel',       '=', 'kel.kd_kel')
            ->leftJoin('kecamatan as kec', 'p.kd_kec',       '=', 'kec.kd_kec')
            ->leftJoin('kabupaten as kab', 'p.kd_kab',       '=', 'kab.kd_kab')
            ->select(
                'ki.no_rawat',
                'r.no_rkm_medis',
                'p.nm_pasien',
                DB::raw("CONCAT(r.umurdaftar, ' ', r.sttsumur) as umur"),
                'r.p_jawab',
                'r.hubunganpj',
                'pj.png_jawab',
                DB::raw("CONCAT(ki.kd_kamar, ' ', b.nm_bangsal) as kamar"),
                'ki.kd_kamar',
                'b.nm_bangsal',
                'b.kd_bangsal',
                'ki.trf_kamar',
                'ki.diagnosa_awal',
                'ki.diagnosa_akhir',
                'ki.tgl_masuk',
                'ki.jam_masuk',
                DB::raw("IF(ki.tgl_keluar='0000-00-00','',ki.tgl_keluar) as tgl_keluar"),
                DB::raw("IF(ki.jam_keluar='00:00:00','',ki.jam_keluar) as jam_keluar"),
                'ki.ttl_biaya',
                'ki.stts_pulang',
                'ki.lama',
                'd.nm_dokter',
                'r.kd_dokter',
                'r.kd_pj',
                'r.status_bayar',
                DB::raw("CONCAT(IFNULL(p.alamat,''), ', ', IFNULL(kel.nm_kel,''), ', ', IFNULL(kec.nm_kec,''), ', ', IFNULL(kab.nm_kab,'')) as alamat_lengkap"),
                'p.jk',
                'p.tgl_lahir',
                'p.tmp_lahir',
                'p.agama',
                'p.pekerjaan'
            )
            ->where('ki.stts_pulang', $status);

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('ki.tgl_masuk', [$request->tgl_awal, $request->tgl_akhir]);
        }

        if ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->where('ki.no_rawat',      'like', "%$cari%")
                  ->orWhere('r.no_rkm_medis', 'like', "%$cari%")
                  ->orWhere('p.nm_pasien',    'like', "%$cari%")
                  ->orWhere('d.nm_dokter',    'like', "%$cari%")
                  ->orWhere('b.nm_bangsal',   'like', "%$cari%")
                  ->orWhere('ki.kd_kamar',    'like', "%$cari%")
                  ->orWhere('ki.diagnosa_awal','like', "%$cari%")
                  ->orWhere('pj.png_jawab',   'like', "%$cari%");
            });
        }

        $pasiens = $query
            ->orderBy('b.nm_bangsal')
            ->orderBy('ki.tgl_masuk')
            ->orderBy('ki.jam_masuk')
            ->paginate(25);

        return view('KasirRanap.index', compact('pasiens'));
    }

    public function setStatus(Request $request, $no_rawat)
    {
        $request->validate([
            'status' => 'required|in:Sehat,Rujuk,APS,+,Meninggal,Sembuh,Membaik,Pulang Paksa,-,Status Belum Lengkap',
        ]);

        $pasien = DB::table('kamar_inap')->where('no_rawat', $no_rawat)->first();

        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        DB::table('kamar_inap')
            ->where('no_rawat', $no_rawat)
            ->update(['stts_pulang' => $request->status]);

        return redirect()->back()->with('success', "Status berhasil diubah menjadi {$request->status}.");
    }

    public function suratKematian($no_rawat)
    {
        $pasien = DB::table('kamar_inap as ki')
            ->join('reg_periksa as r',     'ki.no_rawat',    '=', 'r.no_rawat')
            ->join('pasien as p',          'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d',          'r.kd_dokter',    '=', 'd.kd_dokter')
            ->leftJoin('kamar as km',      'ki.kd_kamar',    '=', 'km.kd_kamar')
            ->leftJoin('bangsal as bg',    'km.kd_bangsal',  '=', 'bg.kd_bangsal')
            ->leftJoin('kelurahan as kel', 'p.kd_kel',       '=', 'kel.kd_kel')
            ->leftJoin('kecamatan as kec', 'p.kd_kec',       '=', 'kec.kd_kec')
            ->leftJoin('kabupaten as kab', 'p.kd_kab',       '=', 'kab.kd_kab')
            ->select(
                'ki.no_rawat',
                'r.no_rkm_medis',
                'r.kd_dokter',
                'ki.tgl_masuk',
                'ki.jam_masuk',
                'ki.stts_pulang',
                DB::raw("CONCAT(r.umurdaftar, ' ', r.sttsumur) as umur"),
                'p.nm_pasien',
                'p.tgl_lahir',
                'p.tmp_lahir',
                'p.jk',
                'p.agama',
                'p.pekerjaan',
                DB::raw("CONCAT(IFNULL(p.alamat,''), ', ', IFNULL(kel.nm_kel,''), ', ', IFNULL(kec.nm_kec,''), ', ', IFNULL(kab.nm_kab,'')) as alamat_lengkap"),
                'd.nm_dokter',
                DB::raw("CONCAT(ki.kd_kamar, ' ', IFNULL(bg.nm_bangsal,'')) as kamar")
            )
            ->where('ki.no_rawat', $no_rawat)
            ->orderBy('ki.tgl_masuk', 'desc')
            ->first();

        if (!$pasien) {
            return redirect()->route('kasir.ranap.index')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $diagnosa = DB::table('diagnosa_pasien as dp')
            ->join('penyakit as py', 'dp.kd_penyakit', '=', 'py.kd_penyakit')
            ->select('dp.kd_penyakit', 'py.nm_penyakit')
            ->where('dp.no_rawat', $no_rawat)
            ->where('dp.prioritas', '1')
            ->first();

        $setting = DB::table('setting')->first();

        $pasienMati = DB::table('pasien_mati')
            ->where('no_rkm_medis', $pasien->no_rkm_medis)
            ->first();

        return view('KasirRanap.surat-kematian', compact(
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

        $pasien = DB::table('kamar_inap as ki')
            ->join('reg_periksa as r',     'ki.no_rawat',    '=', 'r.no_rawat')
            ->join('pasien as p',          'r.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d',          'r.kd_dokter',    '=', 'd.kd_dokter')
            ->leftJoin('kamar as km',      'ki.kd_kamar',    '=', 'km.kd_kamar')
            ->leftJoin('bangsal as bg',    'km.kd_bangsal',  '=', 'bg.kd_bangsal')
            ->leftJoin('kelurahan as kel', 'p.kd_kel',       '=', 'kel.kd_kel')
            ->leftJoin('kecamatan as kec', 'p.kd_kec',       '=', 'kec.kd_kec')
            ->leftJoin('kabupaten as kab', 'p.kd_kab',       '=', 'kab.kd_kab')
            ->select(
                'ki.no_rawat',
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
            ->where('ki.no_rawat', $no_rawat)
            ->orderBy('ki.tgl_masuk', 'desc')
            ->first();

        if (!$pasien) {
            return redirect()->route('kasir.ranap.index')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $diagnosa = DB::table('diagnosa_pasien as dp')
            ->join('penyakit as py', 'dp.kd_penyakit', '=', 'py.kd_penyakit')
            ->where('dp.no_rawat', $no_rawat)
            ->where('dp.prioritas', '1')
            ->first();

        $setting = DB::table('setting')->first();

        // Simpan ke pasien_mati
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

        // QR Code SVG
        $qrContent = implode("\n", [
            'RS      : ' . ($setting->nama_instansi ?? ''),
            'Dokter  : ' . $pasien->nm_dokter,
            'Tanggal : ' . Carbon::parse($request->tgl_meninggal)->format('d-m-Y'),
            'Jam     : ' . $request->jam_meninggal,
        ]);

        $renderer = new ImageRenderer(new RendererStyle(150), new SvgImageBackEnd());
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

        $namaFile = 'surat-kematian-ranap-' . str_replace('/', '-', $no_rawat) . '.pdf';

        return Pdf::loadView('KasirRanap.pdf-surat-kematian', $data)
            ->setPaper('A4', 'portrait')
            ->stream($namaFile);
    }
}