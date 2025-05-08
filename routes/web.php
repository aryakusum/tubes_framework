<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiAuthController;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;

=======
use App\Http\Controllers\KonsumenAuthController;
use App\Http\Controllers\KonsumenController;
use Illuminate\Support\Facades\Auth;
>>>>>>> 72d8c1429be3fd86c80bfae5e8719539f91b0953

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

Route::get('/Pegawai', function () {
    return view('Presensi');
});
// tambahan route untuk proses login


Route::get('/Pegawai', [PegawaiAuthController::class, 'showLoginForm'])
    ->middleware('Pegawai')
    ->name('Presensi');
Route::post('/Pegawai', [PegawaiAuthController::class, 'login']);

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/Pegawai');
})->name('logout');

<<<<<<< HEAD




=======
// Login & Register Konsumen
Route::get('/konsumen/login', [KonsumenAuthController::class, 'showLoginForm'])->name('konsumen.login');
Route::post('/konsumen/login', [KonsumenAuthController::class, 'login']);
Route::get('/konsumen/register', [KonsumenAuthController::class, 'showRegisterForm'])->name('konsumen.register');
Route::post('/konsumen/register', [KonsumenAuthController::class, 'register']);
Route::get('/konsumen/verify-otp', [KonsumenAuthController::class, 'showVerifyOtpForm'])->name('konsumen.verify-otp');
Route::post('/konsumen/verify-otp', [KonsumenAuthController::class, 'verifyOtp']);
Route::post('/konsumen/send-otp', [KonsumenController::class, 'sendOtp']);
>>>>>>> 72d8c1429be3fd86c80bfae5e8719539f91b0953
