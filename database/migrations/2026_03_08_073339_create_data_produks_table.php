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
        Schema::create('data_produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->text('foto_produk')->nullable();
            $table->string('satuan');
            $table->string('kategori')->nullable();
            $table->decimal('harga', 15, 2)->default(0);

            // cache stok terakhir (biar cepat)
            $table->integer('stok')->default(0);

            $table->string('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();

            $table->index('kode_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_produks');
    }
};
