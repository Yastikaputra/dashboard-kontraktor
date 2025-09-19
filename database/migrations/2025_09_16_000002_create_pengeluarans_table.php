<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->foreignId('id_proyek')->constrained('proyeks', 'id_proyek');
            $table->string('toko');
            $table->decimal('total', 15, 2);
            $table->text('items');
            $table->date('tanggal_struk');
            $table->time('waktu_input');
            $table->string('bukti_struk')->nullable(); // Path ke file gambar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
