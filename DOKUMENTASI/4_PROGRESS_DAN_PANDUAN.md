# PROGRESS & PANDUAN IMPLEMENTASI
## Aplikasi Peminjaman Alat - Status 50% Complete

### ✅ SUDAH SELESAI (50%)

#### 1. Dokumentasi (15%)
- ✅ `DOKUMENTASI/1_ANALISIS_KEBUTUHAN.md` - Analisis lengkap sistem
- ✅ `DOKUMENTASI/2_DESAIN_DATABASE_ERD.md` - ERD & struktur database detail
- ✅ `DOKUMENTASI/3_FLOWCHART_PSEUDOCODE.md` - Flowchart login, peminjaman, pengembalian

#### 2. Database (20%)
- ✅ Database `peminjaman_alat` sudah dibuat
- ✅ 6 Migration files lengkap:
  - `modify_users_table_add_role_and_fields` - Role (admin/petugas/peminjam), phone, address, status
  - `create_kategori_table` - Kategori alat
  - `create_alat_table` - Master alat dengan FK ke kategori
  - `create_peminjaman_table` - Transaksi peminjaman
  - `create_pengembalian_table` - Transaksi pengembalian + denda
  - `create_log_aktivitas_table` - Audit trail
- ✅ Semua migrasi sudah dijalankan (9 tabel total)

#### 3. Models (15%)
- ✅ `User.php` - Dengan helper methods (isAdmin, isPetugas, isPeminjam, isActive)
- ✅ `Kategori.php` - Relasi ke Alat
- ✅ `Alat.php` - Scopes (tersedia, kondisiBaik), helper isTersedia()
- ✅ `Peminjaman.php` - Scopes (pending, approved, dipinjam, selesai), helper hitung keterlambatan & denda
- ✅ `Pengembalian.php` - Helper hitungTotalDenda() static method
- ✅ `LogAktivitas.php` - Helper catat() untuk logging otomatis

#### 4. Authentication
- ✅ Laravel Breeze installed
- ✅ Auth scaffolding (login, register, forgot password)
- ✅ Blade templates

### 🔧 YANG PERLU DILANJUTKAN (50%)

#### 5. Seeders (Prioritas TINGGI - 5%)

Buat file seeders berikut:

```php
// database/seeders/DatabaseSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            AlatSeeder::class,
        ]);
    }
}
```

```php
// database/seeders/UserSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'status' => 'active',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Lab',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Jl. Petugas No. 2',
            'status' => 'active',
        ]);

        // Peminjam
        User::create([
            'name' => 'Peminjam Test',
            'email' => 'peminjam@example.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'phone' => '081234567892',
            'address' => 'Jl. Peminjam No. 3',
            'status' => 'active',
        ]);
    }
}
```

```php
// database/seeders/KategoriSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Peralatan elektronik'],
            ['nama_kategori' => 'Komputer', 'deskripsi' => 'Perangkat komputer dan aksesoris'],
            ['nama_kategori' => 'Laboratorium', 'deskripsi' => 'Alat-alat laboratorium'],
            ['nama_kategori' => 'Olahraga', 'deskripsi' => 'Peralatan olahraga'],
            ['nama_kategori' => 'Multimedia', 'deskripsi' => 'Peralatan multimedia'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
```

```php
// database/seeders/AlatSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alat;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $alats = [
            [
                'kategori_id' => 2,
                'kode_alat' => 'LAP-001',
                'nama_alat' => 'Laptop ASUS ROG',
                'merk' => 'ASUS',
                'spesifikasi' => 'Intel i7, RAM 16GB, SSD 512GB',
                'kondisi' => 'baik',
                'jumlah_total' => 5,
                'jumlah_tersedia' => 5,
            ],
            [
                'kategori_id' => 2,
                'kode_alat' => 'PRJ-001',
                'nama_alat' => 'Proyektor Epson',
                'merk' => 'Epson',
                'spesifikasi' => 'Full HD 1080p, 3000 lumens',
                'kondisi' => 'baik',
                'jumlah_total' => 3,
                'jumlah_tersedia' => 3,
            ],
            [
                'kategori_id' => 5,
                'kode_alat' => 'CAM-001',
                'nama_alat' => 'Kamera DSLR Canon',
                'merk' => 'Canon',
                'spesifikasi' => 'EOS 80D, 24.2 MP',
                'kondisi' => 'baik',
                'jumlah_total' => 2,
                'jumlah_tersedia' => 2,
            ],
            [
                'kategori_id' => 3,
                'kode_alat' => 'MIC-001',
                'nama_alat' => 'Mikroskop Digital',
                'merk' => 'Olympus',
                'spesifikasi' => 'Pembesaran 40x-1000x',
                'kondisi' => 'baik',
                'jumlah_total' => 4,
                'jumlah_tersedia' => 4,
            ],
            [
                'kategori_id' => 4,
                'kode_alat' => 'BALL-001',
                'nama_alat' => 'Bola Basket',
                'merk' => 'Molten',
                'spesifikasi' => 'Size 7, Kulit Sintetis',
                'kondisi' => 'baik',
                'jumlah_total' => 10,
                'jumlah_tersedia' => 10,
            ],
        ];

        foreach ($alats as $alat) {
            Alat::create($alat);
        }
    }
}
```

