<?php

namespace App\Imports;

use App\Models\HistoriTransaksi;
use App\Models\Barang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class HistoriImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati header
        $rows->shift();

        foreach ($rows as $row) {
            // Temukan barang berdasarkan QR code
            $barang = Barang::firstOrCreate(
                ['kode_qr' => $row[0]],
                ['nama_barang' => $row[1], 'stok' => 0]
            );

            // Update stok berdasarkan jenis
            if ($row[2] === 'Masuk') {
                $barang->stok += $row[3];
            } elseif ($row[2] === 'Keluar') {
                $barang->stok -= $row[3];
            }
            $barang->save();

            // Simpan histori transaksi
            HistoriTransaksi::create([
                'barang_id' => $barang->id,
                'jenis' => strtolower($row[2]) === 'masuk' ? 'in' : 'out',
                'jumlah' => $row[3],
                'oleh' => $row[4],
                'divisi' => $row[5] ?? null,
                'keterangan' => $row[6] ?? null,
                'created_at' => now(), // atau bisa parsing dari kolom
            ]);
        }
    }
}
