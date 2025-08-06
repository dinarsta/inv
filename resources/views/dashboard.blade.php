@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="mb-4 fw-semibold text-center fs-4">📊 Dashboard Inventaris</h2>

    <div class="row g-4 text-center">
        <div class="col-12 col-md-4">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Barang Masuk</h6>
                    <p class="card-text fs-3">{{ $totalMasuk }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Barang Keluar</h6>
                    <p class="card-text fs-3">{{ $totalKeluar }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Jumlah Nama Barang</h6>
                    <p class="card-text fs-3">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-semibold text-center my-5 fs-5">🔍 Navigasi Inventaris</h4>
    <div class="row g-4 text-center">
        @auth
            @if(Auth::user()->role === 'admin')
                <div class="col-12 col-md-4">
                    <a href="/in" class="text-decoration-none">
                        <div class="card card-nav shadow-sm bg-white h-100">
                            <div class="card-body">
                                <h6 class="card-title text-success fw-semibold">➕ Barang Masuk</h6>
                                <p class="card-text text-muted">Tambah data barang yang masuk ke gudang</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a href="/out" class="text-decoration-none">
                        <div class="card card-nav shadow-sm bg-white h-100">
                            <div class="card-body">
                                <h6 class="card-title text-danger fw-semibold">➖ Barang Keluar</h6>
                                <p class="card-text text-muted">Catat barang yang keluar dari gudang</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endauth

        <div class="col-12 col-md-4">
            <a href="/histori" class="text-decoration-none">
                <div class="card card-nav shadow-sm bg-white h-100">
                    <div class="card-body">
                        <h6 class="card-title text-secondary fw-semibold">📋 Histori Transaksi</h6>
                        <p class="card-text text-muted">Lihat riwayat barang masuk dan keluar</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
