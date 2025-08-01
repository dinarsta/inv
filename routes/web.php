<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BarangController;
use App\Http\Controllers\HistoriTransaksiController;

// Menampilkan form barang masuk
Route::get('/in', [HistoriTransaksiController::class, 'formMasuk']);

// Menampilkan form barang keluar
Route::get('/out', [HistoriTransaksiController::class, 'formKeluar']);

// Menyimpan transaksi masuk/keluar
Route::post('/transaksi', [HistoriTransaksiController::class, 'store'])->name('transaksi.store');

// Mengecek barang berdasarkan QR/kode batang
Route::get('/barang/cek/{kode_qr}', [BarangController::class, 'cekBarang']);

Route::get('/histori', [HistoriTransaksiController::class, 'histori'])->name('histori.histori');

Route::get('/', [HistoriTransaksiController::class, 'dashboard'])->name('dashboard');

Route::get('/histori/export', [HistoriTransaksiController::class, 'export'])->name('histori.export');

Route::post('/histori/import', [HistoriTransaksiController::class, 'import'])->name('histori.import');

Route::get('/barang/suggest', [BarangController::class, 'suggest']);

Route::get('/histori/export-by-date', [HistoriTransaksiController::class, 'exportByDate'])->name('histori.exportByDate');

Route::put('/histori/{id}', [HistoriTransaksiController::class, 'update'])->name('histori.update');
Route::delete('/histori/{id}', [HistoriTransaksiController::class, 'destroy'])->name('histori.destroy');
