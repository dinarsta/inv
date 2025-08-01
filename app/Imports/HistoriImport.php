<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\HistoriTransaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class HistoriImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati baris pertama (header)
        $rows->shift();

        foreach ($rows as $row) {
            $kodeQr     = (string) $row[0];
            $namaBarang = $row[1];
            $jenis      = strtolower($row[2]) === 'masuk' ? 'in' : 'out';
            $jumlah     = (int) $row[3];
            $oleh       = $row[4];
            $divisi     = $row[5];
            $keterangan = $row[6];

       // Cek dan ubah format tanggal dari Excel
$waktu = null;

if (is_numeric($row[7])) {
    // Format Excel (serial number)
    $waktu = Carbon::instance(Date::excelToDateTimeObject($row[7]));
} else {
    // Format teks (ex: 2025-08-01 14:30:00)
    try {
        $waktu = Carbon::parse($row[7]);
    } catch (\Exception $e) {
        // Lewati baris jika format tidak valid
        continue;
    }
}

            // Cek apakah barang sudah ada
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
                // Update stok barang
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
