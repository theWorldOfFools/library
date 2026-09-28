-- =====================================================================
-- migrasi-admin.sql — Migrasi database untuk Admin Panel E-Book
-- Database : e-book (MySQL/MariaDB)
-- Cara pakai : mysql -h HOST -u USER -p e-book < migrasi-admin.sql
-- Aman dijalankan ulang (idempotent, memakai IF NOT EXISTS).
-- Catatan : butuh MariaDB 10.1+ untuk klausa IF NOT EXISTS pada
--           ADD COLUMN / CREATE INDEX. Jika error 'Duplicate', abaikan
--           saja bagian itu karena strukturnya sudah ada.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. Tabel akun admin (login panel /admin, password bcrypt)
--    role  : 'admin' = akses penuh (termasuk menu Akun Admin)
--            'supervisi' = semua menu KECUALI Akun Admin
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kolom role untuk database yang tabel admin-nya dibuat sebelum ada role
ALTER TABLE `admin` ADD COLUMN IF NOT EXISTS `role` varchar(20) NOT NULL DEFAULT 'admin';
UPDATE `admin` SET `role` = 'admin' WHERE `role` IS NULL OR `role` = '';

-- ---------------------------------------------------------------------
-- 2. Primary key `id` di tabel dokumen
--    Kolom `no` TETAP dipertahankan (dipakai paging search.php lama),
--    jadi hanya tambah `id` auto_increment sebagai PK baru.
-- ---------------------------------------------------------------------
ALTER TABLE `dokumen` ADD COLUMN IF NOT EXISTS `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

-- ---------------------------------------------------------------------
-- 3. Index untuk filter kategori di admin panel
-- ---------------------------------------------------------------------
CREATE INDEX IF NOT EXISTS `idx_dokumen_poli` ON `dokumen` (`poli`);
CREATE INDEX IF NOT EXISTS `idx_dokumen_namafile` ON `dokumen` (`namafile`(191));

-- ---------------------------------------------------------------------
-- 4. Akun admin default : username `admin` / password `admin`
--    SEGERA ganti password setelah login pertama via menu Akun Admin.
--    Baris ini tidak menimpa akun yang sudah ada (berkat ON DUPLICATE KEY
--    yang mengabaikan insert jika username sudah terdaftar).
-- ---------------------------------------------------------------------
INSERT INTO `admin` (`username`, `password_hash`, `nama`, `role`)
VALUES (
  'admin',
  '$2y$10$F4MuL4kNzUpuL8m8uTBF1e42hy1/ANAHDYN4SVrjAB2VFAnXGc5wm',
  'Administrator',
  'admin'
)
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- ---------------------------------------------------------------------
-- 5. Contoh akun supervisi (opsional — hapus blok ini jika tidak perlu).
--    Username : supervisi / Password : supervisi123 (SEGERA diganti).
-- ---------------------------------------------------------------------
-- INSERT INTO `admin` (`username`, `password_hash`, `nama`, `role`)
-- VALUES (
--   'supervisi',
--   '$2y$10$ganti-dengan-hash-bcrypt-dari-password_hash',
--   'Supervisi',
--   'supervisi'
-- )
-- ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);
