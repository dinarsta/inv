<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Histori Transaksi | PRIMANUSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        header,
        footer {
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
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
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

            table th,
            table td {
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Header -->
    <!-- Header -->
    <header class="bg-white shadow-sm py-3">
        <div class="container">
            <!-- Judul dan Navigasi -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">
                <h3 class="mb-0 fw-bold text-center text-md-start">
                    📑 Monitoring Histori Transaksi
                </h3>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <!-- Tombol Home -->
                    <a href="/" class="btn btn-outline-primary">
                        <i class="bi bi-house-door"></i> Home
                    </a>

                    <!-- Jika user login -->
                    @auth
                    <!-- Tombol khusus admin -->
                    @if(auth()->user()->role === 'admin')
                    <a href="/in" class="btn btn-outline-success">
                        <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
                    </a>
                    <a href="/out" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-up"></i> Barang Keluar
                    </a>
                    @endif

                    <!-- Tombol Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                    @endauth
                </div>

            </div>

            <!-- Export & Import hanya untuk admin -->
            @auth
            @if(auth()->user()->role === 'admin')
            <div class="row g-4">
                <!-- Export By Date -->
                <div class="col-12 col-lg-7">
                    <div class="bg-white border rounded p-3 shadow-sm h-100">
                        <form action="{{ route('histori.exportByDate') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-medium">
                                    <i class="fa-regular fa-calendar-days"></i> Dari Tanggal
                                </label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-medium">
                                    <i class="fa-regular fa-calendar-days"></i> Sampai Tanggal
                                </label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fa-solid fa-file-export"></i> Export Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Import Excel -->
                <div class="col-12 col-lg-5">
                    <div class="bg-white border rounded p-3 shadow-sm h-100">
                        <form action="{{ route('histori.import') }}" method="POST" enctype="multipart/form-data"
                            class="row g-3 align-items-center">
                            @csrf
                            <div class="col-12">
                                <label for="file" class="form-label fw-medium">📁 Pilih File Excel</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning w-100">📥 Import Excel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endauth
        </div>
    </header>

    <div class="container mt-4 mb-5">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="dataTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="histori-tab" data-bs-toggle="tab" data-bs-target="#histori"
                    type="button" role="tab">Histori Transaksi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stok-tab" data-bs-toggle="tab" data-bs-target="#stok" type="button"
                    role="tab">Ringkasan Stok</button>
            </li>
        </ul>

        <div class="tab-content" id="dataTabContent">
            <!-- Tab Histori -->
            <div class="tab-pane fade show active" id="histori" role="tabpanel">
                <div class="table-responsive bg-white p-3 border rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">📋 Data Transaksi Barang</h5>
                        <input type="text" id="searchInput" class="form-control w-auto" placeholder="🔍 Cari...">
                    </div>
                    <table class="table table-bordered table-striped table-hover" id="historiTable">
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
                                @if(Auth::check() && Auth::user()->role === 'admin')
                                <th colspan="2">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody id="historiBody">
                            @forelse($histori as $index => $item)
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
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>

                                @if(Auth::check() && Auth::user()->role === 'admin')
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                                @endif

                                {{-- modals --}}
                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('histori.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit
                                                        Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Nama Barang --}}
                                                    <div class="mb-3">
                                                        <label>Nama Barang</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $item->barang->nama_barang ?? '-' }}" readonly>
                                                    </div>

                                                    {{-- QR Code --}}
                                                    <div class="mb-3">
                                                        <label>Kode QR</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $item->barang->kode_qr ?? '-' }}" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Jumlah</label>
                                                        <input type="number" name="jumlah" value="{{ $item->jumlah }}"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Jenis</label>
                                                        <select name="jenis" class="form-control" required>
                                                            <option value="in"
                                                                {{ $item->jenis == 'in' ? 'selected' : '' }}>
                                                                Masuk</option>
                                                            <option value="out"
                                                                {{ $item->jenis == 'out' ? 'selected' : '' }}>Keluar
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Oleh</label>
                                                        <input type="text" name="oleh" value="{{ $item->oleh }}"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Divisi</label>
                                                        <input type="text" name="divisi" value="{{ $item->divisi }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Keterangan</label>
                                                        <textarea name="keterangan"
                                                            class="form-control">{{ $item->keterangan }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Waktu</label>
                                                        <input type="datetime-local" name="waktu"
                                                            value="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d\TH:i') }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Hapus -->
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('histori.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                                                        Konfirmasi
                                                        Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah kamu yakin ingin menghapus transaksi
                                                    <strong>{{ $item->barang->nama_barang }}</strong>
                                                    ({{ $item->jumlah }}
                                                    unit)?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada histori transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                    <div id="pagination" class="d-flex justify-content-center mt-3"></div>
                </div>
            </div>
            {{-- end modals --}}
            <!-- Tab Stok -->
            <div class="tab-pane fade" id="stok" role="tabpanel">
                <div class="table-responsive bg-white p-3 border rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">📊 Ringkasan Stok per Barang</h5>
                        <input type="text" id="searchStokInput" class="form-control w-auto"
                            placeholder="🔍 Cari Nama Barang...">
                    </div>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="mb-4">
                        <button id="exportStokExcel" class="btn btn-success">
                            📁 Export Excel
                        </button>
                    </div>
                    @endif
                    @endauth

                    <table class="table table-bordered table-striped table-hover" id="stokTable">
                        <thead class="table-secondary">
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Sisa Stok (unit)</th>
                            </tr>
                        </thead>
                        <tbody id="stokBody">
                            @foreach($stokBarang as $nama => $stok)
                            @php
                            // Cari kode QR dari barang berdasarkan nama
                            $barang = $histori->firstWhere('barang.nama_barang', $nama)?->barang;
                            @endphp

                            @if($barang)
                            <tr class="{{ $stok < 3 ? 'table-danger' : '' }}">
                                <td>{{ $barang->kode_qr }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $stok }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Buttons -->
                    <div class="d-flex justify-content-center mt-3">
                        <nav>
                            <ul class="pagination" id="paginationStok"></ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Alert -->
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

        <!-- JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Histori Table Pagination + Search
                const rowsPerPage = 10;
                const tbody = document.getElementById('historiBody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const pagination = document.getElementById('pagination');
                const searchInput = document.getElementById('searchInput');
                let currentPage = 1;

                function renderTable(filtered = rows) {
                    const start = (currentPage - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    tbody.innerHTML = '';
                    filtered.slice(start, end).forEach(row => tbody.appendChild(row));
                    renderPagination(filtered);
                }

                function renderPagination(filtered) {
                    const totalPages = Math.ceil(filtered.length / rowsPerPage);
                    pagination.innerHTML = '';
                    for (let i = 1; i <= totalPages; i++) {
                        const btn = document.createElement('button');
                        btn.className = 'btn btn-sm btn-outline-primary mx-1';
                        btn.textContent = i;
                        if (i === currentPage) btn.classList.add('active');
                        btn.onclick = () => {
                            currentPage = i;
                            renderTable(filtered);
                        };
                        pagination.appendChild(btn);
                    }
                }
                searchInput.addEventListener('input', () => {
                    const keyword = searchInput.value.toLowerCase();
                    const filtered = rows.filter(row => row.textContent.toLowerCase().includes(
                        keyword));
                    currentPage = 1;
                    renderTable(filtered);
                });
                renderTable();
                // Ringkasan Stok Search
                const stokInput = document.getElementById('searchStokInput');
                const stokBody = document.getElementById('stokBody');
                const stokRows = Array.from(stokBody.querySelectorAll('tr'));
                stokInput.addEventListener('input', () => {
                    const keyword = stokInput.value.toLowerCase();
                    stokRows.forEach(row => {
                        row.style.display = row.textContent.toLowerCase().includes(keyword) ?
                            '' : 'none';
                    });
                });
            });
            // pagination
            document.addEventListener("DOMContentLoaded", function() {
                const rowsPerPage = 10; // GANTI DI SINI
                const table = document.getElementById("stokTable");
                const tbody = table.querySelector("tbody");
                const rows = tbody.querySelectorAll("tr");
                const totalPages = Math.ceil(rows.length / rowsPerPage);
                const pagination = document.getElementById("paginationStok");

                function showPage(page) {
                    let start = (page - 1) * rowsPerPage;
                    let end = start + rowsPerPage;
                    rows.forEach((row, index) => {
                        row.style.display = (index >= start && index < end) ? "" : "none";
                    });
                }

                function createPagination() {
                    pagination.innerHTML = "";
                    for (let i = 1; i <= totalPages; i++) {
                        let li = document.createElement("li");
                        li.classList.add("page-item");
                        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        li.addEventListener("click", function(e) {
                            e.preventDefault();
                            document.querySelectorAll("#paginationStok .page-item").forEach(item => item
                                .classList.remove("active"));
                            li.classList.add("active");
                            showPage(i);
                        });
                        pagination.appendChild(li);
                    }
                    // Aktifkan halaman pertama secara default
                    if (pagination.firstChild) {
                        pagination.firstChild.classList.add("active");
                    }
                }
                if (rows.length > 0) {
                    createPagination();
                    showPage(1);
                }
            });
            // export stok
            document.getElementById('exportStokExcel').addEventListener('click', function() {
                // Sembunyikan pagination dan tampilkan semua baris sebelum export
                const pagination = document.getElementById('paginationStok');
                const rows = document.querySelectorAll('#stokBody tr');
                // Simpan tampilan awal
                const displayStyles = [];
                rows.forEach((row, index) => {
                    displayStyles[index] = row.style.display;
                    row.style.display = ''; // Tampilkan semua
                });
                // Export
                let table = document.getElementById('stokTable');
                let wb = XLSX.utils.table_to_book(table, {
                    sheet: "Stok Barang"
                });
                XLSX.writeFile(wb, 'stok_barang.xlsx');
                // Kembalikan tampilan awal
                rows.forEach((row, index) => {
                    row.style.display = displayStyles[index];
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
