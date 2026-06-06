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
        Schema::create('t_stock_opname', function (Blueprint $table) {
            $table->id('id_opname');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_unit');
            $table->date('tgl_opname');
            $table->integer('qty_fisik');
            $table->integer('qty_sistem');
            $table->integer('qty_selisih');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('m_barang')->onDelete('cascade');
            $table->foreign('id_unit')->references('id_unit')->on('m_unit_kerja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stock_opname');
    }
};
