<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class main_cash_trans extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_date',
        'keterangan',
        'periode',
        'jenis_transaksi',
        'status',
        'kategori_buku_besar',
        'kredit_transaction',
        'debet_transaction'
    ];

    public function mainCash()
    {
        return $this->belongsTo(main_cashs::class, 'main_cash_id');
    }
}
