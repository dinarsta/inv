<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\HistoriTransaksi;
use App\Exports\HistoriExport;
use App\Imports\HistoriImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistoriExportByDate;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;

class HistoriTransaksiController extends Controller
{
public function formMasuk()
{
    $user = Auth::user(); // Ambil data user yang sedang login
    return view('form_masuk', compact('user'));
}

public function formKeluar()
{
    $user = Auth::user(); // Ambil data user yang sedang login
    return view('form_keluar', compact('user'));
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
        $histori = HistoriTransaksi::create([
    'barang_id' => $barang->id,
    'user_id' => Auth::id(), // ✅ Wajib agar tidak error
    'jenis' => $request->jenis,
    'jumlah' => $request->jumlah,
    'oleh' => Auth::user()->name, // Otomatis dari user login
    'divisi' => $request->divisi,
    'created_at' => $request->created_at,
    'keterangan' => $request->keterangan,
]);

// ✅ Tambahkan log aktivitas ke activity_logs
ActivityLog::create([
    'user_id' => Auth::id(),
    'login_at' => now(), // Gunakan waktu sekarang
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
    'keterangan' => 'Transaksi ' . $request->jenis,
    'aktivitas_detail' => Auth::user()->name . ' dari divisi ' . $request->divisi .
        ' melakukan transaksi ' . ($request->jenis === 'in' ? 'masuk' : 'keluar') .
        ' pada tanggal ' . now()->format('Y-m-d H:i'),
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

    // Ambil stok dari tabel barangs (dari kolom 'stok')
    $stokBarang = Barang::select('nama_barang', 'stok')
        ->get()
        ->pluck('stok', 'nama_barang'); // hasil: [nama_barang => stok]

    // Ambil data terakhir dari activity_logs
    $lastActivity = \App\Models\ActivityLog::orderByDesc('created_at')->first();

    return view('histori', compact('histori', 'stokBarang', 'lastActivity'));
}

   public function dashboard()
{
    $totalMasuk = HistoriTransaksi::where('jenis', 'in')->sum('jumlah');
    $totalKeluar = HistoriTransaksi::where('jenis', 'out')->sum('jumlah');
    $totalBarang = Barang::count();

    $user = Auth::user(); // ambil data user yang sedang login

    return view('dashboard', compact('totalMasuk', 'totalKeluar', 'totalBarang', 'user'));
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

public function update(Request $request, $id)
{
    $histori = HistoriTransaksi::findOrFail($id);

    // Simpan data lama sebelum diubah (optional tapi disarankan)
    $original = $histori->getOriginal();

    // Update histori transaksi
    $histori->jumlah = $request->jumlah;
    $histori->jenis = $request->jenis;
    $histori->oleh = $request->oleh;
    $histori->divisi = $request->divisi;
    $histori->keterangan = $request->keterangan;
    $histori->created_at = $request->waktu;
    $histori->save();

    // ✅ Tambahkan log aktivitas perubahan
    ActivityLog::create([
        'user_id' => Auth::id(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'keterangan' => 'Edit Transaksi',
        'aktivitas_detail' => Auth::user()->name .
            ' mengedit transaksi ID ' . $id .
            ' dari: [jenis: ' . $original['jenis'] . ', jumlah: ' . $original['jumlah'] . '] ' .
            'ke: [jenis: ' . $request->jenis . ', jumlah: ' . $request->jumlah . '] ' .
            'pada ' . now()->format('Y-m-d H:i'),
        'login_at' => now(), // Sebagai timestamp umum untuk aktivitas
    ]);

    return redirect()->back()->with('success', 'Data berhasil diperbarui!');
}


public function destroy($id)
{
    $histori = HistoriTransaksi::findOrFail($id);
    $histori->delete();

    return redirect()->route('histori.histori')->with('success', 'Data berhasil dihapus.');
}


}
