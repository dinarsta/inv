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
