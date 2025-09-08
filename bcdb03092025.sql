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
/*Table structure for table `assessment_participant` */

DROP TABLE IF EXISTS `assessment_participant`;

CREATE TABLE `assessment_participant` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sesi_penilaian_id` bigint unsigned NOT NULL,
  `peserta_id` bigint unsigned NOT NULL,
  `status` enum('aktif','nonaktif','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `durasi_menit` int DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assessment_participant_unique` (`sesi_penilaian_id`,`peserta_id`),
  KEY `assessment_participant_peserta_id_status_index` (`peserta_id`,`status`),
  KEY `assessment_participant_sesi_penilaian_id_status_index` (`sesi_penilaian_id`,`status`),
  CONSTRAINT `assessment_participant_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_participant_sesi_penilaian_id_foreign` FOREIGN KEY (`sesi_penilaian_id`) REFERENCES `sesi_penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `assessment_participant` */

LOCK TABLES `assessment_participant` WRITE;

insert  into `assessment_participant`(`id`,`sesi_penilaian_id`,`peserta_id`,`status`,`waktu_mulai`,`waktu_selesai`,`durasi_menit`,`catatan_admin`,`created_at`,`updated_at`) values 
(1,1,14,'aktif',NULL,NULL,120,NULL,'2025-09-03 04:38:24','2025-09-03 04:38:24');

UNLOCK TABLES;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

LOCK TABLES `cache` WRITE;

UNLOCK TABLES;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

LOCK TABLES `cache_locks` WRITE;

UNLOCK TABLES;

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

/*Data for the table `catatan_fgd` */

LOCK TABLES `catatan_fgd` WRITE;

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `catatan_roleplay` */

LOCK TABLES `catatan_roleplay` WRITE;

insert  into `catatan_roleplay`(`id`,`peserta_id`,`penilaian_id`,`catatan`,`status`,`waktu_simpan`,`created_at`,`updated_at`) values 
(5,14,3,'jawaban assessment role play','draft','2025-09-03 07:50:14','2025-09-03 07:49:52','2025-09-03 07:50:14');

UNLOCK TABLES;

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

/*Data for the table `failed_jobs` */

LOCK TABLES `failed_jobs` WRITE;

UNLOCK TABLES;

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

/*Data for the table `item_penilaian` */

LOCK TABLES `item_penilaian` WRITE;

insert  into `item_penilaian`(`id`,`penilaian_id`,`judul`,`konten`,`petunjuk`,`jenis`,`urutan`,`opsi`,`aktif`,`created_at`,`updated_at`) values 
(1,1,'Analisis Situasi Perusahaan','Anda adalah seorang manajer yang baru saja bergabung dengan perusahaan XYZ. Perusahaan ini mengalami penurunan kinerja selama 6 bulan terakhir. Analisis situasi dan berikan rekomendasi strategis untuk mengatasi masalah tersebut.','Berikan analisis yang sistematis dengan pendekatan SWOT dan rekomendasi yang konkret dan dapat diimplementasikan.','studi_kasus',1,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(2,1,'Manajemen Konflik Tim','Tim Anda mengalami konflik internal antara dua anggota kunci. Konflik ini berdampak pada produktivitas dan moral tim. Bagaimana Anda akan mengatasi situasi ini?','Gunakan pendekatan win-win solution dan tunjukkan kemampuan mediasi dan resolusi konflik.','studi_kasus',2,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(3,2,'Memo Prioritas','Anda memiliki 10 memo yang harus diproses dalam waktu 2 jam. Setiap memo memiliki tingkat urgensi dan kepentingan yang berbeda.','Urutkan memo berdasarkan prioritas dan berikan disposisi yang tepat untuk setiap memo.','in_tray',1,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(4,3,'Presentasi kepada Direksi','Anda diminta untuk mempresentasikan proposal proyek baru kepada direksi perusahaan. Presentasikan dengan meyakinkan dan siap menghadapi pertanyaan kritis.','Fokus pada value proposition, feasibility, dan return on investment dari proyek yang diusulkan.','roleplay',1,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(5,3,'Negosiasi dengan Vendor','Lakukan negosiasi dengan vendor untuk mendapatkan harga terbaik untuk pembelian peralatan kantor senilai Rp 500 juta.','Gunakan teknik negosiasi yang efektif, siapkan BATNA, dan capai kesepakatan yang menguntungkan kedua belah pihak.','roleplay',2,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(6,4,'Strategi Digital Transformation','Diskusikan strategi digital transformation untuk perusahaan tradisional yang ingin bertransformasi menjadi perusahaan digital.','Berikan kontribusi yang konstruktif, dengarkan pendapat orang lain, dan bangun konsensus dalam kelompok.','fgd',1,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(7,4,'Work-Life Balance di Era Digital','Diskusikan tantangan dan solusi untuk menjaga work-life balance di era digital yang serba cepat dan terhubung.','Berikan perspektif yang beragam dan solusi yang praktis untuk diterapkan dalam organisasi.','fgd',2,NULL,1,'2025-09-03 03:29:24','2025-09-03 03:29:24');

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jawaban_in_tray` */

