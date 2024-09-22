<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBesarUsipaCashIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kas_usipa_trans',
        'kas',
        'bank_sp',
        'bank_induk',
        'piutang_uang',
        'piutang_brg_toko',
        'dana_sosial',
        'dana_dik',
        'dana_pdk',
        'resiko_kredit',
        'simp_pokok',
        'simp_wajib',
        'simp_khusus',
        'penjualan_tunai',
        'jasa_sp',
        'provisi',
        'shu_puskop',
        'modal_disetor'
    ];
}
