<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\HistoriTransaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HistoriImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati baris pertama (header)
        $rows->shift();

        foreach ($rows as $row) {
            // Pastikan ada minimal kolom yang dibutuhkan
            if (count($row) < 8) {
                continue;
            }

            $kodeQr     = trim((string)($row[0] ?? ''));
            $namaBarang = trim((string)($row[1] ?? ''));
            $jenisRaw   = strtolower(trim((string)($row[2] ?? '')));
            $jumlah     = is_numeric($row[3]) ? (int)$row[3] : 0;
            $oleh       = trim((string)($row[4] ?? ''));
            $divisi     = trim((string)($row[5] ?? ''));
            $keterangan = trim((string)($row[6] ?? ''));
            $waktuRaw   = $row[7] ?? null;

            // Validasi jenis transaksi dengan fleksibilitas
            if (in_array($jenisRaw, ['masuk', 'in'])) {
                $jenis = 'in';
            } elseif (in_array($jenisRaw, ['keluar', 'out'])) {
                $jenis = 'out';
            } else {
                continue; // jenis tidak dikenali
            }

            // Jika QR atau nama barang kosong → skip
            if (!$kodeQr || !$namaBarang) {
                continue;
            }

            // Ubah format tanggal lebih fleksibel
            if (is_numeric($waktuRaw)) {
                $waktu = Carbon::instance(Date::excelToDateTimeObject($waktuRaw));
            } else {
                try {
                    $waktu = Carbon::parse($waktuRaw);
                } catch (\Exception $e) {
                    $waktu = now(); // fallback jika format tanggal tidak valid
                }
            }

            // Cari barang berdasarkan kode QR
            $barang = Barang::where('kode_qr', $kodeQr)->first();

            if (!$barang) {
                // Buat barang baru
                $barang = Barang::create([
                    'kode_qr'     => $kodeQr,
                    'nama_barang' => $namaBarang,
                    'stok'        => $jenis === 'in' ? $jumlah : 0,
                    'divisi'      => $divisi,
                ]);
            } else {
                // Update stok
                if ($jenis === 'in') {
                    $barang->stok += $jumlah;
                } else {
                    $barang->stok = max(0, $barang->stok - $jumlah);
                }
                $barang->save();
            }

            // Simpan histori transaksi
            HistoriTransaksi::create([
                'barang_id'   => $barang->id,
                'user_id'     => Auth::id(),
                'jenis'       => $jenis,
                'jumlah'      => $jumlah,
                'oleh'        => $oleh ?: '-',
                'divisi'      => $divisi,
                'keterangan'  => $keterangan,
                'created_at'  => $waktu,
                'updated_at'  => $waktu,
            ]);
        }
    }
}
