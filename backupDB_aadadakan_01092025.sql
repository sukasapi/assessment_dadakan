/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 8.0.30 : Database - aa_dadakan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `catatan_fgd` */

DROP TABLE IF EXISTS `catatan_fgd`;

CREATE TABLE `catatan_fgd` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `waktu_simpan` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catatan_fgd_peserta_id_foreign` (`peserta_id`),
  KEY `catatan_fgd_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `catatan_fgd_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catatan_fgd_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `catatan_roleplay` */

DROP TABLE IF EXISTS `catatan_roleplay`;

CREATE TABLE `catatan_roleplay` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `waktu_simpan` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catatan_roleplay_peserta_id_foreign` (`peserta_id`),
  KEY `catatan_roleplay_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `catatan_roleplay_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catatan_roleplay_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `item_penilaian` */

DROP TABLE IF EXISTS `item_penilaian`;

CREATE TABLE `item_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `penilaian_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `petunjuk` text COLLATE utf8mb4_unicode_ci,
  `jenis` enum('studi_kasus','in_tray','roleplay','fgd') COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `opsi` json DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_penilaian_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `item_penilaian_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jawaban_in_tray` */

DROP TABLE IF EXISTS `jawaban_in_tray`;

CREATE TABLE `jawaban_in_tray` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `latihan_in_tray_id` bigint unsigned NOT NULL,
  `urutan_prioritas` int NOT NULL,
  `disposisi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `waktu_simpan` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jawaban_in_tray_peserta_id_foreign` (`peserta_id`),
  KEY `jawaban_in_tray_penilaian_id_foreign` (`penilaian_id`),
  KEY `jawaban_in_tray_latihan_in_tray_id_foreign` (`latihan_in_tray_id`),
  CONSTRAINT `jawaban_in_tray_latihan_in_tray_id_foreign` FOREIGN KEY (`latihan_in_tray_id`) REFERENCES `latihan_in_tray` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_in_tray_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_in_tray_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jawaban_studi_kasus` */

DROP TABLE IF EXISTS `jawaban_studi_kasus`;

CREATE TABLE `jawaban_studi_kasus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `jawaban` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `waktu_simpan` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jawaban_studi_kasus_peserta_id_foreign` (`peserta_id`),
  KEY `jawaban_studi_kasus_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `jawaban_studi_kasus_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_studi_kasus_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kemajuan_penilaian` */

DROP TABLE IF EXISTS `kemajuan_penilaian`;

CREATE TABLE `kemajuan_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `status` enum('belum_mulai','sedang_berlangsung','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_mulai',
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `aktivitas_terakhir` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kemajuan_penilaian_peserta_id_penilaian_id_unique` (`peserta_id`,`penilaian_id`),
  KEY `kemajuan_penilaian_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `kemajuan_penilaian_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kemajuan_penilaian_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `latihan_in_tray` */

DROP TABLE IF EXISTS `latihan_in_tray`;

CREATE TABLE `latihan_in_tray` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `penilaian_id` bigint unsigned NOT NULL,
  `konten_memo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `latihan_in_tray_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `latihan_in_tray_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `penilaian` */

DROP TABLE IF EXISTS `penilaian`;

CREATE TABLE `penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sesi_penilaian_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('studi_kasus','in_tray','roleplay','fgd') COLLATE utf8mb4_unicode_ci NOT NULL,
  `petunjuk` text COLLATE utf8mb4_unicode_ci,
  `konten` text COLLATE utf8mb4_unicode_ci,
  `durasi_menit` int NOT NULL DEFAULT '60',
  `urutan` int NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penilaian_sesi_penilaian_id_foreign` (`sesi_penilaian_id`),
  CONSTRAINT `penilaian_sesi_penilaian_id_foreign` FOREIGN KEY (`sesi_penilaian_id`) REFERENCES `sesi_penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `peserta` */

DROP TABLE IF EXISTS `peserta`;

CREATE TABLE `peserta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_rumah` text COLLATE utf8mb4_unicode_ci,
  `nomor_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan_saat_ini` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peserta_pin_unique` (`pin`),
  KEY `peserta_user_id_foreign` (`user_id`),
  CONSTRAINT `peserta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sesi_penilaian` */

DROP TABLE IF EXISTS `sesi_penilaian`;

CREATE TABLE `sesi_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','active','paused','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `durasi_menit` int NOT NULL DEFAULT '60',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
