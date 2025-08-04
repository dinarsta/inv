<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriTransaksi extends Model
{
    // ✅ Tambahkan ini agar Laravel tidak overwrite created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'jenis',
        'jumlah',
        'oleh',
        'divisi',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
