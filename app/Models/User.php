<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: User memiliki banyak peminjaman (sebagai peminjam)
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    /**
     * Relasi: User (petugas) menyetujui banyak peminjaman
     */
    public function peminjamanDisetujui()
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }

    /**
     * Relasi: User memiliki banyak log aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    /**
     * Helper method: Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Helper method: Cek apakah user adalah petugas
     */
    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    /**
     * Helper method: Cek apakah user adalah peminjam
     */
    public function isPeminjam()
    {
        return $this->role === 'peminjam';
    }

    /**
     * Helper method: Cek apakah user aktif
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
