<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiAuthController;
use App\Http\Controllers\KonsumenAuthController;
use App\Http\Controllers\KonsumenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PresensiController;

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

Route::middleware(['auth', 'Pegawai'])->group(function () {
    Route::get('/Pegawai', [PegawaiAuthController::class, 'showLoginForm'])
        ->middleware('Pegawai')
        ->name('Presensi');
    Route::post('/Pegawai', [PegawaiAuthController::class, 'login']);
    Route::get('/dashboard-pegawai', function () {
        $presensis = \App\Models\Presensi::orderBy('tanggal', 'desc')->get();
        return view('dashboard-pegawai', compact('presensis'));
    })->name('dashboard.pegawai');
    Route::get('/pegawai/tambah-presensi', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/pegawai/tambah-presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/dashboard-pegawai-keluar', function () {
        $presensis = \App\Models\Presensi::orderBy('tanggal', 'desc')->get();
        return view('dashboard-pegawai-keluar', compact('presensis'));
    })->name('dashboard.pegawai.keluar');
});

Route::post('/logout', function () {
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
    return view('Presensi');
})->name('loginpegawai');

// Route proses login pegawai
Route::post('/Pegawai', [PegawaiAuthController::class, 'login']);

// Route dashboard pegawai, hanya untuk yang sudah login dan user_group Pegawai
// Route::middleware(['auth', 'Pegawai'])->group(function () {
//     Route::get('/dashboard-pegawai', function () {
//         return view('dashboard-pegawai');
//     })->name('dashboard.pegawai');
// });

Route::resource('presensi', App\Http\Controllers\PresensiController::class);

Route::post('/presensi/keluar/{id}', [PresensiController::class, 'updateJamKeluar'])->name('presensi.keluar');

Route::post('/presensi/mulai-bekerja/{id}', [PresensiController::class, 'mulaiBekerja'])->name('presensi.mulai_bekerja');


