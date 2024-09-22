<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBesarUsipaCashOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kas_usipa_trans',
        'bank_sp',           // BANK S/P
        'bank_induk',        // BANK INDUK
        'simpanan_pinjaman', // SIMPAN PINJAM
        'inventaris',        // INVENTARIS
        'penyertaan_puskop', // PENYERTAAN PUSKOP
        'hutang_toko',       // HUTANG TOKO
        'dana_pengurus',     // DANA PENGURUS
        'dana_karyawan',     // DANA KARYAWAN
        'dana_sosial',       // DANA SOSIAL
        'dana_dik',          // DANA DIK
        'dana_pdk',          // DANA PDK
        'simp_pokok',        // SIMP POKOK
        'simp_wajib',        // SIMP WAJIB
        'simp_khusus',       // SIMP KHUSUS
        'shu_angg',          // SHU ANGG
        'pembelian_toko',    // PEMBELIAN TOKO
        'biaya_insentif',    // BIAYA INSENTIF
        'biaya_atk',         // BIAYA ATK
        'biaya_transport',   // BIAYA TRANSPORT
        'biaya_pembinaan',   // BIAYA PEMBINAAN
        'biaya_pembungkus',  // BIAYA PEMBUNGKUS
        'biaya_rat',         // BIAYA RAT
        'biaya_thr',         // BIAYA THR
        'biaya_pajak',       // BIAYA PAJAK
        'biaya_admin',       // BIAYA ADMIN
        'biaya_training',    // BIAYA TRAINING
        'modal_disetor',     // MODAL DISETOR
        'lain_lain',         // LAIN-LAIN
        'kas'                // KAS
    ];
}
