<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\HistoriTransaksi;
use App\Exports\HistoriExport;
use App\Imports\HistoriImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistoriExportByDate;

class HistoriTransaksiController extends Controller
{
    public function formMasuk()
    {
        return view('form_masuk');
    }

    public function formKeluar()
    {
        return view('form_keluar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'oleh' => 'required|string',
            'divisi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'jenis' => 'required|in:in,out',
        ]);

        $barang = Barang::where('kode_qr', $request->kode_qr)->first();
        $isNew = false;

        if (!$barang) {
            if ($request->jenis === 'in') {
                $barang = Barang::create([
                    'kode_qr' => $request->kode_qr,
                    'nama_barang' => $request->nama_barang,
                    'stok' => $request->jumlah,
                    'divisi' => $request->divisi,
                ]);
                $isNew = true;
            } else {
                return back()->with('error', 'Barang tidak ditemukan saat keluar.');
            }
        } else {
            if ($request->jenis === 'in') {
                $barang->stok += $request->jumlah;
            } else {
                if ($barang->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak cukup.');
                }
                $barang->stok -= $request->jumlah;
            }
            $barang->save();
        }

        HistoriTransaksi::create([
            'barang_id' => $barang->id,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'oleh' => $request->oleh,
            'divisi' => $request->divisi,
            'keterangan' => $request->keterangan,
        ]);

        // Buat pesan sukses
        if ($request->jenis === 'in' && $isNew) {
            $pesan = "✅ Barang baru *{$barang->nama_barang}* ditambahkan sebanyak {$request->jumlah} unit.";
        } elseif ($request->jenis === 'in') {
            $pesan = "✅ Stok *{$barang->nama_barang}* ditambah sebanyak {$request->jumlah} unit.";
        } else {
            $pesan = "✅ Barang *{$barang->nama_barang}* dikeluarkan sebanyak {$request->jumlah} unit.";
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function histori()
    {
        $histori = HistoriTransaksi::with('barang')->orderByDesc('created_at')->get();
        return view('histori', compact('histori'));
    }

    public function dashboard()
    {
        $totalMasuk = HistoriTransaksi::where('jenis', 'in')->sum('jumlah');
        $totalKeluar = HistoriTransaksi::where('jenis', 'out')->sum('jumlah');
        $totalBarang = Barang::count();

        return view('dashboard', compact('totalMasuk', 'totalKeluar', 'totalBarang'));
    }

    public function export()
    {
        $tanggal = now()->format('d-m-Y');
        $fileName = 'histori_transaksi_' . $tanggal . '.xlsx';

        return Excel::download(new HistoriExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new HistoriImport, $request->file('file'));

        return redirect()->back()->with('success', '📥 Data histori berhasil diimport!');
    }

    public function exportByDate(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    return Excel::download(new HistoriExportByDate($request->start_date, $request->end_date), 'histori_' . $request->start_date . '_to_' . $request->end_date . '.xlsx');
}
}
