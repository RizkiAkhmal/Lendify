<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('alat_id')->constrained('alat')->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_peminjaman')->nullable();
            $table->date('tanggal_kembali_rencana')->nullable();
            $table->unsignedInteger('jumlah')->default(1);
            $table->text('keperluan');
            $table->enum('status', ['pending', 'approved', 'rejected', 'dipinjam', 'selesai'])->default('pending');
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('alat_id');
            $table->index('status');
            $table->index('tanggal_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
