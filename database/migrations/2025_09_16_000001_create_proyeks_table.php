<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyeks', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->string('nama_proyek');
            $table->string('klien');
            $table->decimal('nilai_kontrak', 15, 2);
            $table->date('tanggal_mulai');
            $table->date('target_selesai');
            $table->string('status');
            $table->string('pic');
            $table->string('no_pic'); // Penanggung Jawab
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
