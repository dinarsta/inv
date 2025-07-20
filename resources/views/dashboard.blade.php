<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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

{{-- Header --}}
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="/">📦 Inventaris</a>
    </div>
</nav>

{{-- Main Content --}}
<div class="container py-5">
    <h2 class="mb-5 fw-semibold text-center">📊 Dashboard Inventaris</h2>

    <div class="row text-center">
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Barang Masuk</h5>
                    <p class="card-text fs-3">{{ $totalMasuk }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Barang Keluar</h5>
                    <p class="card-text fs-3">{{ $totalKeluar }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Nama Barang</h5>
                    <p class="card-text fs-3">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-semibold text-center my-5">🔍 Navigasi Inventaris</h4>

    <div class="row text-center">
        <div class="col-md-4">
            <a href="/in" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-success fw-semibold">+ Barang Masuk</h5>
                        <p class="card-text">Tambah data barang yang masuk ke gudang</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="/out" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-danger fw-semibold">- Barang Keluar</h5>
                        <p class="card-text">Catat barang yang keluar dari gudang</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="/histori" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-secondary fw-semibold">📋 Histori Transaksi</h5>
                        <p class="card-text">Lihat riwayat barang masuk dan keluar</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="footer text-center text-muted py-4 mt-5">
    <small>&copy; {{ date('Y') }} Inventaris Primanusa</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
