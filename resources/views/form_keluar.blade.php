<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .btn-custom {
            min-width: 160px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📤 Form Barang Keluar</h3>
        <div>
            <a href="/" class="btn btn-outline-secondary me-2 btn-custom">← Kembali</a>
            <a href="/histori" class="btn btn-outline-primary btn-custom">📜 Lihat Histori</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-4 bg-white">
        <form method="POST" action="{{ route('transaksi.store') }}">
            @csrf
            <input type="hidden" name="jenis" value="out">

            <div class="mb-3">
                <label for="kode_qr" class="form-label">Kode QR / Barcode</label>
                <input type="text" name="kode_qr" id="kode_qr" class="form-control" required onblur="cekBarang()">
            </div>

            <div id="form-lanjutan" style="display: none;">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Keluar</label>
                    <input type="number" name="jumlah" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label for="oleh" class="form-label">Dikeluarkan Oleh</label>
                    <input type="text" name="oleh" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="divisi" class="form-label">Divisi</label>
                    <input type="text" name="divisi" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-danger btn-custom">💾 Simpan Barang Keluar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function cekBarang() {
        const kodeQR = document.getElementById('kode_qr').value;
        if (!kodeQR) return;

        fetch(`/barang/cek/${kodeQR}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('nama_barang').value = data.nama_barang;
                    document.getElementById('form-lanjutan').style.display = 'block';
                } else {
                    document.getElementById('nama_barang').value = 'Barang tidak ditemukan';
                    document.getElementById('form-lanjutan').style.display = 'none';
                    alert('⚠️ Barang tidak ditemukan. Pastikan kode benar.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memeriksa barang.');
                document.getElementById('form-lanjutan').style.display = 'none';
            });
    }
</script>
</body>
</html>
