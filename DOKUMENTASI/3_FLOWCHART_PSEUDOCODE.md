# FLOWCHART & PSEUDOCODE
## Aplikasi Peminjaman Alat

### 1. FLOWCHART & PSEUDOCODE - PROSES LOGIN

#### 1.1 Flowchart Login
```
        [START]
            |
            v
    ┌───────────────────┐
    │ Tampilkan Form    │
    │ Login (Email &    │
    │ Password)         │
    └────────┬──────────┘
             |
             v
    ┌────────────────────┐
    │ User Input Email   │
    │ & Password         │
    └────────┬───────────┘
             |
             v
    ┌────────────────────┐
    │ Validasi Input:    │
    │ - Email required   │
    │ - Password required│
    └────────┬───────────┘
             |
         ┌───┴───┐
         │ Valid?│
         └───┬───┘
         No  |   Yes
         |   v
         |  ┌─────────────────────┐
         |  │ Cek ke Database:    │
         |  │ SELECT * FROM users │
         |  │ WHERE email = ?     │
         |  └──────────┬──────────┘
         |             |
         |        ┌────┴─────┐
         |        │ Found &  │
         |        │ Password │
         |        │ Match?   │
         |        └────┬─────┘
         |         No  |   Yes
         |         |   v
         |         |  ┌──────────────────┐
         |         |  │ Cek Status User  │
         |         |  └────────┬─────────┘
         |         |           |
         |         |      ┌────┴────┐
         |         |      │ Active? │
         |         |      └────┬────┘
         |         |       No  |   Yes
         |         |       |   v
         |         |       |  ┌───────────────────┐
         |         |       |  │ Create Session:   │
         |         |       |  │ - user_id         │
         |         |       |  │ - role            │
         |         |       |  │ - name            │
         |         |       |  └────────┬──────────┘
         |         |       |           |
         |         |       |           v
         |         |       |  ┌────────────────────┐
         |         |       |  │ Log Aktivitas:     │
         |         |       |  │ "User Login"       │
         |         |       |  └────────┬───────────┘
         |         |       |           |
         |         |       |           v
         |         |       |  ┌────────────────────┐
         |         |       |  │ Redirect By Role:  │
         |         |       |  │ - Admin → Dashboard│
         |         |       |  │ - Petugas→ Approval│
         |         |       |  │ - Peminjam→ Catalog│
         |         |       |  └────────┬───────────┘
         |         |       |           |
         v         v       v           v
    ┌─────────────────────────┐   [SUCCESS]
    │ Show Error Message:     │
    │ - Invalid credentials   │
    │ - Account inactive      │
    │ - Validation error      │
    └────────┬────────────────┘
             |
             v
      [BACK TO FORM]
```

#### 1.2 Pseudocode Login
```
FUNCTION processLogin(email, password):
    // Input Validation
    IF email IS EMPTY OR password IS EMPTY THEN
        RETURN error("Email dan password wajib diisi")
    END IF
    
    IF NOT isValidEmail(email) THEN
        RETURN error("Format email tidak valid")
    END IF
    
    // Database Query
    user = SELECT * FROM users WHERE email = email
    
    IF user NOT FOUND THEN
        logFailedAttempt(email, "User not found")
        RETURN error("Email atau password salah")
    END IF
    
    // Password Verification
    IF NOT verifyPassword(password, user.password) THEN
        logFailedAttempt(email, "Wrong password")
        RETURN error("Email atau password salah")
    END IF
    
    // Status Check
    IF user.status != 'active' THEN
        logFailedAttempt(email, "Inactive account")
        RETURN error("Akun Anda tidak aktif. Hubungi administrator.")
    END IF
    
    // Create Session
    session.set('user_id', user.id)
    session.set('user_name', user.name)
    session.set('user_role', user.role)
    session.set('user_email', user.email)
    
    // Log Activity
    INSERT INTO log_aktivitas (
        user_id, aksi, tabel, ip_address, user_agent, created_at
    ) VALUES (
        user.id, 'login', 'users', getClientIP(), getUserAgent(), NOW()
    )
    
    // Redirect Based on Role
    SWITCH user.role:
        CASE 'admin':
            RETURN redirect('/admin/dashboard')
        CASE 'petugas':
            RETURN redirect('/petugas/dashboard')
        CASE 'peminjam':
            RETURN redirect('/peminjam/dashboard')
        DEFAULT:
            RETURN redirect('/dashboard')
    END SWITCH
END FUNCTION

// Helper Functions
FUNCTION isValidEmail(email):
    pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    RETURN email MATCHES pattern
END FUNCTION

FUNCTION verifyPassword(inputPassword, hashedPassword):
    RETURN bcrypt_verify(inputPassword, hashedPassword)
END FUNCTION

FUNCTION logFailedAttempt(email, reason):
    INSERT INTO failed_login_attempts (
        email, reason, ip_address, created_at
    ) VALUES (
        email, reason, getClientIP(), NOW()
    )
END FUNCTION
```

