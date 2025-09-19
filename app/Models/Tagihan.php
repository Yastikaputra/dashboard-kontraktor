<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    // **PERBAIKAN:** Beri tahu Laravel nama primary key yang benar
    protected $primaryKey = 'id_tagihan';

    // Izinkan mass assignment
    protected $fillable = [
        'kode_tagihan',
        'nama_supplier',
        'id_proyek',
        'no_invoice',
        'tanggal_tagihan',
        'jatuh_tempo',
        'deskripsi',
        'status_bayar',
        'tanggal_bayar',
        'nilai_tagihan'
    ];

    /**
     * Relasi many-to-one ke Proyek.
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}

