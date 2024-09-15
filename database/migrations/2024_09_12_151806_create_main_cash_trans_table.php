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
        Schema::create('main_cash_trans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_cash_id')->constrained('main_cashs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('trans_date');
            $table->string('keterangan');
            $table->integer('periode');
            $table->enum('status', ['KM', 'KK']);
            $table->string('jenis_transaksi')->nullable();
            $table->string('kategori_buku_besar')->nullable();
            $table->float('kredit_transaction')->nullable();
            $table->float('debet_transaction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_cash_trans');
    }
};
