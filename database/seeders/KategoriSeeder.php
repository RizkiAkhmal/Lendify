<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi' => 'Peralatan elektronik umum'
            ],
            [
                'nama_kategori' => 'Komputer',
                'deskripsi' => 'Perangkat komputer dan aksesoris'
            ],
            [
                'nama_kategori' => 'Laboratorium',
                'deskripsi' => 'Alat-alat laboratorium'
            ],
            [
                'nama_kategori' => 'Olahraga',
                'deskripsi' => 'Peralatan olahraga'
            ],

        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
