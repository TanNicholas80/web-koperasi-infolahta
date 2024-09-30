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
        Schema::create('data_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_brg')->unique();
            $table->string('nama_brg');
            $table->integer('stock');
            $table->decimal('harga_satuan', 15, 2);
            $table->date('tanggal'); // Input date from user
            $table->timestamps(); // Optional, remove if you don't need it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_barangs');
    }
};
