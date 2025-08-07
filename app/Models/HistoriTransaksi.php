<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriTransaksi extends Model
{
    // Jika kamu memang tidak ingin Laravel otomatis mengatur timestamp
    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'user_id', // ✅ Tambahkan ini
        'jenis',
        'jumlah',
        'oleh',
        'divisi',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // ✅ Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
