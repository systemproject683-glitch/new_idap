-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: idap-mysql
-- Generation Time: Mar 17, 2026 at 01:33 AM
-- Server version: 8.4.8
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `idap_db_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'admin@example.com', NULL, '$2y$12$xSWLmIAFxUXz5fn9h2U3/uyY0TWJMYoeUkzcc2qekr0W7bwDMcCSe', NULL, '2026-02-26 03:43:33', '2026-02-26 03:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conducted_interventions`
--

CREATE TABLE `conducted_interventions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type_of_lnd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_conducted` date DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leaving_service_provided` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_number_of_participants` int DEFAULT NULL,
  `actual_number_of_participants` int DEFAULT NULL,
  `completion_rate` int DEFAULT NULL,
  `proof_of_documentation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conducted_interventions`
--

INSERT INTO `conducted_interventions` (`id`, `user_id`, `type_of_lnd`, `title`, `date_conducted`, `duration`, `leaving_service_provided`, `target_number_of_participants`, `actual_number_of_participants`, `completion_rate`, `proof_of_documentation`, `created_at`, `updated_at`) VALUES
(1, 3, 'fgfchfchf', 'hfhfhf', NULL, '66', 'ghghgfhgh', 5, 6, 6, 'fhghgfh', '2026-03-05 05:56:09', '2026-03-05 05:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `development_objectives`
--

CREATE TABLE `development_objectives` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `objective` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lnd_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lnd_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lnd_period_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lnd_hours` decimal(8,2) DEFAULT NULL,
  `lnd_proof_completion` text COLLATE utf8mb4_unicode_ci,
  `action_plan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_hours` int UNSIGNED DEFAULT NULL,
  `budget_requirement` decimal(10,2) DEFAULT NULL,
  `target_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_date_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_date_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `support_required` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_files` int NOT NULL DEFAULT '1',
  `status` enum('pending','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_admin_created` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `development_objectives`
--

INSERT INTO `development_objectives` (`id`, `user_id`, `objective`, `title`, `lnd_type`, `lnd_title`, `lnd_period_date`, `lnd_hours`, `lnd_proof_completion`, `action_plan`, `number_of_hours`, `budget_requirement`, `target_period`, `target_date_from`, `target_date_to`, `support_required`, `file_path`, `file_name`, `max_files`, `status`, `is_admin_created`, `created_at`, `updated_at`) VALUES
(1, 1, 'ASEAN Engineer/Architect', NULL, NULL, NULL, NULL, NULL, NULL, 'try', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'pending', 0, '2026-02-26 03:58:38', '2026-02-26 03:58:38'),
(2, 1, 'Industry Immersion Program', NULL, NULL, NULL, NULL, NULL, NULL, 'test', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'pending', 0, '2026-02-26 08:54:14', '2026-02-26 08:54:14'),
(3, 4, 'Faculty & Staff Exchange Program', NULL, NULL, NULL, NULL, NULL, NULL, 'resrew', 33, 33.00, 'Q2', NULL, NULL, NULL, NULL, NULL, 1, 'pending', 0, '2026-03-04 02:25:57', '2026-03-04 02:25:57'),
(4, 4, 'Membership in International Organization & Networks', NULL, NULL, NULL, NULL, NULL, NULL, 'Participate in international professional organizations and networks to expand collaborations, visibility, and global engagement.', 500, NULL, 'Q3', 'January', 'March', NULL, NULL, NULL, 2, 'pending', 0, '2026-03-04 02:52:43', '2026-03-04 02:52:43'),
(5, 4, 'Skills Proficiency Certification – International', 'mema', NULL, NULL, NULL, NULL, NULL, 'frgrgrg', 55, NULL, NULL, 'October', 'November', NULL, NULL, NULL, 2, 'pending', 0, '2026-03-04 03:11:31', '2026-03-04 03:11:31'),
(6, 1, 'Training/Seminar – International', 'hhhl', 'Training/Seminar – International', 'xdfgd', 'march - april 2026', 34.00, 'bf d d', 'jkkljljl', 77, NULL, 'Q2', 'August', 'August', NULL, NULL, NULL, 2, 'pending', 0, '2026-03-04 03:35:17', '2026-03-04 06:17:47'),
(7, 1, 'Skills Proficiency Certification – International', 'klkgkj', 'Skills Proficiency Certification – International', 'ASfzsdg', 'march - april 2026', 28.00, 'zsdbfh', 'njnkjb', 88, NULL, 'Q3', 'July', 'August', NULL, NULL, NULL, 3, 'pending', 0, '2026-03-04 03:35:50', '2026-03-04 06:16:47'),
(8, 5, 'Graduate Studies – Master', 'test', 'test', 'test', 'march - april 2026', 35.00, 'test', 'test', 40, 10000.00, 'Q1', 'February', 'March', 'test', 'development-objectives/1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 1, 'completed', 0, '2026-03-08 10:38:41', '2026-03-11 00:20:59'),
(9, 5, 'ASEAN Engineer/Architect', 'try', NULL, NULL, NULL, NULL, NULL, 'try', 44, 2222.00, 'Q1', 'January', 'January', 'try', 'development-objectives/1773019222_Summary-of-LD-Interventions-Conducted (1).docx', '1773019222_Summary-of-LD-Interventions-Conducted (1).docx', 1, 'completed', 0, '2026-03-08 12:46:31', '2026-03-09 01:21:32'),
(10, 5, 'Industry Immersion Program', 'try', NULL, NULL, NULL, NULL, NULL, 'try', 12, 1000.00, 'Q2', 'January', 'February', 'try', NULL, NULL, 2, 'pending', 0, '2026-03-11 00:22:01', '2026-03-11 00:22:01'),
(11, 5, 'Paper Presentation – Local', 'test', NULL, NULL, NULL, NULL, NULL, 'test', 20, 4000.00, 'Q1', 'June', 'June', 'test', 'development-objectives/1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 3, 'in_progress', 0, '2026-03-11 00:26:58', '2026-03-11 00:27:52');

-- --------------------------------------------------------

--
-- Table structure for table `development_objective_files`
--

CREATE TABLE `development_objective_files` (
  `id` bigint UNSIGNED NOT NULL,
  `development_objective_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `development_objective_files`
--

INSERT INTO `development_objective_files` (`id`, `development_objective_id`, `file_path`, `file_name`, `verification_status`, `rejection_reason`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES
(1, 9, 'development-objectives/1773019222_Summary-of-LD-Interventions-Conducted (1).docx', '1773019222_Summary-of-LD-Interventions-Conducted (1).docx', 'approved', NULL, '2026-03-09 01:21:32', 2, '2026-03-09 01:20:22', '2026-03-09 01:21:32'),
(2, 8, 'development-objectives/1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 'approved', NULL, '2026-03-11 00:20:59', 2, '2026-03-11 00:10:57', '2026-03-11 00:20:59'),
(3, 11, 'development-objectives/1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 'pending', NULL, NULL, NULL, '2026-03-11 00:27:52', '2026-03-11 00:27:52');

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_10_031444_create_admins_table', 1),
(5, '2026_02_10_050247_add_department_to_users_table', 1),
(6, '2026_02_10_053339_add_role_to_users_table', 1),
(7, '2026_02_10_064316_create_development_objectives_table', 1),
(8, '2026_02_10_065439_add_is_admin_created_to_development_objectives_table', 1),
(9, '2026_02_10_065913_modify_user_id_nullable_in_development_objectives', 1),
(10, '2026_02_10_164200_add_file_upload_to_development_objectives', 1),
(11, '2026_02_11_090200_add_max_files_to_development_objectives', 1),
(12, '2026_02_11_090300_create_development_objective_files_table', 1),
(13, '2026_02_11_120000_add_file_verification_fields', 1),
(14, '2026_02_16_011950_make_max_files_nullable_in_development_objectives_table', 1),
(15, '2026_02_16_051552_split_name_to_first_middle_last_name_in_users_table', 1),
(16, '2026_02_23_000000_add_budget_target_support_to_development_objectives_table', 1),
(17, '2026_02_23_000001_add_regularized_at_to_users_table', 1),
(18, '2026_02_25_120000_add_number_of_hours_to_development_objectives_table', 1),
(19, '2026_02_25_130000_add_academic_rank_to_users_table', 1),
(20, '2026_03_04_add_target_dates_to_development_objectives', 2),
(21, '2026_03_04_add_title_to_development_objectives', 3),
(22, '2026_03_05_add_actual_lnd_fields_to_development_objectives', 4),
(24, '2026_03_05_create_proposed_interventions_table', 5),
(26, '2026_03_05_create_conducted_interventions_table', 6);

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
-- Table structure for table `proposed_interventions`
--

CREATE TABLE `proposed_interventions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objectives` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_number_of_participants` int DEFAULT NULL,
  `dates` date DEFAULT NULL,
  `person_responsible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_participants` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposed_interventions`
--

INSERT INTO `proposed_interventions` (`id`, `user_id`, `title`, `objectives`, `budget`, `expected_number_of_participants`, `dates`, `person_responsible`, `target_participants`, `created_at`, `updated_at`) VALUES
(7, 3, 'Membership in International Organization & Networks', 'dfdfdfdfs', 'dsfsdfds', 9, '2026-04-02', 'fsdfdsf', 'sdfsdfdsf', '2026-03-05 05:33:38', '2026-03-05 05:33:38'),
(8, 3, 'dgdgdg', 'dgdgdg', '45', 4, '2026-04-04', 'dgfdgdf', 'fgdgdfg', '2026-03-05 05:48:47', '2026-03-05 05:48:47'),
(9, 3, 'ddggdg', 'hfhfhf', '66', 7, '2026-03-25', 'hfhfhfh', 'hgfhfhf', '2026-03-05 06:02:35', '2026-03-05 06:02:35'),
(10, 3, 'fhfhg', 'hgfghfghgf', '55', 6, '2026-04-02', 'hfghfhfg', 'fhghfghfg', '2026-03-05 06:02:58', '2026-03-05 06:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3IkPT5ziuATqJMVIL21S4nzoX5UA6b1k5krVqSnc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiam9WTlhDVU53NElJcjRsc1g5TnN0dkdaam1sMTZqMFAwcFVjblQ2cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1773711171),
('MlH42MTgr0ByYJ53nfcvbsCJL8PHhCbcrNjOV7mj', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZEE0NDhhTkpJRWgzUW00a1VqeDRlZTg3MHZRQnd0UnVNdlBjVWpSTCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGV2ZWxvcG1lbnQtb2JqZWN0aXZlcyI7czo1OiJyb3V0ZSI7czoyODoiZGV2ZWxvcG1lbnQtb2JqZWN0aXZlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1773645125);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'faculty',
  `academic_rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regularized_at` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `department`, `role`, `academic_rank`, `regularized_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Gabriel', 'Espiritu', 'Nepomuceno', 'gabriel@example.com', 'DIT', 'faculty', NULL, '2026-02-26', NULL, '$2y$12$WZrgUcnzxq1iWZ.5tkC5geJnUYrsm7Cgz1iF3.xvX.KSQAmF8ZQ3O', NULL, '2026-02-26 03:57:12', '2026-02-26 03:57:12'),
(2, 'Lorie Jane', NULL, 'Aguilar', 'lorie@example.com', 'DIT', 'chairperson', NULL, '2020-01-01', NULL, '$2y$12$lNTh0Dgz61TlAXZt3zb79OvsaXuas8lCN4gOwYeg2uBwiQ3rCWaIa', NULL, '2026-03-04 02:19:28', '2026-03-04 02:19:28'),
(3, 'Kim Zyrene', NULL, 'Retania', 'kim@example.com', 'DIET', 'chairperson', NULL, '2020-01-01', NULL, '$2y$12$lYbGuzTsmpWOzV.07VqbROzgg.aI4VLnefDZNIdX6Ito6pvPhrVTa', NULL, '2026-03-04 02:24:25', '2026-03-04 02:24:25'),
(4, 'Lorens', NULL, 'Muella', 'lorens@example.com', 'DIET', 'faculty', NULL, '2020-01-01', NULL, '$2y$12$63V8aKsqhyJftAQ6ZFfSPe5TTC4HCbHfBKONP9lWZ/SAp9BNSEiMq', NULL, '2026-03-04 02:25:15', '2026-03-04 02:25:15'),
(5, 'Levy', NULL, 'Fidel', 'levy@example.com', 'DIT', 'faculty', 'University Professor', '2020-01-01', NULL, '$2y$12$mBPNh.QMdurryJhbruihDOuaWNoEe6YKrOJgAbyf16s1gfPLiNohu', NULL, '2026-03-05 06:20:52', '2026-03-05 06:20:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `conducted_interventions`
--
ALTER TABLE `conducted_interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conducted_interventions_user_id_foreign` (`user_id`);

--
-- Indexes for table `development_objectives`
--
ALTER TABLE `development_objectives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `development_objectives_user_id_foreign` (`user_id`);

--
-- Indexes for table `development_objective_files`
--
ALTER TABLE `development_objective_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `development_objective_files_development_objective_id_foreign` (`development_objective_id`),
  ADD KEY `development_objective_files_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `proposed_interventions`
--
ALTER TABLE `proposed_interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposed_interventions_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `conducted_interventions`
--
ALTER TABLE `conducted_interventions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `development_objectives`
--
ALTER TABLE `development_objectives`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `development_objective_files`
--
ALTER TABLE `development_objective_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `proposed_interventions`
--
ALTER TABLE `proposed_interventions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conducted_interventions`
--
ALTER TABLE `conducted_interventions`
  ADD CONSTRAINT `conducted_interventions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `development_objectives`
--
ALTER TABLE `development_objectives`
  ADD CONSTRAINT `development_objectives_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `development_objective_files`
--
ALTER TABLE `development_objective_files`
  ADD CONSTRAINT `development_objective_files_development_objective_id_foreign` FOREIGN KEY (`development_objective_id`) REFERENCES `development_objectives` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `development_objective_files_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `proposed_interventions`
--
ALTER TABLE `proposed_interventions`
  ADD CONSTRAINT `proposed_interventions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
