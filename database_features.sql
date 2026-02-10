-- ============================================
-- DATABASE FEATURES: STORED PROCEDURES, FUNCTIONS, TRIGGERS
-- Aplikasi Peminjaman Alat
-- ============================================

USE peminjaman_alat;

-- ============================================
-- 1. STORED PROCEDURES
-- ============================================

-- SP 1: Approve Peminjaman
DELIMITER //
CREATE PROCEDURE sp_approve_peminjaman(
    IN p_peminjaman_id BIGINT UNSIGNED,
    IN p_petugas_id BIGINT UNSIGNED,
    IN p_catatan TEXT
)
BEGIN
    DECLARE v_alat_id BIGINT UNSIGNED;
    DECLARE v_jumlah INT UNSIGNED;
    DECLARE v_stok_tersedia INT UNSIGNED;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Gagal approve peminjaman';
    END;

    START TRANSACTION;

    -- Ambil data peminjaman
    SELECT alat_id, jumlah INTO v_alat_id, v_jumlah
    FROM peminjaman
    WHERE id = p_peminjaman_id AND status = 'pending';

    -- Cek stok tersedia
    SELECT jumlah_tersedia INTO v_stok_tersedia
    FROM alat
    WHERE id = v_alat_id;

    IF v_stok_tersedia < v_jumlah THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Stok alat tidak mencukupi';
    END IF;

    -- Update status peminjaman
    UPDATE peminjaman
    SET status = 'dipinjam',
        petugas_id = p_petugas_id,
        catatan_petugas = p_catatan,
        updated_at = NOW()
    WHERE id = p_peminjaman_id;

    -- Kurangi stok alat
    UPDATE alat
    SET jumlah_tersedia = jumlah_tersedia - v_jumlah,
        updated_at = NOW()
    WHERE id = v_alat_id;

    COMMIT;
END //
DELIMITER ;

-- SP 2: Reject Peminjaman
DELIMITER //
CREATE PROCEDURE sp_reject_peminjaman(
    IN p_peminjaman_id BIGINT UNSIGNED,
    IN p_petugas_id BIGINT UNSIGNED,
    IN p_catatan TEXT
)
BEGIN
    UPDATE peminjaman
    SET status = 'rejected',
        petugas_id = p_petugas_id,
        catatan_petugas = p_catatan,
        updated_at = NOW()
    WHERE id = p_peminjaman_id AND status = 'pending';
END //
DELIMITER ;

-- SP 3: Proses Pengembalian
DELIMITER //
CREATE PROCEDURE sp_proses_pengembalian(
    IN p_peminjaman_id BIGINT UNSIGNED,
    IN p_tanggal_kembali DATETIME,
    IN p_kondisi_alat ENUM('baik', 'rusak_ringan', 'rusak_berat'),
    IN p_catatan TEXT
)
BEGIN
    DECLARE v_alat_id BIGINT UNSIGNED;
    DECLARE v_jumlah INT UNSIGNED;
    DECLARE v_tanggal_kembali_rencana DATE;
    DECLARE v_keterlambatan_hari INT DEFAULT 0;
    DECLARE v_denda DECIMAL(10,2) DEFAULT 0.00;
    DECLARE v_denda_keterlambatan DECIMAL(10,2) DEFAULT 0.00;
    DECLARE v_denda_kerusakan DECIMAL(10,2) DEFAULT 0.00;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Gagal proses pengembalian';
    END;

    START TRANSACTION;

    -- Ambil data peminjaman
    SELECT alat_id, jumlah, tanggal_kembali_rencana
    INTO v_alat_id, v_jumlah, v_tanggal_kembali_rencana
    FROM peminjaman
    WHERE id = p_peminjaman_id AND status = 'dipinjam';

    -- Hitung keterlambatan
    SET v_keterlambatan_hari = GREATEST(0, DATEDIFF(p_tanggal_kembali, v_tanggal_kembali_rencana));
    SET v_denda_keterlambatan = v_keterlambatan_hari * 5000;

    -- Hitung denda kerusakan
    SET v_denda_kerusakan = CASE p_kondisi_alat
        WHEN 'rusak_ringan' THEN 50000
        WHEN 'rusak_berat' THEN 200000
        ELSE 0
    END;

    -- Total denda
    SET v_denda = v_denda_keterlambatan + v_denda_kerusakan;

    -- Insert pengembalian
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
        p_peminjaman_id,
        p_tanggal_kembali,
        p_kondisi_alat,
        v_keterlambatan_hari,
        v_denda,
        p_catatan,
        NOW(),
        NOW()
    );

    -- Update status peminjaman
    UPDATE peminjaman
    SET status = 'selesai',
        updated_at = NOW()
    WHERE id = p_peminjaman_id;

    -- Kembalikan stok alat
    UPDATE alat
    SET jumlah_tersedia = jumlah_tersedia + v_jumlah,
        updated_at = NOW()
    WHERE id = v_alat_id;

    -- Update kondisi alat jika rusak
    IF p_kondisi_alat != 'baik' THEN
        UPDATE alat
        SET kondisi = p_kondisi_alat,
            updated_at = NOW()
        WHERE id = v_alat_id;
    END IF;

    COMMIT;
