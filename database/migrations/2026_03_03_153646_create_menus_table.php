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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('route')->nullable()->unique();
            $table->string('icon')->nullable();
            $table->string('group')->nullable();
            $table->integer('group_order')->default(0);
            $table->integer('order')->default(0);
            $table->string('prefix')->index();
            $table->boolean('is_active')
                ->default(true)
                ->comment('true = tampil di sidebar');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
