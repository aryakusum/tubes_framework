<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Konsumen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpMail;

class KonsumenAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('konsumen.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['user_group' => 'Konsumen']))) {
            $request->session()->regenerate();
            return redirect()->intended('/konsumen/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showRegisterForm()
    {
        return view('konsumen.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'no_telp' => 'required|string|max:20',
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->nama_konsumen,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_group' => 'Konsumen',
            'email_verified_at' => null,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Konsumen::create([
            'user_id' => $user->id,
            'nama_konsumen' => $request->nama_konsumen,
            'no_telp' => $request->no_telp,
        ]);

        // Kirim OTP ke email via Mailtrap
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Verifikasi Email');
        });

        return redirect()->route('konsumen.verify-otp', ['email' => $user->email]);
    }

    public function showVerifyOtpForm(Request $request)
    {
        return view('konsumen.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->otp == $request->otp && $user->otp_expires_at > now()) {
            $user->email_verified_at = now();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            Auth::login($user);
            return redirect('/konsumen/dashboard')->with('success', 'Verifikasi berhasil!');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa.']);
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $otp = rand(100000, 999999); // Generate OTP
            $user->otp = $otp;
            $user->otp_expires_at = now()->addMinutes(5); // Set expiration time
            $user->save();

            // Kirim OTP ke email
            Mail::to($user->email)->send(new OtpMail($otp));
        }

        return back()->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
