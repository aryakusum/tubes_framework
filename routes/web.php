<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PegawaiAuthController;
use App\Http\Controllers\KonsumenAuthController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CobaMidtransController;
use App\Http\Controllers\KeranjangController;

// Coba Midtrans
Route::get('/cekmidtrans', [CobaMidtransController::class, 'cekmidtrans']);

// Halaman utama diarahkan ke login
Route::get('/', function () {
    return view('login');
});

// Login Pegawai
Route::get('/Pegawai', [PegawaiAuthController::class, 'showLoginForm'])->middleware('Pegawai')->name('Presensi');
Route::post('/Pegawai', [PegawaiAuthController::class, 'login']);

// Logout Umum
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/Pegawai');
})->name('logout');

// Konsumen Auth
Route::get('/konsumen/login', [KonsumenAuthController::class, 'showLoginForm'])->name('konsumen.login');
Route::post('/konsumen/login', [KonsumenAuthController::class, 'login']);
Route::get('/konsumen/register', [KonsumenAuthController::class, 'showRegisterForm'])->name('konsumen.register');
Route::post('/konsumen/register', [KonsumenAuthController::class, 'register']);
Route::get('/konsumen/verify-otp', [KonsumenAuthController::class, 'showVerifyOtpForm'])->name('konsumen.verify-otp');
Route::post('/konsumen/verify-otp', [KonsumenAuthController::class, 'verifyOtp']);
Route::post('/konsumen/send-otp', [KonsumenController::class, 'sendOtp']);

// Konsumen Area
Route::middleware(['auth', 'konsumen'])->group(function () {
    Route::get('/konsumen/dashboard', [KonsumenController::class, 'dashboard'])->name('konsumen.dashboard');
    Route::get('/lihatriwayat', [KeranjangController::class, 'lihatriwayat'])->middleware(\App\Http\Middleware\CustomerMiddleware::class);
    Route::get('/cek_status_pembayaran_pg', [KeranjangController::class, 'cek_status_pembayaran_pg']);
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Cart (Keranjang)
Route::get('/keranjang', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/keranjang/{id}/decrease', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
Route::post('/keranjang/{id}/increase', [CartController::class, 'increaseQuantity'])->name('cart.increase');

// Tambahan cart konsumen
Route::get('/konsumen/keranjang', [KonsumenController::class, 'keranjang'])->name('konsumen.keranjang');
Route::post('/konsumen/add-to-cart', [KonsumenController::class, 'addToCart'])->name('add.to.cart');


// proses pengiriman email
use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);
