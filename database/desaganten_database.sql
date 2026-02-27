-- =============================================
-- DESAGANTEN - Database SQL File
-- Sistema Informasi Desa Ganten
-- Generated: September 12, 2025
-- =============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `desaganten` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `desaganten`;

-- =============================================
-- Core Tables
-- =============================================

-- Users Table
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password Reset Tokens Table
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Personal Access Tokens Table
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed Jobs Table
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings Table
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Wilayah (Regional) Table
-- =============================================

CREATE TABLE `wilayah` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `laki_laki` int(11) NOT NULL DEFAULT 0,
  `perempuan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Statistics Tables
-- =============================================

-- Statistik Pekerjaan Table
CREATE TABLE `statistik_pekerjaan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pekerjaan` varchar(255) NOT NULL,
  `laki_laki` int(11) NOT NULL DEFAULT 0,
  `perempuan` int(11) NOT NULL DEFAULT 0,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Statistik Umur Table
CREATE TABLE `statistik_umur` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kelompok_umur` varchar(255) NOT NULL,
  `rentang_awal` int(11) NOT NULL,
  `rentang_akhir` int(11) NOT NULL,
  `laki_laki` int(11) NOT NULL DEFAULT 0,
  `perempuan` int(11) NOT NULL DEFAULT 0,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Statistik Pendidikan Table
CREATE TABLE `statistik_pendidikan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tingkat_pendidikan` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 1,
  `laki_laki` int(11) NOT NULL DEFAULT 0,
  `perempuan` int(11) NOT NULL DEFAULT 0,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Statistik Wilayah Table (New)
CREATE TABLE `statistik_wilayah` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_wilayah` varchar(255) NOT NULL,
  `jenis_wilayah` varchar(100) NOT NULL DEFAULT 'Dusun',
  `laki_laki` int(11) NOT NULL DEFAULT 0,
  `perempuan` int(11) NOT NULL DEFAULT 0,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Surat (Letter) Main Table
-- =============================================

CREATE TABLE `surat` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `jenis_surat` varchar(255) NOT NULL,
  `file_berkas` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Menunggu Verifikasi',
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_nik_index` (`nik`),
  KEY `surat_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Specific Letter Types Tables
-- =============================================

-- Domisili Table
CREATE TABLE `domisili` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `kewarganegaraan` varchar(255) NOT NULL DEFAULT 'Indonesia',
  `agama` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `pekerjaan` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `keperluan` text NOT NULL,
  `status_pengajuan` varchar(255) NOT NULL DEFAULT 'pending',
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi` text DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `domisili_user_id_foreign` (`user_id`),
  CONSTRAINT `domisili_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surat KTP Table
CREATE TABLE `surat_ktp` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(255) NOT NULL,
  `status_perkawinan` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`catatan_verifikasi_detail`)),
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_ktp_user_id_foreign` (`user_id`),
  CONSTRAINT `surat_ktp_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surat SKCK Table
CREATE TABLE `surat_skck` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(255) NOT NULL,
  `status_perkawinan` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`catatan_verifikasi_detail`)),
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_skck_user_id_foreign` (`user_id`),
  CONSTRAINT `surat_skck_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surat Kelahiran Table
CREATE TABLE `surat_kelahiran` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_bayi` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `nama_ayah` varchar(255) NOT NULL,
  `nik_ayah` varchar(16) NOT NULL,
  `nama_ibu` varchar(255) NOT NULL,
  `nik_ibu` varchar(16) NOT NULL,
  `alamat_orang_tua` text NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`catatan_verifikasi_detail`)),
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_kelahiran_user_id_foreign` (`user_id`),
  CONSTRAINT `surat_kelahiran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surat Kematian Table
CREATE TABLE `surat_kematian` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_almarhum` varchar(255) NOT NULL,
  `nik_almarhum` varchar(16) NOT NULL,
  `tempat_lahir_almarhum` varchar(255) NOT NULL,
  `tanggal_lahir_almarhum` date NOT NULL,
  `tanggal_kematian` date NOT NULL,
  `tempat_kematian` varchar(255) NOT NULL,
  `sebab_kematian` varchar(255) NOT NULL,
  `nama_pelapor` varchar(255) NOT NULL,
  `nik_pelapor` varchar(16) NOT NULL,
  `hubungan_pelapor` varchar(255) NOT NULL,
  `alamat_pelapor` text NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`catatan_verifikasi_detail`)),
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_kematian_user_id_foreign` (`user_id`),
  CONSTRAINT `surat_kematian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surat KK Table
CREATE TABLE `surat_kk` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(255) NOT NULL,
  `status_perkawinan` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`catatan_verifikasi_detail`)),
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_kk_user_id_foreign` (`user_id`),
  CONSTRAINT `surat_kk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tidak Mampu Table
CREATE TABLE `tidak_mampu` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `kewarganegaraan` varchar(255) NOT NULL DEFAULT 'Indonesia',
  `agama` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `pekerjaan` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `keperluan` text NOT NULL,
  `status_pengajuan` varchar(255) NOT NULL DEFAULT 'pending',
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi` text DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tidak_mampu_user_id_foreign` (`user_id`),
  CONSTRAINT `tidak_mampu_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Belum Menikah Table