---

### 2. FLOWCHART & PSEUDOCODE - PROSES PEMINJAMAN ALAT

#### 2.1 Flowchart Peminjaman
```
        [START - Peminjam Login]
            |
            v
    ┌──────────────────────┐
    │ Tampilkan Katalog    │
    │ Alat Tersedia        │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Peminjam Pilih Alat  │
    │ & Lihat Detail       │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Klik "Ajukan         │
    │ Peminjaman"          │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Isi Form Peminjaman: │
    │ - Tanggal Pinjam     │
    │ - Tanggal Kembali    │
    │ - Jumlah             │
    │ - Keperluan          │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Validasi Input       │
    └──────────┬───────────┘
               |
           ┌───┴───┐
           │ Valid?│
           └───┬───┘
           No  |   Yes
           |   v
           |  ┌──────────────────────┐
           |  │ Cek Stok Tersedia:   │
           |  │ jumlah_tersedia >= ? │
           |  └──────────┬───────────┘
           |             |
           |        ┌────┴─────┐
           |        │ Stok     │
           |        │ Cukup?   │
           |        └────┬─────┘
           |         No  |   Yes
           |         |   v
           |         |  ┌──────────────────────┐
           |         |  │ Simpan ke Database:  │
           |         |  │ INSERT peminjaman    │
           |         |  │ status = 'pending'   │
           |         |  └──────────┬───────────┘
           |         |             |
           |         |             v
           |         |  ┌──────────────────────┐
           |         |  │ Kirim Notifikasi     │
           |         |  │ ke Petugas           │
           |         |  └──────────┬───────────┘
           |         |             |
           |         |             v
           |         |  ┌──────────────────────┐
           |         |  │ Log Aktivitas        │
           |         |  └──────────┬───────────┘
           |         |             |
           v         v             v
    ┌───────────────────────┐  [SUCCESS]
    │ Tampilkan Error:      │  "Pengajuan berhasil"
    │ - Stok tidak cukup    │
    │ - Validasi gagal      │
    └───────┬───────────────┘
            |
            v
    [KEMBALI KE FORM]


    ===== PROSES APPROVAL OLEH PETUGAS =====
    
        [START - Petugas Login]
            |
            v
    ┌──────────────────────┐
    │ Lihat Daftar         │
    │ Peminjaman Pending   │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Petugas Pilih        │
    │ Peminjaman untuk     │
    │ di-Review            │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Lihat Detail:        │
    │ - Data Peminjam      │
    │ - Data Alat          │
    │ - Keperluan          │
    └──────────┬───────────┘
               |
          ┌────┴────┐
          │Keputusan│
          └────┬────┘
        Approve|  Reject
           |   v
           |  ┌──────────────────────┐
           |  │ UPDATE peminjaman:   │
           |  │ status = 'rejected'  │
           |  │ petugas_id = ?       │
           |  │ catatan = ?          │
           |  └──────────┬───────────┘
           |             |
           v             v
  ┌──────────────────────┐  [NOTIFICATION]
  │ Cek Stok Lagi        │  "Peminjaman ditolak"
  └──────────┬───────────┘
             |
        ┌────┴─────┐
        │ Stok OK? │
        └────┬─────┘
         No  |   Yes
         |   v
         |  ┌──────────────────────────┐
         |  │ BEGIN TRANSACTION        │
         |  │ 1. UPDATE peminjaman:    │
         |  │    status = 'dipinjam'   │
         |  │    petugas_id = ?        │
         |  │ 2. UPDATE alat:          │
         |  │    jumlah_tersedia -= ?  │
         |  │ COMMIT                   │
         |  └──────────┬───────────────┘
         |             |
         |             v
         |  ┌──────────────────────┐
         |  │ Log Aktivitas        │
         |  └──────────┬───────────┘
         |             |
         v             v
    [ROLLBACK]    [SUCCESS]
    "Stok habis"  "Peminjaman disetujui"
```

