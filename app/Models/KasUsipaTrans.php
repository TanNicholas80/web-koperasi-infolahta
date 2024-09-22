<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasUsipaTrans extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_date_usipa',
        'keterangan_usipa',
        'periode_usipa',
        'jenis_transaksi_usipa',
        'status_usipa',
        'kategori_buku_besar_usipa',
        'kredit_transaction_usipa',
        'debet_transaction_usipa'
    ];

    public function kasUsipa()
    {
        return $this->belongsTo(KasUsipa::class, 'kas_usipa_id');
    }
}
