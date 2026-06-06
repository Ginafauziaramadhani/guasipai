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
        Schema::create('t_riwayat_servis', function (Blueprint $table) {
            $table->id('id_servis');
            $table->unsignedBigInteger('id_inventaris');
            $table->date('tgl_servis');
            $table->text('rincian_kerusakan');
            $table->decimal('biaya_servis', 15, 2);
            $table->timestamps();

            $table->foreign('id_inventaris')->references('id_inventaris')->on('t_inventaris_fisik')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_riwayat_servis');
    }
};
