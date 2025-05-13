<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col py-8 px-4 min-h-screen">
        <div class="flex flex-col items-center mb-8">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama_pegawai) }}" alt="Profile" class="w-20 h-20 rounded-full mb-2">
            <div class="text-center">
                <div class="font-bold">{{ $pegawai->nama_pegawai }}</div>
                <div class="text-sm text-gray-400">{{ $pegawai->user->email }}</div>
            </div>
        </div>
        <nav class="flex-1">
            <ul class="space-y-2">
                <li><a href="/dashboard-pegawai" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded font-bold">Presensi Masuk</a></li>
            </ul>
            <ul class="space-y-2">
                <li class="mt-4"><a href="/dashboard-pegawai-keluar" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded font-bold">Presensi Keluar</a></li>
            </ul>
        </nav>
        <div class="mt-8">
            <form action="{{ route('logout') }}" method="POST">
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
                <span class="text-2xl font-bold text-gray-800">Pendataan Pegawai Resto</span>
            </div>
            <div class="text-gray-600 font-semibold">{{ now()->format('Y') }}</div>
        </div>
        <!-- Kehadiran Table -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Presensi Masuk Pegawai</h2>
                <a href="/pegawai/tambah-presensi" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">+ Tambah Presensi</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-3 py-2">NO</th>
                            <th class="px-3 py-2">ID PEGAWAI</th>
                            <th class="px-3 py-2">NAMA PEGAWAI</th>
                            <th class="px-3 py-2">EMAIL</th>
                            <th class="px-3 py-2">TANGGAL</th>
                            <th class="px-3 py-2">JAM MASUK</th>
                            <th class="px-3 py-2">STATUS</th>
                            <th class="px-3 py-2">KETERANGAN</th>
                            <th class="px-3 py-2">MULAI BEKERJA</th>
                            <th class="px-3 py-2">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $presensi)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2">{{ $presensi->id_pegawai }}</td>
                            <td class="px-3 py-2">{{ $pegawai->nama_pegawai }}</td>
                            <td class="px-3 py-2">{{ $pegawai->user->email }}</td>
                            <td class="px-3 py-2">{{ $presensi->tanggal }}</td>
                            <td class="px-3 py-2">{{ $presensi->jam_masuk }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($presensi->status == 'Hadir') bg-green-100 text-green-800
                                    @elseif($presensi->status == 'Izin') bg-yellow-100 text-yellow-800
                                    @elseif($presensi->status == 'Sakit') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $presensi->status }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $presensi->keterangan ?? '-' }}</td>
                            <td class="px-3 py-2">
                                @if($presensi->mulai_bekerja)
                                {{ $presensi->mulai_bekerja }}
                                @else
                                <form action="{{ url('/presensi/mulai-bekerja/'.$presensi->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">Mulai Bekerja</button>
                                </form>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex space-x-2">
                                    <a href="/presensi/{{ $presensi->id }}" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">View</a>
                                    <a href="/presensi/{{ $presensi->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs font-semibold">Edit</a>
                                    <form action="/presensi/{{ $presensi->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-gray-400">Belum ada data presensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(session('success'))
        <div class="mt-6 max-w-lg mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif
    </main>
</body>

</html>