LOCK TABLES `jawaban_in_tray` WRITE;

insert  into `jawaban_in_tray`(`id`,`peserta_id`,`penilaian_id`,`latihan_in_tray_id`,`urutan_prioritas`,`disposisi`,`status`,`waktu_simpan`,`created_at`,`updated_at`) values 
(15,14,2,9,1,'sekretaris','draft','2025-09-03 07:41:22','2025-09-03 07:41:22','2025-09-03 07:41:22'),
(16,14,2,8,2,'sekretaris','draft','2025-09-03 07:41:22','2025-09-03 07:41:22','2025-09-03 07:41:22');

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jawaban_studi_kasus` */

LOCK TABLES `jawaban_studi_kasus` WRITE;

UNLOCK TABLES;

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

/*Data for the table `job_batches` */

LOCK TABLES `job_batches` WRITE;

UNLOCK TABLES;

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

/*Data for the table `jobs` */

LOCK TABLES `jobs` WRITE;

UNLOCK TABLES;

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
  `jawaban` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kemajuan_penilaian_peserta_id_penilaian_id_unique` (`peserta_id`,`penilaian_id`),
  KEY `kemajuan_penilaian_penilaian_id_foreign` (`penilaian_id`),
  CONSTRAINT `kemajuan_penilaian_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kemajuan_penilaian_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kemajuan_penilaian` */

LOCK TABLES `kemajuan_penilaian` WRITE;

insert  into `kemajuan_penilaian`(`id`,`peserta_id`,`penilaian_id`,`status`,`waktu_mulai`,`waktu_selesai`,`aktivitas_terakhir`,`jawaban`,`created_at`,`updated_at`) values 
(21,14,1,'sedang_berlangsung','2025-09-03 05:43:10',NULL,'2025-09-03 07:59:15','jawaban studi kasus','2025-09-03 04:58:59','2025-09-03 07:59:15'),
(22,14,2,'sedang_berlangsung',NULL,NULL,'2025-09-03 07:47:57',NULL,'2025-09-03 05:44:18','2025-09-03 07:47:57'),
(23,14,3,'selesai',NULL,'2025-09-03 07:50:14','2025-09-03 07:49:41',NULL,'2025-09-03 05:59:50','2025-09-03 07:50:14');

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `latihan_in_tray` */

LOCK TABLES `latihan_in_tray` WRITE;

