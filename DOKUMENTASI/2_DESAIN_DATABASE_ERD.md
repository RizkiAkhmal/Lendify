# DESAIN DATABASE & ERD
## Aplikasi Peminjaman Alat

### 1. Entity Relationship Diagram (ERD)

```
┌─────────────────────────┐
│        USERS            │
├─────────────────────────┤
│ PK id                   │
│    name                 │
│    email (unique)       │
│    password             │
│    role (enum)          │
│    phone                │
│    address              │
│    status               │
│    created_at           │
│    updated_at           │
└──────────┬──────────────┘
           │ 1
           │
           │ ∞
┌──────────┴──────────────┐         ┌─────────────────────────┐
│    PEMINJAMAN           │    ∞    │      KATEGORI           │
├─────────────────────────┤   ────  ├─────────────────────────┤
│ PK id                   │    1    │ PK id                   │
│ FK user_id              │         │    nama_kategori        │
│ FK alat_id          ────┼────┐    │    deskripsi            │
│ FK petugas_id (null)    │    │    │    created_at           │
│    tanggal_pengajuan    │    │    │    updated_at           │
│    tanggal_peminjaman   │    │    └─────────┬───────────────┘
│    tanggal_kembali_     │    │              │ 1
│       rencana           │    │              │
│    jumlah               │    │              │ ∞
│    keperluan            │    │    ┌─────────┴───────────────┐
│    status (enum)        │    │    │        ALAT             │
│    catatan_petugas      │    └────┤─────────────────────────┤
│    created_at           │         │ PK id                   │
│    updated_at           │         │ FK kategori_id          │
└──────────┬──────────────┘         │    kode_alat (unique)   │
           │ 1                      │    nama_alat            │
           │                        │    merk                 │
           │ 1                      │    spesifikasi          │
┌──────────┴──────────────┐         │    kondisi (enum)       │
│    PENGEMBALIAN         │         │    jumlah_total         │
├─────────────────────────┤         │    jumlah_tersedia      │
│ PK id                   │         │    foto                 │
│ FK peminjaman_id        │         │    created_at           │
│    tanggal_kembali_     │         │    updated_at           │
│       aktual            │         └─────────────────────────┘
│    kondisi_alat (enum)  │
│    keterlambatan_hari   │
│    denda                │
│    catatan              │
│    created_at           │
│    updated_at           │
└─────────────────────────┘

┌─────────────────────────┐
│    LOG_AKTIVITAS        │
├─────────────────────────┤
│ PK id                   │
│ FK user_id              │
│    aksi                 │
│    tabel                │
│    data_lama (JSON)     │
│    data_baru (JSON)     │
│    ip_address           │
│    user_agent           │
│    created_at           │
└─────────────────────────┘
```

### 2. Relasi Antar Tabel

#### 2.1 Users → Peminjaman (One to Many)
- Satu user dapat memiliki banyak peminjaman
- FK: `peminjaman.user_id` → `users.id`
- ON DELETE: RESTRICT (tidak bisa hapus user yang punya riwayat peminjaman)

#### 2.2 Users (Petugas) → Peminjaman (One to Many)
- Satu petugas dapat meng-approve banyak peminjaman
- FK: `peminjaman.petugas_id` → `users.id`
- ON DELETE: SET NULL

#### 2.3 Kategori → Alat (One to Many)
- Satu kategori dapat memiliki banyak alat
- FK: `alat.kategori_id` → `kategori.id`
- ON DELETE: RESTRICT

#### 2.4 Alat → Peminjaman (One to Many)
- Satu alat dapat dipinjam berkali-kali
- FK: `peminjaman.alat_id` → `alat.id`
- ON DELETE: RESTRICT

#### 2.5 Peminjaman → Pengembalian (One to One)
- Satu peminjaman hanya punya satu pengembalian
- FK: `pengembalian.peminjaman_id` → `peminjaman.id`
- ON DELETE: CASCADE

#### 2.6 Users → Log Aktivitas (One to Many)
- Satu user dapat punya banyak log aktivitas
- FK: `log_aktivitas.user_id` → `users.id`
- ON DELETE: SET NULL

### 3. Tipe Data Detail

#### 3.1 Tabel: users
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
name            VARCHAR(255) NOT NULL
email           VARCHAR(255) NOT NULL UNIQUE
password        VARCHAR(255) NOT NULL
role            ENUM('admin', 'petugas', 'peminjam') DEFAULT 'peminjam'
phone           VARCHAR(20) NULL
address         TEXT NULL
status          ENUM('active', 'inactive') DEFAULT 'active'
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### 3.2 Tabel: kategori
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
nama_kategori   VARCHAR(100) NOT NULL
deskripsi       TEXT NULL
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### 3.3 Tabel: alat
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
kategori_id     BIGINT UNSIGNED NOT NULL
kode_alat       VARCHAR(50) NOT NULL UNIQUE
nama_alat       VARCHAR(255) NOT NULL
merk            VARCHAR(100) NULL
spesifikasi     TEXT NULL
kondisi         ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik'
jumlah_total    INT UNSIGNED NOT NULL DEFAULT 0
jumlah_tersedia INT UNSIGNED NOT NULL DEFAULT 0
foto            VARCHAR(255) NULL
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL

FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT
INDEX idx_kode_alat (kode_alat)
INDEX idx_kategori (kategori_id)
```

#### 3.4 Tabel: peminjaman
```sql
id                      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
user_id                 BIGINT UNSIGNED NOT NULL
alat_id                 BIGINT UNSIGNED NOT NULL
petugas_id              BIGINT UNSIGNED NULL
tanggal_pengajuan       DATE NOT NULL
tanggal_peminjaman      DATE NULL
tanggal_kembali_rencana DATE NULL
jumlah                  INT UNSIGNED NOT NULL DEFAULT 1
keperluan               TEXT NOT NULL
status                  ENUM('pending','approved','rejected','dipinjam','selesai') 
                        DEFAULT 'pending'
catatan_petugas         TEXT NULL
created_at              TIMESTAMP NULL
updated_at              TIMESTAMP NULL

FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
FOREIGN KEY (alat_id) REFERENCES alat(id) ON DELETE RESTRICT
FOREIGN KEY (petugas_id) REFERENCES users(id) ON DELETE SET NULL
INDEX idx_user (user_id)
INDEX idx_alat (alat_id)
INDEX idx_status (status)
INDEX idx_tanggal (tanggal_peminjaman)
```

#### 3.5 Tabel: pengembalian
```sql
id                      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
peminjaman_id           BIGINT UNSIGNED NOT NULL UNIQUE
tanggal_kembali_aktual  DATETIME NOT NULL
kondisi_alat            ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik'
keterlambatan_hari      INT DEFAULT 0
denda                   DECIMAL(10,2) DEFAULT 0.00
catatan                 TEXT NULL
created_at              TIMESTAMP NULL
updated_at              TIMESTAMP NULL

FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE
INDEX idx_peminjaman (peminjaman_id)
```

#### 3.6 Tabel: log_aktivitas
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
user_id         BIGINT UNSIGNED NULL
aksi            VARCHAR(50) NOT NULL
tabel           VARCHAR(50) NOT NULL
data_lama       JSON NULL
data_baru       JSON NULL
ip_address      VARCHAR(45) NULL
user_agent      TEXT NULL
created_at      TIMESTAMP NULL

FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
INDEX idx_user (user_id)
INDEX idx_aksi (aksi)
INDEX idx_tabel (tabel)
INDEX idx_created (created_at)
```

### 4. Constraint & Index Strategy

#### 4.1 Unique Constraints
- `users.email` - Mencegah duplikasi email
- `alat.kode_alat` - Kode alat harus unik
- `pengembalian.peminjaman_id` - Satu peminjaman satu pengembalian

#### 4.2 Index untuk Performance
- Index pada Foreign Keys untuk JOIN optimization
- Index pada kolom yang sering di-filter (status, tanggal)
- Index pada kolom untuk search (kode_alat, email)

#### 4.3 Default Values
- `users.role` = 'peminjam'
- `users.status` = 'active'
- `alat.kondisi` = 'baik'
- `peminjaman.status` = 'pending'
- `pengembalian.keterlambatan_hari` = 0
- `pengembalian.denda` = 0.00

### 5. Database Normalization

#### Tingkat Normalisasi: 3NF (Third Normal Form)

**1NF (First Normal Form):**
- ✓ Semua kolom atomik (tidak ada multi-value)
- ✓ Setiap tabel punya primary key
- ✓ Tidak ada duplicate rows

**2NF (Second Normal Form):**
- ✓ Memenuhi 1NF
- ✓ Tidak ada partial dependency
- ✓ Non-key attributes fully dependent on primary key

**3NF (Third Normal Form):**
- ✓ Memenuhi 2NF
- ✓ Tidak ada transitive dependency
- ✓ Non-key attributes hanya dependent pada primary key

**Contoh Pemisahan:**
- Kategori dipisah dari Alat (menghindari redundansi nama kategori)
- Pengembalian dipisah dari Peminjaman (data pengembalian optional)
- Log Aktivitas terpisah untuk audit trail

### 6. Data Integrity Rules

#### 6.1 Entity Integrity
- Setiap tabel memiliki Primary Key yang NOT NULL dan UNIQUE
- Auto-increment untuk kemudahan

#### 6.2 Referential Integrity
- Semua Foreign Key memiliki constraint yang jelas
- ON DELETE policy sesuai business logic:
  - RESTRICT: Mencegah penghapusan data berelasi
  - CASCADE: Hapus data terkait
  - SET NULL: Set NULL jika dihapus

#### 6.3 Domain Integrity
- ENUM untuk kolom dengan nilai terbatas
- NOT NULL untuk kolom wajib
- DEFAULT value untuk inisialisasi

### 7. Stored Procedures & Functions (Akan dibuat)

#### 7.1 Stored Procedures
1. `sp_approve_peminjaman(peminjaman_id, petugas_id, status)`
2. `sp_proses_pengembalian(peminjaman_id, kondisi, tanggal_kembali)`
3. `sp_hitung_denda(peminjaman_id)`

#### 7.2 Functions
1. `fn_get_stok_tersedia(alat_id) RETURNS INT`
2. `fn_hitung_keterlambatan(peminjaman_id) RETURNS INT`
3. `fn_total_denda_user(user_id, tahun, bulan) RETURNS DECIMAL`

#### 7.3 Triggers
1. `trg_after_peminjaman_approved` - Kurangi stok alat
2. `trg_after_pengembalian` - Tambah stok alat
3. `trg_before_peminjaman` - Validasi stok tersedia
4. `trg_log_aktivitas` - Catat setiap perubahan data
