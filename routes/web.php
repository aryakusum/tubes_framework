<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiAuthController;
use App\Http\Controllers\KonsumenAuthController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\KeranjangController;
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
    return view('konsumen.login');
});
// login customer

Route::middleware(['auth', 'role:Pegawai'])->group(function () {
    Route::get('/Pegawai', [PegawaiAuthController::class, 'showLoginForm'])
        ->middleware('Pegawai')
        ->name('Presensi');
    Route::post('/Pegawai', [PegawaiAuthController::class, 'Presensi']);
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/loginpegawai');
})->name('logout');

// Login & Register Konsumen
Route::get('/konsumen/login', [KonsumenAuthController::class, 'showLoginForm'])->name('konsumen.login');
Route::post('/konsumen/login', [KonsumenAuthController::class, 'login']);
Route::get('/konsumen/register', [KonsumenAuthController::class, 'showRegisterForm'])->name('konsumen.register');
Route::post('/konsumen/register', [KonsumenAuthController::class, 'register']);
Route::get('/konsumen/verify-otp', [KonsumenAuthController::class, 'showVerifyOtpForm'])->name('konsumen.verify-otp');
Route::post('/konsumen/verify-otp', [KonsumenAuthController::class, 'verifyOtp']);
Route::post('/konsumen/send-otp', [KonsumenController::class, 'sendOtp']);

Route::get('/loginpegawai', function () {
    // Ganti 'login' dengan nama view login yang kamu punya
    return view('Presensi');
})->name('loginpegawai');
Route::get('/konsumen/dashboard', [KonsumenController::class, 'dashboard'])->name('konsumen.dashboard');

Route::post('/konsumen/add-to-cart', [KonsumenController::class, 'addToCart'])->name('konsumen.addToCart');
Route::get('/dashboard', [KeranjangController::class, 'dashboard'])->name('dashboard');
Route::get('/galeri', [KeranjangController::class, 'dashboard'])->name('galeri');
Route::middleware(['auth', 'konsumen'])->group(function () {
    Route::get('/konsumen/dashboard', [KonsumenController::class, 'dashboard'])->name('konsumen.dashboard');
});