insert  into `latihan_in_tray`(`id`,`penilaian_id`,`konten_memo`,`urutan`,`aktif`,`created_at`,`updated_at`) values 
(8,2,'<p><strong>Kepada&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Direktur Utama</strong></p><p><strong>Dari&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Direktur pemasaran dan Ekspor</strong><br><strong>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; 1 Februari 2024</strong><br><strong>Perihal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Pengembangan Produk </strong><i><strong>Low-Sugar Canned Pineapple</strong></i></p><p>&nbsp;</p><p>Seiring perubahan tren pasar global yang semakin mengarah pada konsumsi produk rendah gula, serta memperhatikan arahan pemegang saham pada saat RUPS yang lalu, kita perlu segera memulai pengembangan produk <i>low-sugar canned pineapple</i>. Produk ini harus memenuhi standar global dan dilengkapi dengan sertifikasi keberlanjutan. Kajian pengembangan produk ini sebaiknya mulai dilakukan pada awal Maret 2024, sehingga jika akhirnya layak dan diputuskan dilakukan pengembangan pada pertengahan tahun ini, kita bisa memulai peluncuran pasar pertama pertengahan 2026.&nbsp;</p><p>&nbsp;</p><p>Mohon arahan Bapak.</p><p>&nbsp;</p><p><strong>Tertanda,&nbsp;</strong></p><p><strong>Direktur Pemasaran dan Ekspor</strong></p><p>&nbsp;</p><p>&nbsp;</p>',1,1,'2025-09-03 07:17:55','2025-09-03 07:17:55'),
(9,2,'<p><strong>Kepada&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Direktur Utama</strong><br><strong>Dari&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Direktur Operasional</strong><br><strong>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; 2 Februari 2024</strong><br><strong>Perihal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; Digitalisasi Kebun dan Pabrik</strong></p><p><br>Sehubungan kesepakatan pada rapat bersama BoD dan Dekom, kita perlu mempercepat digitalisasi operasional kebun dan pabrik untuk meningkatkan efisiensi dan mengurangi ketergantungan pada metode manual. Mengingat tren pasar yang semakin maju, kita harus mulai mengimplementasikan sistem manajemen berbasis <i>Internet of Things (IoT)</i> dan <i>Big Data&nbsp;</i>pada 2024 untuk monitoring tanaman, kualitas produksi, serta pemeliharaan mesin. <i>Pilot project</i> di pabrik pengalengan dan kebun harus dimulai pada Q3 2024, dengan target implementasi penuh pada Q1 2025. Untuk itu kami mohon agar dapat segera diberikan ijin penggunaan anggaran. Berdasarkan diskusi dengan Direktur Keuangan, nampaknya pencairan anggaran diharapkan menyesuaikan dengan sales yang pada Q1-Q2 ini akan mengalami perlambatan.&nbsp;</p><p>&nbsp;</p><p>Mohon arahan Bapak.</p><p>&nbsp;</p><p><strong>Tertanda,</strong></p><p><strong>Direktur Operasional</strong></p>',2,1,'2025-09-03 07:17:55','2025-09-03 07:17:55');

UNLOCK TABLES;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

LOCK TABLES `migrations` WRITE;

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_09_01_135254_create_sesi_penilaian_table',1),
(5,'2025_09_01_135327_create_penilaian_table',1),
(6,'2025_09_01_135353_create_item_penilaian_table',1),
(7,'2025_09_01_135418_create_peserta_table',1),
(8,'2025_09_01_135441_create_kemajuan_penilaian_table',1),
(9,'2025_09_01_135509_create_jawaban_studi_kasus_table',1),
(10,'2025_09_01_135533_create_latihan_in_tray_table',1),
(11,'2025_09_01_135601_create_jawaban_in_tray_table',1),
(12,'2025_09_01_135651_create_catatan_roleplay_table',1),
(13,'2025_09_01_135717_create_catatan_fgd_table',1),
(14,'2025_09_01_141055_add_role_to_users_table',1),
(15,'2025_09_02_000000_create_assessment_participant_table',1),
(16,'2025_09_02_080458_create_sesi_assessment_table',1),
(17,'2025_09_02_152212_fix_assessment_participant_table_structure',1),
(18,'2025_09_02_152241_recreate_assessment_participant_table',1),
(19,'2025_09_02_000000_add_file_pdf_to_penilaian_table',2),
(20,'2025_09_03_044318_add_jawaban_to_kemajuan_penilaian_table',3),
(21,'2025_09_03_120000_add_memos_to_sesi_assessment_table',4);

