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
        Schema::create('buku_besar_cash_outs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_main_cash_trans')->nullable();
            $table->float('kas')->nullable()->default(0);
            $table->float('bank_sp')->nullable()->default(0);
            $table->float('bank_induk')->nullable()->default(0);
            $table->float('piutang_uang')->nullable()->default(0);
            $table->float('inventaris')->nullable()->default(0);
            $table->float('penyertaan_puskop')->nullable()->default(0);
            $table->float('hutang_toko')->nullable()->default(0);
            $table->float('dana_pengurus')->nullable()->default(0);
            $table->float('dana_karyawan')->nullable()->default(0);
            $table->float('dana_sosial')->nullable()->default(0);
            $table->float('dana_dik')->nullable()->default(0);
            $table->float('dana_pdk')->nullable()->default(0);
            $table->float('simp_pokok')->nullable()->default(0);
            $table->float('simp_wajib')->nullable()->default(0);
            $table->float('simp_khusus')->nullable()->default(0);
            $table->float('shu_angg')->nullable()->default(0);
            $table->float('pembelian_toko')->nullable()->default(0);
            $table->float('biaya_insentif')->nullable()->default(0);
            $table->float('biaya_atk')->nullable()->default(0);
            $table->float('biaya_transport')->nullable()->default(0);
            $table->float('biaya_pembinaan')->nullable()->default(0);
            $table->float('biaya_pembungkus')->nullable()->default(0);
            $table->float('biaya_rat')->nullable()->default(0);
            $table->float('biaya_thr')->nullable()->default(0);
            $table->float('biaya_pajak')->nullable()->default(0);
            $table->float('biaya_admin')->nullable()->default(0);
            $table->float('biaya_training')->nullable()->default(0);
            $table->float('inv_usipa')->nullable()->default(0);
            $table->float('lain_lain')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_besar_cash_outs');
    }
};
