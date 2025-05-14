<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Pegawai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-2">Login Pegawai</h2>
            <p class="text-gray-500">Silakan login untuk melanjutkan</p>
        </div>

        @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 border border-red-300 rounded px-4 py-2">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('pegawai.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>
            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>

</html>