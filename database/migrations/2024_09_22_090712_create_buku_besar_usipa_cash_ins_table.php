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
        Schema::create('buku_besar_usipa_cash_ins', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kas_usipa_trans');
            $table->float('kas')->default(0);
            $table->float('bank_sp')->default(0);
            $table->float('bank_induk')->default(0);
            $table->float('piutang_uang')->default(0);
            $table->float('piutang_brg_toko')->default(0);
            $table->float('dana_sosial')->default(0);
            $table->float('dana_dik')->default(0);
            $table->float('dana_pdk')->default(0);
            $table->float('resiko_kredit')->default(0);
            $table->float('simp_pokok')->default(0);
            $table->float('simp_wajib')->default(0);
            $table->float('simp_khusus')->default(0);
            $table->float('penjualan_tunai')->default(0);
            $table->float('jasa_sp')->default(0);
            $table->float('provinsi')->default(0);
            $table->float('shu_puskop')->default(0);
            $table->float('modal_disetor')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_besar_usipa_cash_ins');
    }
};
