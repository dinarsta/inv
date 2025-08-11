<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HistoriTransaksiController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Routes untuk user belum login (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Proses login
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Halaman register
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    // Proses register
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

/*
|--------------------------------------------------------------------------
| Routes untuk user yang sudah login (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [HistoriTransaksiController::class, 'dashboard'])->name('dashboard');

    // Menampilkan form barang masuk
    Route::get('/in', [HistoriTransaksiController::class, 'formMasuk']);
    // Menampilkan form barang keluar
    Route::get('/out', [HistoriTransaksiController::class, 'formKeluar']);
    // Menyimpan transaksi masuk/keluar
    Route::post('/transaksi', [HistoriTransaksiController::class, 'store'])->name('transaksi.store');

    // Mengecek barang berdasarkan QR/kode batang
    Route::get('/barang/cek/{kode_qr}', [BarangController::class, 'cekBarang']);
    Route::get('/barang/suggest', [BarangController::class, 'suggest']);

    // Histori transaksi
    Route::get('/histori', [HistoriTransaksiController::class, 'histori'])->name('histori.histori');
    Route::get('/histori/export', [HistoriTransaksiController::class, 'export'])->name('histori.export');
    Route::post('/histori/import', [HistoriTransaksiController::class, 'import'])->name('histori.import');
    Route::get('/histori/export-by-date', [HistoriTransaksiController::class, 'exportByDate'])->name('histori.exportByDate');
    Route::put('/histori/{id}', [HistoriTransaksiController::class, 'update'])->name('histori.update');
    Route::delete('/histori/{id}', [HistoriTransaksiController::class, 'destroy'])->name('histori.destroy');
});
