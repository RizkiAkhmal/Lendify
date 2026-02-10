# ANALISIS KEBUTUHAN SISTEM
## Aplikasi Peminjaman Alat

### 1. Deskripsi Umum
Aplikasi Peminjaman Alat adalah sistem berbasis web untuk mengelola peminjaman peralatan dengan 3 level pengguna: Admin, Petugas, dan Peminjam.

### 2. User Roles & Permissions

#### 2.1 Admin
**Hak Akses Penuh:**
- Login/Logout
- CRUD User (Create, Read, Update, Delete pengguna)
- CRUD Alat (Kelola data peralatan)
- CRUD Kategori (Kelola kategori alat)
- CRUD Data Peminjaman (Kelola semua data peminjaman)
- CRUD Pengembalian (Kelola data pengembalian)
- Log Aktivitas (Melihat semua aktivitas sistem)

#### 2.2 Petugas
**Hak Akses Terbatas:**
- Login/Logout
- Menyetujui Peminjaman (Approve/Reject pengajuan)
- Memantau Pengembalian (Monitor status pengembalian)
- Mencetak Laporan (Generate laporan peminjaman)

#### 2.3 Peminjam
**Hak Akses Terbatas:**
- Login/Logout
- Melihat Daftar Alat (Browse katalog alat)
- Mengajukan Peminjaman (Request peminjaman alat)
- Mengembalikan Alat (Proses pengembalian)

### 3. Entitas Utama

#### 3.1 Users
- id
- name
- email
- password
- role (admin/petugas/peminjam)
- phone
- address
- status (active/inactive)
- created_at
- updated_at

#### 3.2 Kategori
- id
- nama_kategori
- deskripsi
- created_at
- updated_at

#### 3.3 Alat
- id
- kategori_id (FK)
- kode_alat (unique)
- nama_alat
- merk
- spesifikasi
- kondisi (baik/rusak ringan/rusak berat)
- jumlah_total
- jumlah_tersedia
- foto
- created_at
- updated_at

#### 3.4 Peminjaman
- id
- user_id (FK - peminjam)
- alat_id (FK)
- petugas_id (FK - yang approve, nullable)
- tanggal_pengajuan
- tanggal_peminjaman
- tanggal_kembali_rencana
- jumlah
- keperluan
- status (pending/approved/rejected/dipinjam/selesai)
- catatan_petugas
- created_at
- updated_at

#### 3.5 Pengembalian
- id
- peminjaman_id (FK)
- tanggal_kembali_aktual
- kondisi_alat (baik/rusak ringan/rusak berat)
- keterlambatan_hari
- denda
- catatan
- created_at
- updated_at

#### 3.6 Log Aktivitas
- id
- user_id (FK)
- aksi
- tabel
- data_lama (JSON)
- data_baru (JSON)
- ip_address
- user_agent
- created_at

### 4. Business Rules

#### 4.1 Peminjaman
- Peminjam harus login untuk mengajukan peminjaman
- Alat harus tersedia (jumlah_tersedia > 0)
- Status awal peminjaman: "pending"
- Petugas dapat approve/reject peminjaman
- Setelah approved, status menjadi "dipinjam"
- Stok alat berkurang saat status "dipinjam"

#### 4.2 Pengembalian
- Pengembalian hanya untuk peminjaman dengan status "dipinjam"
- Sistem menghitung keterlambatan otomatis
- Denda: Rp 5.000/hari untuk keterlambatan
- Denda tambahan untuk kerusakan:
  - Rusak ringan: Rp 50.000
  - Rusak berat: Rp 200.000
- Stok alat bertambah saat pengembalian
- Status peminjaman berubah menjadi "selesai"

#### 4.3 Keamanan
- Password di-hash menggunakan bcrypt
- Middleware authentication untuk semua route protected
- Middleware authorization berdasarkan role
- CSRF protection untuk semua form
- Session timeout setelah 120 menit inaktif

### 5. Functional Requirements

#### 5.1 Modul Login
- Form login dengan email & password
- Validasi credentials
- Redirect berdasarkan role
- Remember me option
- Session management

#### 5.2 Modul Dashboard
- Dashboard Admin: Statistik lengkap sistem
- Dashboard Petugas: Pengajuan pending, statistik peminjaman
- Dashboard Peminjam: Riwayat peminjaman, alat populer

#### 5.3 Modul CRUD
- Semua modul CRUD dengan validasi
- Soft delete untuk data penting
- Audit trail untuk perubahan data
- Search & filter functionality
- Pagination untuk list data

#### 5.4 Modul Laporan
- Laporan peminjaman per periode
- Laporan alat populer
- Laporan denda
- Laporan per user
- Export ke PDF/Excel

### 6. Non-Functional Requirements

#### 6.1 Performance
- Page load time < 2 detik
- Database query optimization
- Lazy loading untuk gambar
- Caching untuk data statis

#### 6.2 Usability
- Responsive design (mobile-friendly)
- Intuitive user interface
- Notifikasi user-friendly
- Breadcrumb navigation

#### 6.3 Reliability
- Database backup otomatis
- Error handling yang baik
- Transaction untuk operasi critical
- Rollback mechanism

### 7. Technology Stack
- **Backend:** Laravel 11.x
- **Database:** MySQL 8.0
- **Frontend:** Blade Template, Bootstrap 5
- **Authentication:** Laravel Breeze/Jetstream
- **Additional:** DataTables, SweetAlert2, Chart.js
