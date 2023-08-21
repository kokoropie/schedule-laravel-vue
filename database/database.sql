-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.33 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table schedule.configs
CREATE TABLE IF NOT EXISTS `configs` (
  `name` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.configs: ~3 rows (approximately)
INSERT INTO `configs` (`name`, `content`) VALUES
	('numOfClassPeriodsPerDay', '[12]'),
	('timeOfEachClassPeriod', '{"1": {"end": [7, 45], "start": [7, 0]}, "2": {"end": [8, 35], "start": [7, 50]}, "3": {"end": [9, 25], "start": [8, 40]}, "4": {"end": [10, 20], "start": [9, 35]}, "5": {"end": [11, 10], "start": [10, 25]}, "6": {"end": [12, 0], "start": [11, 15]}, "7": {"end": [13, 15], "start": [12, 30]}, "8": {"end": [14, 5], "start": [13, 20]}, "9": {"end": [14, 55], "start": [14, 10]}, "10": {"end": [15, 50], "start": [15, 5]}, "11": {"end": [16, 40], "start": [15, 55]}, "12": {"end": [17, 30], "start": [16, 45]}}'),
	('title', '["Thời khóa biểu"]');

-- Dumping structure for table schedule.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table schedule.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table schedule.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.migrations: ~8 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_05_14_114719_create_configs_table', 1),
	(6, '2023_07_03_161548_create_teachers_table', 1),
	(7, '2023_07_03_162349_create_subjects_table', 1),
	(8, '2023_07_03_162401_create_schedules_table', 1);

-- Dumping structure for table schedule.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table schedule.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table schedule.schedules
CREATE TABLE IF NOT EXISTS `schedules` (
  `schedule_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` tinyint NOT NULL,
  `end` tinyint NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `dateOfWeek` enum('1','2','3','4','5','6','7') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2',
  `type` enum('ONLINE','OFFLINE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OFFLINE',
  PRIMARY KEY (`schedule_id`),
  KEY `schedules_subject_id_foreign` (`subject_id`),
  CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.schedules: ~14 rows (approximately)
INSERT INTO `schedules` (`schedule_id`, `subject_id`, `start`, `end`, `from`, `to`, `dateOfWeek`, `type`) VALUES
	(1, 'QK', 1, 12, '2023-09-01', '2023-09-01', '6', 'OFFLINE'),
	(2, 'QK', 1, 12, '2023-09-02', '2023-09-02', '7', 'OFFLINE'),
	(3, 'QK', 1, 12, '2023-09-03', '2023-09-03', '1', 'OFFLINE'),
	(4, 'QK', 1, 12, '2023-09-04', '2023-09-04', '2', 'OFFLINE'),
	(5, 'NGVN', 1, 12, '2023-11-20', '2023-11-20', '2', 'OFFLINE'),
	(6, 'T12', 1, 5, '2023-08-14', '2023-10-23', '2', 'OFFLINE'),
	(7, 'T13', 7, 11, '2023-08-15', '2023-10-24', '3', 'OFFLINE'),
	(8, 'T7', 7, 12, '2023-08-17', '2023-10-12', '5', 'OFFLINE'),
	(9, 'B13', 4, 6, '2023-08-18', '2023-12-29', '6', 'OFFLINE'),
	(10, 'B13', 4, 6, '2023-12-28', '2023-12-28', '5', 'OFFLINE'),
	(11, 'T3', 7, 11, '2023-08-19', '2023-11-11', '7', 'OFFLINE'),
	(12, 'T23', 7, 11, '2023-11-18', '2023-12-23', '7', 'OFFLINE'),
	(13, 'B19', 1, 4, '2023-10-04', '2023-11-15', '4', 'OFFLINE'),
	(14, 'B19', 1, 2, '2023-11-22', '2023-11-22', '4', 'OFFLINE');

-- Dumping structure for table schedule.subjects
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credits` tinyint NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`subject_id`),
  KEY `subjects_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.subjects: ~9 rows (approximately)
INSERT INTO `subjects` (`subject_id`, `name`, `credits`, `teacher_id`) VALUES
	('B13', 'Tiếng Anh 4', 3, 6),
	('B19', 'Giáo dục thể chất 3', 1, 7),
	('NGVN', 'Ngày nhà giáo Việt Nam', 1, 16),
	('QK', 'Ngày Quốc Khánh', 1, 16),
	('T12', 'Công nghệ phần mềm', 3, 3),
	('T13', 'Phân tích và thiết kế thuật toán', 3, 1),
	('T23', 'Mạng máy tính nâng cao', 3, 5),
	('T3', 'An toàn mạng máy tính', 3, 4),
	('T7', 'Nguyên lý hệ điều hành', 3, 2);

-- Dumping structure for table schedule.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.teachers: ~7 rows (approximately)
INSERT INTO `teachers` (`teacher_id`, `name`) VALUES
	(1, 'Nguyễn Minh Đế'),
	(2, 'Đặng Anh Tuấn'),
	(3, 'Nguyễn Văn Thịnh'),
	(4, 'Trần Đắc Tốt'),
	(5, 'Nguyễn Ngọc Đại'),
	(6, 'Nguyễn Ngọc Thành'),
	(7, 'Trần Văn Khang'),
	(16, 'Ngày nghỉ');

-- Dumping structure for table schedule.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table schedule.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Akatsuki Kaga', 'filberttkarry2210@gmail.com', NULL, '$2y$10$XMvOhDhcSpvBDu67wJySIeSmWGS4OSfHkToa0bR5pUnbUyIhiSvaG', 'RqVIEFgrZ0TZ4IP0A7LfWYhlTb2Epc6pOm1Y5qBYHLRt9vZgVC50h8jW3qw3', '2023-07-03 09:34:19', '2023-07-03 09:34:19');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
