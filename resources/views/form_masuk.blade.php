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

            {{-- Form lengkap --}}
            <div id="form-lengkap">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control">
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


<!-- Modal Barang Tidak Terdaftar -->
<div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-body py-5">
        <div class="text-danger fs-1 mb-3">❗</div>
        <h5 class="modal-title fw-bold text-uppercase" id="barangModalLabel">Barang Belum Terdaftar</h5>
        <p class="mt-2 mb-4">Silakan isi data barang terlebih dahulu..</p>
        <button type="button" class="btn btn-danger px-4" id="modal-oke" data-bs-dismiss="modal">OKE</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function cekBarang() {
        const kodeQR = document.getElementById('kode_qr').value.trim();

        if (kodeQR === '') return;

        fetch(`/barang/cek/${kodeQR}`)
            .then(response => response.json())
            .then(data => {
                const namaInput = document.getElementById('nama_barang');
                const formLengkap = document.getElementById('form-lengkap');

                if (data.exists) {
                    namaInput.value = data.nama_barang;
                    namaInput.readOnly = true;
                    formLengkap.style.display = 'block';
                } else {
                    namaInput.value = '';
                    namaInput.readOnly = false;

                    // Tampilkan modal, form hanya muncul setelah klik OK
                    const modal = new bootstrap.Modal(document.getElementById('barangModal'));
                    modal.show();

                    // Tampilkan form setelah klik OKE
                    document.getElementById('modal-oke').onclick = function () {
                        formLengkap.style.display = 'block';
                    };
                }
            })
            .catch(error => {
                console.error('Gagal cek barang:', error);
            });
    }
</script>

</body>
</html>
