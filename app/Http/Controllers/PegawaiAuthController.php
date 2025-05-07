<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// tambahan untuk proses authentikasi
use Illuminate\Support\Facades\Auth;
use App\Models\User; //untuk akses kelas model user

// untuk bisa menggunakan hash
use Illuminate\Support\Facades\Hash;

class PegawaiAuthController extends Controller
{
    // method untuk menampilkan halaman awal login
    public function showLoginForm()
    {
        return view('Presensi');
    }

    // proses validasi data login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // if (Auth::attempt($credentials)) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_group' => 'Pegawai'])) {
            $request->session()->regenerate();
            return redirect()->intended('/Pegawai');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
            'user_group' => 'User Grup tidak berhak mengakses',
        ]);
    }

    // method untuk menangani logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/Pegawai');
    }

    // ubah password
    public function ubahpassword()
    {
        return view('ubahpassword');
    }
}
