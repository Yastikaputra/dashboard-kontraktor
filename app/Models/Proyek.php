<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyeks';
    protected $primaryKey = 'id_proyek';

    /**
     * Kolom yang diizinkan untuk diisi.
     */
    protected $fillable = [
        'nama_proyek',
        'klien',
        'nilai_kontrak',
        'tanggal_mulai',
        'target_selesai',
        'status',
        'pic',
        'no_pic' // <-- PASTIKAN BARIS INI ADA
    ];
    /**
     * Relasi one-to-many ke Pengeluaran.
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Accessor untuk menghitung sisa waktu proyek.
     * Ini akan membuat properti 'sisa_waktu' bisa diakses.
     */
    public function getSisaWaktuAttribute()
{
    $sekarang = Carbon::now();
    $tanggalSelesai = Carbon::parse($this->target_selesai);

    // Jika tanggal target sudah lewat
    if ($sekarang->gt($tanggalSelesai)) {
        return 'Waktu Habis';
    }
    
    // Menghitung selisih hari dari sekarang ke tanggal target
    return $sekarang->diffInDays($tanggalSelesai) . ' hari';
}
    
    /**
     * Method untuk menghitung total pengeluaran proyek.
     */
    public function totalPengeluaran()
{
    // Ganti 'jumlah' menjadi 'total' agar sesuai dengan nama kolom di database
    return $this->pengeluarans()->sum('total');
}
}