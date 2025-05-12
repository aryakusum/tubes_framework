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
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="Profile" class="w-20 h-20 rounded-full mb-2">
            <div class="text-center">
                <div class="font-bold">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
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
                <span class="text-2xl font-bold text-gray-800">Pendataan Pegawai Resto</span>
            </div>
            <div class="text-gray-600 font-semibold">2025</div>
        </div>
        <!-- Presensi Keluar Table -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Presensi Keluar Pegawai</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-3 py-2">NO</th>
                            <th class="px-3 py-2">ID PEGAWAI</th>
                            <th class="px-3 py-2">NAMA</th>
                            <th class="px-3 py-2">TANGGAL</th>
                            <th class="px-3 py-2">JAM KELUAR</th>
                            <th class="px-3 py-2">STATUS</th>
                            <th class="px-3 py-2">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($presensis as $presensi)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $no++ }}</td>
                            <td class="px-3 py-2">{{ $presensi->id_pegawai }}</td>
                            <td class="px-3 py-2">{{ $presensi->nama }}</td>
                            <td class="px-3 py-2">{{ $presensi->tanggal }}</td>
                            <td class="px-3 py-2">
                                @if(empty($presensi->jam_keluar))
                                <form action="{{ url('/presensi/keluar/'.$presensi->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="time" name="jam_keluar" class="border px-2 py-1 rounded" required>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold">Simpan</button>
                                </form>
                                @else
                                    {{ $presensi->jam_keluar }}
                                @endif
                            </td>
                            <td class="px-3 py-2">{{ $presensi->status }}</td>
                            <td class="px-3 py-2">{{ $presensi->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html> 