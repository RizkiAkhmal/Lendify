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
                'kategori_id' => 2, // Komputer
                'kode_alat' => 'LAP-001',
                'nama_alat' => 'Laptop ASUS ROG',
                'merk' => 'ASUS',
                'spesifikasi' => 'Intel i7-12700H, RAM 16GB DDR5, SSD 512GB NVMe, RTX 3060',

                'jumlah_total' => 5,
                'jumlah_tersedia' => 5,
            ],
           
            [
                'kategori_id' => 3, // Laboratorium
                'kode_alat' => 'MIC-001',
                'nama_alat' => 'Mikroskop Digital Olympus',
                'merk' => 'Olympus',
                'spesifikasi' => 'Pembesaran 40x-1000x, LED Illumination, USB Output',

                'jumlah_total' => 4,
                'jumlah_tersedia' => 4,
            ],
            [
                'kategori_id' => 4, // Olahraga
                'kode_alat' => 'BALL-001',
                'nama_alat' => 'Bola Basket Molten',
                'merk' => 'Molten',
                'spesifikasi' => 'Size 7, Kulit Sintetis, Official Match Ball',

                'jumlah_total' => 10,
                'jumlah_tersedia' => 10,
            ],
            [
                'kategori_id' => 2, // Komputer
                'kode_alat' => 'TAB-001',
                'nama_alat' => 'Tablet iPad Pro',
                'merk' => 'Apple',
                'spesifikasi' => '12.9 inch, M2 Chip, 256GB, Apple Pencil Support',

                'jumlah_total' => 3,
                'jumlah_tersedia' => 3,
            ],
            [
                'kategori_id' => 1, // Elektronik
                'kode_alat' => 'POW-001',
                'nama_alat' => 'Power Bank Anker 20000mAh',
                'merk' => 'Anker',
                'spesifikasi' => '20000mAh, USB-C PD, Quick Charge 3.0',

                'jumlah_total' => 15,
                'jumlah_tersedia' => 15,
            ],
       
        ];

        foreach ($alats as $alat) {
            Alat::create($alat);
        }
    }
}
