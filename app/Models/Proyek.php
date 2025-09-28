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

    protected $fillable = [
        'nama_proyek',
        'klien',
        'nilai_kontrak',
        'tanggal_mulai',
        'target_selesai',
        'status',
        'pic',
        'no_pic'
    ];

    /**
     * Relasi one-to-many ke Pengeluaran.
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'id_proyek', 'id_proyek');
    }

    /**
     * [BARU] Relasi one-to-many ke Tagihan.
     * Asumsi nama model adalah Tagihan dan foreign key-nya id_proyek.
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Accessor untuk menghitung sisa waktu proyek.
     */
    public function getSisaWaktuAttribute()
    {
        $sekarang = Carbon::now();
        $tanggalSelesai = Carbon::parse($this->target_selesai);

        if ($sekarang->gt($tanggalSelesai)) {
            return 'Waktu Habis';
        }
        
        return $sekarang->diffInDays($tanggalSelesai) . ' hari';
    }
    
    /**
     * Method ini tidak akan kita gunakan lagi di view untuk efisiensi,
     * tapi biarkan saja di sini jika diperlukan di tempat lain.
     */
    public function totalPengeluaran()
    {
        // Menghitung total dari pengeluaran langsung dan tagihan
        $totalPengeluaranLangsung = $this->pengeluarans()->sum('total');
        $totalTagihan = $this->tagihans()->sum('nilai_tagihan'); // Asumsi nama kolom nilai_tagihan
        return $totalPengeluaranLangsung + $totalTagihan;
    }
}