#### 2.2 Pseudocode Peminjaman
```
// ===== PENGAJUAN PEMINJAMAN (Peminjam) =====
FUNCTION ajukanPeminjaman(user_id, data):
    // Input Validation
    VALIDATE data:
        - alat_id REQUIRED
        - tanggal_peminjaman REQUIRED, >= TODAY
        - tanggal_kembali REQUIRED, > tanggal_peminjaman
        - jumlah REQUIRED, > 0
        - keperluan REQUIRED, MIN 10 chars
    
    IF validation_fails THEN
        RETURN error("Harap lengkapi semua field dengan benar")
    END IF
    
    // Get Alat Info
    alat = SELECT * FROM alat WHERE id = data.alat_id
    
    IF alat NOT FOUND THEN
        RETURN error("Alat tidak ditemukan")
    END IF
    
    // Check Stock Availability
    IF alat.jumlah_tersedia < data.jumlah THEN
        RETURN error("Stok tidak mencukupi. Tersedia: " + alat.jumlah_tersedia)
    END IF
    
    // Calculate Duration
    durasi_hari = DATEDIFF(data.tanggal_kembali, data.tanggal_peminjaman)
    
    IF durasi_hari > 30 THEN
        RETURN error("Durasi peminjaman maksimal 30 hari")
    END IF
    
    // Insert Peminjaman
    peminjaman_id = INSERT INTO peminjaman (
        user_id,
        alat_id,
        tanggal_pengajuan,
        tanggal_peminjaman,
        tanggal_kembali_rencana,
        jumlah,
        keperluan,
        status,
        created_at,
        updated_at
    ) VALUES (
        user_id,
        data.alat_id,
        TODAY(),
        data.tanggal_peminjaman,
        data.tanggal_kembali,
        data.jumlah,
        data.keperluan,
        'pending',
        NOW(),
        NOW()
    )
    
    // Log Activity
    logAktivitas(user_id, 'create', 'peminjaman', NULL, data)
    
    // Send Notification to Petugas
    sendNotification('petugas', 'Pengajuan peminjaman baru dari ' + user.name)
    
    RETURN success("Pengajuan peminjaman berhasil. Menunggu persetujuan petugas.")
END FUNCTION


// ===== APPROVAL PEMINJAMAN (Petugas) =====
FUNCTION approvePeminjaman(peminjaman_id, petugas_id, decision, catatan):
    // Get Peminjaman Data
    peminjaman = SELECT * FROM peminjaman WHERE id = peminjaman_id
    
    IF peminjaman NOT FOUND THEN
        RETURN error("Data peminjaman tidak ditemukan")
    END IF
    
    IF peminjaman.status != 'pending' THEN
        RETURN error("Peminjaman sudah diproses sebelumnya")
    END IF
    
    // Get Alat Data
    alat = SELECT * FROM alat WHERE id = peminjaman.alat_id
    
    IF decision == 'reject' THEN
        // Reject Peminjaman
        UPDATE peminjaman SET
            status = 'rejected',
            petugas_id = petugas_id,
            catatan_petugas = catatan,
            updated_at = NOW()
        WHERE id = peminjaman_id
        
        logAktivitas(petugas_id, 'reject_peminjaman', 'peminjaman', peminjaman, updated_data)
        sendNotification(peminjaman.user_id, 'Peminjaman ditolak: ' + catatan)
        
        RETURN success("Peminjaman berhasil ditolak")
    
    ELSE IF decision == 'approve' THEN
        // Check Stock Again
        IF alat.jumlah_tersedia < peminjaman.jumlah THEN
            RETURN error("Stok tidak mencukupi saat ini")
        END IF
        
        // Start Transaction
        BEGIN TRANSACTION
        
        TRY:
            // Update Peminjaman Status
            UPDATE peminjaman SET
                status = 'dipinjam',
                petugas_id = petugas_id,
                catatan_petugas = catatan,
                updated_at = NOW()
            WHERE id = peminjaman_id
            
            // Reduce Stock
            UPDATE alat SET
                jumlah_tersedia = jumlah_tersedia - peminjaman.jumlah,
                updated_at = NOW()
            WHERE id = peminjaman.alat_id
            
            // Commit Transaction
            COMMIT
            
            // Log Activity
            logAktivitas(petugas_id, 'approve_peminjaman', 'peminjaman', peminjaman, updated_data)
            
            // Send Notification
            sendNotification(peminjaman.user_id, 'Peminjaman disetujui. Silakan ambil alat.')
            
            RETURN success("Peminjaman berhasil disetujui")
        
        CATCH error:
            ROLLBACK
            RETURN error("Gagal memproses peminjaman: " + error.message)
        END TRY
    END IF
END FUNCTION
```

