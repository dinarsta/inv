<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | PRIMANUSA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Font Poppins (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg transition duration-300">

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-14">
        </div>

        <!-- Header -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Akun</h2>
            <p class="text-sm text-gray-500">Buat akun baru untuk melanjutkan</p>
        </div>

        <!-- Error message -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-300 p-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Register form -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                       required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                       required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                       required>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                       required>
            </div>

            <div class="pt-3">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition duration-200 shadow-sm">
                    <i class="fas fa-user-plus mr-2"></i> Daftar
                </button>
            </div>

            <p class="text-center mt-4 text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Login di sini</a>
            </p>
        </form>
    </div>
</body>
</html>