END //
DELIMITER ;

-- ============================================
-- 2. FUNCTIONS
-- ============================================

-- Function 1: Get Stok Tersedia
DELIMITER //
CREATE FUNCTION fn_get_stok_tersedia(p_alat_id BIGINT UNSIGNED)
RETURNS INT UNSIGNED
READS SQL DATA
BEGIN
    DECLARE v_stok INT UNSIGNED;
    
    SELECT jumlah_tersedia INTO v_stok
    FROM alat
    WHERE id = p_alat_id;
    
    RETURN IFNULL(v_stok, 0);
END //
DELIMITER ;

-- Function 2: Hitung Keterlambatan
DELIMITER //
CREATE FUNCTION fn_hitung_keterlambatan(p_peminjaman_id BIGINT UNSIGNED)
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE v_tanggal_rencana DATE;
    DECLARE v_keterlambatan INT DEFAULT 0;
    
    SELECT tanggal_kembali_rencana INTO v_tanggal_rencana
    FROM peminjaman
    WHERE id = p_peminjaman_id AND status = 'dipinjam';
    
    IF v_tanggal_rencana IS NOT NULL THEN
        SET v_keterlambatan = GREATEST(0, DATEDIFF(CURDATE(), v_tanggal_rencana));
    END IF;
    
    RETURN v_keterlambatan;
END //
DELIMITER ;

-- Function 3: Hitung Total Denda User
DELIMITER //
CREATE FUNCTION fn_total_denda_user(
    p_user_id BIGINT UNSIGNED,
    p_tahun INT,
    p_bulan INT
)
RETURNS DECIMAL(10,2)
READS SQL DATA
BEGIN
    DECLARE v_total_denda DECIMAL(10,2) DEFAULT 0.00;
    
    SELECT IFNULL(SUM(pg.denda), 0.00) INTO v_total_denda
    FROM pengembalian pg
    INNER JOIN peminjaman pm ON pg.peminjaman_id = pm.id
    WHERE pm.user_id = p_user_id
      AND YEAR(pg.created_at) = p_tahun
      AND MONTH(pg.created_at) = p_bulan;
    
    RETURN v_total_denda;
END //
DELIMITER ;

