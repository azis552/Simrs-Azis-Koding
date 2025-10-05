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
        $order  = $request->get('order', 'kamar_inap.tgl_masuk DESC'); // default order

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
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pasien.nm_pasien', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.no_rawat', 'like', "%{$search}%")
                        ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%");
                });
            })
            ->orderByRaw($order)
            ->paginate(10)
            ->withQueryString();
        return view('inacbg.ranap', compact('data'));
    }
}
