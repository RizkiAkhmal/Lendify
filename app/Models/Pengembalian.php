<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali_aktual',
        'kondisi_alat',
        'keterlambatan_hari',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'datetime',
        'keterlambatan_hari' => 'integer',
        'denda' => 'decimal:2',
    ];

    /**
     * Relasi: Pengembalian belongs to Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Helper: Hitung total denda (keterlambatan + kerusakan)
     */
    public static function hitungTotalDenda($keterlambatan_hari, $kondisi_alat)
    {
        $denda_keterlambatan = $keterlambatan_hari * 5000;
        
        $denda_kerusakan = 0;
        if ($kondisi_alat === 'rusak_ringan') {
            $denda_kerusakan = 50000;
        } elseif ($kondisi_alat === 'rusak_berat') {
            $denda_kerusakan = 200000;
        }
        
        return $denda_keterlambatan + $denda_kerusakan;
    }

    /**
     * Helper: Format denda ke Rupiah
     */
    public function getDendaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }
}
