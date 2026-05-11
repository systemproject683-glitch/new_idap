-- PostgreSQL-compatible dump converted from MySQL
-- Original: idap_db_1
-- Converted: March 17, 2026

BEGIN;

-- --------------------------------------------------------
-- Enum types
-- --------------------------------------------------------

CREATE TYPE development_objective_status AS ENUM ('pending', 'in_progress', 'completed');
CREATE TYPE verification_status_type AS ENUM ('pending', 'approved', 'rejected');

-- --------------------------------------------------------
-- Table: users (created first due to foreign key dependencies)
-- --------------------------------------------------------

CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  first_name VARCHAR(255) NOT NULL,
  middle_name VARCHAR(255) DEFAULT NULL,
  last_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  department VARCHAR(255) DEFAULT NULL,
  role VARCHAR(255) NOT NULL DEFAULT 'faculty',
  academic_rank VARCHAR(255) DEFAULT NULL,
  regularized_at DATE DEFAULT NULL,
  email_verified_at TIMESTAMP DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: admins
-- --------------------------------------------------------

CREATE TABLE admins (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: cache
-- --------------------------------------------------------

CREATE TABLE cache (
  key VARCHAR(255) PRIMARY KEY,
  value TEXT NOT NULL,
  expiration INTEGER NOT NULL
);

CREATE INDEX cache_expiration_index ON cache (expiration);

-- --------------------------------------------------------
-- Table: cache_locks
-- --------------------------------------------------------

CREATE TABLE cache_locks (
  key VARCHAR(255) PRIMARY KEY,
  owner VARCHAR(255) NOT NULL,
  expiration INTEGER NOT NULL
);

CREATE INDEX cache_locks_expiration_index ON cache_locks (expiration);

-- --------------------------------------------------------
-- Table: conducted_interventions
-- --------------------------------------------------------

CREATE TABLE conducted_interventions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  type_of_lnd VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  date_conducted DATE DEFAULT NULL,
  duration VARCHAR(255) DEFAULT NULL,
  leaving_service_provided VARCHAR(255) DEFAULT NULL,
  target_number_of_participants INTEGER DEFAULT NULL,
  actual_number_of_participants INTEGER DEFAULT NULL,
  completion_rate INTEGER DEFAULT NULL,
  proof_of_documentation VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: development_objectives
-- --------------------------------------------------------

CREATE TABLE development_objectives (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT DEFAULT NULL REFERENCES users(id) ON DELETE CASCADE,
  objective VARCHAR(255) NOT NULL,
  title VARCHAR(255) DEFAULT NULL,
  lnd_type VARCHAR(255) DEFAULT NULL,
  lnd_title VARCHAR(255) DEFAULT NULL,
  lnd_period_date VARCHAR(255) DEFAULT NULL,
  lnd_hours DECIMAL(8,2) DEFAULT NULL,
  lnd_proof_completion TEXT,
  action_plan TEXT NOT NULL,
  number_of_hours INTEGER DEFAULT NULL,
  budget_requirement DECIMAL(10,2) DEFAULT NULL,
  target_period VARCHAR(255) DEFAULT NULL,
  target_date_from VARCHAR(255) DEFAULT NULL,
  target_date_to VARCHAR(255) DEFAULT NULL,
  support_required TEXT,
  file_path VARCHAR(255) DEFAULT NULL,
  file_name VARCHAR(255) DEFAULT NULL,
  max_files INTEGER NOT NULL DEFAULT 1,
  status development_objective_status NOT NULL DEFAULT 'pending',
  is_admin_created BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: development_objective_files
-- --------------------------------------------------------

CREATE TABLE development_objective_files (
  id BIGSERIAL PRIMARY KEY,
  development_objective_id BIGINT NOT NULL REFERENCES development_objectives(id) ON DELETE CASCADE,
  file_path VARCHAR(255) NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  verification_status verification_status_type NOT NULL DEFAULT 'pending',
  rejection_reason TEXT,
  verified_at TIMESTAMP DEFAULT NULL,
  verified_by BIGINT DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: failed_jobs
-- --------------------------------------------------------

CREATE TABLE failed_jobs (
  id BIGSERIAL PRIMARY KEY,
  uuid VARCHAR(255) NOT NULL UNIQUE,
  connection TEXT NOT NULL,
  queue TEXT NOT NULL,
  payload TEXT NOT NULL,
  exception TEXT NOT NULL,
  failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Table: jobs
-- --------------------------------------------------------

CREATE TABLE jobs (
  id BIGSERIAL PRIMARY KEY,
  queue VARCHAR(255) NOT NULL,
  payload TEXT NOT NULL,
  attempts SMALLINT NOT NULL,
  reserved_at INTEGER DEFAULT NULL,
  available_at INTEGER NOT NULL,
  created_at INTEGER NOT NULL
);

CREATE INDEX jobs_queue_index ON jobs (queue);

-- --------------------------------------------------------
-- Table: job_batches
-- --------------------------------------------------------

CREATE TABLE job_batches (
  id VARCHAR(255) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  total_jobs INTEGER NOT NULL,
  pending_jobs INTEGER NOT NULL,
  failed_jobs INTEGER NOT NULL,
  failed_job_ids TEXT NOT NULL,
  options TEXT,
  cancelled_at INTEGER DEFAULT NULL,
  created_at INTEGER NOT NULL,
  finished_at INTEGER DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: migrations
-- --------------------------------------------------------

CREATE TABLE migrations (
  id SERIAL PRIMARY KEY,
  migration VARCHAR(255) NOT NULL,
  batch INTEGER NOT NULL
);

-- --------------------------------------------------------
-- Table: password_reset_tokens
-- --------------------------------------------------------

CREATE TABLE password_reset_tokens (
  email VARCHAR(255) PRIMARY KEY,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: proposed_interventions
-- --------------------------------------------------------

CREATE TABLE proposed_interventions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  title VARCHAR(255) NOT NULL,
  objectives TEXT NOT NULL,
  budget VARCHAR(255) DEFAULT NULL,
  expected_number_of_participants INTEGER DEFAULT NULL,
  dates DATE DEFAULT NULL,
  person_responsible VARCHAR(255) DEFAULT NULL,
  target_participants VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- --------------------------------------------------------
-- Table: sessions
-- --------------------------------------------------------

CREATE TABLE sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id BIGINT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT,
  payload TEXT NOT NULL,
  last_activity INTEGER NOT NULL
);

CREATE INDEX sessions_user_id_index ON sessions (user_id);
CREATE INDEX sessions_last_activity_index ON sessions (last_activity);

-- ========================================================
-- DATA INSERTS
-- ========================================================

-- --------------------------------------------------------
-- Data for table: users
-- --------------------------------------------------------

INSERT INTO users (id, first_name, middle_name, last_name, email, department, role, academic_rank, regularized_at, email_verified_at, password, remember_token, created_at, updated_at) VALUES
(1, 'Gabriel', 'Espiritu', 'Nepomuceno', 'gabriel@example.com', 'DIT', 'faculty', NULL, '2026-02-26', NULL, '$2y$12$WZrgUcnzxq1iWZ.5tkC5geJnUYrsm7Cgz1iF3.xvX.KSQAmF8ZQ3O', NULL, '2026-02-26 03:57:12', '2026-02-26 03:57:12'),
(2, 'Lorie Jane', NULL, 'Aguilar', 'lorie@example.com', 'DIT', 'chairperson', NULL, '2020-01-01', NULL, '$2y$12$lNTh0Dgz61TlAXZt3zb79OvsaXuas8lCN4gOwYeg2uBwiQ3rCWaIa', NULL, '2026-03-04 02:19:28', '2026-03-04 02:19:28'),
(3, 'Kim Zyrene', NULL, 'Retania', 'kim@example.com', 'DIET', 'chairperson', NULL, '2020-01-01', NULL, '$2y$12$lYbGuzTsmpWOzV.07VqbROzgg.aI4VLnefDZNIdX6Ito6pvPhrVTa', NULL, '2026-03-04 02:24:25', '2026-03-04 02:24:25'),
(4, 'Lorens', NULL, 'Muella', 'lorens@example.com', 'DIET', 'faculty', NULL, '2020-01-01', NULL, '$2y$12$63V8aKsqhyJftAQ6ZFfSPe5TTC4HCbHfBKONP9lWZ/SAp9BNSEiMq', NULL, '2026-03-04 02:25:15', '2026-03-04 02:25:15'),
(5, 'Levy', NULL, 'Fidel', 'levy@example.com', 'DIT', 'faculty', 'University Professor', '2020-01-01', NULL, '$2y$12$mBPNh.QMdurryJhbruihDOuaWNoEe6YKrOJgAbyf16s1gfPLiNohu', NULL, '2026-03-05 06:20:52', '2026-03-05 06:20:52');

-- --------------------------------------------------------
-- Data for table: admins
-- --------------------------------------------------------

INSERT INTO admins (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES
(3, 'Admin', 'admin@example.com', NULL, '$2y$12$xSWLmIAFxUXz5fn9h2U3/uyY0TWJMYoeUkzcc2qekr0W7bwDMcCSe', NULL, '2026-02-26 03:43:33', '2026-02-26 03:43:33');

-- --------------------------------------------------------
-- Data for table: conducted_interventions
-- --------------------------------------------------------

INSERT INTO conducted_interventions (id, user_id, type_of_lnd, title, date_conducted, duration, leaving_service_provided, target_number_of_participants, actual_number_of_participants, completion_rate, proof_of_documentation, created_at, updated_at) VALUES
(1, 3, 'fgfchfchf', 'hfhfhf', NULL, '66', 'ghghgfhgh', 5, 6, 6, 'fhghgfh', '2026-03-05 05:56:09', '2026-03-05 05:56:09');

-- --------------------------------------------------------
-- Data for table: development_objectives
-- --------------------------------------------------------

INSERT INTO development_objectives (id, user_id, objective, title, lnd_type, lnd_title, lnd_period_date, lnd_hours, lnd_proof_completion, action_plan, number_of_hours, budget_requirement, target_period, target_date_from, target_date_to, support_required, file_path, file_name, max_files, status, is_admin_created, created_at, updated_at) VALUES
(1, 1, 'ASEAN Engineer/Architect', NULL, NULL, NULL, NULL, NULL, NULL, 'try', 100, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'pending', FALSE, '2026-02-26 03:58:38', '2026-02-26 03:58:38'),
(2, 1, 'Industry Immersion Program', NULL, NULL, NULL, NULL, NULL, NULL, 'test', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'pending', FALSE, '2026-02-26 08:54:14', '2026-02-26 08:54:14'),
(3, 4, 'Faculty & Staff Exchange Program', NULL, NULL, NULL, NULL, NULL, NULL, 'resrew', 33, 33.00, 'Q2', NULL, NULL, NULL, NULL, NULL, 1, 'pending', FALSE, '2026-03-04 02:25:57', '2026-03-04 02:25:57'),
(4, 4, 'Membership in International Organization & Networks', NULL, NULL, NULL, NULL, NULL, NULL, 'Participate in international professional organizations and networks to expand collaborations, visibility, and global engagement.', 500, NULL, 'Q3', 'January', 'March', NULL, NULL, NULL, 2, 'pending', FALSE, '2026-03-04 02:52:43', '2026-03-04 02:52:43'),
(5, 4, E'Skills Proficiency Certification \u2013 International', 'mema', NULL, NULL, NULL, NULL, NULL, 'frgrgrg', 55, NULL, NULL, 'October', 'November', NULL, NULL, NULL, 2, 'pending', FALSE, '2026-03-04 03:11:31', '2026-03-04 03:11:31'),
(6, 1, E'Training/Seminar \u2013 International', 'hhhl', E'Training/Seminar \u2013 International', 'xdfgd', 'march - april 2026', 34.00, 'bf d d', 'jkkljljl', 77, NULL, 'Q2', 'August', 'August', NULL, NULL, NULL, 2, 'pending', FALSE, '2026-03-04 03:35:17', '2026-03-04 06:17:47'),
(7, 1, E'Skills Proficiency Certification \u2013 International', 'klkgkj', E'Skills Proficiency Certification \u2013 International', 'ASfzsdg', 'march - april 2026', 28.00, 'zsdbfh', 'njnkjb', 88, NULL, 'Q3', 'July', 'August', NULL, NULL, NULL, 3, 'pending', FALSE, '2026-03-04 03:35:50', '2026-03-04 06:16:47'),
(8, 5, E'Graduate Studies \u2013 Master', 'test', 'test', 'test', 'march - april 2026', 35.00, 'test', 'test', 40, 10000.00, 'Q1', 'February', 'March', 'test', 'development-objectives/1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 1, 'completed', FALSE, '2026-03-08 10:38:41', '2026-03-11 00:20:59'),
(9, 5, 'ASEAN Engineer/Architect', 'try', NULL, NULL, NULL, NULL, NULL, 'try', 44, 2222.00, 'Q1', 'January', 'January', 'try', 'development-objectives/1773019222_Summary-of-LD-Interventions-Conducted (1).docx', '1773019222_Summary-of-LD-Interventions-Conducted (1).docx', 1, 'completed', FALSE, '2026-03-08 12:46:31', '2026-03-09 01:21:32'),
(10, 5, 'Industry Immersion Program', 'try', NULL, NULL, NULL, NULL, NULL, 'try', 12, 1000.00, 'Q2', 'January', 'February', 'try', NULL, NULL, 2, 'pending', FALSE, '2026-03-11 00:22:01', '2026-03-11 00:22:01'),
(11, 5, E'Paper Presentation \u2013 Local', 'test', NULL, NULL, NULL, NULL, NULL, 'test', 20, 4000.00, 'Q1', 'June', 'June', 'test', 'development-objectives/1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 3, 'in_progress', FALSE, '2026-03-11 00:26:58', '2026-03-11 00:27:52');

-- --------------------------------------------------------
-- Data for table: development_objective_files
-- --------------------------------------------------------

INSERT INTO development_objective_files (id, development_objective_id, file_path, file_name, verification_status, rejection_reason, verified_at, verified_by, created_at, updated_at) VALUES
(1, 9, 'development-objectives/1773019222_Summary-of-LD-Interventions-Conducted (1).docx', '1773019222_Summary-of-LD-Interventions-Conducted (1).docx', 'approved', NULL, '2026-03-09 01:21:32', 2, '2026-03-09 01:20:22', '2026-03-09 01:21:32'),
(2, 8, 'development-objectives/1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773187856_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 'approved', NULL, '2026-03-11 00:20:59', 2, '2026-03-11 00:10:57', '2026-03-11 00:20:59'),
(3, 11, 'development-objectives/1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', '1773188872_34a91072-2e4a-4d1c-8331-4d8497cc94bd.jpg', 'pending', NULL, NULL, NULL, '2026-03-11 00:27:52', '2026-03-11 00:27:52');

-- --------------------------------------------------------
-- Data for table: migrations
-- --------------------------------------------------------

INSERT INTO migrations (id, migration, batch) VALUES
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
-- Data for table: proposed_interventions
-- --------------------------------------------------------

INSERT INTO proposed_interventions (id, user_id, title, objectives, budget, expected_number_of_participants, dates, person_responsible, target_participants, created_at, updated_at) VALUES
(7, 3, 'Membership in International Organization & Networks', 'dfdfdfdfs', 'dsfsdfds', 9, '2026-04-02', 'fsdfdsf', 'sdfsdfdsf', '2026-03-05 05:33:38', '2026-03-05 05:33:38'),
(8, 3, 'dgdgdg', 'dgdgdg', '45', 4, '2026-04-04', 'dgfdgdf', 'fgdgdfg', '2026-03-05 05:48:47', '2026-03-05 05:48:47'),
(9, 3, 'ddggdg', 'hfhfhf', '66', 7, '2026-03-25', 'hfhfhfh', 'hgfhfhf', '2026-03-05 06:02:35', '2026-03-05 06:02:35'),
(10, 3, 'fhfhg', 'hgfghfghgf', '55', 6, '2026-04-02', 'hfghfhfg', 'fhghfghfg', '2026-03-05 06:02:58', '2026-03-05 06:02:58');

-- --------------------------------------------------------
-- Data for table: sessions
-- --------------------------------------------------------

INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity) VALUES
('3IkPT5ziuATqJMVIL21S4nzoX5UA6b1k5krVqSnc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiam9WTlhDVU53NElJcjRsc1g5TnN0dkdaam1sMTZqMFAwcFVjblQ2cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1773711171),
('MlH42MTgr0ByYJ53nfcvbsCJL8PHhCbcrNjOV7mj', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZEE0NDhhTkpJRWgzUW00a1VqeDRlZTg3MHZRQnd0UnVNdlBjVWpSTCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGV2ZWxvcG1lbnQtb2JqZWN0aXZlcyI7czo1OiJyb3V0ZSI7czoyODoiZGV2ZWxvcG1lbnQtb2JqZWN0aXZlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1773645125);

COMMIT;
