<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'pengeluarans';

    /**
     * **PERBAIKAN:** Mendefinisikan primary key dengan nama yang benar (snake_case).
     *
     * @var string
     */
    protected $primaryKey = 'id_pengeluaran';

    /**
     * Mendefinisikan relasi "belongsTo" ke model Proyek.
     */
    public function proyek()
    {
        // **PERBAIKAN:** Menggunakan 'id_proyek' sebagai foreign key.
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}