---

### 3. FLOWCHART & PSEUDOCODE - PROSES PENGEMBALIAN & PERHITUNGAN DENDA

#### 3.1 Flowchart Pengembalian
```
        [START - Peminjam Login]
            |
            v
    ┌──────────────────────┐
    │ Lihat Daftar         │
    │ Peminjaman Aktif     │
    │ (status: dipinjam)   │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Pilih Peminjaman     │
    │ untuk Dikembalikan   │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Isi Form Pengembalian│
    │ - Tanggal Kembali    │
    │ - Kondisi Alat       │
    │ - Catatan (optional) │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ HITUNG KETERLAMBATAN │
    │ keterlambatan =      │
    │ tanggal_kembali -    │
    │ tanggal_rencana      │
    └──────────┬───────────┘
               |
          ┌────┴────┐
          │Terlambat│
          └────┬────┘
          No   |   Yes
          |    v
          |   ┌──────────────────────┐
          |   │ HITUNG DENDA         │
          |   │ denda_telat =        │
          |   │ keterlambatan * 5000 │
          |   └──────────┬───────────┘
          |              |
          v              v
    ┌──────────────────────┐
    │ HITUNG DENDA KERUSAKAN│
    └──────────┬───────────┘
               |
        ┌──────┴──────┐
        │   Kondisi   │
        └──────┬──────┘
      Baik│Rusak│Rusak
          │Ringan│Berat
          |  |   |
          v  v   v
       0  50K 200K
          |  |   |
          └──┴───┘
             |
             v
    ┌──────────────────────┐
    │ TOTAL DENDA =        │
    │ denda_telat +        │
    │ denda_kerusakan      │
    └──────────┬───────────┘
               |
               v
    ┌──────────────────────┐
    │ Tampilkan Ringkasan: │
    │ - Keterlambatan: X   │
    │ - Denda Telat: Rp X  │
    │ - Denda Rusak: Rp X  │
    │ - TOTAL: Rp X        │
    └──────────┬───────────┘
               |
          ┌────┴────┐
          │Konfirmasi│
          └────┬────┘
         Batal |   OK
          |    v
          |   ┌──────────────────────────┐
          |   │ BEGIN TRANSACTION        │
          |   │ 1. INSERT pengembalian   │
          |   │ 2. UPDATE peminjaman:    │
          |   │    status = 'selesai'    │
          |   │ 3. UPDATE alat:          │
          |   │    jumlah_tersedia += X  │
          |   │ 4. UPDATE kondisi alat   │
          |   │    jika rusak            │
          |   │ COMMIT                   │
          |   └──────────┬───────────────┘
          |              |
          |              v
          |   ┌──────────────────────┐
          |   │ Log Aktivitas        │
          |   └──────────┬───────────┘
          |              |
          |         ┌────┴─────┐
          |         │Ada Denda?│
          |         └────┬─────┘
          |          No  |   Yes
          |          |   v
          |          |  ┌──────────────────────┐
          |          |  │ Generate Invoice     │
          |          |  │ Pembayaran Denda     │
          |          |  └──────────┬───────────┘
          |          |             |
          v          v             v
    [CANCELLED]  [SUCCESS]    [SUCCESS + INVOICE]
                 "Dikembalikan" "Dikembalikan,
                 "tanpa denda"   Denda: Rp X"
```

