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
        Schema::create('master_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('email')->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('logo')->nullable();

            $table->json('rekening_bank')->nullable();
            $table->string('qris')->nullable();

            $table->boolean('bank_active')->default(true);
            $table->boolean('qris_active')->default(true);
            $table->boolean('cash_active')->default(true);

            $table->json('jam_operasional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_settings');
    }
};
