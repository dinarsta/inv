<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="/">📦 Inventaris</a>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid px-3 px-md-5 py-4">
    <h2 class="mb-4 fw-semibold text-center fs-4">📊 Dashboard Inventaris</h2>

    <div class="row g-3 text-center">
        <div class="col-12 col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Total Barang Masuk</h6>
                    <p class="card-text fs-3">{{ $totalMasuk }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Total Barang Keluar</h6>
                    <p class="card-text fs-3">{{ $totalKeluar }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Jumlah Nama Barang</h6>
                    <p class="card-text fs-3">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-semibold text-center my-4 fs-5">🔍 Navigasi Inventaris</h4>

    <div class="row g-3 text-center">
        <div class="col-12 col-md-4">
            <a href="/in" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-success fw-semibold">+ Barang Masuk</h6>
                        <p class="card-text text-break">Tambah data barang yang masuk ke gudang</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="/out" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-danger fw-semibold">- Barang Keluar</h6>
                        <p class="card-text text-break">Catat barang yang keluar dari gudang</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="/histori" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-secondary fw-semibold">📋 Histori Transaksi</h6>
                        <p class="card-text text-break">Lihat riwayat barang masuk dan keluar</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer text-center text-muted py-4 mt-5">
    <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
