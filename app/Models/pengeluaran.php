<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengeluaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_proyek',
        'toko',
        'total',
        'items',
        'tanggal_struk',
        'waktu_input',
        'bukti_struk',
        'status_bayar',
        'tanggal_bayar', // [PERBAIKAN] Tambahkan kolom ini
    ];

    /**
     * Mendefinisikan relasi ke model Proyek.
     */
    protected $casts = [
        'tanggal_struk' => 'date',
        'tanggal_bayar' => 'date',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}