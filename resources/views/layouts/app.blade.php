<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Inventaris')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Logo favicon -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom Style -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card-nav {
            cursor: pointer;
        }

        .footer {
            border-top: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .card-text {
            font-size: 0.95rem;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-dark fw-semibold" href="/">
                <i class="fas fa-box-open fs-4 text-primary me-2"></i>
                <span class="fs-5">Inventaris</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-center gap-lg-3 gap-2">
                    @auth
                        <li class="nav-item text-dark">
                            <span class="nav-link fw-semibold">
                                👤 {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role ?? 'user') }})
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link text-primary fw-semibold d-flex align-items-center">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container-fluid px-3 px-md-5 py-5">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer text-center text-muted py-4 mt-5">
        <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA. All rights reserved.</small>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
