<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->string('kode_tagihan')->unique();
            $table->string('nama_supplier');
            $table->foreignId('id_proyek')->constrained('proyeks', 'id_proyek');
            $table->string('no_invoice');
            $table->date('tanggal_tagihan');
            $table->date('jatuh_tempo');
            $table->text('deskripsi');
            $table->string('status_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('nilai_tagihan', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
