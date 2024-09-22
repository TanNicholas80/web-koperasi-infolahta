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
        Schema::create('kas_usipa_trans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kas_usipa_id')->constrained('kas_usipas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('trans_date_usipa');
            $table->string('keterangan_usipa');
            $table->integer('periode_usipa');
            $table->enum('status_usipa', ['KM', 'KK']);
            $table->string('jenis_transaksi_usipa')->nullable();
            $table->string('kategori_buku_besar_usipa')->nullable();
            $table->float('kredit_transaction_usipa')->nullable();
            $table->float('debet_transaction_usipa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_usipa_trans');
    }
};
