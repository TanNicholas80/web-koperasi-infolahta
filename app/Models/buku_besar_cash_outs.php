<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class buku_besar_cash_outs extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_main_cash_trans',
        'kas',
        'bank_sp',
        'bank_induk',
        'simpan_pinjam',
        'inventaris',
        'penyertaan_puskop',
        'hutang_toko',
        'dana_pengurus',
        'dana_karyawan',
        'dana_sosial',
        'dana_dik',
        'dana_pdk',
        'simp_pokok',
        'simp_wajib',
        'simp_khusus',
        'shu_angg',
        'pembelian_toko',
        'biaya_insentif',
        'biaya_atk',
        'biaya_transport',
        'biaya_pembinaan',
        'biaya_pembungkus',
        'biaya_rat',
        'biaya_thr',
        'biaya_pajak',
        'biaya_admin',
        'biaya_training',
        'inv_usipa',
        'lain_lain',
    ];
}
