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
     * [PERBAIKAN] Menambahkan 'deskripsi' ke dalam fillable
     */
    protected $fillable = [
        'nama_proyek',
        'klien',
        'nilai_kontrak',
        'tanggal_mulai',
        'target_selesai',
        'status',
        'pic',
        'no_pic',
        'deskripsi'
    ];

    /**
     * [DITAMBAHKAN] Relasi many-to-many ke User (Owner).
     * Mendefinisikan relasi sebaliknya dari model User.
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'proyek_user',
            'proyek_id',
            'user_id',
            'id_proyek',
            'id'
        );
    }

    /**
     * Relasi one-to-many ke Pengeluaran.
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'id_proyek', 'id_proyek');
    }

    /**
     * Relasi one-to-many ke Tukang.
     */
    public function tukangs()
    {
        return $this->hasMany(Tukang::class, 'id_proyek', 'id_proyek');
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
}