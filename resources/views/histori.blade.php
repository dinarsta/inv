<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori Transaksi Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        header, footer {
            padding: 1rem 0;
            background-color: transparent;
            border-bottom: 1px solid #dee2e6;
        }
        footer {
            border-top: 1px solid #dee2e6;
            border-bottom: none;
        }
        .alert-custom {
            animation: fadeIn 1s ease-in-out;
            margin-top: 1rem;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <h3 class="mb-0">📑 Histori Transaksi Barang</h3>
            <div>
                <a href="/" class="btn btn-outline-primary me-2">🏠 Home</a>
                <a href="/in" class="btn btn-outline-success me-2">➕ Barang Masuk</a>
                <a href="/out" class="btn btn-outline-danger">➖ Barang Keluar</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mt-4 mb-5">
        <!-- Table -->
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>QR / Barcode</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Oleh</th>
                    <th>Divisi</th>
                    <th>Keterangan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalMasuk = 0;
                    $totalKeluar = 0;
                    $stokBarang = [];
                @endphp
                @forelse($histori as $index => $item)
                    @php
                        if ($item->jenis === 'in') {
                            $totalMasuk += $item->jumlah;
                        } else {
                            $totalKeluar += $item->jumlah;
                        }

                        $namaBarang = $item->barang->nama_barang;
                        if (!isset($stokBarang[$namaBarang])) {
                            $stokBarang[$namaBarang] = 0;
                        }
                        $stokBarang[$namaBarang] += $item->jenis === 'in' ? $item->jumlah : -$item->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->barang->kode_qr }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>
                            <span class="badge bg-{{ $item->jenis === 'in' ? 'success' : 'danger' }}">
                                {{ $item->jenis === 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->oleh }}</td>
                        <td>{{ $item->divisi ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada histori transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary Box -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">📊 Ringkasan Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-secondary">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Sisa Stok (unit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stokBarang as $nama => $stok)
                                <tr>
                                    <td>{{ $nama }}</td>
                                    <td><strong>{{ $stok }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada data stok barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alert Transaksi Terakhir -->
        @php
            $last = $histori->first();
        @endphp
        @if($last)
        <div class="alert alert-{{ $last->jenis === 'in' ? 'success' : 'danger' }} alert-custom" role="alert">
            📢 <strong>{{ strtoupper($last->oleh) }}</strong> baru saja
            <strong>{{ $last->jenis === 'in' ? 'MENAMBAHKAN' : 'MENGELUARKAN' }}</strong>
            <strong>{{ $last->jumlah }}</strong> unit
            <strong>{{ $last->barang->nama_barang }}</strong>!
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA</small>
        </div>
    </footer>
</body>
</html>
