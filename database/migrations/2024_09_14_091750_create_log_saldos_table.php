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
        Schema::create('log_saldos', function (Blueprint $table) {
            $table->id();
            $table->integer('main_cash_id')->nullable();
            $table->integer('main_cash_trans_id')->nullable();
            $table->float('old_saldo')->nullable();
            $table->float('new_saldo')->nullable();
            $table->enum('action_type', ['insert', 'update', 'delete'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_saldos');
    }
};
