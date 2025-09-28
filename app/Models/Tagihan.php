<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'tagihans';

    /**
     * Primary key dari tabel.
     */
    protected $primaryKey = 'id'; // Disesuaikan dengan struktur baru Anda

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * Disesuaikan dengan semua kolom yang ada.
     */
    protected $fillable = [
        'id',
        'id_proyek',
        'id_pengeluaran',
        'nama_vendor',
        'alamat',
        'nomor_telepon',
        'jenis_toko',
        'daerah',
        'nomor_rekening',
        'nama_bank',
        'kode_tagihan',
        'no_invoice',
        'tanggal_tagihan',
        'jatuh_tempo',
        'deskripsi',
        'status_bayar',
        'tanggal_bayar',
        'nilai_tagihan',
    ];

    /**
     * Mendefinisikan relasi ke model Proyek.
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Mendefinisikan relasi ke model Pengeluaran.
     */
    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}

