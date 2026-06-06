<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_unit_kerja', function (Blueprint $table) {
            $table->id('id_unit');
            $table->string('nama_unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_unit_kerja');
    }
};
