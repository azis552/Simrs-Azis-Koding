<?php

use App\Http\Controllers\EklaimController;
use App\Http\Controllers\InacbgRajalController;
use App\Http\Controllers\InacbgRanapController;
use App\Http\Controllers\KasirRalanController;
use App\Http\Controllers\KasirRanapController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ResepObatController;
use App\Http\Controllers\TelaahObatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('logout', [UserController::class, 'logout'])->name('users.logout');

Route::post('logincheck', [UserController::class, 'logincheck'])->name('users.logincheck');

Route::group(['middleware' => ['auth']], function () {
    // Apotik bypass obat


    Route::get("obat/{id}/validasi", [ResepObatController::class, 'obat'])->name('obat.validasi');
    Route::put('/obat/{no_resep}/updatejam', [ResepObatController::class, 'updateJam'])->name('obats.updatejam');
    Route::post('/obat/{no_resep}/tambah', [ResepObatController::class, 'tambahObat'])->name('obats.tambah');
    Route::patch('/obat/{no_resep}/{kode_brng}/kurang', [ResepObatController::class, 'kurangObat'])->name('obats.kurang');
    Route::delete('/obat/{no_resep}/{kode_brng}/hapus', [ResepObatController::class, 'hapusObat'])->name('obats.hapus');
    Route::patch('/obat/{no_resep}/{kode_brng}/updateaturan', [ResepObatController::class, 'updateAturanPakai'])->name('obats.updateaturan');
    Route::put('/obat/{no_resep}/updatedokter', [ResepObatController::class, 'updateDokter'])->name('obats.updatedokter');
    Route::post('users/mapping', [UserController::class, 'mapping'])->name('users.mapping');
    Route::resource('users', UserController::class);
    Route::resource('obats', ResepObatController::class);
    Route::resource('telaah', TelaahObatController::class);
    Route::post('obats', [ResepObatController::class, 'search'])->name('obats.search');
    Route::get('stokObat', [ObatController::class, 'index'])->name('obats.stokObat');
    Route::get('/stok/{kode_brng}/riwayat', [ObatController::class, 'riwayat'])->name('stok.riwayat');
    // untuk cetak (versi print-friendly)
    Route::get('/stok/{kode_brng}/riwayat/cetak', [ObatController::class, 'cetakRiwayat'])->name('stok.riwayat.cetak');


    // Inacbg Ranap

    Route::get('inacbg-ranap', [InacbgRanapController::class, 'index'])->name('inacbg-ranap.index');
    Route::post('inacbg-ranap', [InacbgRanapController::class, 'store'])->name('inacbg-ranap.store');
    Route::get('inacbg-ranap/show', [InacbgRanapController::class, 'show'])->name('inacbg-ranap.show');
    Route::post('inacbg/hapusklaim', [InacbgRanapController::class, 'hapusklaim'])->name('inacbg.hapusklaim');
    Route::post('/idrg/update-log', [InacbgRanapController::class, 'updateLog'])->name('idrg.updateLog');
    Route::post('/grouping-idrg', [EklaimController::class, 'groupingIdrg']);
    Route::post('/save-grouping-idrg-log', [InacbgRanapController::class, 'saveGroupingIdrgLog']);

    Route::post('/final-idrg', [EklaimController::class, 'finalIdrg']);
    Route::post('/save-final-idrg-log', [InacbgRanapController::class, 'saveFinalIdrgLog']);

    Route::post('/idrg-grouper-reedit', [EklaimController::class, 'idrgGrouperReedit']);
    Route::post('/hapus-final-idrg', [InacbgRanapController::class, 'hapusFinalIdrg']);

    Route::post('/inacbg/import/save-log', [InacbgRanapController::class, 'saveImportInacbgLog']);
    Route::post('/save-grouping-inacbg-stage1-log', [InacbgRanapController::class, 'saveGroupingStage1Log']);
    Route::post('/save-grouping-inacbg-stage2-log', [InacbgRanapController::class, 'saveGroupingStage2Log']);

    Route::post('/save-final-inacbg-log', [InacbgRanapController::class, 'saveFinalInacbgLog']);
    Route::post('/grouping-inacbg-reedit-final', [InacbgRanapController::class, 'reeditGroupingInacbg']);

    Route::post('/save-claim-final-log', [InacbgRanapController::class, 'saveClaimFinalLog']);
    Route::post('/reedit-claim', [InacbgRanapController::class, 'reeditClaim']);

    Route::post('/log/claim-send/save', [InacbgRanapController::class, 'saveClaimSendLog']);

    Route::post('/delete-response-grouping-inacbg', [InacbgRanapController::class, 'deleteResponseGroupinginacbg']);
    Route::post('/delete-response-grouping-idrg', [InacbgRanapController::class, 'deleteResponseGroupingIdrg']);

    // Inacbg Rajal

    Route::get('inacbg-rajal', [InacbgRajalController::class, 'index'])->name('inacbg-rajal.index');
    Route::post('inacbg-rajal', [InacbgRajalController::class, 'store'])->name('inacbg-rajal.store');
    Route::get('inacbg-rajal/show', [InacbgRajalController::class, 'show'])->name('inacbg-rajal.show');
    Route::post('inacbg-Rajal/hapusklaim', [InacbgRajalController::class, 'hapusklaim'])->name('inacbg.hapusklaimrajal');
    Route::post('/idrg/update-log-rajal', [InacbgRajalController::class, 'updateLogRajal'])->name('idrg.updateLograjal');
    Route::post('/grouping-idrg-rajal', [EklaimController::class, 'groupingIdrg']);
    Route::post('/save-grouping-idrg-log-rajal', [InacbgRajalController::class, 'saveGroupingIdrgLog']);

    Route::post('/final-idrg-rajal', [EklaimController::class, 'finalIdrg']);
    Route::post('/save-final-idrg-log-rajal', [InacbgRajalController::class, 'saveFinalIdrgLog']);

    Route::post('/idrg-grouper-reedit-rajal', [EklaimController::class, 'idrgGrouperReedit']);
    Route::post('/hapus-final-idrg-rajal', [InacbgRajalController::class, 'hapusFinalIdrg']);

    Route::post('/inacbg/import/save-log-rajal', [InacbgRajalController::class, 'saveImportInacbgLog']);
    Route::post('/save-grouping-inacbg-stage1-log-rajal', [InacbgRajalController::class, 'saveGroupingStage1Log']);
    Route::post('/save-grouping-inacbg-stage2-log-rajal', [InacbgRajalController::class, 'saveGroupingStage2Log']);

    Route::post('/save-final-inacbg-log-rajal', [InacbgRajalController::class, 'saveFinalInacbgLog']);
    Route::post('/grouping-inacbg-reedit-final-rajal', [InacbgRajalController::class, 'reeditGroupingInacbg']);

    Route::post('/save-claim-final-log-rajal', [InacbgRajalController::class, 'saveClaimFinalLog']);
    Route::post('/reedit-claim-rajal', [InacbgRajalController::class, 'reeditClaim']);

    Route::post('/log/claim-send/save-rajal', [InacbgRajalController::class, 'saveClaimSendLog']);

    // Kasir Ralan
    Route::prefix('kasir/ralan')->name('kasir.ralan.')->group(function () {
        Route::get('/', [KasirRalanController::class, 'index'])->name('index');
        Route::put('/{no_rawat}/status', [KasirRalanController::class, 'setStatus'])
            ->name('status')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/billing', [KasirRalanController::class, 'billing'])
            ->name('billing')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/pemberian-obat', [KasirRalanController::class, 'pemberianObat'])
            ->name('pemberian-obat')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/tindakan', [KasirRalanController::class, 'tindakan'])
            ->name('tindakan')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/lab', [KasirRalanController::class, 'lab'])
            ->name('lab')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/radiologi', [KasirRalanController::class, 'radiologi'])
            ->name('radiologi')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/diagnosa', [KasirRalanController::class, 'diagnosa'])
            ->name('diagnosa')
            ->where('no_rawat', '.*');

        // Surat-surat
        Route::get('/{no_rawat}/surat/kematian', [KasirRalanController::class, 'suratKematian'])
            ->name('surat.kematian')
            ->where('no_rawat', '.*');
        Route::post('/{no_rawat}/surat/kematian/cetak', [KasirRalanController::class, 'cetakSuratKematian'])
            ->name('surat.kematian.cetak')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/sakit', [KasirRalanController::class, 'suratSakit'])
            ->name('surat.sakit')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/sehat', [KasirRalanController::class, 'suratSehat'])
            ->name('surat.sehat')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/kontrol', [KasirRalanController::class, 'suratKontrol'])
            ->name('surat.kontrol')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/rujukan', [KasirRalanController::class, 'suratRujukan'])
            ->name('surat.rujukan')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/persetujuan', [KasirRalanController::class, 'suratPersetujuan'])
            ->name('surat.persetujuan')
            ->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/pulang-paksa', [KasirRalanController::class, 'suratPulangPaksa'])
            ->name('surat.pulang-paksa')
            ->where('no_rawat', '.*');
    });

    // Kasir Ranap
    Route::prefix('kasir/ranap')->name('kasir.ranap.')->group(function () {
        Route::get('/', [KasirRanapController::class, 'index'])->name('index');
        Route::put('/{no_rawat}/status', [KasirRanapController::class, 'setStatus'])->name('status')->where('no_rawat', '.*');
        Route::get('/{no_rawat}/surat/kematian', [KasirRanapController::class, 'suratKematian'])->name('surat.kematian')->where('no_rawat', '.*');
        Route::post('/{no_rawat}/surat/kematian/cetak', [KasirRanapController::class, 'cetakSuratKematian'])->name('surat.kematian.cetak')->where('no_rawat', '.*');
    });


});

