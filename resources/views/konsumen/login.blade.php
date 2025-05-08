<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Konsumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-100 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Login Konsumen</h2>
        @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 border border-red-300 rounded px-4 py-2">
            {{ $errors->first() }}
        </div>
        @endif
        <form method="POST" action="{{ route('konsumen.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition duration-200">
                Login
            </button>
        </form>
        <div class="text-center mt-4">
            <a href="{{ route('konsumen.register') }}" class="text-blue-600 hover:underline">Belum punya akun? Daftar</a>
        </div>
    </div>
</body>

</html>