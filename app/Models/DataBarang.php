<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'data_barangs'; // Nama tabel di database, sesuaikan jika berbeda
    protected $primaryKey = 'id';  // Primary key

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'kode_brg',
        'nama_brg',
        'stock',
        'harga_satuan',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}

