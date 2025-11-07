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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_menu')->references('id')->on('menus')->onDelete('cascade');
            $table->foreignId('id_meja')->references('id')->on('mejas')->onDelete('cascade');
            $table->foreignId('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->integer('jumlah');
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