UNLOCK TABLES;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

LOCK TABLES `password_reset_tokens` WRITE;

UNLOCK TABLES;

/*Table structure for table `penilaian` */

DROP TABLE IF EXISTS `penilaian`;

CREATE TABLE `penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sesi_penilaian_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('studi_kasus','in_tray','roleplay','fgd') COLLATE utf8mb4_unicode_ci NOT NULL,
  `petunjuk` text COLLATE utf8mb4_unicode_ci,
  `konten` text COLLATE utf8mb4_unicode_ci,
  `file_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durasi_menit` int NOT NULL DEFAULT '60',
  `urutan` int NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penilaian_sesi_penilaian_id_foreign` (`sesi_penilaian_id`),
  CONSTRAINT `penilaian_sesi_penilaian_id_foreign` FOREIGN KEY (`sesi_penilaian_id`) REFERENCES `sesi_penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `penilaian` */

LOCK TABLES `penilaian` WRITE;

insert  into `penilaian`(`id`,`sesi_penilaian_id`,`nama`,`jenis`,`petunjuk`,`konten`,`file_pdf`,`durasi_menit`,`urutan`,`aktif`,`created_at`,`updated_at`) values 
(1,1,'Studi Kasus - Manajemen Konflik','studi_kasus','Baca dan analisis kasus berikut dengan seksama. Berikan jawaban yang komprehensif berdasarkan pemahaman Anda tentang manajemen konflik dan kepemimpinan.','Anda adalah seorang Manager di departemen IT yang baru saja bergabung dengan perusahaan. Tim Anda terdiri dari 5 orang dengan berbagai latar belakang dan pengalaman. Beberapa minggu terakhir, Anda melihat ada ketegangan antara dua anggota tim senior yang saling bersaing untuk posisi Team Lead. Bagaimana Anda akan menangani situasi ini?','assessments/pdf/3qWPSRCeFAq9KNK0FReIT20GRkOOkeM6JgWTJkGn.pdf',30,1,1,'2025-09-03 03:29:24','2025-09-03 05:32:36'),
(2,1,'In-Tray Exercise - Prioritas Manajemen','in_tray','Anda adalah seorang Manager yang baru saja masuk kantor dan menemukan 10 memo di meja Anda. Urutkan memo-memo tersebut berdasarkan prioritas dan berikan disposisi untuk masing-masing memo.','Latihan ini menguji kemampuan Anda dalam mengelola prioritas dan mengambil keputusan yang tepat dalam situasi yang menekan.',NULL,45,2,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(3,1,'Role-Play - Negosiasi Tim','roleplay','Anda akan melakukan role-play sebagai seorang Team Leader yang harus memotivasi tim yang sedang mengalami penurunan performa.','Skenario: Tim Anda telah gagal mencapai target selama 3 bulan berturut-turut. Beberapa anggota tim mulai kehilangan semangat dan ada yang ingin pindah ke tim lain. Bagaimana Anda akan memotivasi dan mempertahankan tim Anda?',NULL,20,3,1,'2025-09-03 03:29:24','2025-09-03 03:29:24'),
(4,1,'FGD - Strategi Digitalisasi','fgd','Diskusikan dengan kelompok tentang strategi digitalisasi untuk meningkatkan efisiensi operasional perusahaan.','Topik: Perusahaan Anda berencana melakukan digitalisasi dalam 2 tahun ke depan. Diskusikan aspek-aspek yang perlu diperhatikan, tantangan yang mungkin dihadapi, dan langkah-langkah implementasi yang efektif.',NULL,25,4,1,'2025-09-03 03:29:24','2025-09-03 03:29:24');

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `peserta` */

LOCK TABLES `peserta` WRITE;

insert  into `peserta`(`id`,`user_id`,`nama_lengkap`,`tempat_lahir`,`tanggal_lahir`,`jenis_kelamin`,`alamat_rumah`,`nomor_telepon`,`email`,`instansi`,`jabatan_saat_ini`,`grade`,`pin`,`aktif`,`created_at`,`updated_at`) values 
(12,13,'John Doe',NULL,'1990-01-01','L',NULL,NULL,'john.doe@example.com','PT Contoh',NULL,'E4','Test123',1,'2025-09-03 04:33:25','2025-09-03 04:33:25'),
(13,14,'Jane Smith',NULL,'1993-03-05','P',NULL,NULL,'jane.smith@example.com','CV Sample',NULL,'E3','Test456',1,'2025-09-03 04:33:25','2025-09-03 04:33:25'),
(14,15,'sahrono',NULL,'1993-04-05','L',NULL,NULL,'sah.rono@example.com','PT Contoh',NULL,'E4','Test137',1,'2025-09-03 04:33:25','2025-09-03 04:33:25');

UNLOCK TABLES;

/*Table structure for table `sesi_assessment` */

DROP TABLE IF EXISTS `sesi_assessment`;

CREATE TABLE `sesi_assessment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sesi_penilaian_id` bigint unsigned NOT NULL,
  `penilaian_id` bigint unsigned NOT NULL,
  `urutan` int NOT NULL DEFAULT '1',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `durasi_default` int DEFAULT NULL,
  `instruksi_khusus` text COLLATE utf8mb4_unicode_ci,
  `memos` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sesi_assessment_unique` (`sesi_penilaian_id`,`penilaian_id`),
  KEY `sesi_assessment_sesi_penilaian_id_aktif_index` (`sesi_penilaian_id`,`aktif`),
  KEY `sesi_assessment_penilaian_id_aktif_index` (`penilaian_id`,`aktif`),
  KEY `sesi_assessment_urutan_index` (`urutan`),
  CONSTRAINT `sesi_assessment_penilaian_id_foreign` FOREIGN KEY (`penilaian_id`) REFERENCES `penilaian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sesi_assessment_sesi_penilaian_id_foreign` FOREIGN KEY (`sesi_penilaian_id`) REFERENCES `sesi_penilaian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sesi_assessment` */

