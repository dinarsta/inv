<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    /**
     * Mengecek barang berdasarkan kode QR.
     * Digunakan di form_masuk dan form_keluar.
     */
public function cekBarang($kode_qr)
{
    $kode_qr = trim($kode_qr); // Remove leading/trailing whitespace
    $barang = Barang::where('kode_qr', $kode_qr)->first();

    if ($barang) {
        Log::info("Cek Barang", [
            'kode_qr' => $kode_qr,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
        ]);

        return response()->json([
            'exists' => true,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok
        ]);
    }

    Log::info("Barang tidak ditemukan", ['kode_qr' => $kode_qr]);

    return response()->json(['exists' => false]);
}

    /**
     * Menampilkan daftar semua barang (opsional untuk admin).
     */
    public function index()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Memberikan saran kode QR (digunakan untuk autocomplete).
     */
  public function suggest(Request $request)
{
    $q = $request->query('q');

    $data = Barang::where('kode_qr', 'like', "%$q%")
        ->orWhere('nama_barang', 'like', "%$q%")
        ->select('kode_qr', 'nama_barang')
        ->limit(10)
        ->get();

    return response()->json($data);
}

}
