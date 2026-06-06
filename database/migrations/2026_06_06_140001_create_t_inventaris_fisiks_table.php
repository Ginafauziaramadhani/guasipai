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
        Schema::create('t_inventaris_fisik', function (Blueprint $table) {
            $table->id('id_inventaris');
            $table->unsignedBigInteger('id_barang');
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('id_unit_sekarang')->nullable();
            $table->enum('status_kondisi', ['Tersedia', 'Terdistribusi', 'Rusak', 'Servis'])->default('Tersedia');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('m_barang')->onDelete('cascade');
            $table->foreign('id_unit_sekarang')->references('id_unit')->on('m_unit_kerja')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_inventaris_fisik');
    }
};
