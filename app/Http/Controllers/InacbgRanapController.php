<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class InacbgRanapController extends Controller
{
    public function index(Request $request)
    {
        // ambil parameter pencarian & urutan
        $search = $request->get('key');   // pencarian
        $order = $request->get('order', 'kamar_inap.tgl_masuk DESC'); // default order

        $data = DB::table('kamar_inap')
            ->selectRaw("
        kamar_inap.no_rawat,
        reg_periksa.no_rkm_medis,
        pasien.nm_pasien,
        concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab) as alamat,
        reg_periksa.p_jawab,
        reg_periksa.hubunganpj,
        penjab.png_jawab,
        concat(kamar_inap.kd_kamar,' ',bangsal.nm_bangsal) as kamar,
        kamar_inap.trf_kamar,
        kamar_inap.diagnosa_awal,
        kamar_inap.diagnosa_akhir,
        kamar_inap.tgl_masuk,
        kamar_inap.jam_masuk,
        if(kamar_inap.tgl_keluar='0000-00-00','',kamar_inap.tgl_keluar) as tgl_keluar,
        if(kamar_inap.jam_keluar='00:00:00','',kamar_inap.jam_keluar) as jam_keluar,
        kamar_inap.ttl_biaya,
        kamar_inap.stts_pulang,
        kamar_inap.lama,
        dokter.nm_dokter,
        kamar_inap.kd_kamar,
        reg_periksa.kd_pj,
        concat(reg_periksa.umurdaftar,' ',reg_periksa.sttsumur) as umur,
        reg_periksa.status_bayar,
        pasien.agama
    ")
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->join('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
            ->join('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
            ->join('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->join('bridging_sep', function ($join) {
                $join->on('kamar_inap.no_rawat', '=', 'bridging_sep.no_rawat')
                     ->where('bridging_sep.jnspelayanan', '1'); // 1 untuk rawat inap
            })
            ->whereNotNull('kamar_inap.tgl_keluar') // sudah keluar
            ->where('kamar_inap.tgl_keluar', '<>', '0000-00-00') // bukan nol
            ->where('kamar_inap.stts_pulang', '<>', '') // status tidak kosong
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kamar_inap.no_rawat', 'like', "%{$search}%")
                        ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%")
                        ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                        ->orWhere(DB::raw("concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab)"), 'like', "%{$search}%")
                        ->orWhere('kamar_inap.kd_kamar', 'like', "%{$search}%")
                        ->orWhere('bangsal.nm_bangsal', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_awal', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_akhir', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_masuk', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_keluar', 'like', "%{$search}%")
                        ->orWhere('dokter.nm_dokter', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.stts_pulang', 'like', "%{$search}%")
                        ->orWhere('penjab.png_jawab', 'like', "%{$search}%")
                        ->orWhere('pasien.agama', 'like', "%{$search}%");
                });
            })
            ->where('reg_periksa.kd_pj', '<>', 'UMUM') // bukan pasien umum
            ->distinct('kamar_inap.no_rawat')
            ->orderByRaw($order)
            ->paginate(10)
            ->withQueryString();

        
        return view('inacbg.ranap', compact('data'));


    }

    public function show(Request $request)
    {
        $no_rawat = $request->input('no_rawat');

        // Ambil data pasien dan rawat inap
        $pasien = DB::table('kamar_inap')
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->select(
                'kamar_inap.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'pasien.tgl_lahir',
                'pasien.jk',
                'pasien.alamat',
                'dokter.nm_dokter',
                'pasien.no_peserta',
                'reg_periksa.kd_pj as jenis_pasien',
                'reg_periksa.umurdaftar as umur',
                'kamar_inap.tgl_masuk',
                'kamar_inap.jam_masuk',
                'kamar_inap.tgl_keluar',
                'kamar_inap.jam_keluar',
                'kamar_inap.lama',
                'kamar_inap.stts_pulang as cara_pulang',
                'reg_periksa.kd_pj as cara_masuk',
                DB::raw("'-' as jenis_rawat"),
                DB::raw("'123456' as no_sep"),
                DB::raw("'INV042025.1396' as no_tagihan")
            )
            
            ->where('kamar_inap.no_rawat', $no_rawat)
            
            ->first();

        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        $sep = DB::table('bridging_sep')
            ->where('no_rawat', $no_rawat)
            ->where('jnspelayanan', '1')
            ->first();

        $bayi = DB::table('penilaian_awal_keperawatan_ralan_bayi')
            ->where('no_rawat', $no_rawat)
            ->first();

        $pemeriksaan = DB::table('pemeriksaan_ranap')
            ->where('no_rawat', $no_rawat)
            ->first();

        $coder = DB::table('mapping_users')
            ->join('pegawai', 'mapping_users.id_pegawai', '=', 'pegawai.id')
            ->where('id_users', auth()->user()->id)
            ->first();
        return view('inacbg.klaim', compact('pasien', 'sep', 'bayi', 'pemeriksaan','coder'));
    }


}