CREATE TABLE `belum_menikah` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `kewarganegaraan` varchar(255) NOT NULL DEFAULT 'Indonesia',
  `agama` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `pekerjaan` varchar(255) DEFAULT NULL,
  `nama_orang_tua` varchar(255) DEFAULT NULL,
  `pekerjaan_orang_tua` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text NOT NULL,
  `keperluan` text NOT NULL,
  `status_pengajuan` varchar(255) NOT NULL DEFAULT 'pending',
  `tahapan_verifikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tahapan_verifikasi`)),
  `catatan_verifikasi` text DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `belum_menikah_user_id_foreign` (`user_id`),
  CONSTRAINT `belum_menikah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Rekap Surat Keluar Table
-- =============================================

CREATE TABLE `rekap_surat_keluar` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tanggal_surat` date NOT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `nama_pemohon` varchar(255) NOT NULL,
  `jenis_surat` varchar(255) NOT NULL,
  `untuk_keperluan` text NOT NULL,
  `status` enum('pending','diproses','selesai','ditolak') NOT NULL DEFAULT 'pending',
  `keterangan` text DEFAULT NULL,
  `surat_type` varchar(255) DEFAULT NULL,
  `surat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rekap_surat_keluar_tanggal_surat_status_index` (`tanggal_surat`,`status`),
  KEY `rekap_surat_keluar_nomor_surat_index` (`nomor_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Default Data Inserts
-- =============================================

-- Insert Default Admin User
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@desaganten.id', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NOW(), NOW());

-- Insert Default Settings
INSERT INTO `settings` (`key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
('site_name', 'Desa Ganten', 'string', 'Nama Situs', NOW(), NOW()),
('site_description', 'Sistem Informasi Desa Ganten', 'string', 'Deskripsi Situs', NOW(), NOW()),
('contact_email', 'info@desaganten.id', 'string', 'Email Kontak', NOW(), NOW()),
('contact_phone', '0276-123456', 'string', 'Nomor Telepon', NOW(), NOW()),
('address', 'Desa Ganten, Kecamatan Banyudono, Kabupaten Boyolali', 'text', 'Alamat Desa', NOW(), NOW());

-- Insert Default Wilayah Data
INSERT INTO `wilayah` (`nama`, `jumlah`, `laki_laki`, `perempuan`, `created_at`, `updated_at`) VALUES
('Dusun Ganten Lor', 450, 225, 225, NOW(), NOW()),
('Dusun Ganten Kidul', 380, 190, 190, NOW(), NOW()),
('Dusun Sempu', 320, 160, 160, NOW(), NOW()),
('Dusun Kraguman', 280, 140, 140, NOW(), NOW());

-- Insert Default Statistik Wilayah Data
INSERT INTO `statistik_wilayah` (`nama_wilayah`, `jenis_wilayah`, `laki_laki`, `perempuan`, `jumlah`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('Dusun Ganten Lor', 'Dusun', 425, 445, 870, 'Wilayah dusun terbesar di Desa Ganten', 1, NOW(), NOW()),
('Dusun Ganten Kidul', 'Dusun', 365, 385, 750, 'Wilayah dusun bagian selatan', 1, NOW(), NOW()),
('Dusun Sempu', 'Dusun', 310, 320, 630, 'Wilayah dusun bagian timur', 1, NOW(), NOW()),
('Dusun Kraguman', 'Dusun', 280, 290, 570, 'Wilayah dusun bagian barat', 1, NOW(), NOW()),
('RT 01', 'RT', 65, 70, 135, 'Rukun Tetangga 01', 1, NOW(), NOW()),
('RT 02', 'RT', 58, 62, 120, 'Rukun Tetangga 02', 1, NOW(), NOW()),
('RT 03', 'RT', 72, 68, 140, 'Rukun Tetangga 03', 1, NOW(), NOW()),
('RT 04', 'RT', 69, 71, 140, 'Rukun Tetangga 04', 1, NOW(), NOW()),
('RW 01', 'RW', 195, 205, 400, 'Rukun Warga 01', 1, NOW(), NOW()),
('RW 02', 'RW', 185, 195, 380, 'Rukun Warga 02', 1, NOW(), NOW()),
('RW 03', 'RW', 175, 185, 360, 'Rukun Warga 03', 1, NOW(), NOW());

-- Insert Default Statistik Pekerjaan Data
INSERT INTO `statistik_pekerjaan` (`nama_pekerjaan`, `laki_laki`, `perempuan`, `jumlah`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('Petani', 320, 180, 500, 'Pekerjaan utama masyarakat desa', 1, NOW(), NOW()),
('Buruh Tani', 150, 120, 270, 'Buruh di sektor pertanian', 1, NOW(), NOW()),
('Pedagang', 85, 95, 180, 'Usaha perdagangan skala kecil', 1, NOW(), NOW()),
('PNS', 45, 55, 100, 'Pegawai Negeri Sipil', 1, NOW(), NOW()),
('Guru', 25, 65, 90, 'Tenaga pendidik', 1, NOW(), NOW()),
('Wiraswasta', 75, 45, 120, 'Usaha mandiri', 1, NOW(), NOW()),
('Ibu Rumah Tangga', 0, 420, 420, 'Mengurus rumah tangga', 1, NOW(), NOW()),
('Pelajar/Mahasiswa', 180, 170, 350, 'Masih dalam pendidikan', 1, NOW(), NOW()),
('Pensiunan', 35, 25, 60, 'Sudah pensiun', 1, NOW(), NOW()),
('Belum Bekerja', 85, 75, 160, 'Belum memiliki pekerjaan', 1, NOW(), NOW());

-- Insert Default Statistik Umur Data
INSERT INTO `statistik_umur` (`kelompok_umur`, `rentang_awal`, `rentang_akhir`, `laki_laki`, `perempuan`, `jumlah`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('Balita', 0, 5, 145, 135, 280, 'Anak usia 0-5 tahun', 1, NOW(), NOW()),
('Anak-anak', 6, 12, 165, 155, 320, 'Anak usia sekolah dasar', 1, NOW(), NOW()),
('Remaja', 13, 17, 125, 130, 255, 'Usia sekolah menengah', 1, NOW(), NOW()),
('Dewasa Muda', 18, 25, 185, 195, 380, 'Usia produktif muda', 1, NOW(), NOW()),
('Dewasa', 26, 40, 325, 340, 665, 'Usia produktif utama', 1, NOW(), NOW()),
('Dewasa Tua', 41, 60, 285, 295, 580, 'Usia produktif senior', 1, NOW(), NOW()),
('Lansia', 61, 100, 150, 160, 310, 'Lanjut usia', 1, NOW(), NOW());

-- Insert Default Statistik Pendidikan Data
INSERT INTO `statistik_pendidikan` (`tingkat_pendidikan`, `urutan`, `laki_laki`, `perempuan`, `jumlah`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('Tidak Sekolah', 1, 45, 55, 100, 'Tidak pernah sekolah', 1, NOW(), NOW()),
('Tidak Tamat SD', 2, 85, 95, 180, 'Putus sekolah sebelum tamat SD', 1, NOW(), NOW()),
('Tamat SD', 3, 285, 295, 580, 'Lulusan Sekolah Dasar', 1, NOW(), NOW()),
('Tamat SMP', 4, 225, 235, 460, 'Lulusan Sekolah Menengah Pertama', 1, NOW(), NOW()),
('Tamat SMA', 5, 195, 205, 400, 'Lulusan Sekolah Menengah Atas', 1, NOW(), NOW()),
('Diploma', 6, 35, 45, 80, 'Lulusan Diploma/D1-D3', 1, NOW(), NOW()),
('Sarjana', 7, 65, 75, 140, 'Lulusan S1', 1, NOW(), NOW()),
('Magister', 8, 15, 20, 35, 'Lulusan S2', 1, NOW(), NOW()),
('Doktor', 9, 5, 5, 10, 'Lulusan S3', 1, NOW(), NOW());

-- =============================================
-- Indexes for Performance
-- =============================================

-- Indexes untuk tabel surat
ALTER TABLE `surat` ADD INDEX `idx_jenis_surat` (`jenis_surat`);
ALTER TABLE `surat` ADD INDEX `idx_created_at` (`created_at`);

-- Indexes untuk statistik tables
ALTER TABLE `statistik_pekerjaan` ADD INDEX `idx_is_active` (`is_active`);
ALTER TABLE `statistik_umur` ADD INDEX `idx_is_active` (`is_active`);
ALTER TABLE `statistik_pendidikan` ADD INDEX `idx_is_active` (`is_active`);
ALTER TABLE `statistik_wilayah` ADD INDEX `idx_is_active` (`is_active`);
ALTER TABLE `statistik_pendidikan` ADD INDEX `idx_urutan` (`urutan`);

-- =============================================
-- End of SQL File
-- =============================================