-- Function 4: Hitung Denda Keterlambatan
DELIMITER //
CREATE FUNCTION fn_hitung_denda_keterlambatan(p_keterlambatan_hari INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    RETURN p_keterlambatan_hari * 5000;
END //
DELIMITER ;

-- Function 5: Get Nama Alat
DELIMITER //
CREATE FUNCTION fn_get_nama_alat(p_alat_id BIGINT UNSIGNED)
RETURNS VARCHAR(255)
READS SQL DATA
BEGIN
    DECLARE v_nama VARCHAR(255);
    
    SELECT nama_alat INTO v_nama
    FROM alat
    WHERE id = p_alat_id;
    
    RETURN IFNULL(v_nama, 'Unknown');
END //
DELIMITER ;

-- ============================================
-- 3. TRIGGERS
-- ============================================

-- Trigger 1: Before Insert Peminjaman - Validasi Stok
DELIMITER //
CREATE TRIGGER trg_before_peminjaman_insert
BEFORE INSERT ON peminjaman
FOR EACH ROW
BEGIN
    DECLARE v_stok INT UNSIGNED;
    
    SELECT jumlah_tersedia INTO v_stok
    FROM alat
    WHERE id = NEW.alat_id;
    
    IF v_stok < NEW.jumlah THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Stok alat tidak mencukupi untuk peminjaman';
    END IF;
    
    -- Set tanggal pengajuan jika belum diset
    IF NEW.tanggal_pengajuan IS NULL THEN
        SET NEW.tanggal_pengajuan = CURDATE();
    END IF;
END //
DELIMITER ;

-- Trigger 2: After Update Peminjaman - Log Perubahan Status
DELIMITER //
CREATE TRIGGER trg_after_peminjaman_update
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO log_aktivitas (
            user_id,
            aksi,
            tabel,
            data_lama,
            data_baru,
            ip_address,
            created_at
        ) VALUES (
            NEW.petugas_id,
            CONCAT('update_status_', NEW.status),
            'peminjaman',
            JSON_OBJECT('id', OLD.id, 'status', OLD.status),
            JSON_OBJECT('id', NEW.id, 'status', NEW.status),
            '127.0.0.1',
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger 3: After Insert Pengembalian - Log Aktivitas
DELIMITER //
CREATE TRIGGER trg_after_pengembalian_insert
AFTER INSERT ON pengembalian
FOR EACH ROW
BEGIN
    DECLARE v_user_id BIGINT UNSIGNED;
    
    SELECT user_id INTO v_user_id
    FROM peminjaman
    WHERE id = NEW.peminjaman_id;
    
    INSERT INTO log_aktivitas (
        user_id,
        aksi,
        tabel,
        data_lama,
        data_baru,
        ip_address,
        created_at
    ) VALUES (
        v_user_id,
        'insert_pengembalian',
        'pengembalian',
        NULL,
        JSON_OBJECT(
            'peminjaman_id', NEW.peminjaman_id,
            'tanggal_kembali', NEW.tanggal_kembali_aktual,
            'kondisi', NEW.kondisi_alat,
            'denda', NEW.denda
        ),
        '127.0.0.1',
        NOW()
    );
END //
DELIMITER ;

-- Trigger 4: After Insert/Update Alat - Validasi Stok
DELIMITER //
CREATE TRIGGER trg_after_alat_update
BEFORE UPDATE ON alat
FOR EACH ROW
BEGIN
    IF NEW.jumlah_tersedia > NEW.jumlah_total THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Jumlah tersedia tidak boleh melebihi jumlah total';
    END IF;
    
    IF NEW.jumlah_tersedia < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Jumlah tersedia tidak boleh negatif';
    END IF;
END //
DELIMITER ;

-- ============================================
-- CONTOH PENGGUNAAN
-- ============================================

/*
-- 1. Approve Peminjaman
CALL sp_approve_peminjaman(1, 2, 'Disetujui untuk keperluan praktikum');

-- 2. Reject Peminjaman
CALL sp_reject_peminjaman(2, 2, 'Stok sedang tidak tersedia');

-- 3. Proses Pengembalian
CALL sp_proses_pengembalian(1, NOW(), 'baik', 'Alat dikembalikan dalam kondisi baik');

-- 4. Get Stok Tersedia
SELECT fn_get_stok_tersedia(1) AS stok_tersedia;

-- 5. Hitung Keterlambatan
SELECT fn_hitung_keterlambatan(1) AS hari_terlambat;

-- 6. Total Denda User per Bulan
SELECT fn_total_denda_user(3, 2026, 2) AS total_denda;

-- 7. Hitung Denda dari Keterlambatan
SELECT fn_hitung_denda_keterlambatan(5) AS denda; -- 5 hari = Rp 25,000

-- 8. Get Nama Alat
SELECT fn_get_nama_alat(1) AS nama_alat;
*/
