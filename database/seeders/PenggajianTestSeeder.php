<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\Carbon;

class PenggajianTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user pegawai
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'user_group' => 'pegawai',
        ]);

        // 2. Buat data pegawai
        $pegawai = Pegawai::create([
            'nama_pegawai' => 'John Doe',
            'jenis_kelamin' => 'Laki-laki',
            'jenis_Pegawai' => 'Pegawai',
            'jabatan' => 'Staff',
            'alamat' => 'Jl. Test No. 123',
            'no_telp' => '08123456789',
            'tgl_masuk' => '2024-01-01',
            'gaji_pokok' => 3000000,
            'user_id' => $user->id,
        ]);

        // 3. Buat data presensi untuk bulan ini
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip hari Sabtu dan Minggu
            if ($date->isWeekend()) {
                continue;
            }

            Presensi::create([
                'id_pegawai' => $pegawai->id,
                'nama' => $pegawai->nama_pegawai,
                'tanggal' => $date->format('Y-m-d'),
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '17:00:00',
                'status' => 'Hadir',
                'keterangan' => 'Masuk kerja',
                'mulai_bekerja' => $date->format('Y-m-d') . ' 08:00:00',
            ]);
        }

        // Buat user admin jika belum ada
        if (!User::where('user_group', 'admin')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'user_group' => 'admin',
            ]);
        }
    }
}
