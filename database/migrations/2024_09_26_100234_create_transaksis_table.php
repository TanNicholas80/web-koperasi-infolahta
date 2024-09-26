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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_barang_id')->constrained('data_barangs')->onDelete('cascade');
            $table->integer('jumlah');  // Quantity of the item purchased
            $table->decimal('total_harga', 15, 2);  // Total price for this item
            $table->timestamp('tanggal_transaksi')->useCurrent();  // Transaction timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
