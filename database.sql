-- ====================================================================
-- Skema Basis Data Sistem Informasi Akademik (SIAKAD)
-- Proyek Praktikum Desain dan Pemrograman Berorientasi Objek (DPBO)
-- Arsitektur: PHP Native Model-View-Controller (MVC)
-- Engine: InnoDB | Charset: utf8mb4 | Collate: utf8mb4_unicode_ci
--
-- Petunjuk Impor:
-- 1. Buat basis data baru di phpMyAdmin (disarankan: tp_mvc25).
-- 2. Pilih basis data tersebut, lalu klik menu Import dan pilih file ini.
--    Atau melalui terminal/command line:
--    mysql -u root tp_mvc25 < database.sql
-- ====================================================================

-- 1. Tabel Dosen (Entitas Utama Awal)
CREATE TABLE IF NOT EXISTS `lecturers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `nidn` VARCHAR(30) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `join_date` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Mata Kuliah (Entitas Tambahan Relasi)
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `credits` TINYINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Penugasan Pengampu (Entitas Relasi Many-to-Many)
CREATE TABLE IF NOT EXISTS `teaching_assignments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lecturer_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `semester` ENUM('Ganjil', 'Genap') NOT NULL,
  `academic_year` VARCHAR(9) NOT NULL,
  CONSTRAINT `fk_assignment_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_assignment_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `unique_assignment` (`lecturer_id`, `course_id`, `semester`, `academic_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Sampel Fiktif untuk Demonstrasi
INSERT INTO `lecturers` (`id`, `name`, `nidn`, `phone`, `join_date`) VALUES
(1, 'Dr. Andi Pratama, M.T.', '0412345678', '081234567890', '2020-01-15'),
(2, 'Siti Nurhaliza, M.Kom.', '0487654321', '081398765432', '2021-08-20'),
(3, 'Budi Raharjo, Ph.D.', '0456789012', '085211223344', '2019-03-10')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `courses` (`id`, `code`, `name`, `credits`) VALUES
(1, 'IK100', 'Dasar Pemrograman', 3),
(2, 'IK200', 'Basis Data', 3),
(3, 'IK300', 'Desain dan Pemrograman Berorientasi Objek', 4)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `teaching_assignments` (`id`, `lecturer_id`, `course_id`, `semester`, `academic_year`) VALUES
(1, 1, 1, 'Ganjil', '2026/2027'),
(2, 2, 2, 'Ganjil', '2026/2027'),
(3, 3, 3, 'Genap', '2026/2027')
ON DUPLICATE KEY UPDATE `semester`=VALUES(`semester`);