#### 3.2 Pseudocode Pengembalian
```
FUNCTION prosesPengembalian(peminjaman_id, user_id, data):
    // Get Peminjaman Data
    peminjaman = SELECT peminjaman.*, alat.nama_alat, alat.jumlah_tersedia
                 FROM peminjaman
                 JOIN alat ON peminjaman.alat_id = alat.id
                 WHERE peminjaman.id = peminjaman_id
                   AND peminjaman.user_id = user_id
    
    IF peminjaman NOT FOUND THEN
        RETURN error("Data peminjaman tidak ditemukan")
    END IF
    
    IF peminjaman.status != 'dipinjam' THEN
        RETURN error("Peminjaman ini tidak dalam status dipinjam")
    END IF
    
    // Validate Input
    VALIDATE data:
        - tanggal_kembali REQUIRED
        - kondisi_alat REQUIRED IN ['baik', 'rusak_ringan', 'rusak_berat']
    
    IF data.tanggal_kembali < peminjaman.tanggal_peminjaman THEN
        RETURN error("Tanggal kembali tidak valid")
    END IF
    
    // Calculate Late Days
    keterlambatan_hari = DATEDIFF(data.tanggal_kembali, peminjaman.tanggal_kembali_rencana)
    
    IF keterlambatan_hari < 0 THEN
        keterlambatan_hari = 0
    END IF
    
    // Calculate Late Fine (Rp 5.000/day)
    denda_keterlambatan = keterlambatan_hari * 5000
    
    // Calculate Damage Fine
    denda_kerusakan = 0
    SWITCH data.kondisi_alat:
        CASE 'rusak_ringan':
            denda_kerusakan = 50000
            BREAK
        CASE 'rusak_berat':
            denda_kerusakan = 200000
            BREAK
        DEFAULT:
            denda_kerusakan = 0
    END SWITCH
    
    // Total Fine
    total_denda = denda_keterlambatan + denda_kerusakan
    
    // Start Transaction
    BEGIN TRANSACTION
    
    TRY:
        // 1. Insert Pengembalian Record
        INSERT INTO pengembalian (
            peminjaman_id,
            tanggal_kembali_aktual,
            kondisi_alat,
            keterlambatan_hari,
            denda,
            catatan,
            created_at,
            updated_at
        ) VALUES (
            peminjaman_id,
            data.tanggal_kembali,
            data.kondisi_alat,
            keterlambatan_hari,
            total_denda,
            data.catatan,
            NOW(),
            NOW()
        )
        
        // 2. Update Peminjaman Status
        UPDATE peminjaman SET
            status = 'selesai',
            updated_at = NOW()
        WHERE id = peminjaman_id
        
        // 3. Return Stock
        UPDATE alat SET
            jumlah_tersedia = jumlah_tersedia + peminjaman.jumlah,
            updated_at = NOW()
        WHERE id = peminjaman.alat_id
        
        // 4. Update Alat Condition if Damaged
        IF data.kondisi_alat IN ['rusak_ringan', 'rusak_berat'] THEN
            UPDATE alat SET
                kondisi = data.kondisi_alat,
                updated_at = NOW()
            WHERE id = peminjaman.alat_id
        END IF
        
        // Commit Transaction
        COMMIT
        
        // Log Activity
        logAktivitas(user_id, 'pengembalian', 'pengembalian', NULL, {
            peminjaman_id: peminjaman_id,
            keterlambatan: keterlambatan_hari,
            denda: total_denda,
            kondisi: data.kondisi_alat
        })
        
        // Send Notification to Petugas
        sendNotification('petugas', 'Pengembalian alat ' + peminjaman.nama_alat + 
                        ' oleh ' + user.name + '. Denda: Rp ' + total_denda)
        
        // Prepare Response
        response = {
            success: TRUE,
            message: 'Pengembalian berhasil diproses',
            data: {
                peminjaman_id: peminjaman_id,
                tanggal_kembali: data.tanggal_kembali,
                keterlambatan_hari: keterlambatan_hari,
                denda_keterlambatan: denda_keterlambatan,
                denda_kerusakan: denda_kerusakan,
                total_denda: total_denda,
                kondisi_alat: data.kondisi_alat
            }
        }
        
        // Generate Invoice if there's fine
        IF total_denda > 0 THEN
            invoice = generateInvoice(peminjaman_id, total_denda)
            response.data.invoice = invoice
            response.message += '. Silakan bayar denda sebesar Rp ' + formatNumber(total_denda)
        END IF
        
        RETURN response
        
    CATCH error:
        ROLLBACK
        logError('pengembalian_error', error)
        RETURN error("Gagal memproses pengembalian: " + error.message)
    END TRY
END FUNCTION


// ===== HELPER FUNCTIONS =====
FUNCTION hitungDenda(peminjaman_id):
    peminjaman = SELECT * FROM peminjaman WHERE id = peminjaman_id
    
    IF peminjaman NOT FOUND THEN
        RETURN 0
    END IF
    
    keterlambatan = DATEDIFF(CURDATE(), peminjaman.tanggal_kembali_rencana)
    
    IF keterlambatan <= 0 THEN
        RETURN 0
    END IF
    
    RETURN keterlambatan * 5000
END FUNCTION

FUNCTION generateInvoice(peminjaman_id, total_denda):
    invoice_number = 'INV-' + DATE('Ymd') + '-' + peminjaman_id
    
    invoice = {
        invoice_number: invoice_number,
        peminjaman_id: peminjaman_id,
        total: total_denda,
        status: 'unpaid',
        due_date: DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    }
    
    // Save invoice to database (optional)
    INSERT INTO invoices (invoice_number, peminjaman_id, amount, status, due_date)
    VALUES (invoice.invoice_number, invoice.peminjaman_id, invoice.total, 
            invoice.status, invoice.due_date)
    
    RETURN invoice
END FUNCTION

FUNCTION formatNumber(number):
    RETURN 'Rp ' + number.toLocaleString('id-ID')
END FUNCTION
```

