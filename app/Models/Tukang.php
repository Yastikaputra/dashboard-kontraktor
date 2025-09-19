<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;

    protected $table = 'tukangs';

    // **PERBAIKAN:** Beri tahu Laravel nama primary key yang benar
    protected $primaryKey = 'id_tukang';
    
    // Izinkan mass assignment
    protected $fillable = [
        'id_proyek',
        'nama_tukang',
        'nama_mandor',
        'jumlah',
        'jatuh_tempo',
        'status'
    ];

    /**
     * **PERBAIKAN BARU:** Mendefinisikan relasi many-to-one ke Proyek.
     * Fungsi ini memberitahu Laravel bahwa setiap 'Tukang' terhubung ke satu 'Proyek'.
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}

