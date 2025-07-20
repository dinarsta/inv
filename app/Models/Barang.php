<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['kode_qr', 'nama_barang', 'stok', 'divisi'];

    public function historiTransaksis()
    {
        return $this->hasMany(HistoriTransaksi::class);
    }
}
