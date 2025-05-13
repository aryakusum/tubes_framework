<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PresensiConfirmationMail;

class PresensiController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        return view('pegawai.tambah-presensi', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // Ambil data pegawai berdasarkan user_id dengan relasi user
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Check for existing attendance
        $existingPresensi = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existingPresensi) {
            return redirect()->back()->with('error', 'Presensi untuk tanggal ini sudah ada!');
        }

        $presensi = Presensi::create([
            'id_pegawai' => $pegawai->id,
            'nama' => $pegawai->nama_pegawai,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // Kirim email konfirmasi presensi masuk
        Mail::to($pegawai->user->email)->send(new PresensiConfirmationMail($presensi, $pegawai, 'masuk'));

        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        return view('pegawai.view-presensi', compact('presensi', 'pegawai'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        return view('pegawai.edit-presensi', compact('presensi', 'pegawai'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        $presensi->update([
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'nama' => $pegawai->nama_pegawai . ' (' . $pegawai->user->email . ')'
        ]);

        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        $presensi->delete();
        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil dihapus!');
    }

    public function updateJamKeluar(Request $request, $id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $request->validate([
            'jam_keluar' => 'required',
        ]);

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        $presensi->update([
            'jam_keluar' => $request->jam_keluar,
            'keterangan' => 'Selesai Bekerja',
            'nama' => $pegawai->nama_pegawai . ' (' . $pegawai->user->email . ')'
        ]);

        // Kirim email konfirmasi presensi keluar
        Mail::to($pegawai->user->email)->send(new PresensiConfirmationMail($presensi, $pegawai, 'keluar'));

        return redirect()->route('dashboard.pegawai.keluar')->with('success', 'Jam keluar berhasil disimpan!');
    }

    public function mulaiBekerja($id)
    {
        $user = Auth::user();
        $pegawai = Pegawai::with('user')
            ->where('user_id', $user->id)
            ->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $presensi = Presensi::where('id', $id)
            ->where('id_pegawai', $pegawai->id)
            ->firstOrFail();

        $presensi->update([
            'mulai_bekerja' => now(),
            'nama' => $pegawai->nama_pegawai . ' (' . $pegawai->user->email . ')'
        ]);

        return redirect()->route('dashboard.pegawai')->with('success', 'Waktu mulai bekerja telah dicatat!');
    }
}
