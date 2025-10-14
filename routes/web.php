<?php

use App\Http\Controllers\EklaimController;
use App\Http\Controllers\InacbgRanapController;
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
    Route::post('users/mapping', [UserController::class, 'mapping'])->name('users.mapping');
    Route::resource('users', UserController::class);
    Route::resource('obats', ResepObatController::class);
    Route::resource('telaah', TelaahObatController::class);
    Route::post('obats', [ResepObatController::class, 'search'])->name('obats.search');
    Route::get('stokObat', [ObatController::class, 'index'])->name('obats.stokObat');
    Route::get('/stok/{kode_brng}/riwayat', [ObatController::class, 'riwayat'])->name('stok.riwayat');
    // untuk cetak (versi print-friendly)
    Route::get('/stok/{kode_brng}/riwayat/cetak', [ObatController::class, 'cetakRiwayat'])->name('stok.riwayat.cetak');
    Route::get('inacbg-ranap', [InacbgRanapController::class, 'index'])->name('inacbg-ranap.index');
    Route::post('inacbg-ranap', [InacbgRanapController::class, 'store'])->name('inacbg-ranap.store');
    Route::post('inacbg-ranap/show', [InacbgRanapController::class, 'show'])->name('inacbg-ranap.show');
    Route::post('inacbg/hapusklaim', [InacbgRanapController::class, 'hapusklaim'])->name('inacbg.hapusklaim');
    Route::post('/idrg/update-log', [InacbgRanapController::class, 'updateLog'])->name('idrg.updateLog');
    Route::post('/grouping-idrg', [EklaimController::class, 'groupingIdrg']);
    Route::post('/save-grouping-idrg-log', [InacbgRanapController::class, 'saveGroupingIdrgLog']);

    Route::post('/final-idrg', [EklaimController::class, 'finalIdrg']);
    Route::post('/save-final-idrg-log', [InacbgRanapController::class, 'saveFinalIdrgLog']);

    Route::post('/idrg-grouper-reedit', [EklaimController::class, 'idrgGrouperReedit']);
    Route::post('/hapus-final-idrg', [InacbgRanapController::class, 'hapusFinalIdrg']);

    Route::post('/inacbg/import/save-log', [InacbgRanapController::class, 'saveImportInacbgLog']);


});

