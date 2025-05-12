<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Presensi Pegawai</h2>
        <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 font-semibold">ID Pegawai</label>
                <input type="text" class="w-full border px-3 py-2 rounded bg-gray-100" value="{{ $presensi->id_pegawai }}" readonly>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nama</label>
                <input type="text" class="w-full border px-3 py-2 rounded bg-gray-100" value="{{ $presensi->nama }}" readonly>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border px-3 py-2 rounded" value="{{ $presensi->tanggal }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Jam Masuk</label>
                <input type="time" name="jam_masuk" class="w-full border px-3 py-2 rounded" value="{{ $presensi->jam_masuk }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Status</label>
                <select name="status" class="w-full border px-3 py-2 rounded" required>
                    <option value="Hadir" {{ $presensi->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Izin" {{ $presensi->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ $presensi->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Keterangan</label>
                <input type="text" name="keterangan" class="w-full border px-3 py-2 rounded" value="{{ $presensi->keterangan }}">
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('dashboard.pegawai') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html> 