### 4. INPUT, PROSES, OUTPUT (IPO) Documentation

#### 4.1 Modul Login
**INPUT:**
- Email (string, required, format email)
- Password (string, required, min 6 characters)

**PROSES:**
1. Validasi format email dan password
2. Query database untuk cari user berdasarkan email
3. Verifikasi password menggunakan bcrypt
4. Cek status akun (active/inactive)
5. Buat session untuk user
6. Log aktivitas login
7. Redirect berdasarkan role

**OUTPUT:**
- Success: Redirect ke dashboard sesuai role
- Error: Pesan error (invalid credentials, account inactive, dll)

#### 4.2 Modul Peminjaman
**INPUT:**
- User ID (dari session)
- Alat ID (integer, required)
- Tanggal Peminjaman (date, required, >= today)
- Tanggal Kembali (date, required, > tanggal peminjaman)
- Jumlah (integer, required, > 0)
- Keperluan (text, required, min 10 chars)

**PROSES:**
1. Validasi semua input
2. Cek ketersediaan stok alat
3. Validasi durasi peminjaman (max 30 hari)
4. Insert data ke tabel peminjaman dengan status 'pending'
5. Log aktivitas
6. Kirim notifikasi ke petugas

**OUTPUT:**
- Success: Pesan sukses + data peminjaman
- Error: Pesan error (stok habis, validasi gagal, dll)

#### 4.3 Modul Pengembalian
**INPUT:**
- Peminjaman ID (integer, required)
- User ID (dari session)
- Tanggal Kembali (datetime, required)
- Kondisi Alat (enum: baik/rusak_ringan/rusak_berat, required)
- Catatan (text, optional)

**PROSES:**
1. Validasi peminjaman exists dan status = 'dipinjam'
2. Hitung keterlambatan (hari)
3. Hitung denda keterlambatan (keterlambatan × Rp 5.000)
4. Hitung denda kerusakan (rusak ringan: Rp 50.000, rusak berat: Rp 200.000)
5. Total denda = denda keterlambatan + denda kerusakan
6. BEGIN TRANSACTION:
   - Insert record pengembalian
   - Update status peminjaman = 'selesai'
   - Update stok alat (jumlah_tersedia + jumlah_pinjam)
   - Update kondisi alat jika rusak
7. COMMIT TRANSACTION
8. Log aktivitas
9. Generate invoice jika ada denda
10. Kirim notifikasi ke petugas

**OUTPUT:**
- Success: Data pengembalian + rincian denda + invoice (jika ada)
- Error: Pesan error (peminjaman tidak valid, transaction failed, dll)
