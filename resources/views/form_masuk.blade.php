@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-sm my-5 px-3">
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h4 class="mb-2 mb-md-0">📥 Form Barang Masuk</h4>
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
            <a href="/" class="btn btn-outline-secondary btn-custom">← Kembali</a>
            <a href="/histori" class="btn btn-outline-primary btn-custom">📜 Lihat Histori</a>
        </div>
    </div>

    <div class="card p-4 bg-white form-wrapper position-relative">
        <form method="POST" action="{{ route('transaksi.store') }}">
            @csrf
            <input type="hidden" name="jenis" value="in">

            <div class="mb-3 position-relative">
                <label for="kode_qr" class="form-label">Scan Kode QR / Barcode</label>
                <input type="text" name="kode_qr" id="kode_qr" class="form-control" required autocomplete="off">
                <button type="button" id="scan-btn" class="btn btn-outline-secondary w-100 d-sm-none mt-2">📷 Scan
                    Barcode</button>
                <div id="qr-reader" style="display: none;" class="my-3"></div>

                <!-- Kotak Saran -->
                <div id="suggestions" class="suggestion-box d-none">
                    <!-- Diisi otomatis oleh JavaScript -->
                </div>
            </div>

            <div id="form-lengkap">
                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Masuk</label>
                    <input type="number" name="jumlah" class="form-control" required min="1">
                </div>

                <!-- Di-Input Oleh -->
                <div class="mb-3">
                    <label for="oleh" class="form-label">Di‑Input Oleh</label>
                    <input type="text" name="oleh" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                <!-- Divisi -->
                <div class="mb-3">
                    <label for="divisi" class="form-label">Divisi</label>
                    <input type="text" name="divisi" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="created_at" class="form-label">Tanggal & Waktu Input</label>
                    <input type="datetime-local" name="created_at" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" autocomplete="off"></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">💾 Simpan Barang Masuk</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal jika barang belum terdaftar -->
<div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body py-5 text-center">
                <div class="text-danger fs-1 mb-3">⚠️</div>
                <h5 class="modal-title fw-bold text-uppercase" id="barangModalLabel">Barang Belum Terdaftar</h5>
                <p class="mt-2 mb-4">Silakan isi data barang terlebih dahulu..</p>
                <button type="button" class="btn btn-danger px-4" id="modal-oke" data-bs-dismiss="modal">OKE</button>
            </div>
        </div>
    </div>
</div>

<footer class="footer text-center text-muted py-4 mt-5">
    <small>&copy; {{ date('Y') }} PRIMANUSA MUKTI UTAMA</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const kodeInput = document.getElementById('kode_qr');
    const suggestionsBox = document.getElementById('suggestions');
    let timeout = null;
    // Auto Suggest
    kodeInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(timeout);
        if (query.length < 2) {
            suggestionsBox.classList.add('d-none');
            return;
        }
        timeout = setTimeout(() => {
            fetch(`/barang/suggest?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsBox.innerHTML = '';
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'suggestion-item';
                            div.innerHTML =
                                `<strong>${item.kode_qr}</strong> – <span class="text-muted">${item.nama_barang}</span>`;
                            div.onclick = () => {
                                kodeInput.value = item.kode_qr;
                                suggestionsBox.classList.add('d-none');
                                cekBarang();
                            };
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.classList.remove('d-none');
                    } else {
                        suggestionsBox.classList.add('d-none');
                    }
                })
                .catch(err => {
                    console.error('Gagal ambil saran:', err);
                });
        }, 300);
    });
    // Sembunyikan saran saat klik di luar
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== kodeInput) {
            suggestionsBox.classList.add('d-none');
        }
    });
    // Cek Barang berdasarkan kode_qr
    function cekBarang() {
        const kodeQR = kodeInput.value.trim();
        if (kodeQR === '') return;
        fetch(`/barang/cek/${encodeURIComponent(kodeQR)}`)
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
                    const modal = new bootstrap.Modal(document.getElementById('barangModal'));
                    modal.show();
                    document.getElementById('modal-oke').onclick = () => {
                        formLengkap.style.display = 'block';
                    };
                }
            })
            .catch(err => {
                console.error('Gagal cek barang:', err);
            });
    }
    // QR Scanner
    const scanBtn = document.getElementById('scan-btn');
    const qrReader = document.getElementById('qr-reader');
    let html5QrCode;
    scanBtn.addEventListener('click', () => {
        if (!Html5Qrcode.getCameras) {
            alert("Browser tidak mendukung kamera.");
            return;
        }
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qr-reader");
        }
        qrReader.style.display = 'block';
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let backCamera = devices.find(device =>
                    device.label.toLowerCase().includes('back')
                ) || devices[devices.length - 1];
                const cameraId = backCamera.id;
                html5QrCode.start(
                    cameraId, {
                        fps: 10,
                        qrbox: 250
                    },
                    qrCodeMessage => {
                        kodeInput.value = qrCodeMessage;
                        html5QrCode.stop().then(() => {
                            qrReader.style.display = 'none';
                            cekBarang();
                        }).catch(err => console.error("Stop error", err));
                    },
                    error => {
                        /* silent */ }
                ).catch(err => console.error("Start error", err));
            }
        }).catch(err => console.error("Camera error", err));
    });
</script>

@endsection
