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
        
        $obat_umum = DB::table('databarang as db')
        ->join('resep_dokter as resdok', 'resdok.kode_brng', '=', 'db.kode_brng')
        ->join('kodesatuan as sat', 'db.kode_sat', '=', 'sat.kode_sat')
        ->select('resdok.*', 'db.nama_brng', 'sat.satuan')
        ->where('resdok.no_resep', $id)
        ->get();

        $obat_racik = DB::table('resep_dokter_racikan as resracik')
        ->join('metode_racik as metracik', 'resracik.kd_racik', '=', 'metracik.kd_racik')
        ->select('resracik.*', 'metracik.nm_racik')
        ->where('resracik.no_resep', $id)
        ->get();

        $telaah = DB::table('resep_obat as res')
        ->join('reg_periksa as reg', 'res.no_rawat', '=', 'reg.no_rawat')
        ->join('pasien as pas', 'reg.no_rkm_medis', '=', 'pas.no_rkm_medis')
        ->leftjoin('telaah_obat_resep_konselings as tel', 'res.no_resep', '=', 'tel.noresep')

        ->select('res.*', 'reg.no_rawat', 'pas.*', 'tel.id as telaah', 'tel.*')
        ->where('res.no_resep',  $id )
        ->first();

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
        return view('DataObat.telaahcetak', compact('obat_umum', 'obat_racik', 'telaah', 'pasien', 'perusahaan', 'pemberiobat'));
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
}
