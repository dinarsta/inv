<?php

namespace App\Exports;

use App\Models\HistoriTransaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoriExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return HistoriTransaksi::with('barang')->get()->map(function ($item) {
            return [
                'QR / Barcode' => $item->barang->kode_qr,
                'Nama Barang'  => $item->barang->nama_barang,
                'Jenis'        => $item->jenis === 'in' ? 'Masuk' : 'Keluar',
                'Jumlah'       => $item->jumlah,
                'Oleh'         => $item->oleh,
                'Divisi'       => $item->divisi,
                'Keterangan'   => $item->keterangan,
                'Waktu'        => $item->created_at->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'QR / Barcode',
            'Nama Barang',
            'Jenis',
            'Jumlah',
            'Oleh',
            'Divisi',
            'Keterangan',
            'Waktu',
        ];
    }
}
