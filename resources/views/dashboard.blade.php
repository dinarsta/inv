<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Inventaris</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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

<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center text-dark fw-semibold" href="/">
            <i class="fas fa-box-open fs-4 text-primary me-2"></i>
            <span class="fs-5">Inventaris</span>
        </a>

        <!-- Burger Menu -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center gap-lg-3 gap-2">
                @if(Auth::check())
                    @if(is_null(Auth::user()->role))
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link text-primary fw-semibold d-flex align-items-center">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link text-primary fw-semibold d-flex align-items-center">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="container-fluid px-3 px-md-5 py-5">
        <h2 class="mb-4 fw-semibold text-center fs-4">📊 Dashboard Inventaris</h2>

        <div class="row g-4 text-center">
            <div class="col-12 col-md-4">
                <div class="card text-white bg-success shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Barang Masuk</h6>
                        <p class="card-text fs-3">{{ $totalMasuk }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card text-white bg-danger shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Barang Keluar</h6>
                        <p class="card-text fs-3">{{ $totalKeluar }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card text-white bg-primary shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title">Jumlah Nama Barang</h6>
                        <p class="card-text fs-3">{{ $totalBarang }}</p>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-semibold text-center my-5 fs-5">🔍 Navigasi Inventaris</h4>
        <div class="row g-4 text-center">
            @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="col-12 col-md-4">
                <a href="/in" class="text-decoration-none">
                    <div class="card card-nav shadow-sm bg-white h-100">
                        <div class="card-body">
                            <h6 class="card-title text-success fw-semibold">➕ Barang Masuk</h6>
                            <p class="card-text text-muted">Tambah data barang yang masuk ke gudang</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="/out" class="text-decoration-none">
                    <div class="card card-nav shadow-sm bg-white h-100">
                        <div class="card-body">
                            <h6 class="card-title text-danger fw-semibold">➖ Barang Keluar</h6>
                            <p class="card-text text-muted">Catat barang yang keluar dari gudang</p>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            <div class="col-12 col-md-4">
                <a href="/histori" class="text-decoration-none">
                    <div class="card card-nav shadow-sm bg-white h-100">
                        <div class="card-body">
                            <h6 class="card-title text-secondary fw-semibold">📋 Histori Transaksi</h6>
                            <p class="card-text text-muted">Lihat riwayat barang masuk dan keluar</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center text-muted py-4 mt-5">
        <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
