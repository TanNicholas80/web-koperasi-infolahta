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
        Schema::create('buku_besar_cash_ins', function (Blueprint $table) {
            $table->id();
            $table->integer('id_main_cash_trans')->nullable();
            $table->float('kas')->nullable()->default(0);
            $table->float('bank_sp')->nullable()->default(0);
            $table->float('bank_induk')->nullable()->default(0);
            $table->float('piutang_uang')->nullable()->default(0);
            $table->float('piutang_barang_toko')->nullable()->default(0);
            $table->float('dana_sosial')->nullable()->default(0);
            $table->float('dana_dik')->nullable()->default(0);
            $table->float('dana_pdk')->nullable()->default(0);
            $table->float('resiko_kredit')->nullable()->default(0);
            $table->float('simpanan_pokok')->nullable()->default(0);
            $table->float('sipanan_wajib')->nullable()->default(0);
            $table->float('sipanan_khusus')->nullable()->default(0);
            $table->float('penjualan_tunai')->nullable()->default(0);
            $table->float('jasa_sp')->nullable()->default(0);
            $table->float('provinsi')->nullable()->default(0);
            $table->float('shu_puskop')->nullable()->default(0);
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
        Schema::dropIfExists('buku_besar_cash_ins');
    }
};
