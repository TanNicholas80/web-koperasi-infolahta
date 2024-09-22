<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_besar_usipa_cash_outs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kas_usipa_trans');
            $table->float('bank_sp')->default(0); // BANK S/P
            $table->float('bank_induk')->default(0); // BANK INDUK
            $table->float('simpanan_pinjaman')->default(0); // SIMPAN PINJAM
            $table->float('inventaris')->default(0); // INVENTARIS
            $table->float('penyertaan_puskop')->default(0); // PENYERTAAN PUSKOP
            $table->float('hutang_toko')->default(0); // HUTANG TOKO
            $table->float('dana_pengurus')->default(0); // DANA PENGURUS
            $table->float('dana_karyawan')->default(0); // DANA KARYAWAN
            $table->float('dana_sosial')->default(0); // DANA SOSIAL
            $table->float('dana_dik')->default(0); // DANA DIK
            $table->float('dana_pdk')->default(0); // DANA PDK
            $table->float('simp_pokok')->default(0); // SIMP POKOK
            $table->float('simp_wajib')->default(0); // SIMP WAJIB
            $table->float('simp_khusus')->default(0); // SIMP KHUSUS
            $table->float('shu_angg')->default(0); // SHU ANGG
            $table->float('pembelian_toko')->default(0); // PEMBELIAN TOKO
            $table->float('biaya_insentif')->default(0); // BIAYA INSENTIF
            $table->float('biaya_atk')->default(0); // BIAYA ATK
            $table->float('biaya_transport')->default(0); // BIAYA TRANSPORT
            $table->float('biaya_pembinaan')->default(0); // BIAYA PEMBINAAN
            $table->float('biaya_pembungkus')->default(0); // BIAYA PEMBUNGKUS
            $table->float('biaya_rat')->default(0); // BIAYA RAT
            $table->float('biaya_thr')->default(0); // BIAYA THR
            $table->float('biaya_pajak')->default(0); // BIAYA PAJAK
            $table->float('biaya_admin')->default(0);// BIAYA ADMIN
            $table->float('biaya_training')->default(0); // BIAYA TRAINING
            $table->float('modal_disetor')->default(0); // MODAL DISETOR
            $table->float('lain_lain')->default(0); // LAIN-LAIN
            $table->float('kas')->default(0); // KAS
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_besar_usipa_cash_outs');
    }
};
