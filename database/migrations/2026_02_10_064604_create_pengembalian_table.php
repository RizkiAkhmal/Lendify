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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjaman')->cascadeOnDelete();
            $table->dateTime('tanggal_kembali_aktual');
            $table->enum('kondisi_alat', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->integer('keterlambatan_hari')->default(0);
            $table->decimal('denda', 10, 2)->default(0.00);
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index('peminjaman_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
