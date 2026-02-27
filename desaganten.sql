-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2025 at 11:12 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desaganten`
--

-- --------------------------------------------------------

--
-- Table structure for table `belum_menikah`
--

CREATE TABLE `belum_menikah` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_orang_tua` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','diproses','selesai','selesai diproses','approved','ditolak','sudah diverifikasi','menunggu') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `keterangan_admin` text COLLATE utf8mb4_unicode_ci,
  `tanggal_diproses` timestamp NULL DEFAULT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domisili`
--

CREATE TABLE `domisili` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Indonesia',
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domisili`
--

INSERT INTO `domisili` (`id`, `nik`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `kewarganegaraan`, `agama`, `status`, `pekerjaan`, `alamat`, `keperluan`, `status_pengajuan`, `tahapan_verifikasi`, `catatan_verifikasi`, `tanggal_verifikasi_terakhir`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '3313012505890001', 'Ahmad Suherman', 'Karanganyar', '1989-05-25', 'L', 'Indonesia', NULL, 'selesai', 'Petani', 'Desa Karanganyar RT 02 RW 01', 'Untuk mengurus KTP baru di Disdukcapil', 'pending', '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": null, \"status\": \"in_progress\", \"started_at\": \"2025-09-09T13:24:08.999222Z\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": null, \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat Permohonan\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan kelengkapan dan keabsahan dokumen pendukung\", \"completed_at\": null, \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek NIK di database\"], \"estimated_duration_days\": 2}, \"3\": {\"name\": \"Survey Lapangan\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Peninjauan langsung ke alamat domisili yang dimohonkan\", \"completed_at\": null, \"stage_number\": 3, \"required_documents\": [\"Foto lokasi\", \"Konfirmasi RT/RW\", \"Berita acara survey\"], \"estimated_duration_days\": 3}, \"4\": {\"name\": \"Verifikasi Data\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pencocokan data dengan database kependudukan\", \"completed_at\": null, \"stage_number\": 4, \"required_documents\": [\"Cek database Dukcapil\", \"Konfirmasi status domisili\"], \"estimated_duration_days\": 1}, \"5\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Review dan persetujuan akhir dari Kepala Desa\", \"completed_at\": null, \"stage_number\": 5, \"required_documents\": [\"Laporan verifikasi lengkap\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Penerbitan Surat\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pembuatan dan penandatanganan surat keterangan domisili\", \"completed_at\": null, \"stage_number\": 6, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', NULL, NULL, NULL, '2025-08-29 06:33:27', '2025-09-09 05:24:08'),
(2, '3313012206850002', 'Eko Prasetyo', 'Karanganyar', '1985-06-22', 'L', 'Indonesia', NULL, 'selesai', 'Wiraswasta', 'Desa Karanganyar RT 03 RW 02', 'Syarat pendaftaran sekolah anak', 'pending', '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": null, \"status\": \"in_progress\", \"started_at\": \"2025-09-09T13:24:13.425393Z\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": null, \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat Permohonan\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan kelengkapan dan keabsahan dokumen pendukung\", \"completed_at\": null, \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek NIK di database\"], \"estimated_duration_days\": 2}, \"3\": {\"name\": \"Survey Lapangan\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Peninjauan langsung ke alamat domisili yang dimohonkan\", \"completed_at\": null, \"stage_number\": 3, \"required_documents\": [\"Foto lokasi\", \"Konfirmasi RT/RW\", \"Berita acara survey\"], \"estimated_duration_days\": 3}, \"4\": {\"name\": \"Verifikasi Data\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pencocokan data dengan database kependudukan\", \"completed_at\": null, \"stage_number\": 4, \"required_documents\": [\"Cek database Dukcapil\", \"Konfirmasi status domisili\"], \"estimated_duration_days\": 1}, \"5\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Review dan persetujuan akhir dari Kepala Desa\", \"completed_at\": null, \"stage_number\": 5, \"required_documents\": [\"Laporan verifikasi lengkap\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Penerbitan Surat\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pembuatan dan penandatanganan surat keterangan domisili\", \"completed_at\": null, \"stage_number\": 6, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', NULL, NULL, NULL, '2025-09-06 06:33:27', '2025-09-09 05:24:13'),
(3, '3313011503820003', 'Hendra Gunawan', 'Karanganyar', '1982-03-15', 'L', 'Indonesia', NULL, 'selesai', 'Buruh', 'Desa Karanganyar RT 01 RW 01', 'Persyaratan melamar pekerjaan', 'pending', NULL, NULL, NULL, NULL, '2025-08-08 06:33:27', '2025-09-08 06:33:27'),
(4, '3313012505890001', 'Ahmad Suherman', 'Karanganyar', '1989-05-25', 'L', 'Indonesia', NULL, 'selesai', 'Petani', 'Desa Karanganyar RT 02 RW 01', 'Untuk mengurus KTP baru di Disdukcapil', 'pending', NULL, NULL, NULL, NULL, '2025-08-29 06:33:52', '2025-09-08 06:33:52'),
(5, '3313012206850002', 'Eko Prasetyo', 'Karanganyar', '1985-06-22', 'L', 'Indonesia', NULL, 'selesai', 'Wiraswasta', 'Desa Karanganyar RT 03 RW 02', 'Syarat pendaftaran sekolah anak', 'pending', NULL, NULL, NULL, NULL, '2025-09-06 06:33:52', '2025-09-08 06:33:52'),
(6, '3313011503820003', 'Hendra Gunawan', 'Karanganyar', '1982-03-15', 'L', 'Indonesia', NULL, 'selesai', 'Buruh', 'Desa Karanganyar RT 01 RW 01', 'Persyaratan melamar pekerjaan', 'pending', NULL, NULL, NULL, NULL, '2025-08-08 06:33:52', '2025-09-08 06:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_01_15_000001_create_tidak_mampu_table', 1),
(6, '2025_01_15_000002_create_belum_menikah_table', 1),
(7, '2025_08_05_000000_create_wilayah_table', 1),
(8, '2025_08_06_000000_create_surat_table', 1),
(9, '2025_08_13_134327_create_domisili_table', 1),
(10, '2025_08_21_000000_create_surat_ktp_table', 1),
(11, '2025_08_22_143121_create_settings_table', 1),
(12, '2025_08_23_150709_add_role_to_users_table', 1),
(13, '2025_08_25_231301_create_surat_kematian_table', 1),
(14, '2025_08_25_231326_create_surat_kelahiran_table', 1),
(15, '2025_08_25_231343_create_surat_skck_table', 1),
(16, '2025_08_25_231355_create_surat_kk_table', 1),
(17, '2025_08_28_122703_add_verification_columns_to_surat_ktp_table', 1),
(18, '2025_08_28_125039_create_surat_usaha_table', 1),
(19, '2025_08_28_125126_create_surat_kehilangan_table', 1),
(20, '2025_08_28_130615_create_notifications_table', 1),
(21, '2025_08_28_233052_add_lama_usaha_and_omzet_usaha_to_surat_usaha_table', 1),
(22, '2025_08_29_150629_add_approved_status_to_enum_tables', 1),
(23, '2025_08_31_024144_make_tanggal_mulai_usaha_nullable_in_surat_usaha_table', 1),
(24, '2025_08_31_032420_add_verification_stages_to_surat_tables_fix', 1),
(25, '2025_08_31_032723_add_user_id_to_domisili_table', 1),
(26, '2025_08_31_061141_add_file_columns_to_surat_usaha_table', 1),
(27, '2025_08_31_075703_add_nik_to_users_table', 1),
(28, '2025_08_31_084617_add_kewarganegaraan_to_surat_skck_table', 1),
(29, '2025_08_31_105725_add_missing_columns_to_surat_ktp_table', 1),
(30, '2025_08_31_114142_add_no_telepon_to_belum_menikah_table', 1),
(31, '2025_08_31_235959_update_status_enum_for_pdf_download', 1),
(32, '2025_09_01_000000_fix_remaining_surat_status_for_pdf', 1),
(33, '2025_09_04_132444_create_statistik_pekerjaans_table', 1),
(34, '2025_09_04_132605_create_statistik_umurs_table', 1),
(35, '2025_09_04_133610_create_statistik_pendidikans_table', 1),
(36, '2025_09_08_134005_create_rekap_surat_keluars_table', 1),
(37, '2025_09_08_135942_update_status_enum_in_rekap_surat_keluars_table', 2),
(38, '2025_09_08_140329_make_nomor_surat_nullable_in_rekap_surat_keluars_table', 3),
(39, '2025_09_08_141250_rename_statistik_tables', 4),
(40, '2025_09_08_143938_rename_rekap_surat_keluars_to_rekap_surat_keluar_table', 5),
(41, '2025_09_08_144118_create_rekap_surat_keluar_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'surat_selesai', 'Surat Telah Selesai', 'Surat Pengantar KK Anda telah selesai diproses dan dapat diunduh.', '{\"surat_id\": 1, \"jenis_surat\": \"kk\", \"nama_pemohon\": \"Rigel Donovan\"}', '2025-09-09 05:22:26', '2025-09-09 05:22:10', '2025-09-09 05:22:26'),
(2, 1, 'surat_selesai', 'Surat Telah Selesai', 'Surat Keterangan Tidak Mampu Anda telah selesai diproses dan dapat diunduh.', '{\"surat_id\": 4, \"jenis_surat\": \"tidak_mampu\", \"nama_pemohon\": \"Fatimah Zahra\"}', NULL, '2025-09-09 05:32:24', '2025-09-09 05:32:24'),
(3, 2, 'surat_diproses', 'Surat Sedang Diproses', 'Surat Keterangan Usaha Anda sedang dalam proses verifikasi oleh admin desa.', '{\"surat_id\": 5, \"jenis_surat\": \"usaha\", \"nama_pemohon\": \"Rigel Donovan\"}', NULL, '2025-09-09 05:37:34', '2025-09-09 05:37:34'),
(4, 2, 'surat_selesai', 'Surat Telah Selesai', 'Surat Keterangan Usaha Anda telah selesai diproses dan dapat diunduh.', '{\"surat_id\": 5, \"jenis_surat\": \"usaha\", \"nama_pemohon\": \"Rigel Donovan\"}', '2025-09-09 05:38:14', '2025-09-09 05:37:49', '2025-09-09 05:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_surat_keluar`
--

CREATE TABLE `rekap_surat_keluar` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_surat` date NOT NULL,
  `nomor_surat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `untuk_keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','diproses','selesai','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `surat_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surat_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekap_surat_keluar`
--

INSERT INTO `rekap_surat_keluar` (`id`, `tanggal_surat`, `nomor_surat`, `nama_pemohon`, `jenis_surat`, `untuk_keperluan`, `status`, `keterangan`, `surat_type`, `surat_id`, `created_at`, `updated_at`) VALUES
(1, '2025-09-05', NULL, 'Dwi Kartika', 'Surat Pengantar', 'Tidak disebutkan', 'pending', 'Data dari tabel surat', 'Surat', 1, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(2, '2025-09-07', NULL, 'Rudi Hartanto', 'Surat Keterangan Kelahiran', 'Tidak disebutkan', 'pending', 'Data dari tabel surat', 'Surat', 2, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(3, '2025-08-29', NULL, 'Ahmad Suherman', 'Surat Keterangan Domisili', 'Untuk mengurus KTP baru di Disdukcapil', 'selesai', 'Data dari tabel domisili', 'Domisili', 1, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(4, '2025-09-06', NULL, 'Eko Prasetyo', 'Surat Keterangan Domisili', 'Syarat pendaftaran sekolah anak', 'selesai', 'Data dari tabel domisili', 'Domisili', 2, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(5, '2025-08-08', NULL, 'Hendra Gunawan', 'Surat Keterangan Domisili', 'Persyaratan melamar pekerjaan', 'selesai', 'Data dari tabel domisili', 'Domisili', 3, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(6, '2025-08-29', NULL, 'Ahmad Suherman', 'Surat Keterangan Domisili', 'Untuk mengurus KTP baru di Disdukcapil', 'selesai', 'Data dari tabel domisili', 'Domisili', 4, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(7, '2025-09-06', NULL, 'Eko Prasetyo', 'Surat Keterangan Domisili', 'Syarat pendaftaran sekolah anak', 'selesai', 'Data dari tabel domisili', 'Domisili', 5, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(8, '2025-08-08', NULL, 'Hendra Gunawan', 'Surat Keterangan Domisili', 'Persyaratan melamar pekerjaan', 'selesai', 'Data dari tabel domisili', 'Domisili', 6, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(9, '2025-08-31', NULL, 'Siti Aminah', 'Surat Keterangan Tidak Mampu', 'Mengurus beasiswa pendidikan anak', 'selesai', 'Data dari tabel tidak_mampu', 'TidakMampu', 1, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(10, '2025-09-07', NULL, 'Fatimah Zahra', 'Surat Keterangan Tidak Mampu', 'Bantuan sosial dari pemerintah daerah', 'diproses', 'Data dari tabel tidak_mampu', 'TidakMampu', 2, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(11, '2025-08-31', NULL, 'Siti Aminah', 'Surat Keterangan Tidak Mampu', 'Mengurus beasiswa pendidikan anak', 'selesai', 'Data dari tabel tidak_mampu', 'TidakMampu', 3, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(12, '2025-09-07', NULL, 'Fatimah Zahra', 'Surat Keterangan Tidak Mampu', 'Bantuan sosial dari pemerintah daerah', 'diproses', 'Data dari tabel tidak_mampu', 'TidakMampu', 4, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(13, '2025-09-03', NULL, 'Bambang Wijaya', 'Surat Keterangan Usaha', 'Mengurus izin usaha warung makan', 'diproses', 'Data dari tabel surat_usaha', 'SuratUsaha', 1, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(14, '2025-09-08', NULL, 'Galih Ramadhan', 'Surat Keterangan Usaha', 'Persyaratan kredit usaha mikro di bank', 'pending', 'Data dari tabel surat_usaha', 'SuratUsaha', 2, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(15, '2025-09-03', NULL, 'Bambang Wijaya', 'Surat Keterangan Usaha', 'Mengurus izin usaha warung makan', 'diproses', 'Data dari tabel surat_usaha', 'SuratUsaha', 3, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(16, '2025-09-08', NULL, 'Galih Ramadhan', 'Surat Keterangan Usaha', 'Persyaratan kredit usaha mikro di bank', 'pending', 'Data dari tabel surat_usaha', 'SuratUsaha', 4, '2025-09-08 06:43:46', '2025-09-08 06:43:46'),
(17, '2025-08-31', 'SK/011/09/2025/DESGANTEN', 'Siti Aminah', 'Surat Keterangan Tidak Mampu', 'Mengurus beasiswa pendidikan anak', 'selesai', 'Data disinkronisasi dari sistem', 'tidak_mampu', 1, '2025-09-08 07:20:28', '2025-09-08 07:20:28'),
(18, '2025-09-07', 'SK/011/09/2025/DESGANTEN', 'Fatimah Zahra', 'Surat Keterangan Tidak Mampu', 'Bantuan sosial dari pemerintah daerah', 'diproses', 'Data disinkronisasi dari sistem', 'tidak_mampu', 2, '2025-09-08 07:20:28', '2025-09-08 07:20:28'),
(19, '2025-08-31', 'SK/012/09/2025/DESGANTEN', 'Siti Aminah', 'Surat Keterangan Tidak Mampu', 'Mengurus beasiswa pendidikan anak', 'selesai', 'Data disinkronisasi dari sistem', 'tidak_mampu', 3, '2025-09-08 07:20:28', '2025-09-08 07:20:28'),
(20, '2025-09-07', 'SK/012/09/2025/DESGANTEN', 'Fatimah Zahra', 'Surat Keterangan Tidak Mampu', 'Bantuan sosial dari pemerintah daerah', 'diproses', 'Data disinkronisasi dari sistem', 'tidak_mampu', 4, '2025-09-08 07:20:28', '2025-09-08 07:20:28'),
(21, '2025-09-03', 'SK/013/09/2025/DESGANTEN', 'Bambang Wijaya', 'Surat Keterangan Usaha', 'Mengurus izin usaha warung makan', 'diproses', 'Data disinkronisasi dari sistem', 'surat_usaha', 1, '2025-09-08 07:20:28', '2025-09-08 07:20:28'),
(22, '2025-09-03', 'SK/014/09/2025/DESGANTEN', 'Bambang Wijaya', 'Surat Keterangan Usaha', 'Mengurus izin usaha warung makan', 'diproses', 'Data disinkronisasi dari sistem', 'surat_usaha', 3, '2025-09-08 07:20:28', '2025-09-08 07:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistik_pekerjaan`
--

CREATE TABLE `statistik_pekerjaan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `laki_laki` int NOT NULL DEFAULT '0',
  `perempuan` int NOT NULL DEFAULT '0',
  `jumlah` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistik_pendidikan`
--

CREATE TABLE `statistik_pendidikan` (
  `id` bigint UNSIGNED NOT NULL,
  `tingkat_pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `laki_laki` int NOT NULL DEFAULT '0',
  `perempuan` int NOT NULL DEFAULT '0',
  `jumlah` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statistik_pendidikan`
--

INSERT INTO `statistik_pendidikan` (`id`, `tingkat_pendidikan`, `urutan`, `laki_laki`, `perempuan`, `jumlah`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tamat SD/Sederajat', 1, 2, 2, 4, NULL, 1, '2025-09-08 06:10:42', '2025-09-08 06:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `statistik_umur`
--

CREATE TABLE `statistik_umur` (
  `id` bigint UNSIGNED NOT NULL,
  `kelompok_umur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia_min` int NOT NULL,
  `usia_max` int DEFAULT NULL,
  `laki_laki` int NOT NULL DEFAULT '0',
  `perempuan` int NOT NULL DEFAULT '0',
  `jumlah` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_berkas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `nik`, `nama`, `alamat`, `jenis_surat`, `file_berkas`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, '3313016209870008', 'Dwi Kartika', 'Desa Karanganyar RT 07 RW 04', 'Surat Pengantar', NULL, 'Menunggu Verifikasi', 'Pengantar untuk mengurus BPJS Kesehatan', '2025-09-05 06:33:52', '2025-09-08 06:33:52'),
(2, '3313011807920009', 'Rudi Hartanto', 'Desa Karanganyar RT 08 RW 04', 'Surat Keterangan Kelahiran', NULL, 'Menunggu Verifikasi', 'Keterangan kelahiran untuk anak yang baru lahir', '2025-09-07 06:33:52', '2025-09-08 06:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `surat_kehilangan`
--

CREATE TABLE `surat_kehilangan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WNI',
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_hilang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_barang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_kehilangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kehilangan` date NOT NULL,
  `waktu_kehilangan` time DEFAULT NULL,
  `kronologi_kehilangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_laporan_polisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_laporan_polisi` date DEFAULT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_kelahiran`
--

CREATE TABLE `surat_kelahiran` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_bayi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin_bayi` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `waktu_lahir` time NOT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_ayah` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_ibu` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_orangtua` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_pelapor` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_kematian`
--

CREATE TABLE `surat_kematian` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_almarhum` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_almarhum` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir_almarhum` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir_almarhum` date NOT NULL,
  `tanggal_kematian` date NOT NULL,
  `tempat_kematian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sebab_kematian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_pelapor` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_pelapor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_kk`
--

CREATE TABLE `surat_kk` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_kk`
--

INSERT INTO `surat_kk` (`id`, `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_perkawinan`, `kewarganegaraan`, `pekerjaan`, `alamat`, `keperluan`, `status`, `catatan_verifikasi`, `user_id`, `tahapan_verifikasi`, `catatan_verifikasi_detail`, `tanggal_verifikasi_terakhir`, `created_at`, `updated_at`) VALUES
(1, 'Rigel Donovan', '6472032407020002', 'Bekasi', '2002-07-24', 'Laki-laki', 'Islam', 'Kawin', 'WNI', 'PNS', 'Jl. Ciputat Raya', 'Melamar pekerjaan', 'selesai diproses', '[09/09/2025 13:22] Admin: acc', 2, '{\"1\": {\"nama\": \"Verifikasi Dokumen\", \"notes\": \"acc\", \"status\": \"completed\", \"updated_at\": \"2025-09-09T13:22:00.499782Z\", \"updated_by\": \"Admin\", \"completed_at\": \"2025-09-09T13:22:00.499794Z\", \"required_documents\": [\"KTP asli\", \"KK lama (jika ada)\", \"Surat Nikah/Cerai (jika ada)\"]}, \"2\": {\"nama\": \"Verifikasi Data Kependudukan\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:22:00.499802Z\", \"updated_at\": \"2025-09-09T13:22:03.105051Z\", \"updated_by\": \"Admin\", \"completed_at\": \"2025-09-09T13:22:03.105062Z\", \"required_documents\": [\"Cek data di Dukcapil\", \"Verifikasi NIK\"]}, \"3\": {\"nama\": \"Pemeriksaan Berkas\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:22:03.105068Z\", \"updated_at\": \"2025-09-09T13:22:05.266517Z\", \"updated_by\": \"Admin\", \"completed_at\": \"2025-09-09T13:22:05.266529Z\", \"required_documents\": [\"Kelengkapan dokumen\", \"Kesesuaian data\"]}, \"4\": {\"nama\": \"Approval Kepala Desa\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:22:05.266541Z\", \"updated_at\": \"2025-09-09T13:22:07.728592Z\", \"updated_by\": \"Admin\", \"completed_at\": \"2025-09-09T13:22:07.728602Z\", \"required_documents\": [\"Persetujuan kepala desa\"]}, \"5\": {\"nama\": \"Penerbitan Surat\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:22:07.728608Z\", \"updated_at\": \"2025-09-09T13:22:10.328982Z\", \"updated_by\": \"Admin\", \"completed_at\": \"2025-09-09T13:22:10.328993Z\", \"required_documents\": [\"Surat pengantar KK selesai\"]}}', NULL, '2025-09-09 05:22:10', '2025-09-09 05:21:09', '2025-09-09 05:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `surat_ktp`
--

CREATE TABLE `surat_ktp` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_uploads` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_skck`
--

CREATE TABLE `surat_skck` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_skck`
--

INSERT INTO `surat_skck` (`id`, `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_perkawinan`, `kewarganegaraan`, `pekerjaan`, `alamat`, `keperluan`, `status`, `catatan_verifikasi`, `user_id`, `tahapan_verifikasi`, `catatan_verifikasi_detail`, `tanggal_verifikasi_terakhir`, `created_at`, `updated_at`) VALUES
(1, 'Terry Indra', '3471102901860001', 'Papua', '2003-03-25', 'Laki-laki', 'Islam', 'Belum Kawin', 'WNI', 'Swasta', 'Jl . Raya No 04 RT 24, Biak Numfor, Papua Barat', 'Melamar pekerjaan', 'sudah diverifikasi', 'Verifikasi diselesaikan oleh admin pada 2025-09-09 14:01:57', 3, '{\"rt\": {\"status\": \"in_progress\", \"catatan\": null, \"tanggal\": null}, \"rw\": {\"status\": \"waiting\", \"catatan\": null, \"tanggal\": null}, \"kelurahan\": {\"status\": \"waiting\", \"catatan\": null, \"tanggal\": null}}', NULL, NULL, '2025-09-09 06:01:41', '2025-09-09 06:01:57');

-- --------------------------------------------------------

--
-- Table structure for table `surat_usaha`
--

CREATE TABLE `surat_usaha` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WNI',
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_usaha` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lama_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai_usaha` date DEFAULT NULL,
  `modal_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `omzet_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_usaha` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_kk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_foto_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_izin_usaha` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_pengantar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi_detail` json DEFAULT NULL,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_usaha`
--

INSERT INTO `surat_usaha` (`id`, `nama_lengkap`, `nik`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `status_perkawinan`, `kewarganegaraan`, `pekerjaan`, `alamat`, `nama_usaha`, `jenis_usaha`, `alamat_usaha`, `lama_usaha`, `tanggal_mulai_usaha`, `modal_usaha`, `omzet_usaha`, `deskripsi_usaha`, `keperluan`, `file_ktp`, `file_kk`, `file_foto_usaha`, `file_izin_usaha`, `file_pengantar`, `status`, `catatan_verifikasi`, `user_id`, `tahapan_verifikasi`, `catatan_verifikasi_detail`, `tanggal_verifikasi_terakhir`, `created_at`, `updated_at`) VALUES
(1, 'Bambang Wijaya', '3313011204800006', 'Laki-laki', 'Karanganyar', '1980-04-12', 'Islam', 'Kawin', 'WNI', 'Pedagang', 'Desa Karanganyar RT 02 RW 01', 'Warung Makan Sederhana', 'Kuliner', 'Jl. Desa Karanganyar No. 15', NULL, '2020-01-01', 'Rp 5.000.000', NULL, 'Usaha warung makan yang menyediakan makanan dan minuman sederhana untuk masyarakat sekitar', 'Mengurus izin usaha warung makan', NULL, NULL, NULL, NULL, NULL, 'diproses', NULL, NULL, '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": null, \"status\": \"in_progress\", \"started_at\": \"2025-09-09T13:24:51.961900Z\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": null, \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat permohonan\", \"Foto usaha\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan kelengkapan dokumen dan identitas pemohon\", \"completed_at\": null, \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek data pemohon\"], \"estimated_duration_days\": 1}, \"3\": {\"name\": \"Survey Lokasi Usaha\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Peninjauan langsung ke lokasi usaha\", \"completed_at\": null, \"stage_number\": 3, \"required_documents\": [\"Foto lokasi usaha\", \"Dokumentasi kegiatan usaha\", \"Wawancara dengan pelaku usaha\"], \"estimated_duration_days\": 3}, \"4\": {\"name\": \"Verifikasi Aktivitas Usaha\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Konfirmasi aktivitas usaha dari lingkungan sekitar\", \"completed_at\": null, \"stage_number\": 4, \"required_documents\": [\"Keterangan RT/RW\", \"Konfirmasi tetangga\", \"Bukti aktivitas usaha\"], \"estimated_duration_days\": 2}, \"5\": {\"name\": \"Verifikasi Legalitas\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan aspek legalitas dan perizinan usaha\", \"completed_at\": null, \"stage_number\": 5, \"required_documents\": [\"Cek izin yang diperlukan\", \"Verifikasi tidak melanggar aturan\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Review dan persetujuan dari Kepala Desa\", \"completed_at\": null, \"stage_number\": 6, \"required_documents\": [\"Laporan verifikasi lengkap\", \"Rekomendasi tim survey\"], \"estimated_duration_days\": 2}, \"7\": {\"name\": \"Penerbitan Surat\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pembuatan dan penandatanganan surat keterangan usaha\", \"completed_at\": null, \"stage_number\": 7, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', NULL, NULL, '2025-09-03 06:33:27', '2025-09-09 05:24:51'),
(2, 'Galih Ramadhan', '3313012909900007', 'Laki-laki', 'Karanganyar', '1990-09-29', 'Islam', 'Belum Kawin', 'WNI', 'Petani', 'Desa Karanganyar RT 06 RW 03', 'Toko Kelontong Berkah', 'Perdagangan', 'Desa Karanganyar RT 06 RW 03', NULL, '2023-06-01', 'Rp 10.000.000', NULL, 'Toko kelontong yang menjual kebutuhan sehari-hari masyarakat desa', 'Persyaratan kredit usaha mikro di bank', NULL, NULL, NULL, NULL, NULL, 'menunggu', NULL, NULL, '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": null, \"status\": \"in_progress\", \"started_at\": \"2025-09-09T13:59:11.481801Z\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": null, \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat permohonan\", \"Foto usaha\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan kelengkapan dokumen dan identitas pemohon\", \"completed_at\": null, \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek data pemohon\"], \"estimated_duration_days\": 1}, \"3\": {\"name\": \"Survey Lokasi Usaha\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Peninjauan langsung ke lokasi usaha\", \"completed_at\": null, \"stage_number\": 3, \"required_documents\": [\"Foto lokasi usaha\", \"Dokumentasi kegiatan usaha\", \"Wawancara dengan pelaku usaha\"], \"estimated_duration_days\": 3}, \"4\": {\"name\": \"Verifikasi Aktivitas Usaha\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Konfirmasi aktivitas usaha dari lingkungan sekitar\", \"completed_at\": null, \"stage_number\": 4, \"required_documents\": [\"Keterangan RT/RW\", \"Konfirmasi tetangga\", \"Bukti aktivitas usaha\"], \"estimated_duration_days\": 2}, \"5\": {\"name\": \"Verifikasi Legalitas\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pengecekan aspek legalitas dan perizinan usaha\", \"completed_at\": null, \"stage_number\": 5, \"required_documents\": [\"Cek izin yang diperlukan\", \"Verifikasi tidak melanggar aturan\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Review dan persetujuan dari Kepala Desa\", \"completed_at\": null, \"stage_number\": 6, \"required_documents\": [\"Laporan verifikasi lengkap\", \"Rekomendasi tim survey\"], \"estimated_duration_days\": 2}, \"7\": {\"name\": \"Penerbitan Surat\", \"notes\": null, \"status\": \"waiting\", \"started_at\": null, \"description\": \"Pembuatan dan penandatanganan surat keterangan usaha\", \"completed_at\": null, \"stage_number\": 7, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', NULL, NULL, '2025-09-08 06:33:27', '2025-09-09 05:59:11'),
(3, 'Bambang Wijaya', '3313011204800006', 'Laki-laki', 'Karanganyar', '1980-04-12', 'Islam', 'Kawin', 'WNI', 'Pedagang', 'Desa Karanganyar RT 02 RW 01', 'Warung Makan Sederhana', 'Kuliner', 'Jl. Desa Karanganyar No. 15', NULL, '2020-01-01', 'Rp 5.000.000', NULL, 'Usaha warung makan yang menyediakan makanan dan minuman sederhana untuk masyarakat sekitar', 'Mengurus izin usaha warung makan', NULL, NULL, NULL, NULL, NULL, 'diproses', NULL, NULL, NULL, NULL, NULL, '2025-09-03 06:33:52', '2025-09-08 06:33:52'),
(4, 'Galih Ramadhan', '3313012909900007', 'Laki-laki', 'Karanganyar', '1990-09-29', 'Islam', 'Belum Kawin', 'WNI', 'Petani', 'Desa Karanganyar RT 06 RW 03', 'Toko Kelontong Berkah', 'Perdagangan', 'Desa Karanganyar RT 06 RW 03', NULL, '2023-06-01', 'Rp 10.000.000', NULL, 'Toko kelontong yang menjual kebutuhan sehari-hari masyarakat desa', 'Persyaratan kredit usaha mikro di bank', NULL, NULL, NULL, NULL, NULL, 'menunggu', NULL, NULL, NULL, NULL, NULL, '2025-09-08 06:33:52', '2025-09-08 06:33:52'),
(5, 'Rigel Donovan', '6472032407020002', 'Laki-laki', 'Surabaya', '2002-07-24', 'Islam', 'Belum Kawin', 'WNI', 'Swasta', 'Dwadwadad', 'Warung Ayam Geprek Pak Makmur', 'Kuliner', 'dwadgsre', '3 Tahun', NULL, '15000000', '5000000', 'Kuliner', 'Membuka cabang baru', NULL, NULL, NULL, NULL, NULL, 'selesai diproses', '[09/09/2025 13:38] Admin: acc', 2, '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:20.255989Z\", \"updated_at\": \"2025-09-09T13:37:34.756336Z\", \"updated_by\": \"Admin\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": \"2025-09-09T13:37:34.756343Z\", \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat permohonan\", \"Foto usaha\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:34.756346Z\", \"updated_at\": \"2025-09-09T13:37:36.706457Z\", \"updated_by\": \"Admin\", \"description\": \"Pengecekan kelengkapan dokumen dan identitas pemohon\", \"completed_at\": \"2025-09-09T13:37:36.706464Z\", \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek data pemohon\"], \"estimated_duration_days\": 1}, \"3\": {\"name\": \"Survey Lokasi Usaha\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:36.706467Z\", \"updated_at\": \"2025-09-09T13:37:39.052289Z\", \"updated_by\": \"Admin\", \"description\": \"Peninjauan langsung ke lokasi usaha\", \"completed_at\": \"2025-09-09T13:37:39.052296Z\", \"stage_number\": 3, \"required_documents\": [\"Foto lokasi usaha\", \"Dokumentasi kegiatan usaha\", \"Wawancara dengan pelaku usaha\"], \"estimated_duration_days\": 3}, \"4\": {\"name\": \"Verifikasi Aktivitas Usaha\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:39.052300Z\", \"updated_at\": \"2025-09-09T13:37:40.928187Z\", \"updated_by\": \"Admin\", \"description\": \"Konfirmasi aktivitas usaha dari lingkungan sekitar\", \"completed_at\": \"2025-09-09T13:37:40.928195Z\", \"stage_number\": 4, \"required_documents\": [\"Keterangan RT/RW\", \"Konfirmasi tetangga\", \"Bukti aktivitas usaha\"], \"estimated_duration_days\": 2}, \"5\": {\"name\": \"Verifikasi Legalitas\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:40.928198Z\", \"updated_at\": \"2025-09-09T13:37:42.829470Z\", \"updated_by\": \"Admin\", \"description\": \"Pengecekan aspek legalitas dan perizinan usaha\", \"completed_at\": \"2025-09-09T13:37:42.829477Z\", \"stage_number\": 5, \"required_documents\": [\"Cek izin yang diperlukan\", \"Verifikasi tidak melanggar aturan\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:42.829480Z\", \"updated_at\": \"2025-09-09T13:37:45.108190Z\", \"updated_by\": \"Admin\", \"description\": \"Review dan persetujuan dari Kepala Desa\", \"completed_at\": \"2025-09-09T13:37:45.108197Z\", \"stage_number\": 6, \"required_documents\": [\"Laporan verifikasi lengkap\", \"Rekomendasi tim survey\"], \"estimated_duration_days\": 2}, \"7\": {\"name\": \"Penerbitan Surat\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:37:45.108200Z\", \"updated_at\": \"2025-09-09T13:37:50.383733Z\", \"updated_by\": \"Admin\", \"description\": \"Pembuatan dan penandatanganan surat keterangan usaha\", \"completed_at\": \"2025-09-09T13:37:50.383740Z\", \"stage_number\": 7, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', NULL, '2025-09-09 05:37:50', '2025-09-09 05:37:20', '2025-09-09 05:38:02');

-- --------------------------------------------------------

--
-- Table structure for table `tidak_mampu`
--

CREATE TABLE `tidak_mampu` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penghasilan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_tanggungan` int NOT NULL,
  `status_rumah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_ekonomi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','diproses','selesai','selesai diproses','approved','ditolak','sudah diverifikasi','menunggu') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `tahapan_verifikasi` json DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_verifikasi_terakhir` timestamp NULL DEFAULT NULL,
  `keterangan_admin` text COLLATE utf8mb4_unicode_ci,
  `tanggal_diproses` timestamp NULL DEFAULT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tidak_mampu`
--

INSERT INTO `tidak_mampu` (`id`, `user_id`, `nama`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pekerjaan`, `penghasilan`, `alamat`, `jumlah_tanggungan`, `status_rumah`, `keterangan_ekonomi`, `keperluan`, `status`, `tahapan_verifikasi`, `catatan_verifikasi`, `tanggal_verifikasi_terakhir`, `keterangan_admin`, `tanggal_diproses`, `file_surat`, `created_at`, `updated_at`) VALUES
(1, 1, 'Siti Aminah', '3313016708920004', 'Karanganyar', '1992-08-27', 'Perempuan', 'Islam', 'Ibu Rumah Tangga', 'Tidak Ada', 'Desa Karanganyar RT 04 RW 02', 2, 'Kontrak', 'Keluarga kurang mampu, suami buruh tani dengan penghasilan tidak tetap', 'Mengurus beasiswa pendidikan anak', 'selesai', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 06:33:27', '2025-09-08 06:33:27'),
(2, 1, 'Fatimah Zahra', '3313017509880005', 'Karanganyar', '1988-09-25', 'Perempuan', 'Islam', 'Buruh Tani', 'Rp 500.000', 'Desa Karanganyar RT 05 RW 03', 3, 'Milik Sendiri', 'Keluarga pra sejahtera dengan kondisi ekonomi sulit', 'Bantuan sosial dari pemerintah daerah', 'diproses', NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 06:33:27', '2025-09-08 06:33:27'),
(3, 1, 'Siti Aminah', '3313016708920004', 'Karanganyar', '1992-08-27', 'Perempuan', 'Islam', 'Ibu Rumah Tangga', 'Tidak Ada', 'Desa Karanganyar RT 04 RW 02', 2, 'Kontrak', 'Keluarga kurang mampu, suami buruh tani dengan penghasilan tidak tetap', 'Mengurus beasiswa pendidikan anak', 'selesai', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 06:33:52', '2025-09-08 06:33:52'),
(4, 1, 'Fatimah Zahra', '3313017509880005', 'Karanganyar', '1988-09-25', 'Perempuan', 'Islam', 'Buruh Tani', 'Rp 500.000', 'Desa Karanganyar RT 05 RW 03', 3, 'Milik Sendiri', 'Keluarga pra sejahtera dengan kondisi ekonomi sulit', 'Bantuan sosial dari pemerintah daerah', 'selesai diproses', '{\"1\": {\"name\": \"Penerimaan Berkas\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:31:59.299188Z\", \"updated_at\": \"2025-09-09T13:32:02.557509Z\", \"updated_by\": \"Admin\", \"description\": \"Berkas permohonan diterima dan dicatat dalam sistem\", \"completed_at\": \"2025-09-09T13:32:02.557515Z\", \"stage_number\": 1, \"required_documents\": [\"KTP\", \"KK\", \"Surat Permohonan\", \"Surat Keterangan RT/RW\"], \"estimated_duration_days\": 1}, \"2\": {\"name\": \"Verifikasi Dokumen\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:02.557519Z\", \"updated_at\": \"2025-09-09T13:32:12.355221Z\", \"updated_by\": \"Admin\", \"description\": \"Pengecekan kelengkapan dokumen dan identitas pemohon\", \"completed_at\": \"2025-09-09T13:32:12.355228Z\", \"stage_number\": 2, \"required_documents\": [\"Verifikasi KTP\", \"Verifikasi KK\", \"Cek data keluarga\"], \"estimated_duration_days\": 2}, \"3\": {\"name\": \"Survey Ekonomi Keluarga\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:12.355232Z\", \"updated_at\": \"2025-09-09T13:32:14.592972Z\", \"updated_by\": \"Admin\", \"description\": \"Peninjauan kondisi ekonomi dan sosial keluarga\", \"completed_at\": \"2025-09-09T13:32:14.592980Z\", \"stage_number\": 3, \"required_documents\": [\"Foto rumah\", \"Wawancara keluarga\", \"Konfirmasi tetangga\"], \"estimated_duration_days\": 4}, \"4\": {\"name\": \"Verifikasi Penghasilan\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:14.592983Z\", \"updated_at\": \"2025-09-09T13:32:17.272567Z\", \"updated_by\": \"Admin\", \"description\": \"Pengecekan sumber penghasilan dan aset keluarga\", \"completed_at\": \"2025-09-09T13:32:17.272574Z\", \"stage_number\": 4, \"required_documents\": [\"Slip gaji (jika ada)\", \"Keterangan usaha\", \"Cek kepemilikan aset\"], \"estimated_duration_days\": 2}, \"5\": {\"name\": \"Analisis Kelayakan\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:17.272578Z\", \"updated_at\": \"2025-09-09T13:32:19.819413Z\", \"updated_by\": \"Admin\", \"description\": \"Analisis apakah keluarga memenuhi kriteria tidak mampu\", \"completed_at\": \"2025-09-09T13:32:19.819420Z\", \"stage_number\": 5, \"required_documents\": [\"Laporan survey lengkap\", \"Analisis ekonomi\"], \"estimated_duration_days\": 2}, \"6\": {\"name\": \"Persetujuan Kepala Desa\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:19.819424Z\", \"updated_at\": \"2025-09-09T13:32:22.140352Z\", \"updated_by\": \"Admin\", \"description\": \"Review dan persetujuan akhir dari Kepala Desa\", \"completed_at\": \"2025-09-09T13:32:22.140359Z\", \"stage_number\": 6, \"required_documents\": [\"Rekomendasi tim verifikasi\"], \"estimated_duration_days\": 2}, \"7\": {\"name\": \"Penerbitan Surat\", \"notes\": \"acc\", \"status\": \"completed\", \"started_at\": \"2025-09-09T13:32:22.140363Z\", \"updated_at\": \"2025-09-09T13:32:24.386510Z\", \"updated_by\": \"Admin\", \"description\": \"Pembuatan dan penandatanganan surat keterangan tidak mampu\", \"completed_at\": \"2025-09-09T13:32:24.386517Z\", \"stage_number\": 7, \"required_documents\": [\"Surat yang sudah ditandatangani\"], \"estimated_duration_days\": 1}}', '[09/09/2025 13:32] Admin: acc', '2025-09-09 05:32:24', NULL, NULL, NULL, '2025-09-07 06:33:52', '2025-09-09 05:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `nik`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', 'admin', NULL, '2025-09-08 05:57:51', '$2y$12$CQDnxuve3olvhUWQgSEodOedgfm4/gjmHIAtxp3WMbOtDAUEFfkpi', 'BnGRDbl496', '2025-09-08 05:57:51', '2025-09-08 05:57:51'),
(2, 'Rigel Donovan', 'rigeldonovan@gmail.com', 'user', NULL, NULL, '$2y$12$quyar2i3SqZhvxFCtTiFhuA5oWgjhb7jsizfFFXPEc9yZtuSnfL96', NULL, '2025-09-08 06:59:41', '2025-09-08 06:59:41'),
(3, 'Terry Indra', 'terryindra@gmail.com', 'user', NULL, NULL, '$2y$12$sWKXxCtMi7xR8EHxAcIAZ.2UVUzf9OExsNO6xrN4wJgnzPXFfVntO', NULL, '2025-09-09 06:00:47', '2025-09-09 06:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah`
--

CREATE TABLE `wilayah` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `laki_laki` int NOT NULL,
  `perempuan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `belum_menikah`
--
ALTER TABLE `belum_menikah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belum_menikah_user_id_status_index` (`user_id`,`status`),
  ADD KEY `belum_menikah_nik_index` (`nik`);

--
-- Indexes for table `domisili`
--
ALTER TABLE `domisili`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domisili_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rekap_surat_keluar`
--
ALTER TABLE `rekap_surat_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekap_surat_keluar_tanggal_surat_status_index` (`tanggal_surat`,`status`),
  ADD KEY `rekap_surat_keluar_nomor_surat_index` (`nomor_surat`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `statistik_pekerjaan`
--
ALTER TABLE `statistik_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistik_pendidikan`
--
ALTER TABLE `statistik_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistik_umur`
--
ALTER TABLE `statistik_umur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat_kehilangan`
--
ALTER TABLE `surat_kehilangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_kehilangan_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_kelahiran`
--
ALTER TABLE `surat_kelahiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_kelahiran_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_kematian`
--
ALTER TABLE `surat_kematian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_kematian_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_kk`
--
ALTER TABLE `surat_kk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_kk_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_ktp`
--
ALTER TABLE `surat_ktp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_ktp_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_skck`
--
ALTER TABLE `surat_skck`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_skck_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_usaha`
--
ALTER TABLE `surat_usaha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_usaha_user_id_foreign` (`user_id`);

--
-- Indexes for table `tidak_mampu`
--
ALTER TABLE `tidak_mampu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tidak_mampu_user_id_status_index` (`user_id`,`status`),
  ADD KEY `tidak_mampu_nik_index` (`nik`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `belum_menikah`
--
ALTER TABLE `belum_menikah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domisili`
--
ALTER TABLE `domisili`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_surat_keluar`
--
ALTER TABLE `rekap_surat_keluar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statistik_pekerjaan`
--
ALTER TABLE `statistik_pekerjaan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statistik_pendidikan`
--
ALTER TABLE `statistik_pendidikan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistik_umur`
--
ALTER TABLE `statistik_umur`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `surat_kehilangan`
--
ALTER TABLE `surat_kehilangan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_kelahiran`
--
ALTER TABLE `surat_kelahiran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_kematian`
--
ALTER TABLE `surat_kematian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_kk`
--
ALTER TABLE `surat_kk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surat_ktp`
--
ALTER TABLE `surat_ktp`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_skck`
--
ALTER TABLE `surat_skck`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surat_usaha`
--
ALTER TABLE `surat_usaha`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tidak_mampu`
--
ALTER TABLE `tidak_mampu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `belum_menikah`
--
ALTER TABLE `belum_menikah`
  ADD CONSTRAINT `belum_menikah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domisili`
--
ALTER TABLE `domisili`
  ADD CONSTRAINT `domisili_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_kehilangan`
--
ALTER TABLE `surat_kehilangan`
  ADD CONSTRAINT `surat_kehilangan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_kelahiran`
--
ALTER TABLE `surat_kelahiran`
  ADD CONSTRAINT `surat_kelahiran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_kematian`
--
ALTER TABLE `surat_kematian`
  ADD CONSTRAINT `surat_kematian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_kk`
--
ALTER TABLE `surat_kk`
  ADD CONSTRAINT `surat_kk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_ktp`
--
ALTER TABLE `surat_ktp`
  ADD CONSTRAINT `surat_ktp_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_skck`
--
ALTER TABLE `surat_skck`
  ADD CONSTRAINT `surat_skck_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_usaha`
--
ALTER TABLE `surat_usaha`
  ADD CONSTRAINT `surat_usaha_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tidak_mampu`
--
ALTER TABLE `tidak_mampu`
  ADD CONSTRAINT `tidak_mampu_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
