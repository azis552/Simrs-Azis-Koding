<?php

namespace App\Http\Controllers;

use App\Models\TelaahObatResepKonseling;
use Illuminate\Http\Request;

class TelaahObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validate = $request->validate([
            "noresep" => "",
            "kejelasanTulisanResep" => "",
            "beratbadan" => "",
            "identitasObat" => "",
            "tepatObat" => "",
            "tepatDosis" => "",
            "tepatRute" => "",
            "tepatWaktu" => "",
            "duplikasi" => "",
            "alergi" => "",
            "interaksiObat" => "",
            "kontraIndikasiLainnya" => "",
            "polifarmasi" => "",
            "obatluar" => "",
            "alatkhusus" => "",
            "antibiotik" => "",
            "pm" => "",
            "efeksamping" => "",
            "indeksterapisempit" => "",
            "interaksiobatKonseling" => "",
            "interaksiobatmakanan" => "",
            "tepatObatKonseling" => "",
            "tepatInformasiKonseling" => "",
            "tepatDokumentasiKonseling" => "",
            "kesesuaianIdentitasPasienResep" => "",
            "namaObatResep" => "",
            "dosisResep" => "",
            "ruteCaraResep" => "",
            "waktuResep" => "",
        ]);

        $simpan = TelaahObatResepKonseling::create($validate);

        return redirect()->route('obats.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
