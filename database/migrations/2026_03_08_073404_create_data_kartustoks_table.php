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
        Schema::create('data_kartustoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('produk_id')
                ->constrained('data_produks')
                ->cascadeOnDelete();
            $table->timestamp('tanggal')->useCurrent();
            // tipe transaksi
            $table->enum('tipe', [
                'masuk',
                'keluar',
                'koreksi_masuk',
                'koreksi_keluar'
            ]);
            // qty transaksi
            $table->integer('qty');
            // saldo stok
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            // referensi dokumen
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('referensi_tipe')->nullable();
            // contoh: stok_masuk, stok_keluar, koreksi
            // nomor transaksi
            $table->string('kode_transaksi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index('produk_id');
            $table->index('tanggal');
            $table->index(['produk_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kartustoks');
    }
};
