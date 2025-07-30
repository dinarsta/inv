<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Monitoring Histori Transaksi | PRIMANUSA</title>
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

    @media (max-width: 576px) {
      h3 {
        font-size: 1.25rem;
      }

      .btn {
        margin-bottom: 8px;
        width: 100%;
      }

      .header-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
      }

      table th, table td {
        font-size: 0.875rem;
      }
    }
  </style>
</head>
<body class="bg-light">

<!-- Header -->
<header class="bg-white shadow-sm py-3">
  <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Title Section -->
    <h3 class="mb-0 fw-bold d-flex align-items-center gap-2">
      <span>📑</span> Monitoring Histori Transaksi
    </h3>

    <!-- Navigation and Form Section -->
    <div class="d-flex flex-wrap align-items-center gap-3">
      <!-- Navigation Buttons -->
      <div class="d-flex flex-wrap gap-2">
        <a href="/" class="btn btn-outline-primary d-flex align-items-center gap-2">
          <span>🏠</span> Home
        </a>
        <a href="/in" class="btn btn-outline-success d-flex align-items-center gap-2">
          <span>➕</span> Barang Masuk
        </a>
        <a href="/out" class="btn btn-outline-danger d-flex align-items-center gap-2">
          <span>➖</span> Barang Keluar
        </a>
      </div>

      <!-- Export by Date Form -->
      <div class="bg-white border rounded p-3 shadow-sm">
        <form action="{{ route('histori.exportByDate') }}" method="GET" class="row g-3 align-items-end">
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-medium">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="end_date" class="form-label fw-medium">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-outline-success d-flex align-items-center gap-2">
              <span>📤</span> Export Sesuai Tanggal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</header>
<!-- Main Content -->
<div class="container mt-4 mb-5">

  <!-- Import Excel -->
  <div class="bg-white border rounded p-3 shadow-sm mb-4">
    <form action="{{ route('histori.import') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-center">
      @csrf
      <div class="col-md-6 col-sm-12">
        <input type="file" name="file" class="form-control" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-warning">
          📤 Import Excel
        </button>
      </div>
    </form>
  </div>

  <!-- Tabel Histori Transaksi -->
  <div class="table-responsive bg-white p-3 border rounded shadow-sm">
    <h5 class="mb-3">📋 Data Transaksi Barang</h5>
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>NO</th>
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
  </div>

  <!-- Ringkasan -->
  <div class="card mt-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">📊 Ringkasan Stok per Barang</h5>
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
  @php $last = $histori->first(); @endphp
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
<footer class="mt-5">
  <div class="container text-center">
    <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA</small>
  </div>
</footer>

</body>
</html>
