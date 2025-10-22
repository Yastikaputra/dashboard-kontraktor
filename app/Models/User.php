<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username', // Pastikan kolom ini sesuai dengan migrasi Anda
        'password',
        'role',     // Atribut untuk membedakan 'admin' dan 'user' (owner)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Mendefinisikan relasi many-to-many antara User (Owner) dan Proyek.
     * Seorang user bisa memiliki akses ke banyak proyek.
     */
    public function proyeks()
    {
        // [DISESUAIKAN] Menambahkan semua parameter untuk kejelasan relasi
        return $this->belongsToMany(
            Proyek::class,
            'proyek_user',
            'user_id',
            'proyek_id',
            'id',
            'id_proyek'
        );
    }
}