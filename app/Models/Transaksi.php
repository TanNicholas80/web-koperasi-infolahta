<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_barang_id',
        'jumlah',
        'total_harga',
        'nama_anggota',
        'jenis_transaksi',
        'tanggal_transaksi'
    ];

    public function dataBarang()
    {
        return $this->belongsTo(DataBarang::class);
    }
}

