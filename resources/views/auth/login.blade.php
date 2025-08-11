<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts (Optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f2f4f7;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            width: 100%;
            max-width: 420px;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #0d6efd;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
        }

        .logo {
            display: block;
            margin: 0 auto 1rem;
            width: 80px;
        }

        .login-title {
            font-weight: 600;
            text-align: center;
            color: #333;
        }

        .form-footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #0d6efd;
            font-weight: 500;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card">
        <!-- Logo -->
        <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">

        <h4 class="login-title mb-4"><i class="fas fa-sign-in-alt me-2 text-primary"></i>Masuk ke Akun Anda</h4>

        {{-- ✅ Tampilkan alert jika sudah ada 2 user login --}}
        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tampilkan error validasi lainnya --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</div>


<!-- Toggle Password Script -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const isPassword = passwordInput.getAttribute('type') === 'password';

        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
