<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    public $timestamps = false; // Hanya created_at

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: LogAktivitas belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Catat aktivitas
     */
    public static function catat($user_id, $aksi, $tabel, $data_lama = null, $data_baru = null)
    {
        return self::create([
            'user_id' => $user_id,
            'aksi' => $aksi,
            'tabel' => $tabel,
            'data_lama' => $data_lama,
            'data_baru' => $data_baru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
