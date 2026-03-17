<?php

namespace App\Http\Controllers;

use App\Models\ResepObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $obats = ResepObat::select('*')->orderBy('tgl_perawatan', 'desc')->orderBy('jam','desc')->paginate(10);

        $obats = DB::table('resep_obat as res')
            ->join('reg_periksa as reg', 'res.no_rawat', '=', 'reg.no_rawat')
            ->join('pasien as pas', 'reg.no_rkm_medis', '=', 'pas.no_rkm_medis')
            ->leftjoin('telaah_obat_resep_konselings as tel', 'res.no_resep', '=', 'tel.noresep')
            ->select('res.*', 'reg.no_rawat', 'pas.nm_pasien', 'tel.id as telaah')
            ->orderBy('res.tgl_perawatan', 'DESC')
            ->orderBy('res.jam', 'DESC')
            ->paginate(10); // Menambahkan pagination

        return view('DataObat.index', compact('obats'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $obats = DB::table('resep_obat as res')
            ->join('reg_periksa as reg', 'res.no_rawat', '=', 'reg.no_rawat')
            ->join('pasien as pas', 'reg.no_rkm_medis', '=', 'pas.no_rkm_medis')
            ->leftjoin('telaah_obat_resep_konselings as tel', 'res.no_resep', '=', 'tel.noresep')
            ->select('res.*', 'reg.no_rawat', 'pas.nm_pasien', 'tel.id as telaah')
            ->where('no_resep', 'like', '%' . $search . '%')
            ->orWhere('res.no_rawat', 'like', '%' . $search . '%')
            ->orWhere('nm_pasien', 'like', '%' . $search . '%')
            ->orWhere('tgl_perawatan', 'like', '%' . $search . '%')
            ->orWhere('jam', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('DataObat.index', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $telaah = DB::table('resep_obat as res')
            ->join('reg_periksa as reg', 'res.no_rawat', '=', 'reg.no_rawat')
            ->join('pasien as pas', 'reg.no_rkm_medis', '=', 'pas.no_rkm_medis')
            ->leftjoin('telaah_obat_resep_konselings as tel', 'res.no_resep', '=', 'tel.noresep')

            ->select('res.*', 'reg.no_rawat', 'pas.*', 'tel.id as telaah', 'tel.*')
            ->where('res.no_resep', $id)
            ->first();

        $obat_umum = DB::select("
        SELECT detail_pemberian_obat.tgl_perawatan, detail_pemberian_obat.jam,
               detail_pemberian_obat.no_rawat, reg_periksa.no_rkm_medis, pasien.nm_pasien, databarang.kode_sat,
               detail_pemberian_obat.kode_brng, databarang.nama_brng, detail_pemberian_obat.embalase, detail_pemberian_obat.tuslah,
               detail_pemberian_obat.jml, detail_pemberian_obat.biaya_obat, detail_pemberian_obat.total, detail_pemberian_obat.h_beli,
               detail_pemberian_obat.kd_bangsal, detail_pemberian_obat.no_batch, detail_pemberian_obat.no_faktur , 
                (SELECT satuan 
                FROM kodesatuan 
                WHERE kode_sat = databarang.kode_sat 
                LIMIT 1) AS satuan,

                (SELECT aturan 
                FROM aturan_pakai 
                WHERE aturan_pakai.no_rawat = detail_pemberian_obat.no_rawat 
                AND aturan_pakai.kode_brng = databarang.kode_brng 
                LIMIT 1) AS aturan_pakai
        FROM detail_pemberian_obat 
        INNER JOIN reg_periksa ON detail_pemberian_obat.no_rawat = reg_periksa.no_rawat 
        INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis 
        INNER JOIN databarang ON detail_pemberian_obat.kode_brng = databarang.kode_brng
        WHERE detail_pemberian_obat.no_rawat = ?", [$telaah->no_rawat]);


        $pemberiobat = DB::table('resep_obat as res')
            ->join('dokter as dok', 'res.kd_dokter', '=', 'dok.kd_dokter')
            ->select('res.*', 'dok.*')
            ->where('res.no_resep', $id)
            ->first();

        $pasien = DB::table('reg_periksa as reg')
            ->join('pasien as pas', 'reg.no_rkm_medis', '=', 'pas.no_rkm_medis')
            ->join('dokter as dok', 'reg.kd_dokter', '=', 'dok.kd_dokter')
            ->join('penjab as pen', 'reg.kd_pj', '=', 'pen.kd_pj')
            ->select('reg.*', 'pas.*', 'dok.*', 'pen.*')
            ->where('reg.no_rawat', $telaah->no_rawat)
            ->first();

        $perusahaan = DB::table('setting')
            ->select('*')
            ->get();
        return view('DataObat.telaahcetak', compact('obat_umum', 'telaah', 'pasien', 'perusahaan', 'pemberiobat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $telaahResep = DB::table('telaah_obat_resep_konselings')
            ->where('noresep', $id)
            ->delete();

        return redirect()->route('obats.index');
    }

    public function obat($id)
    {
        $resep_obat = DB::table('resep_obat')->where('no_resep', $id)->first();

        $obats = DB::table('resep_dokter as det')
            ->join('databarang as dat', 'det.kode_brng', '=', 'dat.kode_brng')
            ->where('det.no_resep', $id)
            ->select('det.*', 'dat.nama_brng')
            ->get();

        $data_obat = DB::table('databarang')->select('kode_brng', 'nama_brng')->get();
        

        return view('DataObat.dataObat', compact('obats', 'resep_obat', 'data_obat'));
    }

    public function updateJam(Request $request, $no_resep)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);

        // Ambil data lama sebelum diupdate
        $resep = DB::table('resep_obat')->where('no_resep', $no_resep)->first();

        if (!$resep) {
            return redirect()->route('obat.validasi', $no_resep)
                ->with('error', 'Resep tidak ditemukan.');
        }

        $tgl_lama = $resep->tgl_perawatan;
        $jam_lama = $resep->jam;

        // Update resep_obat
        DB::table('resep_obat')->where('no_resep', $no_resep)->update([
            'tgl_perawatan' => $request->tanggal,
            'tgl_peresepan' => $request->tanggal,
            'jam' => $request->jam,
            'jam_peresepan' => $request->jam,
        ]);

        // Update detail_pemberian_obat berdasarkan no_rawat + tanggal & jam lama
        DB::table('detail_pemberian_obat')
            ->where('no_rawat', $resep->no_rawat)
            ->where('tgl_perawatan', $tgl_lama)
            ->where('jam', $jam_lama)
            ->update([
                'tgl_perawatan' => $request->tanggal,
                'jam' => $request->jam,
            ]);

        return redirect()->route('obat.validasi', $no_resep)
            ->with('success', 'Tanggal dan jam berhasil diubah.');
    }

    public function tambahObat(Request $request, $no_resep)
    {
        $request->validate([
            'kode_brng' => 'required',
            'jml' => 'required|integer|min:1',
        ]);

        $exists = DB::table('resep_dokter')
            ->where('no_resep', $no_resep)
            ->where('kode_brng', $request->kode_brng)
            ->first();

        if ($exists) {
            DB::table('resep_dokter')
                ->where('no_resep', $no_resep)
                ->where('kode_brng', $request->kode_brng)
                ->update([
                    'jml' => $exists->jml + $request->jml,
                ]);
        } else {
            DB::table('resep_dokter')->insert([
                'no_resep' => $no_resep,
                'kode_brng' => $request->kode_brng,
                'jml' => $request->jml,
            ]);
        }

        return redirect()->route('obat.validasi', $no_resep)
            ->with('success', 'Obat berhasil ditambahkan.');
    }

    public function kurangObat(Request $request, $no_resep, $kode_brng)
    {
        $obat = DB::table('resep_dokter')
            ->where('no_resep', $no_resep)
            ->where('kode_brng', $kode_brng)
            ->first();

        if (!$obat) {
            return redirect()->route('obat.validasi', $no_resep)
                ->with('error', 'Obat tidak ditemukan.');
        }

        if ($obat->jml <= 1) {
            // Jika jumlah tinggal 1, hapus saja
            DB::table('resep_dokter')
                ->where('no_resep', $no_resep)
                ->where('kode_brng', $kode_brng)
                ->delete();

            return redirect()->route('obat.validasi', $no_resep)
                ->with('success', 'Obat dihapus karena jumlah sudah 0.');
        }

        DB::table('resep_dokter')
            ->where('no_resep', $no_resep)
            ->where('kode_brng', $kode_brng)
            ->update([
                'jml' => $obat->jml - 1,
            ]);

        return redirect()->route('obat.validasi', $no_resep)
            ->with('success', 'Jumlah obat berhasil dikurangi.');
    }

    public function hapusObat($no_resep, $kode_brng)
    {
        DB::table('resep_dokter')
            ->where('no_resep', $no_resep)
            ->where('kode_brng', $kode_brng)
            ->delete();

        return redirect()->route('obat.validasi', $no_resep)
            ->with('success', 'Obat berhasil dihapus.');
    }
}
