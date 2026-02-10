<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\Peminjam;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes (default Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
        Route::post('monitoring/{peminjaman}/pickup', [Petugas\MonitoringController::class, 'pickup'])->name('monitoring.pickup');
        Route::get('monitoring/{peminjaman}/return', [Petugas\MonitoringController::class, 'returnForm'])->name('monitoring.return.form');
        Route::post('monitoring/{peminjaman}/return', [Petugas\MonitoringController::class, 'pengembalian'])->name('monitoring.return');
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
