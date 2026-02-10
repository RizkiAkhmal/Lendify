<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'alat_id',
        'petugas_id',
        'tanggal_pengajuan',
        'tanggal_peminjaman',
        'tanggal_kembali_rencana',
        'jumlah',
        'keperluan',
        'status',
        'catatan_petugas',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_peminjaman' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'jumlah' => 'integer',
    ];

    /**
     * Relasi: Peminjaman belongs to User (peminjam)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Peminjaman belongs to Alat
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    /**
     * Relasi: Peminjaman belongs to User (petugas yang approve)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Relasi: Peminjaman has one Pengembalian
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Scope: Peminjaman pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Peminjaman approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Peminjaman dipinjam (aktif)
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope: Peminjaman selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Helper: Hitung durasi peminjaman (hari)
     */
    public function getDurasiHariAttribute()
    {
        if (!$this->tanggal_peminjaman || !$this->tanggal_kembali_rencana) {
            return 0;
        }
        return $this->tanggal_peminjaman->diffInDays($this->tanggal_kembali_rencana);
    }

    /**
     * Helper: Hitung keterlambatan (hari)
     */
    public function hitungKeterlambatan()
    {
        if ($this->status !== 'dipinjam' || !$this->tanggal_kembali_rencana) {
            return 0;
        }
        
        $today = Carbon::today();
        if ($today->lte($this->tanggal_kembali_rencana)) {
            return 0;
        }
        
        return $today->diffInDays($this->tanggal_kembali_rencana);
    }

    /**
     * Helper: Hitung denda keterlambatan
     */
    public function hitungDendaKeterlambatan()
    {
        return $this->hitungKeterlambatan() * 5000;
    }
}
