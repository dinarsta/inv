<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Mengecek barang berdasarkan kode QR.
     * Digunakan di form_masuk dan form_keluar.
     */
public function cekBarang($kode_qr)
{
    $barang = \App\Models\Barang::where('kode_qr', $kode_qr)->first();
    if ($barang) {
        return response()->json([
            'exists' => true,
            'nama_barang' => $barang->nama_barang,
                  'stok' => $barang->stok
        ]);
    } else {
        return response()->json(['exists' => false]);
    }
}

    /**
     * (Opsional) Menampilkan daftar semua barang - bisa dipakai untuk menu admin.
     */
    public function index()
    {
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('barang.index', compact('barangs'));
    }

    public function suggest(Request $request)
{
    $q = $request->query('q');
    $data = \App\Models\Barang::where('kode_qr', 'like', "%$q%")
        ->select('kode_qr')
        ->limit(10)
        ->get();

    return response()->json($data);
}

}