LOCK TABLES `sesi_assessment` WRITE;

insert  into `sesi_assessment`(`id`,`sesi_penilaian_id`,`penilaian_id`,`urutan`,`aktif`,`durasi_default`,`instruksi_khusus`,`memos`,`created_at`,`updated_at`) values 
(19,1,1,1,1,NULL,'<p>Tolong ikuti studi kasus pada deskripsi soal</p>',NULL,'2025-09-03 07:17:55','2025-09-03 07:17:55'),
(20,1,2,2,1,NULL,'<p>Berikut adalah memo dan surat yang masuk ke meja Direktur Utama yang Saudara perankan.&nbsp;Karena satu dan lain hal, memo dan surat baru dapat saudara respon pada hari Senin, 19 Februari 2024 (posisi saat ini).&nbsp;</p><ol><li>Buat skala prioritas&nbsp;</li><li>Respon memo-memo dan surat-surat tersebut sebaik-baiknya.</li></ol><p>&nbsp;</p><p>Selamat bekerja</p>',NULL,'2025-09-03 07:17:55','2025-09-03 07:17:55'),
(21,1,3,3,1,NULL,'<p>ikuti instruksi dari instruktur</p>',NULL,'2025-09-03 07:17:55','2025-09-03 07:17:55');

UNLOCK TABLES;

/*Table structure for table `sesi_penilaian` */

DROP TABLE IF EXISTS `sesi_penilaian`;

CREATE TABLE `sesi_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','pending','active','paused','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `durasi_menit` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sesi_penilaian` */

LOCK TABLES `sesi_penilaian` WRITE;

insert  into `sesi_penilaian`(`id`,`nama`,`status`,`waktu_mulai`,`waktu_selesai`,`durasi_menit`,`catatan`,`aktif`,`created_at`,`updated_at`) values 
(1,'Assessment Center Batch 1 - 2024','active',NULL,NULL,120,'Assessment Center untuk posisi Manager dan Supervisor',1,'2025-09-03 03:29:24','2025-09-03 05:18:07');

