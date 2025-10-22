<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'import_logs';

    /**
     * Tentukan kolom yang boleh diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'file_proyek',
        'file_pengeluaran',
        'file_tukang',
        'summary',
        'errors',
    ];

    /**
     * Beritahu Eloquent bahwa tabel ini tidak memiliki kolom 'updated_at'.
     * Database akan menangani 'created_at' secara otomatis.
     */
    const UPDATED_AT = null;

    /**
     * Mendefinisikan relasi: Sebuah log impor 'milik' seorang User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}