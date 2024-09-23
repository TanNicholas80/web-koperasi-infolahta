<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class buku_besar_cash_ins extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_main_cash_trans',
        'kas',
        'bank_sp',
        'bank_induk',
        'piutang_uang',
        'piutang_barang_toko',
        'dana_sosial',
        'dana_dik',
        'dana_pdk',
        'resiko_kredit',
        'simpanan_pokok',
        'sipanan_wajib',
        'sipanan_khusus',
        'penjualan_tunai',
        'jasa_sp',
        'provinsi',
        'shu_puskop',
        'inv_usipa',
        'lain_lain',
    ];
}
