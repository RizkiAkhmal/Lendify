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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('kode_alat', 50)->unique();
            $table->string('nama_alat');
            $table->string('merk', 100)->nullable();
            $table->text('spesifikasi')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->unsignedInteger('jumlah_total')->default(0);
            $table->unsignedInteger('jumlah_tersedia')->default(0);
            $table->string('foto')->nullable();
            $table->timestamps();
            
            $table->index('kode_alat');
            $table->index('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