**Jalankan seeder:**
```bash
php artisan make:seeder UserSeeder
php artisan make:seeder KategoriSeeder
php artisan make:seeder AlatSeeder
php artisan db:seed
```

#### 6. Middleware untuk Role-Based Access (Prioritas TINGGI - 3%)

```php
// app/Http/Middleware/CheckRole.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

Register di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

#### 7. Controllers (Prioritas TINGGI - 15%)

Buat controllers dengan command:
```bash
php artisan make:controller Admin/UserController --resource
php artisan make:controller Admin/KategoriController --resource
php artisan make:controller Admin/AlatController --resource
php artisan make:controller Admin/PeminjamanController --resource
php artisan make:controller Admin/PengembalianController --resource
php artisan make:controller Admin/LogAktivitasController
php artisan make:controller Petugas/ApprovalController
php artisan make:controller Petugas/MonitoringController
php artisan make:controller Petugas/LaporanController
php artisan make:controller Peminjam/KatalogController
php artisan make:controller Peminjam/PeminjamanController
php artisan make:controller Peminjam/PengembalianController
php artisan make:controller DashboardController
```

#### 8. Routes (Prioritas TINGGI - 5%)

Edit `routes/web.php` - tambahkan:
```php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\Peminjam;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', Admin\UserController::class);
        Route::resource('kategori', Admin\KategoriController::class);
        Route::resource('alat', Admin\AlatController::class);
        Route::resource('peminjaman', Admin\PeminjamanController::class);
        Route::resource('pengembalian', Admin\PengembalianController::class);
        Route::get('log-aktivitas', [Admin\LogAktivitasController::class, 'index'])->name('log.index');
    });
    
    // Petugas routes
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('approval', [Petugas\ApprovalController::class, 'index'])->name('approval.index');
        Route::post('approval/{peminjaman}/approve', [Petugas\ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('approval/{peminjaman}/reject', [Petugas\ApprovalController::class, 'reject'])->name('approval.reject');
        Route::get('monitoring', [Petugas\MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('laporan', [Petugas\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export', [Petugas\LaporanController::class, 'export'])->name('laporan.export');
    });
    
    // Peminjam routes
    Route::middleware(['role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
        Route::get('katalog', [Peminjam\KatalogController::class, 'index'])->name('katalog.index');
        Route::get('katalog/{alat}', [Peminjam\KatalogController::class, 'show'])->name('katalog.show');
        Route::get('peminjaman', [Peminjam\PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('peminjaman', [Peminjam\PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('pengembalian/{peminjaman}', [Peminjam\PengembalianController::class, 'show'])->name('pengembalian.show');
        Route::post('pengembalian/{peminjaman}', [Peminjam\PengembalianController::class, 'store'])->name('pengembalian.store');
    });
});

require __DIR__.'/auth.php';
```

#### 9. Views (12%)

Struktur folder views yang perlu dibuat:
```
resources/views/
├── layouts/
│   ├── app.blade.php (sudah ada dari Breeze)
│   └── admin.blade.php
├── dashboard.blade.php
├── admin/
│   ├── users/ (index, create, edit)
│   ├── kategori/ (index, create, edit)
│   ├── alat/ (index, create, edit, show)
│   ├── peminjaman/ (index, show)
│   ├── pengembalian/ (index)
│   └── log/ (index)
├── petugas/
│   ├── approval/ (index)
│   ├── monitoring/ (index)
│   └── laporan/ (index)
└── peminjam/
    ├── katalog/ (index, show)
    ├── peminjaman/ (index)
    └── pengembalian/ (show)
```

### 📋 CARA MELANJUTKAN

1. **Buat Seeders** - Copy code seeder di atas
2. **Buat Middleware** - CheckRole untuk authorization
3. **Buat Controllers** - Gunakan command artisan
4. **Setup Routes** - Copy routes yang sudah disediakan
5. **Buat Views** - Gunakan Bootstrap 5 (sudah included dari Breeze)
6. **Implementasi Logic** - Isi method-method di Controller
7. **Testing** - Test 5 skenario yang diminta
8. **SQL Features** - Buat stored procedures, functions, triggers
9. **Documentation** - Screenshot hasil testing
10. **Export SQL** - `mysqldump -u root peminjaman_alat > peminjaman_alat.sql`

### 🎯 FILE YANG SUDAH ADA

- ✅ All Migration files
- ✅ All Model files dengan relasi lengkap
- ✅ Database schema di MySQL
- ✅ Auth system (Breeze)
- ✅ Dokumentasi analisis, ERD, flowchart

### 📝 CATATAN PENTING

- Password default untuk semua user seeder: **password**
- Denda keterlambatan: **Rp 5.000/hari**
- Denda rusak ringan: **Rp 50.000**
- Denda rusak berat: **Rp 200.000**
- Max durasi peminjaman: **30 hari**

### ⏱️ ESTIMASI WAKTU

- Seeders: 30 menit
- Middleware: 15 menit  
- Controllers: 3-4 jam
- Views: 4-5 jam
- Testing: 1 jam
- SQL Features: 1-2 jam
- Documentation: 1 jam
- **Total: ~11-13 jam** (dari total 23 jam)

Anda sudah 50% selesai! Tinggal implementasi logic dan UI.
