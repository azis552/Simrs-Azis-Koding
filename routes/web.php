<?php

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

Route::post('logincheck', [UserController::class,  'logincheck'])->name('users.logincheck');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', UserController::class);
    Route::resource('obats', ResepObatController::class);
    Route::resource('telaah', TelaahObatController::class);
    Route::post('obats', [ResepObatController::class, 'search'])->name('obats.search');
});

