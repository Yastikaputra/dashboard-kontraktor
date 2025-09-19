<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyeks';
    
    // **PERBAIKAN:** Beri tahu Laravel nama primary key yang benar
    protected $primaryKey = 'id_proyek';

    // Izinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'nama_proyek',
        'klien',
        'nilai_kontrak',
        'tanggal_mulai',
        'target_selesai',
        'status',
        'pic'
    ];

    /**
     * Relasi one-to-many ke Pengeluaran.
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Relasi one-to-many ke Tagihan.
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_proyek', 'id_proyek');
    }
}

