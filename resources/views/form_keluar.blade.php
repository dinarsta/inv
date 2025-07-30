<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Form Barang Keluar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .btn-custom {
      min-width: 150px;
    }
    .suggestion-box {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 999;
      background: white;
      width: 100%;
      border: 1px solid #ced4da;
      border-top: none;
      max-height: 200px;
      overflow-y: auto;
    }
    .suggestion-item {
      padding: 10px;
      cursor: pointer;
    }
    .suggestion-item:hover {
      background-color: #f1f1f1;
    }
    @media (max-width: 576px) {
      .btn-custom {
        width: 100%;
      }
      .form-wrapper {
        padding: 1rem;
      }
    }
  </style>
</head>
<body class="bg-light">
  <div class="container-sm my-5 px-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
      <h4 class="mb-2 mb-md-0">📤 Form Barang Keluar</h4>
      <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
        <a href="/" class="btn btn-outline-secondary btn-custom">← Kembali</a>
        <a href="/histori" class="btn btn-outline-primary btn-custom">📜 Lihat Histori</a>
      </div>
    </div>

    <div class="card p-4 bg-white form-wrapper">
      <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <input type="hidden" name="jenis" value="out" />

        <div class="mb-3 position-relative">
          <label for="kode_qr" class="form-label">Kode QR / Barcode</label>
          <input type="text" name="kode_qr" id="kode_qr" class="form-control" required autocomplete="off" />
          <div id="suggestions" class="suggestion-box d-none"></div>
        </div>

        <div id="form-lanjutan" style="display: none;">
          <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly />
          </div>

          <div class="mb-3">
            <label for="stok_barang" class="form-label">Stok Barang</label>
            <input type="text" name="stok_barang" id="stok_barang" class="form-control" readonly />
            <small id="peringatan-stok" class="text-danger fw-bold" style="display: none;">⚠️ Barang hampir habis!</small>
          </div>

          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Keluar</label>
            <input type="number" name="jumlah" class="form-control" required min="1" />
          </div>

          <div class="mb-3">
            <label for="oleh" class="form-label">Dikeluarkan Oleh</label>
            <input type="text" name="oleh" class="form-control" required autocomplete="off" />
          </div>

          <div class="mb-3">
            <label for="divisi" class="form-label">Divisi</label>
            <input type="text" name="divisi" class="form-control" autocomplete="off" />
          </div>

          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" autocomplete="off"></textarea>
          </div>

          <button type="submit" class="btn btn-danger btn-custom w-100">💾 Simpan Barang Keluar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal untuk peringatan -->
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header bg-danger bg-gradient text-white rounded-top-4">
          <h5 class="modal-title d-flex align-items-center gap-2" id="alertModalLabel">
            <i class="bi bi-exclamation-triangle-fill"></i> Peringatan
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body fs-6 text-dark" id="alertModalBody"></div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer text-center text-muted py-4 mt-5">
    <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const kodeInput = document.getElementById('kode_qr');
    const suggestionsBox = document.getElementById('suggestions');

    function showModal(message) {
      document.getElementById('alertModalBody').textContent = message;
      const modal = new bootstrap.Modal(document.getElementById('alertModal'));
      modal.show();
    }

    function cekBarang() {
      const kodeQR = kodeInput.value.trim();
      if (!kodeQR) return;

      fetch(`/barang/cek/${kodeQR}`)
        .then(res => res.json())
        .then(data => {
          if (data.exists) {
            document.getElementById('nama_barang').value = data.nama_barang;
            document.getElementById('stok_barang').value = data.stok;
            document.getElementById('peringatan-stok').style.display = parseInt(data.stok) <= 3 ? 'block' : 'none';
            document.getElementById('form-lanjutan').style.display = 'block';
          } else {
            document.getElementById('form-lanjutan').style.display = 'none';
            showModal('Barang tidak ditemukan. Pastikan kode benar.');
          }
        })
        .catch(() => {
          document.getElementById('form-lanjutan').style.display = 'none';
          showModal('Terjadi kesalahan saat memeriksa barang.');
        });
    }

    // Suggestion logic
    let debounce;
    kodeInput.addEventListener('input', () => {
      const query = kodeInput.value.trim();
      clearTimeout(debounce);
      if (query.length < 2) {
        suggestionsBox.classList.add('d-none');
        return;
      }

      debounce = setTimeout(() => {
        fetch(`/barang/suggest?q=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(data => {
            suggestionsBox.innerHTML = '';
            if (data.length === 0) {
              suggestionsBox.innerHTML = '<div class="suggestion-item text-muted">Tidak ditemukan</div>';
            } else {
              data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.textContent = item.kode_qr;
                div.onclick = () => {
                  kodeInput.value = item.kode_qr;
                  suggestionsBox.classList.add('d-none');
                  cekBarang();
                };
                suggestionsBox.appendChild(div);
              });
            }
            suggestionsBox.classList.remove('d-none');
          });
      }, 300);
    });

    document.addEventListener('click', e => {
      if (!suggestionsBox.contains(e.target) && e.target !== kodeInput) {
        suggestionsBox.classList.add('d-none');
      }
    });

    kodeInput.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        suggestionsBox.classList.add('d-none');
        cekBarang();
      }
    });
  </script>
</body>
</html>
