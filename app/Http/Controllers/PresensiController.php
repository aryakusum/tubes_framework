<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi; // Pastikan model Presensi sudah ada
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function create()
    {
        return view('pegawai.tambah-presensi');
    }

    // Proses simpan data
    public function store(Request $request)
    {
        $pegawai = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        Presensi::create([
            'id_pegawai' => $pegawai->id,
            'nama' => $pegawai->name,
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $presensi = Presensi::findOrFail($id);
        return view('pegawai.view-presensi', compact('presensi'));
    }

    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        return view('pegawai.edit-presensi', compact('presensi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'tanggal' => $request->tanggal,
            'jam_masuk' => $request->jam_masuk,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();
        return redirect()->route('dashboard.pegawai')->with('success', 'Presensi berhasil dihapus!');
    }

    public function updateJamKeluar(Request $request, $id)
    {
        $request->validate([
            'jam_keluar' => 'required',
        ]);
        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'jam_keluar' => $request->jam_keluar,
            'keterangan' => 'Selesai Bekerja',
        ]);
        return redirect()->route('dashboard.pegawai.keluar')->with('success', 'Jam keluar berhasil disimpan!');
    }

    public function mulaiBekerja($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'mulai_bekerja' => now(),
        ]);
        return redirect()->route('dashboard.pegawai')->with('success', 'Waktu mulai bekerja telah dicatat!');
    }
}
