<?php
namespace App\Exports;

use App\Models\HistoriTransaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class HistoriExportByDate implements FromCollection, WithHeadings, WithMapping
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = Carbon::parse($start)->format('Y-m-d');
        $this->end = Carbon::parse($end)->format('Y-m-d');
    }

    public function collection()
    {
        return HistoriTransaksi::with('barang')
            ->whereBetween('created_at', [$this->start . ' 00:00:00', $this->end . ' 23:59:59'])
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->barang->kode_qr ?? '',
            $row->barang->nama_barang ?? '',
            $row->jenis,
            $row->jumlah,
            $row->oleh,
            $row->divisi,
            $row->keterangan,
            $row->created_at->format('d-m-Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Kode QR',
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
