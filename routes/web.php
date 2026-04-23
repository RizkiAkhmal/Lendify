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
        Route::get('users/export', [Admin\UserController::class, 'export'])->name('users.export');
        Route::resource('users', Admin\UserController::class);
        
        Route::get('alat/export', [Admin\AlatController::class, 'export'])->name('alat.export');
        Route::get('alat/{alat}/repair', [Admin\AlatController::class, 'showRepair'])->name('alat.repair.show');
        Route::post('alat/{alat}/repair', [Admin\AlatController::class, 'postRepair'])->name('alat.repair');
        Route::resource('alat', Admin\AlatController::class);
        
        Route::resource('kategori', Admin\KategoriController::class);
        
        Route::get('peminjaman/export', [Admin\PeminjamanController::class, 'export'])->name('peminjaman.export');
        Route::get('peminjaman/{peminjaman}/invoice', [Admin\PeminjamanController::class, 'invoice'])->name('peminjaman.invoice');
        Route::resource('peminjaman', Admin\PeminjamanController::class);
        
        Route::get('pengembalian/export', [Admin\PengembalianController::class, 'export'])->name('pengembalian.export');
        Route::resource('pengembalian', Admin\PengembalianController::class);
        
        Route::get('log-aktivitas', [Admin\LogAktivitasController::class, 'index'])->name('log.index');
    });
    
    // Petugas routes
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('katalog', [Petugas\KatalogController::class, 'index'])->name('katalog.index');
        Route::get('katalog/{alat}', [Petugas\KatalogController::class, 'show'])->name('katalog.show');
        
        Route::get('approval', [Petugas\ApprovalController::class, 'index'])->name('approval.index');
        Route::post('approval/{peminjaman}/approve', [Petugas\ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('approval/{peminjaman}/reject', [Petugas\ApprovalController::class, 'reject'])->name('approval.reject');
        Route::get('monitoring', [Petugas\MonitoringController::class, 'index'])->name('monitoring.index');
        Route::post('approval/{peminjaman}/pickup', [Petugas\ApprovalController::class, 'pickup'])->name('approval.pickup');
        Route::get('monitoring/{peminjaman}/return', [Petugas\MonitoringController::class, 'returnForm'])->name('monitoring.return.form');
        Route::post('monitoring/{peminjaman}/return', [Petugas\MonitoringController::class, 'pengembalian'])->name('monitoring.return');
        Route::get('laporan', [Petugas\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export', [Petugas\LaporanController::class, 'export'])->name('laporan.export');
    });
    
    // Peminjam routes
    Route::middleware(['role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
        Route::get('katalog', [Peminjam\KatalogController::class, 'index'])->name('katalog.index');
        Route::get('katalog/{alat}', [Peminjam\KatalogController::class, 'show'])->name('katalog.show');
        
        // Cart Routes
        Route::get('cart', [Peminjam\CartController::class, 'index'])->name('cart.index');
        Route::get('cart/checkout', function() { return redirect()->route('peminjam.cart.index'); });
        Route::post('cart/checkout', [Peminjam\CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('cart/{alat}', [Peminjam\CartController::class, 'add'])->name('cart.add');
        Route::patch('cart/{id}', [Peminjam\CartController::class, 'update'])->name('cart.update');
        Route::delete('cart/{id}', [Peminjam\CartController::class, 'remove'])->name('cart.remove');

        Route::get('peminjaman', [Peminjam\PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('peminjaman', [Peminjam\PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('pengembalian/{peminjaman}', [Peminjam\PengembalianController::class, 'show'])->name('pengembalian.show');
        Route::post('pengembalian/{peminjaman}', [Peminjam\PengembalianController::class, 'store'])->name('pengembalian.store');
    });
});

require __DIR__.'/auth.php';
