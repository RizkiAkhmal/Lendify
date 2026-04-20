<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'kategori_id',
        'kode_alat',
        'nama_alat',
        'merk',
        'spesifikasi',
        'kondisi',
        'jumlah_total',
        'jumlah_tersedia',
        'jumlah_rusak',
        'foto',
    ];

    protected $casts = [
        'jumlah_total' => 'integer',
        'jumlah_tersedia' => 'integer',
        'jumlah_rusak' => 'integer',
    ];

    /**
     * Relasi: Alat belongs to Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi: Alat memiliki banyak peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Scope: Alat yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('jumlah_tersedia', '>', 0)
                     ->where('kondisi', '!=', 'rusak_berat');
    }

    /**
     * Scope: Alat dengan kondisi baik
     */
    public function scopeKondisiBaik($query)
    {
        return $query->where('kondisi', 'baik');
    }

    /**
     * Helper: Cek apakah alat tersedia
     */
    public function isTersedia($jumlah = 1)
    {
        return $this->jumlah_tersedia >= $jumlah;
    }
}
