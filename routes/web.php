<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PegawaiAuthController;
use App\Http\Controllers\KonsumenAuthController;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CobaMidtransController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PresensiController;

// Coba Midtrans
Route::get('/cekmidtrans', [CobaMidtransController::class, 'cekmidtrans']);

// Halaman utama diarahkan ke login
Route::get('/', function () {
    return view('konsumen.login');
});

// Login Pegawai
Route::get('/Pegawai', [PegawaiAuthController::class, 'showLoginForm'])->middleware('Pegawai')->name('Presensi');
Route::post('/Pegawai', [PegawaiAuthController::class, 'login']);
Route::get('/loginpegawai', [PegawaiAuthController::class, 'showLoginForm'])->name('loginpegawai');
Route::post('/loginpegawai', [PegawaiAuthController::class, 'login'])->name('pegawai.login');

// Logout Routes
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/Pegawai');
})->name('logout');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('konsumen/login');
})->name('logout');

// Konsumen Auth
Route::get('/konsumen/login', [KonsumenAuthController::class, 'showLoginForm'])->name('konsumen.login');
Route::post('/konsumen/login', [KonsumenAuthController::class, 'login']);
Route::get('/konsumen/register', [KonsumenAuthController::class, 'showRegisterForm'])->name('konsumen.register');
Route::post('/konsumen/register', [KonsumenAuthController::class, 'register']);
Route::get('/konsumen/verify-otp', [KonsumenAuthController::class, 'showVerifyOtpForm'])->name('konsumen.verify-otp');
Route::post('/konsumen/verify-otp', [KonsumenAuthController::class, 'verifyOtp']);
Route::post('/konsumen/send-otp', [KonsumenController::class, 'sendOtp']);

// Protected Pegawai Routes
Route::middleware(['auth', 'Pegawai'])->group(function () {
    Route::get('/dashboard-pegawai', function () {
        $presensis = \App\Models\Presensi::orderBy('tanggal', 'desc')->get();
        $pegawai = auth()->user()->pegawai;
        return view('dashboard-pegawai', compact('presensis', 'pegawai'));
    })->name('dashboard.pegawai');

    Route::get('/pegawai/tambah-presensi', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/pegawai/tambah-presensi', [PresensiController::class, 'store'])->name('presensi.store');

    Route::get('/dashboard-pegawai-keluar', function () {
        $presensis = \App\Models\Presensi::orderBy('tanggal', 'desc')->get();
        $pegawai = auth()->user()->pegawai;
        return view('dashboard-pegawai-keluar', compact('presensis', 'pegawai'));
    })->name('dashboard.pegawai.keluar');
});

// Presensi Routes
Route::resource('presensi', PresensiController::class);
Route::post('/presensi/keluar/{id}', [PresensiController::class, 'updateJamKeluar'])->name('presensi.keluar');
Route::post('/presensi/mulai-bekerja/{id}', [PresensiController::class, 'mulaiBekerja'])->name('presensi.mulai_bekerja');

// Konsumen Area
Route::middleware(['auth', 'konsumen'])->group(function () {
    Route::get('/konsumen/dashboard', [KonsumenController::class, 'dashboard'])->name('konsumen.dashboard');
    Route::get('/lihatriwayat', [KeranjangController::class, 'lihatriwayat'])->middleware(\App\Http\Middleware\CustomerMiddleware::class);
    Route::get('/cek_status_pembayaran_pg', [KeranjangController::class, 'cek_status_pembayaran_pg']);
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Cart (Keranjang) Routes
Route::get('/keranjang', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/keranjang/{id}/decrease', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
Route::post('/keranjang/{id}/increase', [CartController::class, 'increaseQuantity'])->name('cart.increase');
Route::get('/konsumen/keranjang', [KonsumenController::class, 'keranjang'])->name('konsumen.keranjang');
Route::post('/konsumen/add-to-cart', [KonsumenController::class, 'addToCart'])->name('add.to.cart');

// Additional Routes
Route::get('/dashboard', [KeranjangController::class, 'dashboard'])->name('dashboard');
Route::get('/galeri', [KeranjangController::class, 'dashboard'])->name('galeri');

// Payment Callback Routes
Route::get('/payment/finish', [CartController::class, 'handlePaymentFinish'])->name('payment.finish');
Route::get('/payment/error', [CartController::class, 'handlePaymentError'])->name('payment.error');
Route::get('/payment/cancel', [CartController::class, 'handlePaymentCancel'])->name('payment.cancel');