UNLOCK TABLES;

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

/*Data for the table `sessions` */

LOCK TABLES `sessions` WRITE;

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('aoRw4461EEPI4QDkgbmpgSbNA9OF2Lz82TLU13XL',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoia3Vjb1pHQ2czbWowa21yaUxCb3NvVE9reFRGNFliSDRiR3NnWUZodyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2dyZXNzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9ncmVzcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1756888514),
('EO3HUTvEOUOMm89QRMuyYF67rAUtGfKOMnbX1O6o',15,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiZUs0V0xKNjJ4MUdURmlaaXh0bHZyNEhwZE51elNlYWZraXd5MzdmbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Blc2VydGEvYXNzZXNzbWVudC8xL3N0dWRpLWthc3VzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTA1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYXNzZXNzbWVudC8xL3BkZi9hc3Nlc3NtZW50cy9wZGYvM3FXUFNSQ2VGQXE5S05LMEZSZUlUMjBHUmtPT2tlTTZKZ1dUSmtHbi5wZGYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNTtzOjEwOiJwZXNlcnRhX2lkIjtpOjE0O3M6MTI6InBlc2VydGFfbmFtZSI7czo3OiJzYWhyb25vIjt9',1756886033),
('pQ6JAxmSpEUMcROET0FfMuMSvyEna0oo4tvsdJxr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTE5EMGF5c1lxRjZRSEVVaWIxSWJuRkI4eVJuRXZhbkNrYWVCQnMwRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXNpLzEvZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1756882431),
('tiNfyxxF0KCwU2eJTo3JGSb7p2WQ8ddo7V9wyQqr',15,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOWxCTnFrTndsYzZIaUtlRlhoSnV4bGRTaEtWN0NYRUFlbjUyUWV4ciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTA1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYXNzZXNzbWVudC8xL3BkZi9hc3Nlc3NtZW50cy9wZGYvM3FXUFNSQ2VGQXE5S05LMEZSZUlUMjBHUmtPT2tlTTZKZ1dUSmtHbi5wZGYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNTtzOjEwOiJwZXNlcnRhX2lkIjtpOjE0O3M6MTI6InBlc2VydGFfbmFtZSI7czo3OiJzYWhyb25vIjt9',1756886338),
('yEuJdpVgp6zOouCo8ojYw0xP0VCihkD4AjTsQ5wy',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVmE1T0g5Sk5tQ1BobjA4ZUhDclBOTmxVcFo0S1dNM0NYb3JSVnFGZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Blc2VydGEvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9ncmVzcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1756887633),
('zFqLEGh2pSULMzHgeB1Itznl2equlhjQoAJP9d6s',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEJxQVhjQmxwQkI0YWl0d1h5cEJUT3JieFVjem50UXNzVXU1SmF4TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1756886703);

UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`role`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Administrator','admin@assessment.com',NULL,'$2y$12$bjc4lum5NVO3pbwvo9NsGOt6UHnKB6URWo4H2ckuTUVJ4rjBca6.K','admin',NULL,'2025-09-03 03:29:22','2025-09-03 03:29:22'),
(13,'John Doe','john.doe@example.com',NULL,'$2y$12$v9k0BGebXcg8nkGhW1B9B.pojqkOnPpQAetnE2WvXKNSBytL0Gwtu','peserta',NULL,'2025-09-03 04:33:25','2025-09-03 04:33:25'),
(14,'Jane Smith','jane.smith@example.com',NULL,'$2y$12$2HWtspULptAI11W2Dk0JiO.ee/zAjv.nayTLx4/mJIMpwGyyoE4zm','peserta',NULL,'2025-09-03 04:33:25','2025-09-03 04:33:25'),
(15,'sahrono','sah.rono@example.com',NULL,'$2y$12$ZKHQsnlFMQzLndfEbDrEXuj.ES/ByohiFR6XfjOHghtM.8jHZ8rwq','peserta',NULL,'2025-09-03 04:33:25','2025-09-03 04:33:25');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
