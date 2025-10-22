<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'tukangs';

    /**
     * Primary key kustom untuk model ini.
     */
    protected $primaryKey = 'id_tukang';
    
    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'id_proyek',
        'nama_tukang',
        'nama_mandor',
        'jumlah',
        'jatuh_tempo',
        'status'
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Proyek.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyek()
    {
        // PENYESUAIAN: Menyederhanakan definisi relasi.
        // Laravel akan secara otomatis menghubungkan 'id_proyek' di tabel ini
        // dengan primary key (biasanya 'id') di tabel proyek.
        return $this->belongsTo(Proyek::class, 'id_proyek');
    }
}