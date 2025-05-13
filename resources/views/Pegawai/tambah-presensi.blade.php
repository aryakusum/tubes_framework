<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col py-8 px-4 min-h-screen">
        <div class="flex flex-col items-center mb-8">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="Profile" class="w-20 h-20 rounded-full mb-2">
            <div class="text-center">
                <div class="font-bold">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <nav class="flex-1">
            <ul class="space-y-2">
                <li><a href="/dashboard-pegawai" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"><span class="ml-2"></span></a></li>
            </ul>
        </nav>
        <div class="mt-8">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded transition duration-200 mt-4">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-100 p-8 overflow-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <span class="text-2xl font-bold text-gray-800">Tambah Presensi</span>
            </div>
        </div>

        <!-- Form -->
        <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Tambah Presensi Pegawai</h2>
            <form action="{{ route('presensi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="id_pegawai" class="block mb-1">ID Pegawai</label>
                    <input type="text" name="id_pegawai" id="id_pegawai" class="w-full border px-3 py-2 rounded bg-gray-100" value="{{ $pegawai->id }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama" class="block mb-1">Nama Pegawai</label>
                    <input type="text" name="nama" id="nama" class="w-full border px-3 py-2 rounded bg-gray-100" value="{{ $pegawai->nama_pegawai }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="block mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full border px-3 py-2 rounded bg-gray-100" value="{{ $pegawai->user->email }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" id="jam_masuk" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Status</label>
                    <select name="status" class="w-full border px-3 py-2 rounded" required>
                        <option value="Hadir">Hadir</option>
                        <!-- Tambahkan opsi lain jika perlu -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="w-full border px-3 py-2 rounded" placeholder="( DI ISI SAAT ANDA SELESAI BEKERJA )">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tanggalInput = document.getElementById('tanggal');
            if (tanggalInput) {
                var today = new Date();
                var day = String(today.getDate()).padStart(2, '0');
                var month = String(today.getMonth() + 1).padStart(2, '0');
                var year = today.getFullYear();
                tanggalInput.value = year + '-' + month + '-' + day;
            }
        });
    </script>
</body>

</html>