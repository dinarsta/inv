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

        foreach ($rows as $index => $row) {
            // Cek jumlah kolom minimal
            if (count($row) < 8) {
                continue;
            }

            $kodeQr     = (string) ($row[0] ?? null);
            $namaBarang = $row[1] ?? null;
            $jenisRaw   = strtolower(trim($row[2] ?? ''));
            $jumlah     = (int) ($row[3] ?? 0);
            $oleh       = $row[4] ?? null;
            $divisi     = $row[5] ?? null;
            $keterangan = $row[6] ?? null;
            $waktuRaw   = $row[7] ?? null;

            // Validasi jenis transaksi
            if (in_array($jenisRaw, ['masuk', 'in'])) {
                $jenis = 'in';
            } elseif (in_array($jenisRaw, ['keluar', 'out'])) {
                $jenis = 'out';
            } else {
                continue; // jenis tidak valid
            }

            // Skip jika data penting kosong
            if (!$kodeQr || !$namaBarang || !$oleh || !$jumlah) {
                continue;
            }

            // Ubah format tanggal dari Excel
            $waktu = null;
            if (is_numeric($waktuRaw)) {
                // Format Excel serial number
                $waktu = Carbon::instance(Date::excelToDateTimeObject($waktuRaw));
            } else {
                try {
                    $waktu = Carbon::parse($waktuRaw);
                } catch (\Exception $e) {
                    continue; // Format tanggal salah
                }
            }

            // Cari barang berdasarkan kode QR
            $barang = Barang::where('kode_qr', $kodeQr)->first();

            if (!$barang) {
                // Buat barang baru jika belum ada
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
                'user_id'     => Auth::id(), // Mengambil ID user login
                'jenis'       => $jenis,
                'jumlah'      => $jumlah,
                'oleh'        => $oleh,
                'divisi'      => $divisi,
                'keterangan'  => $keterangan,
                'created_at'  => $waktu,
                'updated_at'  => $waktu,
            ]);
        }
    }
}
