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
        Schema::create('t_distribusi_detail', function (Blueprint $table) {
            $table->id('id_distribusi_d');
            $table->unsignedBigInteger('id_distribusi_h');
            $table->unsignedBigInteger('id_barang');
            $table->integer('qty_keluar');
            $table->timestamps();

            $table->foreign('id_distribusi_h')->references('id_distribusi_h')->on('t_distribusi_header')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_distribusi_detail');
    }
};
