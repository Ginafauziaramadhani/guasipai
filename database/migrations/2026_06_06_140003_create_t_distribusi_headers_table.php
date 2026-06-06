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
        Schema::create('t_distribusi_header', function (Blueprint $table) {
            $table->id('id_distribusi_h');
            $table->unsignedBigInteger('id_unit');
            $table->unsignedBigInteger('id_personel_penerima');
            $table->date('tgl_distribusi');
            $table->timestamps();

            $table->foreign('id_unit')->references('id_unit')->on('m_unit_kerja')->onDelete('cascade');
            $table->foreign('id_personel_penerima')->references('id_personel')->on('m_personel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_distribusi_header');
    }
};
