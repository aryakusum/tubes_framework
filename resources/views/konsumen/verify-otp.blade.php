<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-100 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Verifikasi OTP</h2>
        <p class="text-center text-gray-600 mb-4">Masukkan kode OTP yang dikirim ke email Anda</p>
        @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 border border-red-300 rounded px-4 py-2">
            {{ $errors->first() }}
        </div>
        @endif
        <form method="POST" action="{{ route('konsumen.verify-otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="mb-6">
                <label class="block text-gray-700 mb-1">Kode OTP</label>
                <input type="text" name="otp" maxlength="6" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition duration-200">
                Verifikasi
            </button>
        </form>
    </div>
</body>

</html>