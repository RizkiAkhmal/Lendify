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
                'kondisi' => 'baik',
                'jumlah_total' => 5,
                'jumlah_tersedia' => 5,
            ],
            [
                'kategori_id' => 5, // Multimedia
                'kode_alat' => 'PRJ-001',
                'nama_alat' => 'Proyektor Epson EB-X06',
                'merk' => 'Epson',
                'spesifikasi' => 'Full HD 1080p, 3600 lumens, HDMI, VGA',
                'kondisi' => 'baik',
                'jumlah_total' => 3,
                'jumlah_tersedia' => 3,
            ],
            [
                'kategori_id' => 5, // Multimedia
                'kode_alat' => 'CAM-001',
                'nama_alat' => 'Kamera DSLR Canon EOS 80D',
                'merk' => 'Canon',
                'spesifikasi' => '24.2 MP, Video Full HD, WiFi, Dual Pixel CMOS AF',
                'kondisi' => 'baik',
                'jumlah_total' => 2,
                'jumlah_tersedia' => 2,
            ],
            [
                'kategori_id' => 3, // Laboratorium
                'kode_alat' => 'MIC-001',
                'nama_alat' => 'Mikroskop Digital Olympus',
                'merk' => 'Olympus',
                'spesifikasi' => 'Pembesaran 40x-1000x, LED Illumination, USB Output',
                'kondisi' => 'baik',
                'jumlah_total' => 4,
                'jumlah_tersedia' => 4,
            ],
            [
                'kategori_id' => 4, // Olahraga
                'kode_alat' => 'BALL-001',
                'nama_alat' => 'Bola Basket Molten',
                'merk' => 'Molten',
                'spesifikasi' => 'Size 7, Kulit Sintetis, Official Match Ball',
                'kondisi' => 'baik',
                'jumlah_total' => 10,
                'jumlah_tersedia' => 10,
            ],
            [
                'kategori_id' => 2, // Komputer
                'kode_alat' => 'TAB-001',
                'nama_alat' => 'Tablet iPad Pro',
                'merk' => 'Apple',
                'spesifikasi' => '12.9 inch, M2 Chip, 256GB, Apple Pencil Support',
                'kondisi' => 'baik',
                'jumlah_total' => 3,
                'jumlah_tersedia' => 3,
            ],
            [
                'kategori_id' => 1, // Elektronik
                'kode_alat' => 'POW-001',
                'nama_alat' => 'Power Bank Anker 20000mAh',
                'merk' => 'Anker',
                'spesifikasi' => '20000mAh, USB-C PD, Quick Charge 3.0',
                'kondisi' => 'baik',
                'jumlah_total' => 15,
                'jumlah_tersedia' => 15,
            ],
            [
                'kategori_id' => 5, // Multimedia
                'kode_alat' => 'MIC-002',
                'nama_alat' => 'Microphone Wireless Shure',
                'merk' => 'Shure',
                'spesifikasi' => 'Wireless Handheld, UHF Band, 100m Range',
                'kondisi' => 'baik',
                'jumlah_total' => 6,
                'jumlah_tersedia' => 6,
            ],
        ];

        foreach ($alats as $alat) {
            Alat::create($alat);
        }
    }
}
