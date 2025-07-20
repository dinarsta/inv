<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\HistoriTransaksi;

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

        // Menentukan pesan sukses
        if ($request->jenis === 'in' && $isNew) {
            $pesan = "✅ Berhasil menambahkan barang baru *{$barang->nama_barang}* sebanyak {$request->jumlah} unit.";
        } elseif ($request->jenis === 'in') {
            $pesan = "✅ Berhasil menambahkan {$request->jumlah} unit ke stok barang *{$barang->nama_barang}*.";
        } else {
            $pesan = "✅ Berhasil mengeluarkan {$request->jumlah} unit *{$barang->nama_barang}*.";
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
}
