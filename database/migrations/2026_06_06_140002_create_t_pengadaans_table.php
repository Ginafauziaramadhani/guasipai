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
        Schema::create('t_pengadaan', function (Blueprint $table) {
            $table->id('id_pengadaan');
            $table->unsignedBigInteger('id_vendor');
            $table->string('no_po');
            $table->date('tgl_datang');
            $table->integer('jumlah');
            $table->unsignedBigInteger('id_barang');
            $table->timestamps();

            $table->foreign('id_vendor')->references('id_vendor')->on('m_vendor')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengadaan');
    }
};
