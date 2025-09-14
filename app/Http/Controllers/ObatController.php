<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        // ambil filter dari input form kalau ada
        $nmjns = $request->input('nmjns', '');
        $nmkategori = $request->input('nmkategori', '');
        $nmgolongan = $request->input('nmgolongan', '');
        $tcari = $request->input('tcari', '');

        $hppfarmasi = "databarang.dasar"; // contoh field hpp

        $data = DB::table('databarang')
            ->select(
                'databarang.kode_brng',
                'databarang.nama_brng',
                'databarang.kode_sat',
                DB::raw("$hppfarmasi as dasar")
            )
            ->join('jenis', 'databarang.kdjns', '=', 'jenis.kdjns')
            ->join('golongan_barang', 'databarang.kode_golongan', '=', 'golongan_barang.kode')
            ->join('kategori_barang', 'databarang.kode_kategori', '=', 'kategori_barang.kode')
            ->where('jenis.nama', 'like', "%$nmjns%")
            ->where('kategori_barang.nama', 'like', "%$nmkategori%")
            ->where('golongan_barang.nama', 'like', "%$nmgolongan%")
            ->where(function ($q) use ($tcari) {
                $q->where('databarang.kode_brng', 'like', "%$tcari%")
                    ->orWhere('databarang.nama_brng', 'like', "%$tcari%");
            })
            ->orderBy('databarang.kode_brng', 'asc')
            ->paginate(10); // <<-- pagination

        // 🔽 ambil data dropdown
        $jenisList = DB::table('jenis')->orderBy('nama')->get();
        $kategoriList = DB::table('kategori_barang')->orderBy('nama')->get();
        $golonganList = DB::table('golongan_barang')->orderBy('nama')->get();

        return view('DataObat.stokObat', compact(
            'data',
            'nmjns',
            'nmkategori',
            'nmgolongan',
            'tcari',
            'jenisList',
            'kategoriList',
            'golonganList'
        ));
    }
    public function riwayat(Request $request, $kode_brng)
    {
        $barang = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
        if (!$barang) {
            abort(404, 'Obat tidak ditemukan');
        }

        // ambil filter tanggal
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        $riwayat = DB::table('riwayat_barang_medis')
            ->where('kode_brng', $kode_brng)
            ->when($tgl_awal && $tgl_akhir, function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->paginate(20)
            ->appends(['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]); // biar pagination bawa filter

        return view('DataObat.riwayatObat', compact('barang', 'riwayat', 'tgl_awal', 'tgl_akhir'));
    }

    public function cetakRiwayat(Request $request, $kode_brng)
    {
        $barang = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
        if (!$barang) {
            abort(404, 'Obat tidak ditemukan');
        }

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        $riwayat = DB::table('riwayat_barang_medis')
            ->where('kode_brng', $kode_brng)
            ->when($tgl_awal && $tgl_akhir, function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal', [$tgl_awal, $tgl_akhir]);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get();

        return view('DataObat.cetakRiwayat', compact('barang', 'riwayat', 'tgl_awal', 'tgl_akhir'));
    }


}