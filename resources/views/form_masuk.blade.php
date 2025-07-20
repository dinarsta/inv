<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .form-wrapper {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .btn-back {
            margin-bottom: 20px;
        }
        #form-lengkap {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="form-wrapper">
        <a href="/" class="btn btn-sm btn-outline-secondary btn-back">← Kembali</a>

        <h4 class="mb-4">📦 Form Barang Masuk</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('transaksi.store') }}">
            @csrf
            <input type="hidden" name="jenis" value="in">

            {{-- Input Scan QR --}}
            <div class="mb-3">
                <label for="kode_qr" class="form-label">Scan Kode QR / Barcode</label>
                <input type="text" name="kode_qr" id="kode_qr" class="form-control" required onblur="cekBarang()">
            </div>

            {{-- Form lainnya hanya muncul jika data ditemukan --}}
            <div id="form-lengkap">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Masuk</label>
                    <input type="number" name="jumlah" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label for="oleh" class="form-label">Di Input Oleh</label>
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

                <button type="submit" class="btn btn-success w-100">Simpan Barang Masuk</button>
            </div>
        </form>
    </div>
</div>

<script>
    function cekBarang() {
        const kodeQR = document.getElementById('kode_qr').value.trim();

        if (kodeQR === '') return;

        fetch(`/barang/cek/${kodeQR}`)
            .then(response => response.json())
            .then(data => {
                const formLengkap = document.getElementById('form-lengkap');
                const namaInput = document.getElementById('nama_barang');

                if (data.exists) {
                    namaInput.value = data.nama_barang;
                    namaInput.readOnly = true;
                    formLengkap.style.display = 'block';
                } else {
                    formLengkap.style.display = 'none';
                    namaInput.value = '';
                    alert('❗ Barang tidak ditemukan. Silakan daftar barang terlebih dahulu.');
                }
            })
            .catch(error => {
                console.error('Error saat cek barang:', error);
            });
    }
</script>
</body>
</html>
