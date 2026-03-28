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
        Schema::create('data_stokmasuks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('produk_id')
                ->constrained('data_produks')
                ->cascadeOnDelete();
            $table->integer('jumlah');
            $table->date('tanggal_masuk');
            $table->string('keterangan')->nullable();
            $table->enum('status', ['draft', 'posted', 'cancelled'])
                ->default('draft');

            $table->timestamps();

            $table->index('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_stokmasuks');
    }
};
