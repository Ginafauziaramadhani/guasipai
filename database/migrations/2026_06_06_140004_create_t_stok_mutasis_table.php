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
        Schema::create('t_stok_mutasi', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->unsignedBigInteger('id_barang');
            $table->dateTime('tgl_mutasi');
            $table->enum('jenis_mutasi', ['masuk', 'keluar', 'penyesuaian']);
            $table->string('id_referensi_mutasi')->nullable();
            $table->integer('qty_mutasi');
            $table->integer('saldo_stok_akhir');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok_mutasi');
    }
};
