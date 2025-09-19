<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tukangs', function (Blueprint $table) {
            $table->id('id_tukang');
            $table->foreignId('id_proyek')->constrained('proyeks', 'id_proyek');
            $table->string('nama_tukang');
            $table->string('nama_mandor');
            $table->decimal('jumlah', 15, 2);
            $table->date('jatuh_tempo');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tukangs');
    }
};
