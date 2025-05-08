<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiAuthController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//tambahan baru tubes
Route::get('/', function () {
    // return view('welcome');
    // diarahkan ke login customer
    return view('login');
});
// login customer
Route::get('/Pegawai', [App\Http\Controllers\PegawaiAuthController::class, 'Presensi'])
     ->middleware('Pegawai')
     ->name('Presensi');

Route::get('/Pegawai', function () {
    return view('Presensi');
});
// tambahan route untuk proses login
use Illuminate\Http\Request;
Route::post('/Pegawai', [App\Http\Controllers\PegawaiAuthController::class, 'Presensi']);

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/Pegawai');
})->name('logout');





