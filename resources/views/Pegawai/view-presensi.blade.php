<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Detail Presensi Pegawai</h2>
        <div class="mb-4"><span class="font-semibold">ID Pegawai:</span> {{ $presensi->id_pegawai }}</div>
        <div class="mb-4"><span class="font-semibold">Nama:</span> {{ $presensi->nama }}</div>
        <div class="mb-4"><span class="font-semibold">Tanggal:</span> {{ $presensi->tanggal }}</div>
        <div class="mb-4"><span class="font-semibold">Jam Masuk:</span> {{ $presensi->jam_masuk }}</div>
        <div class="mb-4"><span class="font-semibold">Status:</span> {{ $presensi->status }}</div>
        <div class="mb-4"><span class="font-semibold">Keterangan:</span> {{ $presensi->keterangan ?? '-' }}</div>
        <div class="mb-4"><span class="font-semibold">Mulai Bekerja:</span> {{ $presensi->mulai_bekerja ?? '-' }}</div>
        <a href="{{ route('dashboard.pegawai') }}" class="block mt-6 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded">Kembali</a>
    </div>
</body>
</html> 