<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriTransaksi extends Model
{
    protected $fillable = ['barang_id', 'jenis', 'jumlah', 'oleh', 'divisi', 'keterangan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
