<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\User;
use App\Models\Alat;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $petugas = User::where('role', 'petugas')->first();
        $peminjam = User::where('role', 'peminjam')->first();
        $alat = Alat::first();

        if (!$admin || !$petugas || !$peminjam || !$alat) {
            return;
        }

        $logs = [
            [
                'user_id' => $admin->id,
                'aksi' => 'CREATE',
                'tabel' => 'users',
                'data_baru' => ['name' => 'Petugas Baru', 'role' => 'petugas'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(5),
            ],
            [
                'user_id' => $peminjam->id,
                'aksi' => 'REQUEST',
                'tabel' => 'peminjaman',
                'data_baru' => ['alat_id' => $alat->id, 'jumlah' => 1],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(4),
            ],
            [
                'user_id' => $petugas->id,
                'aksi' => 'APPROVE',
                'tabel' => 'peminjaman',
                'data_baru' => ['status' => 'approved'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => $petugas->id,
                'aksi' => 'PICKUP',
                'tabel' => 'peminjaman',
                'data_baru' => ['status' => 'dipinjam'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $petugas->id,
                'aksi' => 'RETURN',
                'tabel' => 'pengembalian',
                'data_baru' => ['kondisi_alat' => 'baik', 'denda' => 0],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHour(),
            ],
        ];

        foreach ($logs as $log) {
            LogAktivitas::create($log);
        }
    }
}
