-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2023 at 09:37 AM
-- Server version: 10.6.15-MariaDB-cll-lve
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `getdenta_microfinance`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `category_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `code`, `status`, `category_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cash and Bank', '1001', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(2, 'Cash Investment', '1002', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(3, 'Loan Portfolio', '1003', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(4, 'Receivable', '1004', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(5, 'Tax Paid on Purchase', '1005', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(6, 'Current Asset', '1006', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(7, 'Non-current Asset', '1007', 'Active', 1, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(8, 'Equity', '2001', 'Active', 2, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(9, 'Retained Earnings', '2002', 'Active', 2, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(10, 'Accumulated Depreciation', '3001', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(11, 'Loan Impairment', '3002', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(12, 'Savings', '3003', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(13, 'Investor Deposit', '3004', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(14, 'Liability', '3005', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(15, 'Merchant Borrowing', '3007', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(16, 'Payable', '3008', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(17, 'Taxes', '3009', 'Active', 3, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(18, 'Revenue from Applications', '4001', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(19, 'Revenue from Loan Repayments', '4002', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(20, 'Revenue from Deposit', '4003', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(21, 'Revenue from Lender Investment', '4004', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(22, 'Non-operating Revenue', '4005', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(23, 'Subsidy', '4006', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(24, 'Membership', '4007', 'Active', 4, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(25, 'Asset Disposal', '5001', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(26, 'Default Loan', '5002', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(27, 'Depreciation', '5003', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(28, 'Exchange Rate Loss', '5004', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(29, 'Expenses on Deposit', '5005', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(30, 'Expenses on Borrowing', '5006', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(31, 'Miscellaneous Expense', '5007', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(32, 'Non-operating Expense', '5008', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(33, 'Provision for Loan Impairment', '5009', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(34, 'Restructured Loan', '5010', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL),
(35, 'Tax', '5011', 'Active', 5, '2023-10-03 08:34:55', '2023-10-03 08:34:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_requests`
--

CREATE TABLE `api_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `method` enum('GET','POST','PUT','PATCH','DELETE') NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('PENDING','SUCCESS','FAILED') NOT NULL,
  `error_message` text DEFAULT NULL,
  `input` text DEFAULT NULL,
  `output` text DEFAULT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `api_requests`
--

INSERT INTO `api_requests` (`id`, `url`, `method`, `ip_address`, `user_agent`, `status`, `error_message`, `input`, `output`, `uuid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '/api/client/login', 'GET', '41.75.182.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'af958898-80ef-4c40-905d-22337577f061', '2023-11-27 02:59:25', '2023-11-27 02:59:25', NULL),
(2, '/api/client/login', 'GET', '41.75.182.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'f6c6ad3c-b916-4774-869d-ec8e8cd8bfca', '2023-11-27 03:00:06', '2023-11-27 03:00:06', NULL),
(3, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', '72958ff4-6c50-4a89-955c-770ca111e38f', '2023-11-27 10:58:55', '2023-11-27 10:58:55', NULL),
(4, '/api/client/auth', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'def732f5-3a00-40d7-8b79-9e61cf8d8735', '2023-11-27 11:00:12', '2023-11-27 11:00:12', NULL),
(5, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"phone\":\"The phone field is required.\",\"password\":\"The password field is required.\"},\"version\":\"1.0.0\"}', '2869412d-7093-4ca3-aa13-222a4fd8a477', '2023-11-27 11:01:07', '2023-11-27 11:01:07', NULL),
(6, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"phone\":\"The phone field is not in the correct format.\",\"password\":\"The password field is required.\"},\"version\":\"1.0.0\"}', '739c849c-78bd-4043-a838-0eaa90c1a665', '2023-11-27 11:01:40', '2023-11-27 11:01:40', NULL),
(7, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"password\":\"The password field is required.\"},\"version\":\"1.0.0\"}', 'b9d986f1-f6bc-4a01-a890-c3e697895546', '2023-11-27 11:02:03', '2023-11-27 11:02:03', NULL),
(8, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"password\":\"The password field is required.\"},\"version\":\"1.0.0\"}', 'b46d4b73-8397-4cfc-8036-86a258d19b5c', '2023-11-27 11:02:14', '2023-11-27 11:02:14', NULL),
(9, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Email Provided. Try again with correct email', NULL, '{\"status\":false,\"message\":\"Wrong Email Provided. Try again with correct email\",\"data\":{\"error\":\"wrongEmail\"},\"version\":\"1.0.0\"}', '8c6707fa-dff5-4317-bf80-0059d9bede00', '2023-11-27 11:02:32', '2023-11-27 11:02:32', NULL),
(10, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', '2fbf3c68-d33b-4a5a-9920-d38c0e3f12fb', '2023-11-27 11:03:04', '2023-11-27 11:03:04', NULL),
(11, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Email Provided. Try again with correct email', NULL, '{\"status\":false,\"message\":\"Wrong Email Provided. Try again with correct email\",\"data\":{\"error\":\"wrongEmail\"},\"version\":\"1.0.0\"}', 'f8d0d954-7fcd-4ff7-b3a9-bc9cf4c1bb5f', '2023-11-27 11:03:24', '2023-11-27 11:03:24', NULL),
(12, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Success. Redirecting to dashboard\",\"data\":{\"client_id\":\"600\",\"userlog_id\":130,\"name\":\"Danfodio\",\"email\":\"daniondanfodio@gmail.com\",\"branch_id\":\"1\",\"photo\":null,\"token\":\"dqfun2ScY9R5kvjJKxzZwTsDMNBPbAC6\",\"client\":true},\"version\":\"1.0.0\"}', 'a9cf9105-c714-4d5a-bbec-ab85fedd81f0', '2023-11-27 11:04:47', '2023-11-27 11:04:47', NULL),
(13, '/api/client/dashboard', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Dashboard\",\"data\":{\"title\":\"Dashboard\",\"menu\":\"Dashboard\",\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$jfA81eZzI6ndtlz9D7zCieMz7i1.A0A5ClCBSVtE74hjvccmE7hvq\",\"token\":\"$2y$10$PGlVL1d6MicUJQantNBdF.Wa9dfXPwZgk0tm5nl9eELIazS1nYHVq\",\"token_expire_date\":\"2023-11-27 11:04:47\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:04:47\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"},\"logs\":[{\"id\":\"130\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:04:47\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"online\",\"token\":\"$2y$10$Nbx2GHXjbW6i1hAH10bUHeuHOThCHoy1lgmJBPt7HCecg636gHz9O\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:04:47\",\"updated_at\":\"2023-11-27 11:04:47\",\"deleted_at\":null},{\"id\":\"129\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 02:57:57\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"online\",\"token\":\"$2y$10$0cwOt0yZyW5v0fUVwS2rS.dBSxioynKwgKAURmpHwkLw0xGBbGGfS\",\"referrer_link\":\"https:\\/\\/www.sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 10:57:57\",\"updated_at\":\"2023-11-27 10:57:57\",\"deleted_at\":null},{\"id\":\"125\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 00:24:38\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$3vo8GWDrmyd\\/5Ffq3wVwnOcDtqJynM1fXZCg8ql4UX.g4MIZ0uZ7S\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 00:24:38\",\"updated_at\":\"2023-11-27 00:24:38\",\"deleted_at\":null},{\"id\":\"124\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:54:17\",\"logout_at\":\"2023-11-26 16:54:39\",\"duration\":\"-02:59:38\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$3JWamoUjUagW4WNGxJPMuuRR9nqsCas4CGQF77UF0XbSDlDQMZDVG\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:54:17\",\"updated_at\":\"2023-11-26 16:54:39\",\"deleted_at\":null},{\"id\":\"123\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:53:29\",\"logout_at\":\"2023-11-26 16:54:05\",\"duration\":\"-02:59:24\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$MWYWa4eZsk7s.dI2TJDCZ.SiBLKEySJ5W36nXaKw8nNgg65gKd7Wy\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:53:29\",\"updated_at\":\"2023-11-26 16:54:05\",\"deleted_at\":null},{\"id\":\"122\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-26 19:47:02\",\"logout_at\":\"2023-11-26 16:48:20\",\"duration\":\"-02:58:42\",\"ip_address\":\"::1\",\"browser\":\"Edge\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$eKdbDJViIhqKj3FaeSXBBefD6nKbydEPm75F2FXhzv\\/96VdES8bfS\",\"referrer_link\":\"http:\\/\\/localhost:8080\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:47:02\",\"updated_at\":\"2023-11-26 16:48:20\",\"deleted_at\":null},{\"id\":\"121\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:42:58\",\"logout_at\":\"2023-11-26 16:45:36\",\"duration\":\"-02:57:22\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$2onRAsc3zE6qxJfsvM0G5ejt0dTin6H9bmMaMw3TFt.X5\\/55I70ji\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:42:58\",\"updated_at\":\"2023-11-26 16:45:36\",\"deleted_at\":null},{\"id\":\"120\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:53:54\",\"logout_at\":\"2023-11-26 16:38:44\",\"duration\":\"-02:15:10\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BgR7KwRryV1vs8vZxStloe1lidun3D.o94VzqiFZQsw8SiMc48yQC\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:53:54\",\"updated_at\":\"2023-11-26 16:38:44\",\"deleted_at\":null},{\"id\":\"119\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:30:03\",\"logout_at\":\"2023-11-26 15:51:48\",\"duration\":\"-02:38:15\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BuMX2xrC7FP1iWMq0XZnSO2xdycD4rRyo7aCkdRKtN1s1g.U1fxq.\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:30:03\",\"updated_at\":\"2023-11-26 15:51:48\",\"deleted_at\":null},{\"id\":\"117\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-25 20:57:09\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"127.0.0.1\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$jAtwHVm07UiVy76jlUcizuTgcp61s0vGfMgRyOD9UWPEVrOlbHZ06\",\"referrer_link\":\"http:\\/\\/smicrofinance.com\\/client\\/account\\/token\\/verification\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-25 20:57:09\",\"updated_at\":\"2023-11-25 20:57:09\",\"deleted_at\":null}],\"loan\":{\"total\":null,\"paid\":null,\"balance\":null},\"products\":[{\"id\":\"8\",\"product_name\":\"Nexen Express\",\"interest_rate\":\"12.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"this is a weekly loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"500000\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:16:50\",\"updated_at\":\"2023-10-18 14:05:33\",\"deleted_at\":null},{\"id\":\"7\",\"product_name\":\"Kagwilawo\",\"interest_rate\":\"20.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"4\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"This loan has no application fees but its 20% upfront deducted on disbursement\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:0;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"0\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:12:47\",\"updated_at\":\"2023-10-18 21:12:50\",\"deleted_at\":null},{\"id\":\"6\",\"product_name\":\"Weyagale\",\"interest_rate\":\"4.50\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"This is a business loan from 30m and above\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"30000000\",\"max_principal\":\"100000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:06:47\",\"updated_at\":\"2023-10-18 21:11:10\",\"deleted_at\":null},{\"id\":\"5\",\"product_name\":\"Tambula Loan\",\"interest_rate\":\"5.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Tambula is a business loan from 5m to 29m\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"5000000\",\"max_principal\":\"29000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:03:02\",\"updated_at\":\"2023-10-18 14:05:59\",\"deleted_at\":null},{\"id\":\"4\",\"product_name\":\"Karibu Loan\",\"interest_rate\":\"8.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Karibu Loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"1500000\",\"max_principal\":\"10000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 11:43:58\",\"updated_at\":\"2023-10-18 14:04:14\",\"deleted_at\":null}],\"disbursements\":[],\"repayments\":[]},\"version\":\"1.0.0\"}', '30e1f6b2-605c-475e-a5db-f1688c43ff3f', '2023-11-27 11:05:46', '2023-11-27 11:05:46', NULL),
(14, '/api/client/logout', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You Logged Out Successfully\",\"data\":{\"url\":\"\\/api\\/client\\/auth\"},\"version\":\"1.0.0\"}', '0d936d80-d62c-43bf-878a-b03a9e4ace9f', '2023-11-27 11:08:39', '2023-11-27 11:08:39', NULL),
(15, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', '4c0ecec2-20df-499b-bef2-e9703616957d', '2023-11-27 11:09:21', '2023-11-27 11:09:21', NULL),
(16, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'f74ca2af-94a1-4523-b99d-24549929c89d', '2023-11-27 11:10:21', '2023-11-27 11:10:21', NULL),
(17, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', 'f74b255f-f3bc-41eb-8470-fbdfb0a8b0b5', '2023-11-27 11:11:15', '2023-11-27 11:11:15', NULL),
(18, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', '553040cd-1ec9-4ca0-b647-719f796fe82f', '2023-11-27 11:11:53', '2023-11-27 11:11:53', NULL),
(19, '/api/client/auth', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'c5d39f60-e01b-4cbb-9bea-c73b19ebc7e2', '2023-11-27 11:17:31', '2023-11-27 11:17:31', NULL),
(20, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', '7db194c3-bee9-4988-93cd-e6f8906e6e85', '2023-11-27 11:18:38', '2023-11-27 11:18:38', NULL),
(21, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'f91bf7c5-da06-4988-84f4-03f42bdf7d8c', '2023-11-27 11:18:54', '2023-11-27 11:18:54', NULL),
(22, '/api/client/account/password/recovery', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"email\":\"The email field is required.\"},\"version\":\"1.0.0\"}', 'e93209e2-f90f-4592-8be9-7deb14523aea', '2023-11-27 11:25:20', '2023-11-27 11:25:20', NULL),
(23, '/api/client/account/password/recovery', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"email\":\"The email field must be at least 6 characters in length.\"},\"version\":\"1.0.0\"}', '50ca072b-8a0c-4347-a5af-638571994ea5', '2023-11-27 11:26:40', '2023-11-27 11:26:40', NULL),
(24, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"phone\":\"The phone field must be at least 10 characters in length.\"},\"version\":\"1.0.0\"}', '069cea82-1ad1-4437-92d1-94cb721a2b38', '2023-11-27 11:32:47', '2023-11-27 11:32:47', NULL),
(25, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"phone\":\"The phone field is not in the correct format.\"},\"version\":\"1.0.0\"}', 'c60a8dc5-8ecc-4f30-9abf-0cd83047b2b9', '2023-11-27 11:32:58', '2023-11-27 11:32:58', NULL),
(26, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', 'dfc334f8-bb3f-443d-bf6b-65e1a179a8fc', '2023-11-27 11:33:23', '2023-11-27 11:33:23', NULL),
(27, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', '3ace4bdc-210a-4436-b30a-279d314f62d4', '2023-11-27 11:33:36', '2023-11-27 11:33:36', NULL),
(28, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Validation Error.', NULL, '{\"status\":false,\"message\":\"Validation Error.\",\"data\":{\"phone\":\"The phone field is not in the correct format.\"},\"version\":\"1.0.0\"}', '90ce3107-5d03-4cd8-9939-9d51f19b8c03', '2023-11-27 11:33:42', '2023-11-27 11:33:42', NULL),
(29, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', '25b350fc-43a6-4ba3-9cc3-7307eb5e501a', '2023-11-27 11:33:56', '2023-11-27 11:33:56', NULL),
(30, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Success. Redirecting to dashboard\",\"data\":{\"client_id\":\"600\",\"userlog_id\":133,\"name\":\"Danfodio\",\"email\":\"daniondanfodio@gmail.com\",\"branch_id\":\"1\",\"photo\":null,\"token\":\"cCAtFgszXj6ovY2WfBh0pm8ZQ1qP5VuJ\",\"client\":true},\"version\":\"1.0.0\"}', '63b6f536-2bc4-414e-8e03-26c47be75839', '2023-11-27 11:42:44', '2023-11-27 11:42:44', NULL),
(31, '/api/client/logout', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You Logged Out Successfully\",\"data\":{\"url\":\"\\/api\\/client\\/auth\"},\"version\":\"1.0.0\"}', '88dc5502-1a64-4b02-8fc8-3493fc98f12d', '2023-11-27 11:43:00', '2023-11-27 11:43:00', NULL),
(32, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Success. Redirecting to dashboard\",\"data\":{\"client_id\":\"600\",\"userlog_id\":134,\"name\":\"Danfodio\",\"email\":\"daniondanfodio@gmail.com\",\"branch_id\":\"1\",\"photo\":null,\"token\":\"YqUWrgZtaz8m3G7S5pHEoVCcDQPX24Ad\",\"client\":true},\"version\":\"1.0.0\"}', 'e6265a24-0684-47a0-9c8c-18186859f5f6', '2023-11-27 11:43:33', '2023-11-27 11:43:33', NULL),
(33, '/api/client/profile', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Client User Profile\",\"data\":{\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$FxPiMwszFaFCS\\/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne\",\"token\":\"$2y$10$FTiAKRICBINxfVD0lBMxP.PLfxRe2b7X0oQWTnKeb2GiPYRrPPhwq\",\"token_expire_date\":\"2023-11-27 11:43:33\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"}},\"version\":\"1.0.0\"}', '1883e55c-ffe8-44c6-a87c-f91131d06e70', '2023-11-27 11:43:58', '2023-11-27 11:43:58', NULL),
(34, '/api/client/dashboard', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Dashboard\",\"data\":{\"title\":\"Dashboard\",\"menu\":\"Dashboard\",\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$FxPiMwszFaFCS\\/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne\",\"token\":\"$2y$10$FTiAKRICBINxfVD0lBMxP.PLfxRe2b7X0oQWTnKeb2GiPYRrPPhwq\",\"token_expire_date\":\"2023-11-27 11:43:33\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"},\"logs\":[{\"id\":\"134\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:43:33\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"online\",\"token\":\"$2y$10$Kpwi777Fkf2CbGiMlDIzaujRSPD1t7mOiGHOoYEnt0atGmnK9kujO\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:43:33\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null},{\"id\":\"133\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:42:44\",\"logout_at\":\"2023-11-27 11:43:00\",\"duration\":\"08:00:16\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$K2bKNd3GH0nJc9Dy\\/sqcWu5JvePjBAU9CJXAQT3K\\/0O58PLLVfW\\/y\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:42:44\",\"updated_at\":\"2023-11-27 11:43:00\",\"deleted_at\":null},{\"id\":\"131\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 03:06:23\",\"logout_at\":\"2023-11-27 11:09:36\",\"duration\":\"08:03:13\",\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"offline\",\"token\":\"$2y$10$dzjBNoo9FWn53qVcUcyQp.urP6RqI026mGzKnK3j3irnYQLX4xG2i\",\"referrer_link\":\"https:\\/\\/sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:06:23\",\"updated_at\":\"2023-11-27 11:09:36\",\"deleted_at\":null},{\"id\":\"130\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:04:47\",\"logout_at\":\"2023-11-27 11:08:39\",\"duration\":\"08:03:52\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$Nbx2GHXjbW6i1hAH10bUHeuHOThCHoy1lgmJBPt7HCecg636gHz9O\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:04:47\",\"updated_at\":\"2023-11-27 11:08:39\",\"deleted_at\":null},{\"id\":\"129\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 02:57:57\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"online\",\"token\":\"$2y$10$0cwOt0yZyW5v0fUVwS2rS.dBSxioynKwgKAURmpHwkLw0xGBbGGfS\",\"referrer_link\":\"https:\\/\\/www.sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 10:57:57\",\"updated_at\":\"2023-11-27 10:57:57\",\"deleted_at\":null},{\"id\":\"125\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 00:24:38\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$3vo8GWDrmyd\\/5Ffq3wVwnOcDtqJynM1fXZCg8ql4UX.g4MIZ0uZ7S\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 00:24:38\",\"updated_at\":\"2023-11-27 00:24:38\",\"deleted_at\":null},{\"id\":\"124\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:54:17\",\"logout_at\":\"2023-11-26 16:54:39\",\"duration\":\"-02:59:38\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$3JWamoUjUagW4WNGxJPMuuRR9nqsCas4CGQF77UF0XbSDlDQMZDVG\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:54:17\",\"updated_at\":\"2023-11-26 16:54:39\",\"deleted_at\":null},{\"id\":\"123\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:53:29\",\"logout_at\":\"2023-11-26 16:54:05\",\"duration\":\"-02:59:24\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$MWYWa4eZsk7s.dI2TJDCZ.SiBLKEySJ5W36nXaKw8nNgg65gKd7Wy\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:53:29\",\"updated_at\":\"2023-11-26 16:54:05\",\"deleted_at\":null},{\"id\":\"122\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-26 19:47:02\",\"logout_at\":\"2023-11-26 16:48:20\",\"duration\":\"-02:58:42\",\"ip_address\":\"::1\",\"browser\":\"Edge\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$eKdbDJViIhqKj3FaeSXBBefD6nKbydEPm75F2FXhzv\\/96VdES8bfS\",\"referrer_link\":\"http:\\/\\/localhost:8080\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:47:02\",\"updated_at\":\"2023-11-26 16:48:20\",\"deleted_at\":null},{\"id\":\"121\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:42:58\",\"logout_at\":\"2023-11-26 16:45:36\",\"duration\":\"-02:57:22\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$2onRAsc3zE6qxJfsvM0G5ejt0dTin6H9bmMaMw3TFt.X5\\/55I70ji\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:42:58\",\"updated_at\":\"2023-11-26 16:45:36\",\"deleted_at\":null},{\"id\":\"120\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:53:54\",\"logout_at\":\"2023-11-26 16:38:44\",\"duration\":\"-02:15:10\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BgR7KwRryV1vs8vZxStloe1lidun3D.o94VzqiFZQsw8SiMc48yQC\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:53:54\",\"updated_at\":\"2023-11-26 16:38:44\",\"deleted_at\":null},{\"id\":\"119\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:30:03\",\"logout_at\":\"2023-11-26 15:51:48\",\"duration\":\"-02:38:15\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BuMX2xrC7FP1iWMq0XZnSO2xdycD4rRyo7aCkdRKtN1s1g.U1fxq.\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:30:03\",\"updated_at\":\"2023-11-26 15:51:48\",\"deleted_at\":null},{\"id\":\"117\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-25 20:57:09\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"127.0.0.1\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$jAtwHVm07UiVy76jlUcizuTgcp61s0vGfMgRyOD9UWPEVrOlbHZ06\",\"referrer_link\":\"http:\\/\\/smicrofinance.com\\/client\\/account\\/token\\/verification\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-25 20:57:09\",\"updated_at\":\"2023-11-25 20:57:09\",\"deleted_at\":null}],\"loan\":{\"total\":null,\"paid\":null,\"balance\":null},\"products\":[{\"id\":\"8\",\"product_name\":\"Nexen Express\",\"interest_rate\":\"12.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"this is a weekly loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"500000\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:16:50\",\"updated_at\":\"2023-10-18 14:05:33\",\"deleted_at\":null},{\"id\":\"7\",\"product_name\":\"Kagwilawo\",\"interest_rate\":\"20.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"4\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"This loan has no application fees but its 20% upfront deducted on disbursement\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:0;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"0\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:12:47\",\"updated_at\":\"2023-10-18 21:12:50\",\"deleted_at\":null},{\"id\":\"6\",\"product_name\":\"Weyagale\",\"interest_rate\":\"4.50\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"This is a business loan from 30m and above\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"30000000\",\"max_principal\":\"100000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:06:47\",\"updated_at\":\"2023-10-18 21:11:10\",\"deleted_at\":null},{\"id\":\"5\",\"product_name\":\"Tambula Loan\",\"interest_rate\":\"5.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Tambula is a business loan from 5m to 29m\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"5000000\",\"max_principal\":\"29000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:03:02\",\"updated_at\":\"2023-10-18 14:05:59\",\"deleted_at\":null},{\"id\":\"4\",\"product_name\":\"Karibu Loan\",\"interest_rate\":\"8.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Karibu Loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"1500000\",\"max_principal\":\"10000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 11:43:58\",\"updated_at\":\"2023-10-18 14:04:14\",\"deleted_at\":null}],\"disbursements\":[],\"repayments\":[]},\"version\":\"1.0.0\"}', 'a9861d32-a88f-436b-895e-0360ffe204af', '2023-11-27 11:44:44', '2023-11-27 11:44:44', NULL);
INSERT INTO `api_requests` (`id`, `url`, `method`, `ip_address`, `user_agent`, `status`, `error_message`, `input`, `output`, `uuid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(35, '/api/client/dashboard', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Dashboard\",\"data\":{\"title\":\"Dashboard\",\"menu\":\"Dashboard\",\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$FxPiMwszFaFCS\\/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne\",\"token\":\"$2y$10$FTiAKRICBINxfVD0lBMxP.PLfxRe2b7X0oQWTnKeb2GiPYRrPPhwq\",\"token_expire_date\":\"2023-11-27 11:43:33\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"},\"logs\":[{\"id\":\"134\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:43:33\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"online\",\"token\":\"$2y$10$Kpwi777Fkf2CbGiMlDIzaujRSPD1t7mOiGHOoYEnt0atGmnK9kujO\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:43:33\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null},{\"id\":\"133\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:42:44\",\"logout_at\":\"2023-11-27 11:43:00\",\"duration\":\"08:00:16\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$K2bKNd3GH0nJc9Dy\\/sqcWu5JvePjBAU9CJXAQT3K\\/0O58PLLVfW\\/y\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:42:44\",\"updated_at\":\"2023-11-27 11:43:00\",\"deleted_at\":null},{\"id\":\"131\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 03:06:23\",\"logout_at\":\"2023-11-27 11:09:36\",\"duration\":\"08:03:13\",\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"offline\",\"token\":\"$2y$10$dzjBNoo9FWn53qVcUcyQp.urP6RqI026mGzKnK3j3irnYQLX4xG2i\",\"referrer_link\":\"https:\\/\\/sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:06:23\",\"updated_at\":\"2023-11-27 11:09:36\",\"deleted_at\":null},{\"id\":\"130\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:04:47\",\"logout_at\":\"2023-11-27 11:08:39\",\"duration\":\"08:03:52\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$Nbx2GHXjbW6i1hAH10bUHeuHOThCHoy1lgmJBPt7HCecg636gHz9O\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:04:47\",\"updated_at\":\"2023-11-27 11:08:39\",\"deleted_at\":null},{\"id\":\"129\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 02:57:57\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"online\",\"token\":\"$2y$10$0cwOt0yZyW5v0fUVwS2rS.dBSxioynKwgKAURmpHwkLw0xGBbGGfS\",\"referrer_link\":\"https:\\/\\/www.sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 10:57:57\",\"updated_at\":\"2023-11-27 10:57:57\",\"deleted_at\":null},{\"id\":\"125\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 00:24:38\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$3vo8GWDrmyd\\/5Ffq3wVwnOcDtqJynM1fXZCg8ql4UX.g4MIZ0uZ7S\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 00:24:38\",\"updated_at\":\"2023-11-27 00:24:38\",\"deleted_at\":null},{\"id\":\"124\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:54:17\",\"logout_at\":\"2023-11-26 16:54:39\",\"duration\":\"-02:59:38\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$3JWamoUjUagW4WNGxJPMuuRR9nqsCas4CGQF77UF0XbSDlDQMZDVG\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:54:17\",\"updated_at\":\"2023-11-26 16:54:39\",\"deleted_at\":null},{\"id\":\"123\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:53:29\",\"logout_at\":\"2023-11-26 16:54:05\",\"duration\":\"-02:59:24\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$MWYWa4eZsk7s.dI2TJDCZ.SiBLKEySJ5W36nXaKw8nNgg65gKd7Wy\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:53:29\",\"updated_at\":\"2023-11-26 16:54:05\",\"deleted_at\":null},{\"id\":\"122\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-26 19:47:02\",\"logout_at\":\"2023-11-26 16:48:20\",\"duration\":\"-02:58:42\",\"ip_address\":\"::1\",\"browser\":\"Edge\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$eKdbDJViIhqKj3FaeSXBBefD6nKbydEPm75F2FXhzv\\/96VdES8bfS\",\"referrer_link\":\"http:\\/\\/localhost:8080\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:47:02\",\"updated_at\":\"2023-11-26 16:48:20\",\"deleted_at\":null},{\"id\":\"121\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:42:58\",\"logout_at\":\"2023-11-26 16:45:36\",\"duration\":\"-02:57:22\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$2onRAsc3zE6qxJfsvM0G5ejt0dTin6H9bmMaMw3TFt.X5\\/55I70ji\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:42:58\",\"updated_at\":\"2023-11-26 16:45:36\",\"deleted_at\":null},{\"id\":\"120\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:53:54\",\"logout_at\":\"2023-11-26 16:38:44\",\"duration\":\"-02:15:10\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BgR7KwRryV1vs8vZxStloe1lidun3D.o94VzqiFZQsw8SiMc48yQC\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:53:54\",\"updated_at\":\"2023-11-26 16:38:44\",\"deleted_at\":null},{\"id\":\"119\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:30:03\",\"logout_at\":\"2023-11-26 15:51:48\",\"duration\":\"-02:38:15\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BuMX2xrC7FP1iWMq0XZnSO2xdycD4rRyo7aCkdRKtN1s1g.U1fxq.\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:30:03\",\"updated_at\":\"2023-11-26 15:51:48\",\"deleted_at\":null},{\"id\":\"117\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-25 20:57:09\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"127.0.0.1\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$jAtwHVm07UiVy76jlUcizuTgcp61s0vGfMgRyOD9UWPEVrOlbHZ06\",\"referrer_link\":\"http:\\/\\/smicrofinance.com\\/client\\/account\\/token\\/verification\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-25 20:57:09\",\"updated_at\":\"2023-11-25 20:57:09\",\"deleted_at\":null}],\"loan\":{\"total\":null,\"paid\":null,\"balance\":null},\"products\":[{\"id\":\"8\",\"product_name\":\"Nexen Express\",\"interest_rate\":\"12.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"this is a weekly loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"500000\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:16:50\",\"updated_at\":\"2023-10-18 14:05:33\",\"deleted_at\":null},{\"id\":\"7\",\"product_name\":\"Kagwilawo\",\"interest_rate\":\"20.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"4\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"This loan has no application fees but its 20% upfront deducted on disbursement\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:0;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"0\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:12:47\",\"updated_at\":\"2023-10-18 21:12:50\",\"deleted_at\":null},{\"id\":\"6\",\"product_name\":\"Weyagale\",\"interest_rate\":\"4.50\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"This is a business loan from 30m and above\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"30000000\",\"max_principal\":\"100000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:06:47\",\"updated_at\":\"2023-10-18 21:11:10\",\"deleted_at\":null},{\"id\":\"5\",\"product_name\":\"Tambula Loan\",\"interest_rate\":\"5.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Tambula is a business loan from 5m to 29m\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"5000000\",\"max_principal\":\"29000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:03:02\",\"updated_at\":\"2023-10-18 14:05:59\",\"deleted_at\":null},{\"id\":\"4\",\"product_name\":\"Karibu Loan\",\"interest_rate\":\"8.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Karibu Loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"1500000\",\"max_principal\":\"10000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 11:43:58\",\"updated_at\":\"2023-10-18 14:04:14\",\"deleted_at\":null}],\"disbursements\":[],\"repayments\":[]},\"version\":\"1.0.0\"}', '69dadc67-8594-4687-aef1-b57580e9af40', '2023-11-27 11:44:54', '2023-11-27 11:44:54', NULL),
(36, '/api/client/dashboard', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Dashboard\",\"data\":{\"title\":\"Dashboard\",\"menu\":\"Dashboard\",\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$FxPiMwszFaFCS\\/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne\",\"token\":\"$2y$10$FTiAKRICBINxfVD0lBMxP.PLfxRe2b7X0oQWTnKeb2GiPYRrPPhwq\",\"token_expire_date\":\"2023-11-27 11:43:33\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"},\"logs\":[{\"id\":\"134\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:43:33\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"online\",\"token\":\"$2y$10$Kpwi777Fkf2CbGiMlDIzaujRSPD1t7mOiGHOoYEnt0atGmnK9kujO\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:43:33\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null},{\"id\":\"133\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:42:44\",\"logout_at\":\"2023-11-27 11:43:00\",\"duration\":\"08:00:16\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$K2bKNd3GH0nJc9Dy\\/sqcWu5JvePjBAU9CJXAQT3K\\/0O58PLLVfW\\/y\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:42:44\",\"updated_at\":\"2023-11-27 11:43:00\",\"deleted_at\":null},{\"id\":\"131\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 03:06:23\",\"logout_at\":\"2023-11-27 11:09:36\",\"duration\":\"08:03:13\",\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"offline\",\"token\":\"$2y$10$dzjBNoo9FWn53qVcUcyQp.urP6RqI026mGzKnK3j3irnYQLX4xG2i\",\"referrer_link\":\"https:\\/\\/sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:06:23\",\"updated_at\":\"2023-11-27 11:09:36\",\"deleted_at\":null},{\"id\":\"130\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 03:04:47\",\"logout_at\":\"2023-11-27 11:08:39\",\"duration\":\"08:03:52\",\"ip_address\":\"54.86.50.139\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"Ashburn, Virginia, United States North America\",\"latitude\":\"39.0469\",\"longitude\":\"-77.4903\",\"status\":\"offline\",\"token\":\"$2y$10$Nbx2GHXjbW6i1hAH10bUHeuHOThCHoy1lgmJBPt7HCecg636gHz9O\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 11:04:47\",\"updated_at\":\"2023-11-27 11:08:39\",\"deleted_at\":null},{\"id\":\"129\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-27 02:57:57\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"41.75.179.87\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"Kampala, Central Region, Uganda Africa\",\"latitude\":\"0.3162\",\"longitude\":\"32.5657\",\"status\":\"online\",\"token\":\"$2y$10$0cwOt0yZyW5v0fUVwS2rS.dBSxioynKwgKAURmpHwkLw0xGBbGGfS\",\"referrer_link\":\"https:\\/\\/www.sacco.realdailykash.com\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 10:57:57\",\"updated_at\":\"2023-11-27 10:57:57\",\"deleted_at\":null},{\"id\":\"125\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-27 00:24:38\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$3vo8GWDrmyd\\/5Ffq3wVwnOcDtqJynM1fXZCg8ql4UX.g4MIZ0uZ7S\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-27 00:24:38\",\"updated_at\":\"2023-11-27 00:24:38\",\"deleted_at\":null},{\"id\":\"124\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:54:17\",\"logout_at\":\"2023-11-26 16:54:39\",\"duration\":\"-02:59:38\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$3JWamoUjUagW4WNGxJPMuuRR9nqsCas4CGQF77UF0XbSDlDQMZDVG\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:54:17\",\"updated_at\":\"2023-11-26 16:54:39\",\"deleted_at\":null},{\"id\":\"123\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:53:29\",\"logout_at\":\"2023-11-26 16:54:05\",\"duration\":\"-02:59:24\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$MWYWa4eZsk7s.dI2TJDCZ.SiBLKEySJ5W36nXaKw8nNgg65gKd7Wy\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:53:29\",\"updated_at\":\"2023-11-26 16:54:05\",\"deleted_at\":null},{\"id\":\"122\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-26 19:47:02\",\"logout_at\":\"2023-11-26 16:48:20\",\"duration\":\"-02:58:42\",\"ip_address\":\"::1\",\"browser\":\"Edge\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$eKdbDJViIhqKj3FaeSXBBefD6nKbydEPm75F2FXhzv\\/96VdES8bfS\",\"referrer_link\":\"http:\\/\\/localhost:8080\\/client\\/login\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:47:02\",\"updated_at\":\"2023-11-26 16:48:20\",\"deleted_at\":null},{\"id\":\"121\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 19:42:58\",\"logout_at\":\"2023-11-26 16:45:36\",\"duration\":\"-02:57:22\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$2onRAsc3zE6qxJfsvM0G5ejt0dTin6H9bmMaMw3TFt.X5\\/55I70ji\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 16:42:58\",\"updated_at\":\"2023-11-26 16:45:36\",\"deleted_at\":null},{\"id\":\"120\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:53:54\",\"logout_at\":\"2023-11-26 16:38:44\",\"duration\":\"-02:15:10\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BgR7KwRryV1vs8vZxStloe1lidun3D.o94VzqiFZQsw8SiMc48yQC\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:53:54\",\"updated_at\":\"2023-11-26 16:38:44\",\"deleted_at\":null},{\"id\":\"119\",\"loginfo\":\"PostmanRuntime\\/7.35.0\",\"login_at\":\"2023-11-26 18:30:03\",\"logout_at\":\"2023-11-26 15:51:48\",\"duration\":\"-02:38:15\",\"ip_address\":\"::1\",\"browser\":\"\",\"browser_version\":\"\",\"operating_system\":\"Unknown Platform\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"offline\",\"token\":\"$2y$10$BuMX2xrC7FP1iWMq0XZnSO2xdycD4rRyo7aCkdRKtN1s1g.U1fxq.\",\"referrer_link\":\"\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-26 15:30:03\",\"updated_at\":\"2023-11-26 15:51:48\",\"deleted_at\":null},{\"id\":\"117\",\"loginfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/119.0.0.0 Sa\",\"login_at\":\"2023-11-25 20:57:09\",\"logout_at\":null,\"duration\":null,\"ip_address\":\"127.0.0.1\",\"browser\":\"Chrome\",\"browser_version\":\"119.0.0.0\",\"operating_system\":\"Windows 10\",\"location\":\"\",\"latitude\":\"\",\"longitude\":\"\",\"status\":\"online\",\"token\":\"$2y$10$jAtwHVm07UiVy76jlUcizuTgcp61s0vGfMgRyOD9UWPEVrOlbHZ06\",\"referrer_link\":\"http:\\/\\/smicrofinance.com\\/client\\/account\\/token\\/verification\",\"user_id\":null,\"client_id\":\"600\",\"account\":null,\"created_at\":\"2023-11-25 20:57:09\",\"updated_at\":\"2023-11-25 20:57:09\",\"deleted_at\":null}],\"loan\":{\"total\":null,\"paid\":null,\"balance\":null},\"products\":[{\"id\":\"8\",\"product_name\":\"Nexen Express\",\"interest_rate\":\"12.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"this is a weekly loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"500000\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:16:50\",\"updated_at\":\"2023-10-18 14:05:33\",\"deleted_at\":null},{\"id\":\"7\",\"product_name\":\"Kagwilawo\",\"interest_rate\":\"20.00\",\"interest_period\":\"week\",\"interest_type\":\"Flat\",\"repayment_period\":\"4\",\"repayment_duration\":\"week(s)\",\"repayment_freq\":\"Weekly\",\"product_desc\":\"This loan has no application fees but its 20% upfront deducted on disbursement\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:0;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"0\",\"max_principal\":\"2000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:12:47\",\"updated_at\":\"2023-10-18 21:12:50\",\"deleted_at\":null},{\"id\":\"6\",\"product_name\":\"Weyagale\",\"interest_rate\":\"4.50\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"This is a business loan from 30m and above\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"30000000\",\"max_principal\":\"100000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:06:47\",\"updated_at\":\"2023-10-18 21:11:10\",\"deleted_at\":null},{\"id\":\"5\",\"product_name\":\"Tambula Loan\",\"interest_rate\":\"5.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Tambula is a business loan from 5m to 29m\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;i:6;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"5000000\",\"max_principal\":\"29000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 16:03:02\",\"updated_at\":\"2023-10-18 14:05:59\",\"deleted_at\":null},{\"id\":\"4\",\"product_name\":\"Karibu Loan\",\"interest_rate\":\"8.00\",\"interest_period\":\"month\",\"interest_type\":\"Flat\",\"repayment_period\":\"12\",\"repayment_duration\":\"month(s)\",\"repayment_freq\":\"Monthly\",\"product_desc\":\"Karibu Loan\",\"product_charges\":\"a:3:{s:12:\\\"ParticularID\\\";a:1:{i:0;i:25;}s:16:\\\"ParticularCharge\\\";a:1:{i:0;d:5.5;}s:22:\\\"ParticularChargeMethod\\\";a:1:{i:0;s:7:\\\"Percent\\\";}}\",\"product_features\":\"\",\"min_principal\":\"1500000\",\"max_principal\":\"10000000\",\"status\":\"Active\",\"created_at\":\"2023-10-10 11:43:58\",\"updated_at\":\"2023-10-18 14:04:14\",\"deleted_at\":null}],\"disbursements\":[],\"repayments\":[]},\"version\":\"1.0.0\"}', '1773f513-32c7-4f8e-8072-0759d5444021', '2023-11-27 11:45:02', '2023-11-27 11:45:02', NULL),
(37, '/api/client/profile', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Client User Profile\",\"data\":{\"settings\":{\"id\":\"1\",\"author\":\"Saipali\",\"system_name\":\"Saipali Micro Credit\",\"system_abbr\":\"Saipali\",\"system_slogan\":\"Applying Knowledge\",\"system_version\":\"1.0.0.1\",\"business_name\":\"Saipali Micro Credit \",\"business_abbr\":\"Nexen\",\"business_slogan\":\"Applying Knowledge\",\"business_contact\":\"+256777237827\",\"business_alt_contact\":\"+256702999488\",\"business_email\":\"danfodio@realdailykash.com\",\"business_pobox\":\"P.O Box 01, Kampala\",\"business_address\":\"Plot 99 Matyrs way, Ntinda Kampala-Uganda\",\"business_web\":\"nexenmicrocredit.com\",\"business_logo\":\"1630914980648.png\",\"business_about\":\"Nexen\",\"description\":\"\",\"background_logo\":\"background.jpg\",\"email_template_logo\":\"https:\\/\\/microfinance.realdailykash.com\\/uploads\\/logo\\/logo.jpeg\",\"google_map_iframe\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\\\" width=\\\"100%\\\" height=\\\"400\\\" frameborder=\\\"0\\\" style=\\\"border:0\\\" allowfullscreen><\\/iframe>\",\"whatsapp\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"linkedin\":\"\",\"tax_rate\":\"30\",\"round_off\":\"100\",\"currency_id\":\"143\",\"created_at\":\"2023-10-03 08:32:27\",\"updated_at\":\"2023-11-26 18:33:33\",\"deleted_at\":null,\"currency\":\"UGX\",\"symbol\":\"&#85;&#83;&#104;\"},\"user\":{\"id\":\"600\",\"name\":\"Danfodio\",\"branch_id\":\"1\",\"staff_id\":\"1\",\"account_no\":\"C2311250600\",\"account_type\":\"Client\",\"account_balance\":\"0.00\",\"email\":\"daniondanfodio@gmail.com\",\"mobile\":\"+256706551841\",\"alternate_no\":\"\",\"gender\":\"Male\",\"dob\":\"2000-11-01\",\"religion\":\"Protestant\",\"nationality\":\"Ugandan\",\"marital_status\":\"Single\",\"occupation\":\"Engineer\",\"job_location\":\"Kasubi, Mengo, Kampala\",\"residence\":\"Kasubi, Mengo, Kampala\",\"id_type\":\"National ID\",\"id_number\":\"CM256706551841\",\"id_expiry_date\":\"2025-11-29\",\"next_of_kin_name\":\"Danfodio\",\"next_of_kin_relationship\":\"Brother\",\"next_of_kin_contact\":\"+256706551841\",\"next_of_kin_alternate_contact\":\"\",\"nok_email\":\"\",\"nok_address\":\"Kasubi, Mengo, Kampala\",\"photo\":null,\"id_photo_front\":null,\"id_photo_back\":null,\"password\":\"$2y$10$FxPiMwszFaFCS\\/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne\",\"token\":\"$2y$10$FTiAKRICBINxfVD0lBMxP.PLfxRe2b7X0oQWTnKeb2GiPYRrPPhwq\",\"token_expire_date\":\"2023-11-27 11:43:33\",\"2fa\":\"False\",\"signature\":null,\"account\":\"Approved\",\"access_status\":\"Active\",\"reg_date\":\"2023-11-25\",\"created_at\":\"2023-11-25 20:56:37\",\"updated_at\":\"2023-11-27 11:43:33\",\"deleted_at\":null,\"branch_name\":\"Main Branch\",\"permissions\":\"a:13:{i:0;s:13:\\\"viewDashboard\\\";i:1;s:12:\\\"viewBranches\\\";i:2;s:14:\\\"exportBranches\\\";i:3;s:11:\\\"viewReports\\\";i:4;s:16:\\\"viewTransactions\\\";i:5;s:9:\\\"viewLoans\\\";i:6;s:17:\\\"viewDisbursements\\\";i:7;s:19:\\\"exportDisbursements\\\";i:8;s:18:\\\"createApplications\\\";i:9;s:18:\\\"exportApplications\\\";i:10;s:16:\\\"viewApplications\\\";i:11;s:18:\\\"updateApplications\\\";i:12;s:13:\\\"createClients\\\";}\"}},\"version\":\"1.0.0\"}', 'd2f0a703-2444-42f0-87d9-688f25bcd452', '2023-11-27 11:45:30', '2023-11-27 11:45:30', NULL),
(38, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', 'dc229573-91c9-41d2-8805-cb8320a93eae', '2023-11-27 13:25:11', '2023-11-27 13:25:11', NULL),
(39, '/api/client/login', 'GET', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"You are required to login first\",\"version\":\"1.0.0\"}', '47de4ef9-7ae2-44d5-85f8-c27aa0a2b30f', '2023-11-27 13:28:10', '2023-11-27 13:28:10', NULL),
(40, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'FAILED', 'Wrong Password Provided. Try again with correct password', NULL, '{\"status\":false,\"message\":\"Wrong Password Provided. Try again with correct password\",\"data\":{\"error\":\"wrongPassword\"},\"version\":\"1.0.0\"}', '11221784-1f90-44b7-87a7-eeaf53473a1f', '2023-11-27 17:14:49', '2023-11-27 17:14:49', NULL),
(41, '/api/client/auth', 'POST', '54.86.50.139', 'PostmanRuntime/7.35.0', 'SUCCESS', NULL, NULL, '{\"status\":true,\"message\":\"Success. Redirecting to dashboard\",\"data\":{\"client_id\":\"600\",\"userlog_id\":137,\"name\":\"Danfodio\",\"email\":\"daniondanfodio@gmail.com\",\"branch_id\":\"1\",\"photo\":null,\"token\":\"KVM8TAcuztXh9qfoG1nvYsH74xUp63PE\",\"client\":true},\"version\":\"1.0.0\"}', 'bb89ccc0-4200-4569-beaa-8b3a1f41d04b', '2023-11-27 17:15:03', '2023-11-27 17:15:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `applicationremarks`
--

CREATE TABLE `applicationremarks` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `status` enum('Pending','Processing','Declined','Approved','Disbursed','Cancelled') NOT NULL,
  `level` enum('Credit Officer','Supervisor','Operations Officer','Accounts Officer') NOT NULL DEFAULT 'Credit Officer',
  `action` enum('Processing','Review','Approved','Disbursed','Declined') DEFAULT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `applicationremarks`
--

INSERT INTO `applicationremarks` (`id`, `application_id`, `staff_id`, `status`, `level`, `action`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Processing', 'Credit Officer', 'Approved', '<p>approved</p>', '2023-10-19 14:04:50', '2023-10-19 14:04:50', NULL),
(2, 1, 1, 'Processing', 'Supervisor', 'Approved', '<p>approved</p>', '2023-10-19 14:05:03', '2023-10-19 14:05:03', NULL),
(3, 1, 1, 'Processing', 'Operations Officer', 'Approved', '<p>approved</p>', '2023-10-19 14:05:16', '2023-10-19 14:05:16', NULL),
(4, 1, 1, 'Processing', 'Accounts Officer', 'Declined', 'declined', '2023-10-19 14:05:47', '2023-10-19 15:23:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) UNSIGNED NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `branch_email` varchar(100) NOT NULL,
  `branch_mobile` varchar(100) NOT NULL,
  `alternate_mobile` varchar(100) NOT NULL,
  `branch_address` varchar(100) NOT NULL,
  `branch_code` varchar(20) DEFAULT NULL,
  `branch_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `slug`, `branch_email`, `branch_mobile`, `alternate_mobile`, `branch_address`, `branch_code`, `branch_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Main Branch', 'main-branch', 'daniondanfodio@gmail.com', '+256706551841', '', 'Bakuli', 'B0001', 'Active', '2023-10-03 08:32:59', '2023-10-03 08:39:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_flow_types`
--

CREATE TABLE `cash_flow_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cash_flow_types`
--

INSERT INTO `cash_flow_types` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Operating Activities', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL),
(2, 'Investing Activities', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL),
(3, 'Financing Activities', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL),
(4, 'Non-operating Activities', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL),
(5, 'Cash Flow From Taxes', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL),
(6, 'Non Applicables', 'Active', '2023-10-03 08:35:09', '2023-10-03 08:35:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_slug` varchar(100) NOT NULL,
  `part` enum('debit','credit') DEFAULT NULL,
  `statement_id` int(11) UNSIGNED NOT NULL,
  `category_type` enum('System','Custom') NOT NULL DEFAULT 'System',
  `bring_forward` enum('Yes','No') NOT NULL DEFAULT 'No',
  `category_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_slug`, `part`, `statement_id`, `category_type`, `bring_forward`, `category_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Assets', 'assets', 'debit', 1, 'System', 'No', 'Active', '2023-10-03 08:34:32', '2023-10-03 08:34:32', NULL),
(2, 'Equity', 'equity', 'credit', 1, 'System', 'No', 'Active', '2023-10-03 08:34:32', '2023-10-03 08:34:32', NULL),
(3, 'Liabilities', 'liabilities', 'credit', 1, 'System', 'No', 'Active', '2023-10-03 08:34:32', '2023-10-03 08:34:32', NULL),
(4, 'Revenue', 'revenue', 'credit', 2, 'System', 'No', 'Active', '2023-10-03 08:34:32', '2023-10-03 08:34:32', NULL),
(5, 'Expenses', 'expenses', 'debit', 2, 'System', 'No', 'Active', '2023-10-03 08:34:32', '2023-10-03 08:34:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED DEFAULT NULL,
  `account_no` varchar(100) NOT NULL,
  `account_type` enum('Client','Staff') NOT NULL DEFAULT 'Client',
  `account_balance` double(10,2) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `alternate_no` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `religion` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `job_location` varchar(100) NOT NULL,
  `residence` varchar(100) NOT NULL,
  `id_type` varchar(100) NOT NULL,
  `id_number` varchar(100) NOT NULL,
  `id_expiry_date` date DEFAULT NULL,
  `next_of_kin_name` varchar(100) NOT NULL,
  `next_of_kin_relationship` varchar(100) NOT NULL,
  `next_of_kin_contact` varchar(100) NOT NULL,
  `next_of_kin_alternate_contact` varchar(100) NOT NULL,
  `nok_email` varchar(100) NOT NULL,
  `nok_address` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `id_photo_front` varchar(100) DEFAULT NULL,
  `id_photo_back` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `token_expire_date` datetime DEFAULT NULL,
  `2fa` enum('True','False') NOT NULL DEFAULT 'False',
  `signature` varchar(50) DEFAULT NULL,
  `account` enum('Pending','Approved','Declined') NOT NULL DEFAULT 'Approved',
  `access_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `reg_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Shadrach Obbo', 1, 1, 'C2310100002', 'Client', 0.00, 'shadrachobbo4@gmail.com', '+256777237827', '+256702999488', 'Male', '1997-10-10', 'Born-Again', 'Ugandan', 'Single', 'Engineer', 'Kampala', 'Kampala', 'National ID', 'CM5&776556864G', '2025-10-10', 'Magero Miriam', 'Mother', '+256752294420', '', '', 'Tororo', 'po1AVh5Nc6.jpg', 'fnb351YP4U.jpg', 'gJMRWVqtd4.jpg', '$2y$10$LHzzFmmpWJ8hAToBenUkJ.VLVKTr900EuQaw9DkEjYy.Vis92GYv.', '$2y$10$xTez/EMg.GahVEcGG12h1ey7It3Vt4We.NWFBz6qzOhzG6TEmHuHO', '2023-10-21 13:01:17', 'False', NULL, 'Approved', 'Active', '2023-10-10', '2023-10-10 05:57:25', '2023-10-21 13:01:17', NULL),
(2, 'Wagaba James', 1, 1, 'C2310100002', 'Client', 0.00, '', '+256701675683', '', 'Male', '1974-11-11', 'Protestant', 'Ugandan', 'Married', 'Business', 'kirinya', 'kirinya ,Bweyogerere', 'National ID', 'CM74030105WA6F', '2025-02-20', 'Namiro Cissy', 'Spouse', '+256753675683', '', '', 'Kirinya', NULL, NULL, NULL, '$2y$10$DF69RZoCDvogcYHkUKsKnOSMl/iGR9RKvBLZ1e94j7HP2II9hhFeW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-10-10', '2023-10-10 11:38:45', '2023-10-12 10:34:38', NULL),
(3, 'Amos Opoya Okech', 1, 1, '00239I0139047', 'Client', 0.00, '', '+256700629050', '', 'Male', '1980-02-26', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Amos', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Q1B/IE6JVVMVtVu0sWDLduMMocETJ5dhsaa0C3o62rxqnmJ8ENNCO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-01-10', '2023-10-12 17:24:04', '2023-10-12 10:36:58', NULL),
(4, 'Andrew Carlos Nkalubo', 1, 1, '00239I0139048', 'Client', 0.00, '', '+256700629051', '', 'Male', '1980-02-27', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Andrew', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$YM/Vt5dR53I3tsYvypq/8utXVWI6S5SWhAqTHeHxMHGvATz49i.li', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2018-07-06', '2023-10-12 17:24:04', '2023-10-12 10:36:58', NULL),
(5, 'CARL PETERS MUGENYI', 1, 1, '00239I0139049', 'Client', 0.00, '', '+256701131645', '', 'Male', '1982-09-15', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'CARL', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$/IRFwcTWUQKQv/ubUbjZQuqA9zJhge9VvMQSMF7qfliH0b.2m97eW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-03', '2023-10-12 17:24:05', '2023-10-12 10:36:58', NULL),
(6, 'CHARLES  YABAISE', 1, 1, '00239I0139050', 'Client', 0.00, '', '+256772594486', '', 'Male', '1977-03-15', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'CHARLES', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$1lfe8loMdPyCUmlPMDcOzul2WR4aycyH7wWGsFo8Ubakoy/Ps3h0m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-13', '2023-10-12 17:24:05', '2023-10-12 10:36:58', NULL),
(7, 'CONRAD LINUS MUHIRWE', 1, 1, '00239I0139051', 'Client', 0.00, '', '+256782019000', '', 'Male', '1987-10-18', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'CONRAD', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$U0hxu7kIfBDf8G536z5YresomuQzPIlsz2tLRgPznkj4zVgMRAolC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-10', '2023-10-12 17:24:05', '2023-10-12 10:36:58', NULL),
(8, 'DANIEL  SEGOMA', 1, 1, '00239I0139052', 'Client', 0.00, '', '+256704393279', '', 'Male', '1986-12-01', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'DANIEL', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$l/vz1i1O1WXWuex8pSYKz.2L0BST2o8usXUqMZBFiFFe.JzgIzGIO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-05-20', '2023-10-12 17:24:05', '2023-10-12 10:36:58', NULL),
(9, 'Dauda  Kiwu', 1, 1, '00239I0139053', 'Client', 0.00, '', '+256757156286', '', 'Male', '1984-01-05', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Dauda', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$esuD/9RVI1rJxATiziLsAOohYVOXneJZQs3zuHIcF1.35u0DqlubS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-07-05', '2023-10-12 17:24:05', '2023-10-12 10:36:58', NULL),
(10, 'David  Basaija', 1, 1, '00239I0139054', 'Client', 0.00, '', '+256706182698', '', 'Male', '1982-09-15', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'David', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Isr3JZukl8rpH1uAYG7UnuSS3t5PUTM4gFw2V.QvxD0VKqRcDo7AO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-20', '2023-10-12 17:24:06', '2023-10-12 10:36:58', NULL),
(11, 'David new wasubira', 1, 1, '00239I0139055', 'Client', 0.00, '', '', '', 'Male', '1983-09-19', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'David', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$20g9fmvm65nvGjNugZKFEuovlEpagBG/MkWaZo8A7C9F7hWBJKKCi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-06', '2023-10-12 17:24:06', '2023-10-12 10:36:58', NULL),
(12, 'david  wasubira', 1, 1, '00239I0139056', 'Client', 0.00, '', '', '', 'Male', '1983-09-19', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'david', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$LnyHHasQ5HRCBYRdaNx2l.nGZFmV3tVqLCBMk4Cy3XUuwJwGQ5p4q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-06', '2023-10-12 17:24:06', '2023-10-12 10:36:58', NULL),
(13, 'Elvis  Nimaro', 1, 1, '00239I0139057', 'Client', 0.00, '', '+256757064896', '', 'Male', '1988-06-28', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Elvis', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$1rTIOcZPxs/IFD1Nfk4x5Olk0R.RnMLpepGdYJELtM6ZgB2dDr6d6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2018-07-31', '2023-10-12 17:24:06', '2023-10-12 10:36:58', NULL),
(14, 'Mr. Fred  Kifubangabo', 1, 1, '00239I0139058', 'Client', 0.00, '', '+256701319457', '', 'Male', '1966-03-26', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Aisha Nakisuyi', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$fKFU0Dsv5N9OdSSqrG9TK.Ai2WmUNaLsN.7hxPQ9yAL3qHU96aUI2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-18', '2023-10-12 17:24:06', '2023-10-12 10:36:58', NULL),
(15, 'Gerald  Ngobi', 1, 1, '00239I0139059', 'Client', 0.00, '', '+256704304784', '', 'Male', '1987-03-22', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Gerald', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$p6H2Xn/x657pfbUadUu1K.0ZbOg8vZf8jsxmoF9F7VBv0/yI7coXa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-28', '2023-10-12 17:24:07', '2023-10-12 10:36:58', NULL),
(16, 'HERBERT  WALUSAGA', 1, 1, '00239I0139060', 'Client', 0.00, '', '+256758880123', '', 'Male', '1982-09-15', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'HERBERT', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$LMLFX2ZDnSCvu7uSK4DY0uUHUK1McenCWWhmEeuhRPOXn/yID0n22', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-25', '2023-10-12 17:24:07', '2023-10-12 10:36:58', NULL),
(17, 'JAMES WALUSAGA AMANYI', 1, 1, '00239I0139061', 'Client', 0.00, '', '+256759299797', '', 'Male', '1981-01-01', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'JAMES', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$VNxpbEW9hw0Qhn3Tlhjmwul.VIV0JB4ZE/Nra8P62hlACnTCrpSwe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-22', '2023-10-12 17:24:07', '2023-10-12 10:36:58', NULL),
(18, 'JAPHET OKEKE UZOCHUKWU', 1, 1, '00239I0139062', 'Client', 0.00, '', '+256776064903', '', 'Male', '1975-02-11', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'JAPHET', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$grx4.LgAuc7y9Y9qKMIYMOBrmUUguRxq3QYIsonejfIxxUgFpwjs.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-05-17', '2023-10-12 17:24:07', '2023-10-12 10:36:58', NULL),
(19, 'LYDIA  NANTALE', 1, 1, '00239I0139063', 'Client', 0.00, '', '+256775214424', '', 'Female', '1993-10-10', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'LYDIA', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$9VnH5dOaocfT8A8rz3rRLulbNrgqasIR0iCCKd30TxT21eGtYqfn2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-01-21', '2023-10-12 17:24:07', '2023-10-12 10:36:58', NULL),
(20, 'Margret  Lumala', 1, 1, '00239I0139064', 'Client', 0.00, '', '+256775263886', '', 'Female', '1979-08-08', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Margret', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$YkjyXU/0wwTYhThmPoKwDeWCubE3k5pc98/DxxvNglJlU4rZYG41O', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-06-28', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(21, 'Mary Mwanja Apio', 1, 1, '00239I0139065', 'Client', 0.00, '', '', '', 'Female', '1979-08-08', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Mary', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$8q04SuHfgh7c2mX2.P5O7OF8HjbCqmlixEoVeDwAyHOaDigAfpyDy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-02-12', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(22, 'PETER  KATALAGA', 1, 1, '00239I0139066', 'Client', 0.00, '', '+256775398441', '', 'Male', '1989-07-06', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'PETER', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$jy1SrTvZVY0.ozRMs5LbSe09YhRKJd6vqAwJ1xSuJ1ZRn5Grj5W8S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-02-12', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(23, 'REBECCA  NAKAKINDA', 1, 1, '00239I0139067', 'Client', 0.00, '', '+256750233332', '', 'Female', '1969-12-01', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'RICHARD', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$dmFqC5DGjpVUjQzHWW4w7eIlsgpp.cH3CqQ6aLddaqy8EdgbPrnpK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-16', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(24, 'RITAH  BUGOYE', 1, 1, '00239I0139068', 'Client', 0.00, '', '+256782086834', '', 'Female', '1979-01-07', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'RITAH', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$r2/4Dw3kpTMtW8FWUz3CYOZpKZ.XEn1SSXx4MPc7jTSFL3JyR6Qyi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-24', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(25, 'Robert  Kato', 1, 1, '00239I0139069', 'Client', 0.00, '', '+256776953067', '', 'Male', '1982-04-26', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Robert', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$f8CtnFMBZ70DnlD88jkv5OlmnpcXpvZJ/87fmilL65EDKu32VH4sG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-07', '2023-10-12 17:24:08', '2023-10-12 10:36:58', NULL),
(26, 'Ronald  Otim', 1, 1, '00239I0139070', 'Client', 0.00, '', '+256772972226', '', 'Male', '1981-01-01', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Ronald', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$q.IQW9eNpaQUTiSX3PEAduN/tq.AMMG99gd.KXYDr54WPKSFofWxa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-07-05', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(27, 'SAM  BARAMAS', 1, 1, '00239I0139071', 'Client', 0.00, '', '+256758594625', '', 'Male', '1982-04-26', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'SAM', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$cSh..1EGFs2aELPn9d0PAeWQ1ZKOOe6lpwo94l2nog5WEjWRK0ZCK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-21', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(28, 'Stanley Henry Okia', 1, 1, '00239I0139072', 'Client', 0.00, '', '+256772681911', '', 'Male', '1963-03-09', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Stanley', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$hLlEfiU5S.8HR.wjh3Ct5eZyGR0HiTWDI7YuDUPCfiMIevOXUR6mG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-10-31', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(29, 'Sulaiman  Kabuye', 1, 1, '00239I0139073', 'Client', 0.00, '', '+256782999063', '', 'Male', '1987-11-11', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'Sulaiman', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$6EvUusZyzM1ksrRRIyGNB.zYWwv6dVHj7Z6fEBb8daESme5aOdomC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-27', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(30, 'SUSAN SIKYOMU NAKIYINGI', 1, 1, '00239I0139074', 'Client', 0.00, '', '+256773500424', '', 'Female', '1980-01-24', '', 'Ugandan', '', '', 'Nakivubo', 'Kisenyi', '', '', '1970-01-01', 'SUSAN', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$62WQl62vDAiema8XECnT0O6VOm7iRzAnQ7jkypyln7DfuN6ULdre.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-09-09', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(31, 'Mr. Nick Fred Irumba', 1, 1, '00239I0139111', 'Client', 0.00, '', '+256772698965', '', 'Male', '1970-04-24', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'Irumba', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$FBPsP5nD6WGrD7p6.BdoseD9EOrIZATG5Hgej74IxJVuUJ7UBIlAu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-01', '2023-10-12 17:24:09', '2023-10-12 10:36:58', NULL),
(32, 'Mr. Herbert  Aijuka', 1, 1, '00239I0139120', 'Client', 0.00, '', '+256702444267', '', 'Male', '1983-05-03', '', 'Ugandan', '', '', 'Bukerere', 'Goma', '', '', '1970-01-01', 'Arinaitwe john', '', '+256701998414', '', '', '', NULL, NULL, NULL, '$2y$10$Y5Q2GqGLjOUSIgoV5JK8Wuj2YBqS09NDXhshDhdMatnnFnQOFL2UO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-02', '2023-10-12 17:24:10', '2023-10-12 17:24:10', NULL),
(33, 'Eunice  Arinaitwe', 1, 1, '00239I0139123', 'Client', 0.00, '', '+256774906709', '', 'Female', '1986-04-18', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'Kansiime Mollian', '', '+256787460699', '', '', '', NULL, NULL, NULL, '$2y$10$oMx6Cnlko6T6PBdBnqTH2eFaL8pwf4JxNLQwEezJ2MdFpVSbrL2Dy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-03', '2023-10-12 17:24:10', '2023-10-12 17:24:10', NULL),
(34, 'Teopista  Nakibuuka', 1, 1, '00239I0139125', 'Client', 0.00, '', '+256754895412', '', 'Female', '1974-08-22', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Nabiwande Grace', '', '+256753569772', '', '', '', NULL, NULL, NULL, '$2y$10$vUrNwOwuqJmkHT/LLECZquYSIXpMLCRTbig2gMGEpgSLhQkOOG3Ku', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-07', '2023-10-12 17:24:10', '2023-10-12 17:24:10', NULL),
(35, 'Mr. Ivan Atwooki Mwebe', 1, 1, '00239I0139126', 'Client', 0.00, '', '', '', 'Male', '1981-08-20', '', 'Ugandan', '', '', 'Kireka ward', 'Kira town council', '', '', '1970-01-01', 'Kalenzi Haruna', '', '+256782007002', '', '', '', NULL, NULL, NULL, '$2y$10$W5u94m/QkDu/DoiaYDKmQuR88oSzhhubWPe0zL3h8n4r693E0UTVC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-02', '2023-10-12 17:24:10', '2023-10-12 10:36:27', NULL),
(36, 'Rashidah  Nakaddu', 1, 1, '00239I0139127', 'Client', 0.00, '', '+256706307422', '', 'Female', '1976-10-12', '', 'Ugandan', '', '', 'Nalukologo', 'Lubaga', '', '', '1970-01-01', 'Kambugu damulira', '', '+256754310428', '', '', '', NULL, NULL, NULL, '$2y$10$.DjcJmpD8VD07nzJpcmEuO1oiKzUUv5AWVaAoiwmJJsLm.pkl.b9G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-06', '2023-10-12 17:24:10', '2023-10-12 17:24:10', NULL),
(37, 'Ms. Annet  Nanono', 1, 1, '00239I0139130', 'Client', 0.00, '', '+256704533100', '', 'Female', '1974-12-14', '', 'Ugandan', '', '', 'Kireka ward', 'Kira town council', '', '', '1970-01-01', 'Nakazzi mary', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$f5kLJYglgVmFs./A/dL2/O.IMDUI56SgUAZ0o68AEDSJwFcjliMJG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-02', '2023-10-12 17:24:11', '2023-10-12 10:36:58', NULL),
(38, 'Edward  Bbosa', 1, 1, '00239I0139131', 'Client', 0.00, '', '+256752841529', '', 'Male', '1955-04-07', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Banura Agnes', '', '+256757914103', '', '', '', NULL, NULL, NULL, '$2y$10$VB4D6a8lLQo5cmuXjPL4j.e18Q7G3Ybwo2Ieu./pT/cvj2dIRU0fy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-06', '2023-10-12 17:24:11', '2023-10-12 17:24:11', NULL),
(39, 'Sylvia  Nabukalu', 1, 1, '00239I0139132', 'Client', 0.00, '', '+256755208951', '', 'Female', '1986-06-06', '', 'Ugandan', '', '', 'Kyebando', 'Kyebando', '', '', '1970-01-01', 'Ddumba monica', '', '+256705933310', '', '', '', NULL, NULL, NULL, '$2y$10$uz2ME8bi05WzrKdKuoD7nOsfsQeHPd63UjWOFZNXmGxue3leorAF.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-05', '2023-10-12 17:24:11', '2023-10-12 17:24:11', NULL),
(40, 'Mr. James  Wagaba', 1, 1, '00239I0139134', 'Client', 0.00, '', '+256701675683', '', 'Male', '1974-11-28', '', 'Ugandan', '', '', 'Kirinya ward', 'Kira town council', '', '', '1970-01-01', 'Namiiro cissy', '', '+256753675683', '', '', '', NULL, NULL, NULL, '$2y$10$DERWA7uw9psHvoUkC4Epo.sHAfqwIu1A/nMZf2cVpu1B8jexfPa9.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-28', '2023-10-12 17:24:11', '2023-10-12 17:24:11', NULL),
(41, 'Narisensio  Niwagaba', 1, 1, '00239I0139135', 'Client', 0.00, '', '+256756083459', '', 'Male', '1975-12-23', '', 'Ugandan', '', '', 'Nalukologo', 'Nalukologo', '', '', '1970-01-01', 'Kanyesigye Dreek', '', '+256779908041', '', '', '', NULL, NULL, NULL, '$2y$10$7DcMrBkutF3mKIlExSrJ7u36Ln9g542oQ51bnTosS.nMUHxx5bmNS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-06', '2023-10-12 17:24:11', '2023-10-12 17:24:11', NULL),
(42, 'Mr. Jonan  Semujju', 1, 1, '00239I0139136', 'Client', 0.00, '', '+256787830723', '', 'Male', '1992-11-22', '', 'Ugandan', '', '', 'Lwanga ward', 'Mpigi town council', '', '', '1970-01-01', 'Amyuyu Emmanuel', '', '+256781216747', '', '', '', NULL, NULL, NULL, '$2y$10$c1704vKLW9pE8IYu1Un5b.Ev0YVEMi4PeonOA0P7WPMniHlgTPXhC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-02', '2023-10-12 17:24:11', '2023-10-12 17:24:11', NULL),
(43, 'Mrs. Sharon  Kasoma', 1, 1, '00239I0139138', 'Client', 0.00, '', '+256782692462', '', 'Female', '1987-08-21', '', 'Ugandan', '', '', 'Kikaya', 'Kawempe Division', '', '', '1970-01-01', 'Mutyaba Isaac', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Qa2Ez1pKGKx10swG3LzE6e9BhRZuFARbW9WGVb5oZdNNV72ejmmRC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-04', '2023-10-12 17:24:11', '2023-10-12 10:36:58', NULL),
(44, 'Mrs. JULIET  NANDUTU', 1, 1, '00239I0139151', 'Client', 0.00, '', '+256781812711', '', 'Female', '1987-01-01', '', 'Ugandan', '', '', 'RUBAGA', 'RUBAGA', '', '', '1970-01-01', 'JULIET', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$.Yz7A.r0cYF4tUrDIfBFc.se2vzuX.7/n/cSVYFzvUAgZrC8sm5f6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2010-01-01', '2023-10-12 17:24:12', '2023-10-12 10:36:58', NULL),
(45, 'Mr. john  Arinaitwe', 1, 1, '00239I0139169', 'Client', 0.00, '', '+256701998414', '', 'Male', '2019-12-05', '', 'Ugandan', '', '', 'Najjera', 'Nakawa', '', '', '1970-01-01', 'Aijuka Hebert/ Kahababo Gift', '', '+256702444267', '', '', '', NULL, NULL, NULL, '$2y$10$isexk6pcUbra.2QPeEZZPuOt3Gq6W0uw.PDrOivxBFzwJHpLgb6JO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:12', '2023-10-12 17:24:12', NULL),
(46, 'vicent  tumushabe', 1, 1, '00239I0139171', 'Client', 0.00, '', '+256752982965', '', 'Male', '1986-07-20', '', 'Ugandan', '', '', 'bwaise', 'bwaise', '', '', '1970-01-01', 'birungi deus', '', '+256758337150', '', '', '', NULL, NULL, NULL, '$2y$10$ePT7z6q4aLmCrn6scA1XMOUXi37O.5J4/MBHduLEYfkgFGSQntI1i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:12', '2023-10-12 17:24:12', NULL),
(47, 'mariam  kaudha', 1, 1, '00239I0139172', 'Client', 0.00, '', '+256752297792', '', 'Female', '1982-12-23', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'tamale siraje', '', '+256704306650', '', '', '', NULL, NULL, NULL, '$2y$10$CUTsO7goGTGkQRAHM961fuh8o5UfbXq6NHi1MKRDY.VpQ2vpFn2ri', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:12', '2023-10-12 17:24:12', NULL),
(48, 'Mr. fred  masaba', 1, 1, '00239I0139173', 'Client', 0.00, '', '+256759900374', '', 'Male', '2019-12-04', '', 'Ugandan', '', '', 'Banda', 'Banda', '', '', '1970-01-01', 'nandutu juliet', '', '+256781812711', '', '', '', NULL, NULL, NULL, '$2y$10$9hZzNJTHwDn/x4WXmqjwUukYnpmLjHqOUjKe9GmQnETc9kzfc1saK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:12', '2023-10-12 17:24:12', NULL),
(49, 'faridah  naluyima', 1, 1, '00239I0139174', 'Client', 0.00, '', '+256753214805', '', 'Female', '1979-12-12', '', 'Ugandan', '', '', 'banda', 'banda', '', '', '1970-01-01', 'kamya proscovia', '', '+256751064610', '', '', '', NULL, NULL, NULL, '$2y$10$JLKSDvlTEAUjSMSM2nfIuuaD07oEDda0pVQgnpe8P1buc88KQ4SMS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:12', '2023-10-12 17:24:12', NULL),
(50, 'eva  katana', 1, 1, '00239I0139175', 'Client', 0.00, '', '+256759131944', '', 'Female', '1982-11-11', '', 'Ugandan', '', '', 'kito', 'kito', '', '', '1970-01-01', 'nalubwega pavin', '', '+256786670310', '', '', '', NULL, NULL, NULL, '$2y$10$UXbbrROrtG3D6Y6cyINGMe0bZWyL3icpvnWpF6zUj.DaTa5FVUM8i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:13', '2023-10-12 17:24:13', NULL),
(51, 'Mr. PATRICK  LUGAYIZI', 1, 1, '00239I0139176', 'Client', 0.00, '', '+256704956809', '', 'Male', '1980-01-01', '', 'Ugandan', '', '', 'KISWA', 'NAKAWA', '', '', '1970-01-01', 'LUGOLOBI ENOCK', '', '+256702810111', '', '', '', NULL, NULL, NULL, '$2y$10$HxIUPxIjT..dtRhX.5EK6OLO4/XLjlssk2EXINpp0ln3URp/LfLwe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:13', '2023-10-12 17:24:13', NULL),
(52, 'Ms. BETTY  AKELLO', 1, 1, '00239I0139177', 'Client', 0.00, '', '+256782863793', '', 'Female', '1966-01-01', '', 'Ugandan', '', '', 'KIREKA WARD', 'KIRA TOWN COUNCIL', '', '', '1970-01-01', 'AMONGIN MATILDA', '', '+256781129138', '', '', '', NULL, NULL, NULL, '$2y$10$dOoGU6gaKgVqg4JFccHim.UjqrvWB0sYpXJ1X7KhQQ7RoV758KB5G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:13', '2023-10-12 17:24:13', NULL),
(53, 'shamira  namubiru', 1, 1, '00239I0139178', 'Client', 0.00, '', '+256755419187', '', 'Female', '1985-12-20', '', 'Ugandan', '', '', 'banda', 'banda', '', '', '1970-01-01', 'nakintu resty', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$/ICLZidT6kcsCeDRKpd6bOzMCT7WBvAodmcXU/sC4EdvQa6vsf0Ai', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:13', '2023-10-12 10:36:58', NULL),
(54, 'Agness Kabahumuza Banura', 1, 1, '00239I0139194', 'Client', 0.00, '', '+256776914103', '', 'Female', '1983-01-01', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Talemwa Rodgers', '', '+256787312582', '', '', '', NULL, NULL, NULL, '$2y$10$p/8IVnomq4UdBIuwoJ7xw.jqHnrYwzqyaDcLjYYjMFUE2omsWUcV6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:13', '2023-10-12 17:24:13', NULL),
(55, 'Mr. HERNY  LUBWAMA', 1, 1, '00239I0139195', 'Client', 0.00, '', '+256781476888', '', 'Male', '1962-01-01', '', 'Ugandan', '', '', 'KIRINYA', 'KIRA TOWN COUNCIL', '', '', '1970-01-01', 'KATANA EVA', '', '+256759131944', '', '', '', NULL, NULL, NULL, '$2y$10$CUwuqne6IkVFCD4LnZjMRukEwnF4sj798uPHGH1/ToXhDgy.rThLq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:13', '2023-10-12 17:24:13', NULL),
(56, 'Abdul  Kato', 1, 1, '00239I0139196', 'Client', 0.00, '', '+256703704218', '', 'Male', '1975-07-25', '', 'Ugandan', '', '', 'lugala', 'lubaga', '', '', '1970-01-01', 'Nsereko Joseph', '', '+256754084083', '', '', '', NULL, NULL, NULL, '$2y$10$G15sdJ4OyZPL5QN.U1Z02.vxQKrR13t0Aa2ZSsMPLjpJeo0CZesju', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(57, 'Mr. DEUS  BYOMUHANGI', 1, 1, '00239I0139197', 'Client', 0.00, '', '+256757539540', '', 'Male', '1977-10-09', '', 'Ugandan', '', '', 'RWENE', 'BUHARA', '', '', '1970-01-01', 'SENDAGALA MICHEAL', '', '+256756220507', '', '', '', NULL, NULL, NULL, '$2y$10$nE7KBhAYobLhLfVilBh7besM.6YEPnER9Q8IKsAf9ZBLdHM3Ughb6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(58, 'Rodgers  Talemwa', 1, 1, '00239I0139198', 'Client', 0.00, '', '+256787312582', '', 'Male', '1984-12-31', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Namukisa Monica', '', '+256782746477', '', '', '', NULL, NULL, NULL, '$2y$10$4DsJNPOkoX6FYcpyK7SJvuAvlxNrirE4UWzxiNAOHrKFGz442RJ3O', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(59, 'Ms. OLIVER  NANYONGA', 1, 1, '00239I0139233', 'Client', 0.00, '', '+256759951620', '', 'Female', '1983-12-12', '', 'Ugandan', '', '', 'KOOKI WARD', 'LYANTONDE', '', '', '1970-01-01', 'AKAMPULIRA ANGEL', '', '+256756557991', '', '', '', NULL, NULL, NULL, '$2y$10$fc03uO7T0G79I3Rtf23s6uQbuFAizWIC5Vu5kKfu8ijj.YNA30.p6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(60, 'Mr. DAVID  MUKWAYA', 1, 1, '00239I0139236', 'Client', 0.00, '', '+256782609358', '', 'Male', '1964-01-01', '', 'Ugandan', '', '', 'KYEBANDO', 'WAKISO', '', '', '1970-01-01', 'NAKYANZIB TRACE', '', '+256706556777', '', '', '', NULL, NULL, NULL, '$2y$10$ZvkkMg975rJd6GyT.m.Owe6e6BM/ycVzEV4N5E2cttnrNaeZIzH0y', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(61, 'Ms. TABISA  MUDHASI', 1, 1, '00239I0139238', 'Client', 0.00, '', '+256774694388', '', 'Female', '1972-04-02', '', 'Ugandan', '', '', 'KIRINYA WARD', 'KIRA TOWN COUNCIL', '', '', '1970-01-01', 'MBEZI PATRICA', '', '+256700783787', '', '', '', NULL, NULL, NULL, '$2y$10$LXShpjcp/TfbXcFJys0jpuClfHhsaM2ziuuTS17REfRbxhiZ3HVyO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:14', '2023-10-12 17:24:14', NULL),
(62, 'Mr. CRANEMAN  BUSULWA', 1, 1, '00239I0139239', 'Client', 0.00, '', '+256701691284', '', 'Male', '1987-02-05', '', 'Ugandan', '', '', 'Namulondo', 'Bweyogerere', '', '', '1970-01-01', 'NAMATOVU SUZAN', '', '+256703409895', '', '', '', NULL, NULL, NULL, '$2y$10$MT3S.EkPdtZwvpvcudzNIeHBlysDc8BFgxRV5O/bbgY.LEn8rjYSq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:15', '2023-10-12 17:24:15', NULL),
(63, 'Ms. ROSE NAKAWUNGU NAMUSOKE', 1, 1, '00239I0139240', 'Client', 0.00, '', '+256775883125', '', 'Female', '1979-04-14', '', 'Ugandan', '', '', 'NSUUBEKAUGA', 'MUKONO DIVISION', '', '', '1970-01-01', 'NABUKENYA FLORENCE', '', '+256701939003', '', '', '', NULL, NULL, NULL, '$2y$10$T4v3vu7R4ET36n/gTS6T1eD3IX2jjhzsY44CaXBMDdjP.s4ZTuPwS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:15', '2023-10-12 17:24:15', NULL),
(64, 'Monica Nassali Ddumba', 1, 1, '00239I0141207', 'Client', 0.00, '', '+256705933310', '', 'Female', '1978-08-30', '', 'Ugandan', '', '', 'kyebando', 'nasana', '', '', '1970-01-01', 'Nakaddu Teopista', '', '+256781677127', '', '', '', NULL, NULL, NULL, '$2y$10$A7r0hr2rWI/qXEL3cW/9F.dCYk/sfT2l.TIBmn3qSoJu3WpQqLe4W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:15', '2023-10-12 17:24:15', NULL),
(65, 'Asumani  Mubiru', 1, 1, '00239I0141209', 'Client', 0.00, '', '+256752009911', '', 'Male', '1973-10-25', '', 'Ugandan', '', '', 'kawaala', 'lubaga', '', '', '1970-01-01', 'Nassolo Gorret', '', '+256703784578', '', '', '', NULL, NULL, NULL, '$2y$10$F5jEDMgNzl3nMh7hRTpEdes7xrdTpam42j6DqEFuygRW0NJtTOO5.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-07', '2023-10-12 17:24:15', '2023-10-12 17:24:15', NULL),
(66, 'Mr. Isaac  Mutyaba', 1, 1, '00239I0141219', 'Client', 0.00, '', '+256774275040', '', 'Male', '1985-06-03', '', 'Ugandan', '', '', 'nasser', 'nasse', '', '', '1970-01-01', 'Muyingo Marian', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$yWm1ICghMBfH5CN5Ftfkje5cU/umpCNBz1Qyx3NdzfqGEeESJf5EG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:15', '2023-10-12 10:36:58', NULL),
(67, 'Mr. Rogerpascal  Okwanyoleke', 1, 1, '00239I0141221', 'Client', 0.00, '', '+256752479590', '', 'Male', '1974-10-17', '', 'Ugandan', '', '', 'Kireka Ward', 'Kira Town council', '', '', '1970-01-01', 'Atworo Carol Velerie', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$C/eELghtM0585iqLuI13Rut2e.KBo0jzb9LHWmEbRPQ7kVhH9Zr0S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-04', '2023-10-12 17:24:16', '2023-10-12 10:36:58', NULL),
(68, 'Mr. Elvis  Nimaro', 1, 1, '00239I0141224', 'Client', 0.00, '', '+256757064896', '', 'Male', '1988-06-28', '', 'Ugandan', '', '', 'kampala', 'kampala', '', '', '1970-01-01', 'Peter Katalaga', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$W66.ADBuHgWnT8LORRc/h.9C/EeKPcaKBdZ5UYIUeyhNTghz6cIGS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-05', '2023-10-12 17:24:16', '2023-10-12 10:36:58', NULL),
(69, 'Ms. leonard  sanyu', 1, 1, '00239I0141242', 'Client', 0.00, '', '+256703263361', '', 'Female', '1986-02-10', '', 'Ugandan', '', '', 'upper estate', 'nakawa', '', '', '1970-01-01', 'namujju irene', '', '+256782211704', '', '', '', NULL, NULL, NULL, '$2y$10$.uHPYofkkx1suegW.zvc7uTUueUHgTvFd35H7CudxuZWs/MAbyWfa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:16', '2023-10-12 17:24:16', NULL),
(70, 'Ms. Robbina  Nankumbi', 1, 1, '00239I0141244', 'Client', 0.00, '', '+256772959679', '', 'Female', '1958-07-01', '', 'Ugandan', '', '', 'Kireka ward', 'Kira Town council', '', '', '1970-01-01', 'Nekesa Esther', '', '+256758250661', '', '', '', NULL, NULL, NULL, '$2y$10$HFEJTJRQdUJX45AWGZOqYOjzHUYVAfBm1suulLITrUP3Hs/0gpDYu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:16', '2023-10-12 17:24:16', NULL),
(71, 'Peggy  Nazziwa', 1, 1, '00239I0141245', 'Client', 0.00, '', '+256758586995', '', 'Female', '1972-10-23', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Nalwoga Christine', '', '+256703637356', '', '', '', NULL, NULL, NULL, '$2y$10$dFhqdQFC3Fb8zRjyUyw3muPdRYf6n0sE/GwOhfAPZXQ1eeKxxvGiW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:16', '2023-10-12 17:24:16', NULL),
(72, 'Ms. Joweria  Baluka', 1, 1, '00239I0141246', 'Client', 0.00, '', '', '', 'Female', '1975-11-10', '', 'Ugandan', '', '', 'Bweyogerere', 'Kira Town Council', '', '', '1970-01-01', 'Yiga Aida', '', '+256789983986', '', '', '', NULL, NULL, NULL, '$2y$10$.RrzIHcdnJFLPyGKBHBmdOwbPgbO0sZO.VXcFWjQrafpY6wI9cPbC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:16', '2023-10-12 10:36:27', NULL),
(73, 'Fatuma  Nabatanzi', 1, 1, '00239I0141247', 'Client', 0.00, '', '+256703198117', '', 'Female', '1979-04-04', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Kebirungi Flavia', '', '+256785745050', '', '', '', NULL, NULL, NULL, '$2y$10$4IED1C.jbGHx/BqRNXDo7u1/qsUXCam1X1Yf8CdPXt9qvmTwjZlxW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:16', '2023-10-12 17:24:16', NULL),
(74, 'Flavia  Kebirungi', 1, 1, '00239I0141248', 'Client', 0.00, '', '+256785745050', '', 'Female', '1989-12-17', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Nabatanzi Fatuma', '', '+256703198117', '', '', '', NULL, NULL, NULL, '$2y$10$nC.SCRNnrPlecMMo5aG2a.T9JI2nQq4WtByZOA4O3iOFboDilL2Ra', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:17', '2023-10-12 17:24:17', NULL),
(75, 'Mr. Deus  Birungi', 1, 1, '00239I0141249', 'Client', 0.00, '', '+256758337150', '', 'Male', '2019-12-09', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'TUMUSHABE VICENT', '', '+256752982965', '', '', '', NULL, NULL, NULL, '$2y$10$ZeUkGu0DbDQ49VnkT9paoOuckn6mIA/DgMJDrhIQHd5AwYBUoQUzG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-09', '2023-10-12 17:24:17', '2023-10-12 17:24:17', NULL),
(76, 'Teddy  Kusoota', 1, 1, '00239I0141279', 'Client', 0.00, '', '+256704940684', '', 'Female', '1964-12-11', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Nassolo Aisha', '', '+256782997420', '', '', '', NULL, NULL, NULL, '$2y$10$Jq7a.6Z.dxA2no8PhPfc4O171yOA84E8qYwviYi6onIGY9bM3BeI.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-10', '2023-10-12 17:24:17', '2023-10-12 17:24:17', NULL),
(77, 'RICHARD  MAGENYI', 1, 1, '00239I0141301', 'Client', 0.00, '', '+256750233332', '', 'Male', '1971-02-04', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'MAGENYI', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$18RGvZ7ANNKFREr12U5HcOX2tE2NU/piiVh9GopClU5MZAOxggicG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-08-01', '2023-10-12 17:24:17', '2023-10-12 10:36:58', NULL),
(78, 'Ms. Amina  Kikomeko', 1, 1, '00239I0141313', 'Client', 0.00, '', '+256752579864', '', 'Female', '1997-10-10', '', 'Ugandan', '', '', 'Bweyogerere ward', 'Kira Town Council', '', '', '1970-01-01', 'Namugga Fatuma Kalembera', '', '+256774637242', '', '', '', NULL, NULL, NULL, '$2y$10$gTh.mll.ZtJG5kwsCv4otuatf14NND4yxWXTncwHEmbsrccE7WnyW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-11', '2023-10-12 17:24:17', '2023-10-12 17:24:17', NULL),
(79, 'Asiha  Nakawooya', 1, 1, '00239I0141331', 'Client', 0.00, '', '+256753056807', '', 'Female', '1996-09-19', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Kalumba Rashidah', '', '+256772505286', '', '', '', NULL, NULL, NULL, '$2y$10$9IgWcCGD3Lt29l/ZS7bjoeewG7TYZmgSgblAmP9OEmyab10DVtd/W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-12', '2023-10-12 17:24:17', '2023-10-12 17:24:17', NULL),
(80, 'Lydia  Kyomugisha', 1, 1, '00239I0141332', 'Client', 0.00, '', '+256753638404', '', 'Female', '1979-09-28', '', 'Ugandan', '', '', 'Nalukolongo', 'Lubaga', '', '', '1970-01-01', 'Nakayiwa Joan', '', '+256751647464', '', '', '', NULL, NULL, NULL, '$2y$10$fh34DPxFlv3aPPf/YktKseYhTzG0ny321y4UsJmojScyDsUTJFB3q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-12', '2023-10-12 17:24:18', '2023-10-12 17:24:18', NULL),
(81, 'Mr. Rogers  Twongyeirwe', 1, 1, '00239I0141334', 'Client', 0.00, '', '+256751331085', '', 'Male', '1975-12-18', '', 'Ugandan', '', '', 'Bukoto', 'Nakawa', '', '', '1970-01-01', 'Karungi Catherine', '', '+256702474435', '', '', '', NULL, NULL, NULL, '$2y$10$elHb94tymMa01Dqa0GVMgeUrTaOTyT1pDHfw.91BHyKQt0EoOxAEm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-12', '2023-10-12 17:24:18', '2023-10-12 17:24:18', NULL),
(82, 'Hadijah  Komugisha', 1, 1, '00239I0141360', 'Client', 0.00, '', '+256700833185', '', 'Female', '1968-10-11', '', 'Ugandan', '', '', 'kyebando', 'Nasana', '', '', '1970-01-01', 'Nisiima Jalia', '', '+256700831905', '', '', '', NULL, NULL, NULL, '$2y$10$bxz1dXRdKExVqyswwN66EucjGJbBYrBp.5hH.muquKa6Mw3wbN4lW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-13', '2023-10-12 17:24:18', '2023-10-12 17:24:18', NULL),
(83, 'Ms. Amina  Nakibuuka', 1, 1, '00239I0141361', 'Client', 0.00, '', '+256781650078', '', 'Female', '1978-05-25', '', 'Ugandan', '', '', 'Kireka Ward', 'Kira Town Council', '', '', '1970-01-01', 'Mutubazi julius', '', '+256704426509', '', '', '', NULL, NULL, NULL, '$2y$10$PMIou.rcm0NzU2./7MAJkexTtdPawH/BtFrRu/h8Ana9nv00wWKyK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-16', '2023-10-12 17:24:18', '2023-10-12 17:24:18', NULL),
(84, 'Abud Salongo Kaliira', 1, 1, '00239I0141393', 'Client', 0.00, '', '+256775238239', '', 'Male', '1972-10-01', '', 'Ugandan', '', '', 'kyaliwajjala', 'kyalijwajjala', '', '', '1970-01-01', 'Kato Shakib', '', '+256779877493', '', '', '', NULL, NULL, NULL, '$2y$10$OddrdA0USe/ajI/39P.Frur.Cf2lnDCvmdUbwNcyvrhUsIq2PfVn6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-16', '2023-10-12 17:24:18', '2023-10-12 17:24:18', NULL),
(85, 'Hadijah  Najjingo', 1, 1, '00239I0141394', 'Client', 0.00, '', '+256772476150', '', 'Female', '1966-06-03', '', 'Ugandan', '', '', 'nalukolongo', 'nalukolongo', '', '', '1970-01-01', 'Kambugu Ddamulira', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$2lVlV6BPzogfmCYrgngkPuTeSafHRKvimWaQR33/oiBL6r4Np8G2C', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-12-16', '2023-10-12 17:24:19', '2023-10-12 17:24:19', NULL),
(86, 'Ms. Winnie  Nalwanga', 1, 1, '00239I0141451', 'Client', 0.00, '', '', '', 'Female', '1979-12-30', '', 'Ugandan', '', '', 'kampala central', 'Nakawa', '', '', '1970-01-01', 'Joseph Lukula', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$JpaXNuV.9K8Sgru8VoxX3Oi4oU7t29fnrBREJ4cFpil3bwv5w1nHq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-06', '2023-10-12 17:24:19', '2023-10-12 10:36:58', NULL),
(87, 'David  Wasubira', 1, 1, '00239I0141463', 'Client', 0.00, '', '+256774915531', '', 'Male', '1983-09-19', '', 'Ugandan', '', '', 'Kampala', 'Kampala', '', '', '1970-01-01', 'Peter Katalaga', '', '+256775398441', '', '', '', NULL, NULL, NULL, '$2y$10$zaYMHtUv1sgn0jZd01Hh9urjOb0vmirchGPDIn.a/ItkbOVkNHBVe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2019-11-20', '2023-10-12 17:24:19', '2023-10-12 17:24:19', NULL),
(88, 'Francis  Kizza', 1, 1, '00239I0141835', 'Client', 0.00, '', '+256753954928', '', 'Male', '1983-01-10', '', 'Ugandan', '', '', 'kireka parish', 'kira town council', '', '', '1970-01-01', 'mwanje Emmaunel', '', '+256706631911', '', '', '', NULL, NULL, NULL, '$2y$10$lBabIkkp7pW01I3YsDSdV.I5AhjTShq5VAUAYuza34XLKm6waozzW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-07', '2023-10-12 17:24:19', '2023-10-12 17:24:19', NULL),
(89, 'Gasta  Kirigola', 1, 1, '00239I0141837', 'Client', 0.00, '', '+256788660826', '', 'Male', '1980-11-10', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Zawende Sharon', '', '+256752481998', '', '', '', NULL, NULL, NULL, '$2y$10$DW0HrpPWAsnxAta.kecjien5rBxmY/GfSjl/LSmo6WiPpBYJm.ubK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-06', '2023-10-12 17:24:19', '2023-10-12 17:24:19', NULL),
(90, 'Annet Atenyi Kusiima', 1, 1, '00239I0141840', 'Client', 0.00, '', '+256752726484', '', 'Female', '1981-10-19', '', 'Ugandan', '', '', 'kasubi', 'kasubi', '', '', '1970-01-01', 'Namiiro Lamula', '', '+256751669627', '', '', '', NULL, NULL, NULL, '$2y$10$Z/Kwkb7HHubNhdzZWZt3R.1k7QYNboRNGsuQSqzI2HW.7YOuq.DKu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-06', '2023-10-12 17:24:19', '2023-10-12 17:24:19', NULL),
(91, 'Julius  Byaruhanga', 1, 1, '00239I0141842', 'Client', 0.00, '', '+256755696890', '', 'Male', '1973-07-10', '', 'Ugandan', '', '', 'nakulabye', 'nakulabye', '', '', '1970-01-01', 'Twijukye simon', '', '+256757800305', '', '', '', NULL, NULL, NULL, '$2y$10$X5906XSfg.NgmtaBKOFW6u9yaNk2sm.QC15BzBfHF3x4EmjHL3ve2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-06', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(92, 'Mr. Mutwalib  Kiyimba', 1, 1, '00239I0141864', 'Client', 0.00, '', '+256756858662', '', 'Male', '1982-05-06', '', 'Ugandan', '', '', 'Kamusabi', 'Kayonza', '', '', '1970-01-01', 'Kiyimba Sula', '', '+256757112902', '', '', '', NULL, NULL, NULL, '$2y$10$ZLxJI9fqRKb6z0nKt/nc8e.x.xnhzz4pCr3n151Ntb8Uda7Ej7DAe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-07', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(93, 'Ms. Rehema  Nabukeera', 1, 1, '00239I0141866', 'Client', 0.00, '', '+256700545580', '', 'Female', '1984-01-01', '', 'Ugandan', '', '', 'Butende', 'Ngando', '', '', '1970-01-01', 'Kambugu D', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$d55OUDzdralkiQUFiNuNteVAZiWJZylAMMP1kZ2Sfl3b9pNCEosDq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-07', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(94, 'Ms. milly  Nambalirwa', 1, 1, '00239I0142768', 'Client', 0.00, '', '+256755930174', '', 'Female', '1981-02-28', '', 'Ugandan', '', '', 'kirinya ward', 'kira town council', '', '', '1970-01-01', 'Bauza Teddy', '', '+256700959094', '', '', '', NULL, NULL, NULL, '$2y$10$LsA98cegR/q.OQ6/fU4.quczEoB51/0AJuF2bMb9YGfYw094n6Igq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(95, 'Samuel  Sseyondo', 1, 1, '00239I0142769', 'Client', 0.00, '', '+256701695336', '', 'Male', '1983-09-27', '', 'Ugandan', '', '', 'Kyebando', 'Nasana', '', '', '1970-01-01', 'Masiko isaac', '', '+256709526152', '', '', '', NULL, NULL, NULL, '$2y$10$cji3gZ9F874T.Q9yCWS5AeYIHrg6jSD/PllWaOLDa9tvKC8tuvUMu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(96, 'Moses  Nkeera', 1, 1, '00239I0142770', 'Client', 0.00, '', '+256756751440', '', 'Male', '1985-10-01', '', 'Ugandan', '', '', 'Kyebando', 'Kyebando', '', '', '1970-01-01', 'Mukundane Gilbert', '', '+256700365927', '', '', '', NULL, NULL, NULL, '$2y$10$Rlhk9RM7nK.pZobyVY6W9eEnYcQo9cwJTVX3.9XVR0qltcZa8/EP6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:20', '2023-10-12 17:24:20', NULL),
(97, 'Shafik  Kitandwe', 1, 1, '00239I0142771', 'Client', 0.00, '', '+256753180293', '', 'Male', '1989-02-22', '', 'Ugandan', '', '', 'Kasubi', 'Kasubi', '', '', '1970-01-01', 'Kitandwe buluhan', '', '+256752123227', '', '', '', NULL, NULL, NULL, '$2y$10$hgpULD.QUH.mBCX2TTtO3Onte/3iPVS67VyDfFBwKDiZy8lA99oqO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:21', '2023-10-12 17:24:21', NULL),
(98, 'Ms. Annet  Ajambo', 1, 1, '00239I0142804', 'Client', 0.00, '', '+256758412216', '', 'Female', '1980-12-05', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Basaaya Peter Rocks', '', '+256784879090', '', '', '', NULL, NULL, NULL, '$2y$10$5pPdq9xORJbS54BnjGH7/eAXdQabMs5gLruorIPPezkmmjh294tRi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:21', '2023-10-12 17:24:21', NULL),
(99, 'Johnson Senior Lule', 1, 1, '00239I0142805', 'Client', 0.00, '', '+256783995858', '', 'Male', '1979-09-10', '', 'Ugandan', '', '', 'Ganda', 'Ganda', '', '', '1970-01-01', 'Naluzze Lillian', '', '+256752072106', '', '', '', NULL, NULL, NULL, '$2y$10$4rTyjdkEvBzqr0JFrhVuO.ZlfnzNhI/3s4AXJUKeYaBTcbJ1UGwZq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:21', '2023-10-12 17:24:21', NULL),
(100, 'Getrude  Ndagire', 1, 1, '00239I0142806', 'Client', 0.00, '', '+256756665688', '', 'Female', '1973-11-22', '', 'Ugandan', '', '', 'Lugujja', 'Lugujja', '', '', '1970-01-01', 'Nankya Mary', '', '+256756486955', '', '', '', NULL, NULL, NULL, '$2y$10$tEjcmWkqgoe3WWTKhCOzHOz2xaMBbN59RvuED9fXS.Qsnu7AmIFy2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:21', '2023-10-12 17:24:21', NULL),
(101, 'Mr. Abdu Disan Ssebalijja', 1, 1, '00239I0142807', 'Client', 0.00, '', '+256779329330', '', 'Male', '1987-09-23', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Nakabira Robina', '', '+256781844245', '', '', '', NULL, NULL, NULL, '$2y$10$iisS/dw/xkOUW3HZeR1Bb.0po.Lccyc3.T4N/5Yc3jrdYpWwu2dI6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-10', '2023-10-12 17:24:22', '2023-10-12 17:24:22', NULL),
(102, 'Edward  Mbaziira', 1, 1, '00239I0142808', 'Client', 0.00, '', '+256701490815', '', 'Male', '1979-08-07', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Namigadde Mariam', '', '+256701490815', '', '', '', NULL, NULL, NULL, '$2y$10$8O3/FvkFpvwubukGERAo0uwczl2xMLN.Th8gHzLJSQZUyzhGzfnAO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-09', '2023-10-12 17:24:22', '2023-10-12 17:24:22', NULL),
(103, 'Mr. James Daniel Gunogere', 1, 1, '00239I0142839', 'Client', 0.00, '', '+256773700590', '', 'Male', '1966-06-06', '', 'Ugandan', '', '', 'mutungo', 'Nakawa', '', '', '1970-01-01', 'Nakintu Aisha', '', '+256779933077', '', '', '', NULL, NULL, NULL, '$2y$10$YYtD8.PnKrMFFUVq3GYdmul.5LO96Hucfbfyin1IiwPTfCJrjUqQa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-11', '2023-10-12 17:24:22', '2023-10-12 17:24:22', NULL),
(104, 'Ms. Cissy  Nantume', 1, 1, '00239I0142881', 'Client', 0.00, '', '+256784178865', '', 'Female', '1982-07-12', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Kagoya sarah', '', '+256756238321', '', '', '', NULL, NULL, NULL, '$2y$10$5XwrGOlbgdFeokjH16JxG.xTe5uc0BbSuU8FQ906gzAiZzt6QhcNW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-14', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(105, 'Ms. proscovia  kamya', 1, 1, '00239I0142882', 'Client', 0.00, '', '+256751064610', '', 'Female', '1980-06-25', '', 'Ugandan', '', '', 'kyampogo', 'Nakawa', '', '', '1970-01-01', 'ssebandeke Ronald', '', '+256753239327', '', '', '', NULL, NULL, NULL, '$2y$10$MbXnBMFnkzDUf2BezEpXUOEH9eeeWBc0q7rlQ8cQiosR82MK.GT1K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-14', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(106, 'Ms. Robina  Nakagwe', 1, 1, '00239I0142884', 'Client', 0.00, '', '+256784123534', '', 'Female', '1977-07-04', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Nalubega Hanifa', '', '+256708792268', '', '', '', NULL, NULL, NULL, '$2y$10$Mop/b6lUvWye6GtlhwcAkeqwjh4kcpghXeylRqLc..WPUicA7CRsO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-14', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(107, 'Zam Mutebi Nabadduka', 1, 1, '00239I0143797', 'Client', 0.00, '', '+256701942118', '', 'Female', '1977-09-27', '', 'Ugandan', '', '', 'makindye', 'makindye', '', '', '1970-01-01', 'Kinobe moses', '', '+256752856828', '', '', '', NULL, NULL, NULL, '$2y$10$IzlWvu52w3v/hkI547noqO/WdVznXdJZbevGzUJPrfYZfTc388fk6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(108, 'viola  Birungi', 1, 1, '00239I0143798', 'Client', 0.00, '', '+256703603846', '', 'Female', '1985-01-01', '', 'Ugandan', '', '', 'kira', 'kira', '', '', '1970-01-01', 'Namuyiga Ruth', '', '+256704219721', '', '', '', NULL, NULL, NULL, '$2y$10$7gvY6Y6gir/kHivh6E99nOb0PXtPy6oQ3GbvTva34PLfq1Bn3NvhS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(109, 'Zayina  Nabulya', 1, 1, '00239I0143799', 'Client', 0.00, '', '+256758976058', '', 'Female', '1975-12-25', '', 'Ugandan', '', '', 'mbuya', 'mbuya', '', '', '1970-01-01', 'Namulembe Emmanuel', '', '+256703867118', '', '', '', NULL, NULL, NULL, '$2y$10$i66zoNkdgzW7S8mJ4lojtu16tCR8skD3LjLfDLEs3Fd7W1wbmr1JG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:23', '2023-10-12 17:24:23', NULL),
(110, 'Ronald Mwesigye Ssebandeke', 1, 1, '00239I0143801', 'Client', 0.00, '', '+256753239327', '', 'Male', '1984-09-30', '', 'Ugandan', '', '', 'Banda', 'banda', '', '', '1970-01-01', 'Kamya Proscovia', '', '+256751064610', '', '', '', NULL, NULL, NULL, '$2y$10$pgiSC1rUBiX2Rjit9wh9qOZNxYXt/zWv7POrM6NuXYwxhhTnN9Lzm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:24', '2023-10-12 17:24:24', NULL),
(111, 'Ms. Phoina  Birungi', 1, 1, '00239I0144712', 'Client', 0.00, '', '+256789455743', '', 'Female', '1991-11-02', '', 'Ugandan', '', '', 'kira ward', 'Kira town council', '', '', '1970-01-01', 'Birungi viola', '', '+256752490560', '', '', '', NULL, NULL, NULL, '$2y$10$KBhuxAQSsVC1kpCZA.KsseOT7sru9SXv1oLiDraVkDR4Izafv3eF2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-16', '2023-10-12 17:24:24', '2023-10-12 17:24:24', NULL),
(112, 'Geofrey  Ssentamu', 1, 1, '00239I0144714', 'Client', 0.00, '', '+256708171890', '', 'Male', '1987-08-02', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Ddumba Abdul', '', '+256757545883', '', '', '', NULL, NULL, NULL, '$2y$10$H.xV0eSmSjuYrqnsjLqMBOjbssM8HVRIXuPsB1Uxu15hV.qZnL2by', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:24', '2023-10-12 17:24:24', NULL),
(113, 'Ms. sarah  Kagoya', 1, 1, '00239I0144715', 'Client', 0.00, '', '+256706238321', '', 'Female', '1979-07-04', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Nantume cissy', '', '+256784178865', '', '', '', NULL, NULL, NULL, '$2y$10$iVw0aDvn0XgsuAi2WrrQPutgXDqQcBiZq0C7eEa76Y2Lg1xgiZzlS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-16', '2023-10-12 17:24:24', '2023-10-12 17:24:24', NULL),
(114, 'Mr. Ezra  Ssebanenya', 1, 1, '00239I0144716', 'Client', 0.00, '', '+256758023763', '', 'Male', '1997-04-04', '', 'Ugandan', '', '', 'kira', 'kira town council', '', '', '1970-01-01', 'Birungi viola', '', '+256752490560', '', '', '', NULL, NULL, NULL, '$2y$10$QbQrFzoxfN3hd9u/go6Qo.eGnoT9fVGRcxqifkJLVw6Sm6eFbmNdW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-16', '2023-10-12 17:24:24', '2023-10-12 17:24:24', NULL),
(115, 'Fred  Ssentenza', 1, 1, '00239I0144717', 'Client', 0.00, '', '', '', 'Male', '1977-06-14', '', 'Ugandan', '', '', 'mutundwe', 'mutundwe', '', '', '1970-01-01', 'kambungu Damulira', '', '+256752958020', '', '', '', NULL, NULL, NULL, '$2y$10$ejtzMfNZeIpU2w5ME.3ZpuZZwMZ7u6nV/Xad4CZ/Po3xuxW2MDAA.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-15', '2023-10-12 17:24:25', '2023-10-12 10:36:27', NULL),
(116, 'Ms. Grace  Kizza', 1, 1, '00239I0144751', 'Client', 0.00, '', '+256758170678', '', 'Female', '1977-12-24', '', 'Ugandan', '', '', 'kasubi', 'rubaga', '', '', '1970-01-01', 'Namuwonge Bunjo', '', '+256754242437', '', '', '', NULL, NULL, NULL, '$2y$10$fFP/PWirs9we1vhkHHfUDOsATKXxg7KRPIwbFdRCQ1kB7H.uxLLCS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:25', '2023-10-12 17:24:25', NULL);
INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(117, 'Ms. Jenifer Rugumayo Nantale', 1, 1, '00239I0144752', 'Client', 0.00, '', '+256759884635', '', 'Female', '1986-01-01', '', 'Ugandan', '', '', 'kasubi', 'rubaga', '', '', '1970-01-01', 'Ahebwa Daniel SEAN', '', '+256700925254', '', '', '', NULL, NULL, NULL, '$2y$10$h99lhKJIrzP6.jiTDyFyyuA9ZeW9PitdG.jI7hCWOxTHOZ7mXnop6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:25', '2023-10-12 17:24:25', NULL),
(118, 'Mr. JAMES KAFUREKA KAGIRA', 1, 1, '00239I0144753', 'Client', 0.00, '', '+256702375257', '', 'Male', '1979-02-06', '', 'Ugandan', '', '', 'Bugolobi', 'nakawa', '', '', '1970-01-01', 'kafureka Enid', '', '+256758465080', '', '', '', NULL, NULL, NULL, '$2y$10$ys8YE92GPLWtKglsEIi2SeK8jxzAd.Hbz7Tjpgi9OXaMHf2DJgB9i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:25', '2023-10-12 17:24:25', NULL),
(119, 'Ms. Lukiya  Nandyose', 1, 1, '00239I0144754', 'Client', 0.00, '', '+256701605032', '', 'Female', '1975-01-01', '', 'Ugandan', '', '', 'kyaliwajjala ward', 'kira town council', '', '', '1970-01-01', 'Namubiru firidausi', '', '+256787974174', '', '', '', NULL, NULL, NULL, '$2y$10$C3iEM4kbPKHnFllxPo/Gyub066rgx3Ooyqc9Lkt9TRVvRzW5ZI0fm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:25', '2023-10-12 17:24:25', NULL),
(120, 'Ms. Grace  Nakku', 1, 1, '00239I0144755', 'Client', 0.00, '', '+256782379738', '', 'Female', '1965-06-01', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Nanyonjo suzan', '', '+256753206168', '', '', '', NULL, NULL, NULL, '$2y$10$rKYaiIj1SfPWBr0DLwk1Ue53RVDxJECOk4PUps.0i3ofS9HrTFgsC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:25', '2023-10-12 17:24:25', NULL),
(121, 'Ms. Rita Rebbecca Nankinga', 1, 1, '00239I0144757', 'Client', 0.00, '', '+256754637108', '', 'Female', '1990-11-11', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Nabukala Ruth Rosette', '', '+256704189448', '', '', '', NULL, NULL, NULL, '$2y$10$9Ed8ECYlFOIgrBCWYMkbqOBUrylnMNNe1g4.ZGN3toF7Env/la8Ji', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(122, 'Aisha  Nanyonjo', 1, 1, '00239I0145673', 'Client', 0.00, '', '+256706832178', '', 'Female', '1979-07-01', '', 'Ugandan', '', '', 'Kyebando', 'Nasana', '', '', '1970-01-01', 'Ssesanga Abunawufar', '', '+256758613732', '', '', '', NULL, NULL, NULL, '$2y$10$AQloTQIWk56mP1PKXmiwV.oexSkHkXs/yMRKgF6eGPiK9LGd8fM7m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(123, 'Mr. Ahamadha Ali Mwiduka', 1, 1, '00239I0145674', 'Client', 0.00, '', '+256752809860', '', 'Male', '2020-01-18', '', 'Ugandan', '', '', 'Kirinya', 'Kireku', '', '', '1970-01-01', 'kaudha zaina', '', '+256785912712', '', '', '', NULL, NULL, NULL, '$2y$10$NOAajJMJHhpSxEheLvq2.OG5E7cfbbJbuGwkdRlRLSyb.dS8kaT9u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-18', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(124, 'Oliver  Nalwanga', 1, 1, '00239I0145675', 'Client', 0.00, '', '+256752422598', '', 'Female', '1986-02-03', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nmisaago Zakia', '', '+256757191447', '', '', '', NULL, NULL, NULL, '$2y$10$fcMOKfiKRkw78dSmkJA5zuRCuMjxwEetKy/QfspLcTBrHhG/F4u4u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-17', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(125, 'Ms. Lydia  Nakimuli', 1, 1, '00239I0145677', 'Client', 0.00, '', '+256758411023', '', 'Female', '1982-01-01', '', 'Ugandan', '', '', 'kira', 'kira town council', '', '', '1970-01-01', 'Buriiro Harriet', '', '+256753938270', '', '', '', NULL, NULL, NULL, '$2y$10$5bT.7KTKczlDII762dAfwOl.99Z5cSLZ/3io6eHuSboaRGOPI1mem', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-18', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(126, 'Rose  Nasejje', 1, 1, '00239I0145736', 'Client', 0.00, '', '+256755778896', '', 'Female', '1979-11-23', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Kabagambe Morice', '', '+256702712828', '', '', '', NULL, NULL, NULL, '$2y$10$p.x8OGxkPHLCZV6512HsS.edDzUOfMQJaxU/ZT1TMta2t8LV7agIS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:26', '2023-10-12 17:24:26', NULL),
(127, 'Scovia  Nakalanzi', 1, 1, '00239I0145737', 'Client', 0.00, '', '+256705360543', '', 'Female', '1973-01-01', '', 'Ugandan', '', '', 'kito', 'kito', '', '', '1970-01-01', 'katana Eva', '', '+256759131944', '', '', '', NULL, NULL, NULL, '$2y$10$TQFuqOITmDmXS39ybwMjkewm4ydzxBIEYO43Co.uh0y04eJbbV31m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:27', '2023-10-12 17:24:27', NULL),
(128, 'Agnes  Namuli', 1, 1, '00239I0145738', 'Client', 0.00, '', '+256708787420', '', 'Female', '1982-02-22', '', 'Ugandan', '', '', 'kito', 'kito', '', '', '1970-01-01', 'Katana Eva', '', '+256759131944', '', '', '', NULL, NULL, NULL, '$2y$10$Q2XJyh1RO7YBknvt7hurnubAstkAyb0NiujLxX4y1jNUYRMyaDi92', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:27', '2023-10-12 17:24:27', NULL),
(129, 'Rose  Namagembe', 1, 1, '00239I0145740', 'Client', 0.00, '', '+256757938270', '', 'Female', '1983-11-28', '', 'Ugandan', '', '', 'bweyogerere', 'bweyogerere', '', '', '1970-01-01', 'Buriiro Harriet', '', '+256757938270', '', '', '', NULL, NULL, NULL, '$2y$10$SKsqlmARpMcICiXyJHymdePicEvxJoZ5tOkciB3zEMtHI7rlisyEq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:27', '2023-10-12 17:24:27', NULL),
(130, 'Harriet  Bashemera', 1, 1, '00239I0145794', 'Client', 0.00, '', '+256754417701', '', 'Female', '1975-12-12', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Bafuna Edith', '', '+256753095029', '', '', '', NULL, NULL, NULL, '$2y$10$Zr0iLUtIy82S8DejLV2GzexdE8pJsAvcdhJG3GtOMvXAjzqfeASTm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:27', '2023-10-12 17:24:27', NULL),
(131, 'Annet  Nakate', 1, 1, '00239I0145795', 'Client', 0.00, '', '+256702652375', '', 'Female', '1984-06-23', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga', '', '', '1970-01-01', 'Bashemera Harriet', '', '+256754417701', '', '', '', NULL, NULL, NULL, '$2y$10$JxdkUmSSNE/kljPqVAGrheor6PlbTEQ8FrRJFVRJ/OaAUISrB.H5S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:27', '2023-10-12 17:24:27', NULL),
(132, 'Priscilla  Elakiza', 1, 1, '00239I0145796', 'Client', 0.00, '', '+256754898777', '', 'Female', '1988-05-19', '', 'Ugandan', '', '', 'nakulabye', 'Rubaga', '', '', '1970-01-01', 'Bashemera Harriet', '', '+256754417701', '', '', '', NULL, NULL, NULL, '$2y$10$41S9/IOcXq9XGOV1kQMsZeZ.jrcJgkiVeK9byi1u17ar7f4DTXSae', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:28', '2023-10-12 17:24:28', NULL),
(133, 'Teddy  Nanziri', 1, 1, '00239I0145797', 'Client', 0.00, '', '', '', 'Female', '1959-10-21', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nabagesera Deborah', '', '+256701058957', '', '', '', NULL, NULL, NULL, '$2y$10$exeigRZMHNmx0QfY8z.3EexJORAACXgGzWbmaavD0gHXS1dmMszKu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:28', '2023-10-12 10:36:27', NULL),
(134, 'Merina  Agaba', 1, 1, '00239I0145798', 'Client', 0.00, '', '+256752847670', '', 'Female', '1974-05-03', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nuwarinda Ezra', '', '+256705115119', '', '', '', NULL, NULL, NULL, '$2y$10$TEZ8j/m0lYCNFCYal54SdOyKXgfwdMfZE1ksARK.EMMS/SGFN4Sgu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-21', '2023-10-12 17:24:28', '2023-10-12 17:24:28', NULL),
(135, 'Monday Erasmus Gad', 1, 1, '00239I0145834', 'Client', 0.00, '', '+256759353552', '', 'Male', '1978-11-24', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga', '', '', '1970-01-01', 'Ssesanga Denis', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$avpHSBKGk7BrnQymu/EzhePaou8g0Hyu5tiFJhk6Iuw4cCG6ZUznK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-22', '2023-10-12 17:24:28', '2023-10-12 10:36:58', NULL),
(136, 'Halima  Namukombe', 1, 1, '00239I0145835', 'Client', 0.00, '', '+256777311207', '', 'Female', '1992-02-06', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Mutuwa Daphine Agnes', '', '+256700414117', '', '', '', NULL, NULL, NULL, '$2y$10$Pv0vhOJXY6t5//F2bFBUAOLIeagjeETxbWZLWt.TmIyvcbm/s.CLS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-23', '2023-10-12 17:24:28', '2023-10-12 17:24:28', NULL),
(137, 'florence  Kaaya', 1, 1, '00239I0145836', 'Client', 0.00, '', '+256701023180', '', 'Female', '1976-05-01', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga', '', '', '1970-01-01', 'Onencan Jimmy', '', '+256756854594', '', '', '', NULL, NULL, NULL, '$2y$10$FeLLNt2Xpa6dc1u7jTueTO.f2fFDGrLIhkng8hBGzWCfmLimRx6B.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-22', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(138, 'Ms. Robinah  Nakabira', 1, 1, '00239I0145870', 'Client', 0.00, '', '+256781844245', '', 'Female', '2020-01-24', '', 'Ugandan', '', '', 'Banda 2', 'Uganda', '', '', '1970-01-01', 'ssebalija Abdul', '', '+256700213461', '', '', '', NULL, NULL, NULL, '$2y$10$F/8UpEK8wPoO39XVOuuwduBy66gBbB0sxZa01Mq1ghLWQt8yvSrcy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-24', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(139, 'Ms. Lukia  Namiiro', 1, 1, '00239I0145872', 'Client', 0.00, '', '+256754358543', '', 'Female', '1973-10-10', '', 'Ugandan', '', '', 'BANDA', 'NAKAWA', '', '', '1970-01-01', 'NAWAGI CISSY', '', '+256757775195', '', '', '', NULL, NULL, NULL, '$2y$10$FM3xpl4cl5HNTCCohqD1nukP7viXhG3oWJVPKNWT..VJ20eV90pFm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-24', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(140, 'Ms. florence  Nabatanzi', 1, 1, '00239I0145886', 'Client', 0.00, '', '+256755598189', '', 'Female', '1983-07-21', '', 'Ugandan', '', '', 'kasubi', 'rubaga', '', '', '1970-01-01', 'kayango edwine', '', '+256705451047', '', '', '', NULL, NULL, NULL, '$2y$10$L8hAnKifGoU5guec6c0S/uAxQ.lQ9ZyLYH4epOh3nICM6JiQYg.LW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-24', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(141, 'Mr. Davis Sam Male', 1, 1, '00239I0145887', 'Client', 0.00, '', '+256701403292', '', 'Male', '1968-09-20', '', 'Ugandan', '', '', 'kansanga', 'makindye', '', '', '1970-01-01', 'Male subbi Teddy', '', '+256701503292', '', '', '', NULL, NULL, NULL, '$2y$10$InbqQI8BYmUrHvz7qIMqCeIWjTTKaBKsa93zYZzfQm/IBH/SjoFRK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-24', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(142, 'Ms. Ramula  Mutesi', 1, 1, '00239I0145913', 'Client', 0.00, '', '+256754235327', '', 'Female', '1988-11-08', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Najjuma Nusulah sophia', '', '+256700166539', '', '', '', NULL, NULL, NULL, '$2y$10$FXiH9x1r8VAX5mn/LOoez.YWlRaxo3H/Hg/F0Rgfge2m00rqNUoJq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-25', '2023-10-12 17:24:29', '2023-10-12 17:24:29', NULL),
(143, 'Oliver  Nabukeera', 1, 1, '00239I0145923', 'Client', 0.00, '', '+256701686335', '', 'Female', '1975-02-10', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Namiiro Lydia', '', '+256758161621', '', '', '', NULL, NULL, NULL, '$2y$10$c9XEQIqQAITln85.weLSeuJzhLM5.udaJk.e9.ziiDxwEKbS3s.tq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-25', '2023-10-12 17:24:30', '2023-10-12 17:24:30', NULL),
(144, 'Simon  Twijukye', 1, 1, '00239I0145924', 'Client', 0.00, '', '+256757800305', '', 'Male', '1965-07-09', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Ashaba Doreen', '', '+256757430761', '', '', '', NULL, NULL, NULL, '$2y$10$J9vrn.Izz95HvmgLaZ0dyOEofcbUOA3fRIkEQl8o.AjduqzaCaJu2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-24', '2023-10-12 17:24:30', '2023-10-12 17:24:30', NULL),
(145, 'Josephine  Nakaddu', 1, 1, '00239I0145925', 'Client', 0.00, '', '+256781304133', '', 'Female', '1973-02-01', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Nakigozi magret', '', '+256700790517', '', '', '', NULL, NULL, NULL, '$2y$10$pquczzJUKjDoA8eY2tSN7OgwUAhlk9pBgcQuVlJDjnMyIIOlUu5Hi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-25', '2023-10-12 17:24:30', '2023-10-12 17:24:30', NULL),
(146, 'Magret  Nakigozi', 1, 1, '00239I0145926', 'Client', 0.00, '', '+256700790517', '', 'Female', '1976-09-06', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Nakaddu Josephine', '', '+256781304133', '', '', '', NULL, NULL, NULL, '$2y$10$AcOEiTU1D2xnJg9qEuv0au2E/gGAV6L6ulAt0y9UBQ5yw3DxEGCwm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-25', '2023-10-12 17:24:30', '2023-10-12 17:24:30', NULL),
(147, 'Maureen  Nakiyimba', 1, 1, '00239I0145927', 'Client', 0.00, '', '+256704214883', '', 'Female', '1990-12-23', '', 'Ugandan', '', '', 'nasana', 'Nasana', '', '', '1970-01-01', 'Namagero Doreen', '', '+256755706310', '', '', '', NULL, NULL, NULL, '$2y$10$ueCRwaqB3J8eC7UCljE.CO9CIGM.9X50i2MUe1b/raBaPte34vKYy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-25', '2023-10-12 17:24:30', '2023-10-12 17:24:30', NULL),
(148, 'Joseph  Nsereko', 1, 1, '00239I0145976', 'Client', 0.00, '', '+256772539198', '', 'Male', '1971-05-01', '', 'Ugandan', '', '', 'Makerere', 'Rubaga', '', '', '1970-01-01', 'Kato Abdul', '', '+256703704218', '', '', '', NULL, NULL, NULL, '$2y$10$pSow9en/La2nY576ys93fO21YPFxMwKpRJ1KYe9Q.92FV1/D/ciJG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:31', '2023-10-12 17:24:31', NULL),
(149, 'Grace  Nambi', 1, 1, '00239I0145978', 'Client', 0.00, '', '+256700855128', '', 'Female', '1978-06-23', '', 'Ugandan', '', '', 'Kiyinya', 'Kiyinya', '', '', '1970-01-01', 'Twinomugisha irene', '', '+256759756052', '', '', '', NULL, NULL, NULL, '$2y$10$D8qGC3h9mHyc26i5QAJypes56aELPL.iHBF3bRW/OUANf56kY62Py', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:31', '2023-10-12 17:24:31', NULL),
(150, 'Irene  Twinomugisha', 1, 1, '00239I0145979', 'Client', 0.00, '', '+256759756052', '', 'Female', '1983-10-09', '', 'Ugandan', '', '', 'Kirinya', 'Kirinya', '', '', '1970-01-01', 'Nambi Grace', '', '+256700855128', '', '', '', NULL, NULL, NULL, '$2y$10$C7nJ/kiadnsoY4wvJo5h3eANUO7fftG1mXjtCYcVyVng9Yin2JjXe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:31', '2023-10-12 17:24:31', NULL),
(151, 'Edward Kigongo Musisi', 1, 1, '00239I0145980', 'Client', 0.00, '', '+256772343874', '', 'Male', '1956-11-08', '', 'Ugandan', '', '', 'Kyaliwajara', 'Kira', '', '', '1970-01-01', 'Twongyeirwe jasper', '', '+256706110099', '', '', '', NULL, NULL, NULL, '$2y$10$095n2otFctmkjdwMuA3bdOwOvM7/3ZMOK5Nug28rEuDWve6Fz5Er2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:31', '2023-10-12 17:24:31', NULL),
(152, 'Jusper  Twongyeirwe', 1, 1, '00239I0145981', 'Client', 0.00, '', '+256706110099', '', 'Female', '1977-06-16', '', 'Ugandan', '', '', 'Kyaliwajjala', 'Kyaliwajjala', '', '', '1970-01-01', 'Birungi viola', '', '+256752490560', '', '', '', NULL, NULL, NULL, '$2y$10$jwtJ5iGpyUoFINkx0W.Weei9Y3ddvnQf3E.QjfHNVGjdQjJ3buTHe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:31', '2023-10-12 17:24:31', NULL),
(153, 'Zidah  Mutesi', 1, 1, '00239I0145982', 'Client', 0.00, '', '+256708180174', '', 'Female', '1972-07-12', '', 'Ugandan', '', '', 'Kyaliwajjala', 'Kyaliwajjala', '', '', '1970-01-01', 'Namunana Shamimu', '', '+256771907936', '', '', '', NULL, NULL, NULL, '$2y$10$ofTKCvzl1JRKBx4awoVUNObzqf3RmK11uDx00MXce2i0kJ6jLW47q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-28', '2023-10-12 17:24:32', '2023-10-12 17:24:32', NULL),
(154, 'Ms. Josephine  Kalebu', 1, 1, '00239I0146008', 'Client', 0.00, '', '+256702645369', '', 'Female', '1977-07-17', '', 'Ugandan', '', '', 'Lubya', 'Rubaga Division', '', '', '1970-01-01', 'Tukahirwa jovairo', '', '+256704331293', '', '', '', NULL, NULL, NULL, '$2y$10$22y53W/av.yTuEVVqdzdQuIh6uFweeKtmelmyanjz/ZeY.vY71XsK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-29', '2023-10-12 17:24:32', '2023-10-12 17:24:32', NULL),
(155, 'Florence  Nagawa', 1, 1, '00239I0146410', 'Client', 0.00, '', '+256785565482', '', 'Female', '1986-06-05', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Nanziri Teddy', '', '+256704039333', '', '', '', NULL, NULL, NULL, '$2y$10$Tv0OeF3cXUr0aWi6c/keJumCZR.LF4xJshJXUz8Lx5yFjkK4.Hkuy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-29', '2023-10-12 17:24:32', '2023-10-12 17:24:32', NULL),
(156, 'Ruth  Namujju', 1, 1, '00239I0146411', 'Client', 0.00, '', '+256704573535', '', 'Female', '1978-04-20', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Nanziri Teddy', '', '+256704039333', '', '', '', NULL, NULL, NULL, '$2y$10$2.BbWF8plpuccaFJ29e7JeN6a5yyHRDH7RyoHDFL.ww8ZVCjHZKkO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-29', '2023-10-12 17:24:32', '2023-10-12 17:24:32', NULL),
(157, 'Oliver  Tumubwine', 1, 1, '00239I0146412', 'Client', 0.00, '', '+256758151627', '', 'Female', '1977-08-01', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Natukunda linus', '', '+256782230590', '', '', '', NULL, NULL, NULL, '$2y$10$fzkS9/NCBsP6vRutBke.yepisGOJ3icsWaidD120Q1LLXy4E1rszK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-29', '2023-10-12 17:24:33', '2023-10-12 17:24:33', NULL),
(158, 'Mr. Hakim  Nkono', 1, 1, '00239I0146442', 'Client', 0.00, '', '+256752086130', '', 'Male', '1981-02-15', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Namagembe Rehema', '', '+256753622048', '', '', '', NULL, NULL, NULL, '$2y$10$EMGbUQmKmAou51hiE2upKOqn/cymp2k504x.iB.604Bf98T/vkfyK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-31', '2023-10-12 17:24:33', '2023-10-12 17:24:33', NULL),
(159, 'Ms. Aminah  Akatukunda', 1, 1, '00239I0146443', 'Client', 0.00, '', '+256701332871', '', 'Female', '1978-08-06', '', 'Ugandan', '', '', 'lungujja', 'Rubaga Division', '', '', '1970-01-01', 'Namujju Ruth', '', '+256704573535', '', '', '', NULL, NULL, NULL, '$2y$10$OKYMCHgorBVKH4MfefEqpu4HeRn607/tfia0i1U66mtTVScN9oTYC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-01-31', '2023-10-12 17:24:33', '2023-10-12 17:24:33', NULL),
(160, 'Ms. Harriet  Nandaula', 1, 1, '00239I0146512', 'Client', 0.00, '', '+256706480526', '', 'Female', '1978-12-24', '', 'Ugandan', '', '', 'Busega', 'Rubaga Division', '', '', '1970-01-01', 'Ndagire Getrude', '', '+256756665688', '', '', '', NULL, NULL, NULL, '$2y$10$0.wdl.BBfLTyhJs3.SdA8e/mXSxQqYWKEHyoipuKq8wjNu8uk5e26', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:33', '2023-10-12 17:24:33', NULL),
(161, 'Ms. Eve  Nakabirwa', 1, 1, '00239I0146513', 'Client', 0.00, '', '+256701446058', '', 'Female', '1982-12-14', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga Division', '', '', '1970-01-01', 'Kusiima Annet', '', '+256701032090', '', '', '', NULL, NULL, NULL, '$2y$10$oEHF1/Qa7XuuhPBvsjkePOQmZYF6airNfLWuGYTCuLVH.TJNNn1ca', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:34', '2023-10-12 17:24:34', NULL),
(162, 'Ezra  Mbenamukama', 1, 1, '00239I0146537', 'Client', 0.00, '', '+256780424869', '', 'Male', '1986-04-02', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Tumubwine Oliver', '', '+256783155846', '', '', '', NULL, NULL, NULL, '$2y$10$Yjm5rQQYTyPtY5iOlROyeuF6aAx/8tZenqT5HIhIwBMrtmxDJZ4f.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:34', '2023-10-12 17:24:34', NULL),
(163, 'Hadijha Ahishakiye Nakintu', 1, 1, '00239I0146538', 'Client', 0.00, '', '+256756425423', '', 'Female', '1978-05-10', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nakavuma justine', '', '+256773431923', '', '', '', NULL, NULL, NULL, '$2y$10$UZb32IUyR7s.zWrw5X/A7OrYe1SD4Z9HB8BBnzc4A/fRkWnwpZt5S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:34', '2023-10-12 17:24:34', NULL),
(164, 'melon  Niwabigaba', 1, 1, '00239I0146539', 'Client', 0.00, '', '+256753418585', '', 'Female', '1985-01-08', '', 'Ugandan', '', '', 'Nakulabye', 'Rubanga', '', '', '1970-01-01', 'Kemigisha Hildah', '', '+256703835478', '', '', '', NULL, NULL, NULL, '$2y$10$sXEryW8zBS64qIM2XzP7NeVNaVHTVFsX7QZjP32Xd6cc9q4KGiPfu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:34', '2023-10-12 17:24:34', NULL),
(165, 'winnie  Nanyombi', 1, 1, '00239I0146540', 'Client', 0.00, '', '+256754962009', '', 'Female', '1986-10-10', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nakintu Hajidha', '', '+256774578284', '', '', '', NULL, NULL, NULL, '$2y$10$SmL6lfe/R6wzshaPFYIFe.gjjH7shn8.o3MOLjhHtMFUS7zPOZIaW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-03', '2023-10-12 17:24:34', '2023-10-12 17:24:34', NULL),
(166, 'Ms. Rose  Kiwuka', 1, 1, '00239I0146597', 'Client', 0.00, '', '+256758632466', '', 'Female', '1972-07-07', '', 'Ugandan', '', '', 'Kyambogo', 'Nakawa', '', '', '1970-01-01', 'Kamya Proscovia', '', '+256751064610', '', '', '', NULL, NULL, NULL, '$2y$10$s3tcYHGBoybisNu5jMK3t.4Ue4HBQjVWAtS8xetiH6TCac4QAvwGS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-05', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(167, 'Mr. Godfery  Kabaale', 1, 1, '00239I0146598', 'Client', 0.00, '', '+256751777063', '', 'Male', '1964-09-13', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga Division', '', '', '1970-01-01', 'Lumu Derrick', '', '+256702116114', '', '', '', NULL, NULL, NULL, '$2y$10$BMzbSdp3xt/.c7AWYAtogO2EdjNACQGzZYTONUEJra6nsGY.mw8xW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-05', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(168, 'Gozanga  Nantumbwe', 1, 1, '00239I0146612', 'Client', 0.00, '', '+256750520683', '', 'Female', '1970-11-01', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Nantale Jeniffer', '', '+256759884635', '', '', '', NULL, NULL, NULL, '$2y$10$BrpoZeZPL3b7ImtN7e0pC.r4AWvnVJyTDYrwh/0RbjNHA3KilzHEC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-06', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(169, 'Ms. Gladys  Mwema', 1, 1, '00239I0146639', 'Client', 0.00, '', '+256705003059', '', 'Female', '1980-01-28', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Lubega Ian', '', '+256773340825', '', '', '', NULL, NULL, NULL, '$2y$10$DM/oJ5kMdPmSSEXzSbYVP.ndHcADbyuqGsEQDD9tfUUC6RfwJhivy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-07', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(170, 'Teo  Nabowa', 1, 1, '00239I0146640', 'Client', 0.00, '', '+256759855900', '', 'Female', '1961-01-22', '', 'Ugandan', '', '', 'Ganda', 'Nasana', '', '', '1970-01-01', 'Namulondo Sylivia', '', '+256757845131', '', '', '', NULL, NULL, NULL, '$2y$10$8kus.xWPTrlSICzrBj6cfOhwX9W5IQzNoWZUE.0YUY.LrSGYzArCW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-06', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(171, 'Ms. Erinah  Namugaya', 1, 1, '00239I0146718', 'Client', 0.00, '', '+256701801833', '', 'Female', '1982-11-28', '', 'Ugandan', '', '', 'Bweyogerere', 'Kira town council', '', '', '1970-01-01', 'Namagembe Rose', '', '+256754458999', '', '', '', NULL, NULL, NULL, '$2y$10$AbrF3xXb2z72A/tW02qUoO5FGKDnflgKNvtjVsvihaVI5uyf7Ek5e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:35', '2023-10-12 17:24:35', NULL),
(172, 'Ms. Rosette  Atim', 1, 1, '00239I0146719', 'Client', 0.00, '', '+256772839941', '', 'Female', '1974-12-29', '', 'Ugandan', '', '', 'kireka ward', 'Kira town council', '', '', '1970-01-01', 'Kiwalabye Yosia', '', '+256772083050', '', '', '', NULL, NULL, NULL, '$2y$10$.E8Ht.kwPMkBAfmc6y0hiOgtaL2hxX8TRrTfAzumknWHU4aTamujK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(173, 'Ms. Rose  Namugaya', 1, 1, '00239I0146720', 'Client', 0.00, '', '+256751743321', '', 'Female', '1975-08-31', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga Division', '', '', '1970-01-01', 'Ssemakula Godfrey', '', '+256703949765', '', '', '', NULL, NULL, NULL, '$2y$10$tprOYHt/ABAWsAHjBFhaeuDWswQ.looNYEL/tYWowsloQ0znYWvq.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(174, 'Ms. Getrude  Ndagire', 1, 1, '00239I0146721', 'Client', 0.00, '', '+256756665688', '', 'Female', '2020-02-10', '', 'Ugandan', '', '', 'Rubaga', 'Uganda', '', '', '1970-01-01', 'Kinobe Moses', '', '+256752856523', '', '', '', NULL, NULL, NULL, '$2y$10$UaR0kbSjMdGBjn.QXz8nB.hyZ7rN2j3hH2xh/SAZB.Qqgl3.PQ.4u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(175, 'Ms. Prossy  Nabunnya', 1, 1, '00239I0146722', 'Client', 0.00, '', '+256756889188', '', 'Female', '2020-02-10', '', 'Ugandan', '', '', 'Kasubi', 'Uganda', '', '', '1970-01-01', 'mawanda Edward', '', '+256705129333', '', '', '', NULL, NULL, NULL, '$2y$10$LqiRzFlh6ZXiCcgGNao5r.jIXOiOH83iqG0788kWQe0A533fjPUMe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(176, 'Mr. Lawrence Barkeley Kamuntu', 1, 1, '00239I0146771', 'Client', 0.00, '', '+256702615541', '', 'Male', '2020-02-11', '', 'Ugandan', '', '', 'Kireka', 'Kira', '', '', '1970-01-01', 'Muhumuza Kenneth', '', '+256752660775', '', '', '', NULL, NULL, NULL, '$2y$10$svZ4A5Cb/JLCOg89FE1RTu2h0.iMmo70Un5rf9KqW4qJDL3vhTQ.i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-11', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(177, 'Ms. proscovia  Babirye', 1, 1, '00239I0146772', 'Client', 0.00, '', '+256700879102', '', 'Female', '1967-07-28', '', 'Ugandan', '', '', 'kira ward', 'kira town council', '', '', '1970-01-01', 'kwagala Hellen', '', '+256702846431', '', '', '', NULL, NULL, NULL, '$2y$10$uXY14UA72X3uhFyTg/IUyOPCOf5MYarz.o8K3213JMS/90F2IpOo.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-11', '2023-10-12 17:24:36', '2023-10-12 17:24:36', NULL),
(178, 'Ms. Rehema  Nalubowa', 1, 1, '00239I0146773', 'Client', 0.00, '', '+256705244430', '', 'Female', '1991-02-01', '', 'Ugandan', '', '', 'kirinya', 'kira town council', '', '', '1970-01-01', 'Idilo Issa', '', '+256771019700', '', '', '', NULL, NULL, NULL, '$2y$10$uU5LjVUQZ6i.Y4UwkhrIAOQoUWMvgLUcW0kEWY/wDIONdV5Py6GOS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-11', '2023-10-12 17:24:37', '2023-10-12 17:24:37', NULL),
(179, 'Ms. Namazzi sarah walugembe', 1, 1, '00239I0146774', 'Client', 0.00, '', '+256775327178', '', 'Female', '1960-01-01', '', 'Ugandan', '', '', 'kira', 'kira town council', '', '', '1970-01-01', 'Namukasa Rita', '', '+256751023534', '', '', '', NULL, NULL, NULL, '$2y$10$qhv.9/UccRvOq3bIObO7CujSrO7GbPHCBiGLjBXIqbZ2oBugfxwh2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-11', '2023-10-12 17:24:37', '2023-10-12 17:24:37', NULL),
(180, 'Mariam  Nabowa', 1, 1, '00239I0146775', 'Client', 0.00, '', '+256758379940', '', 'Female', '1982-07-17', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Nanyonjo mayimunah', '', '+256701880932', '', '', '', NULL, NULL, NULL, '$2y$10$uanRN.6IxdrIM/DMLUSew.bdmlKWGh1/0/8GTw.UezknUPPGvxXiq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:37', '2023-10-12 17:24:37', NULL),
(181, 'Rose  Tushabe', 1, 1, '00239I0146776', 'Client', 0.00, '', '+256754826887', '', 'Female', '1979-03-06', '', 'Ugandan', '', '', 'kawaala', 'Rubaga', '', '', '1970-01-01', 'Mubiru Asumani', '', '+256752609911', '', '', '', NULL, NULL, NULL, '$2y$10$Z0va0xESfL6dEd5aOCLmiuCfVFrjHKuxnjstRJqcYC9nUv3r1yfuW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:37', '2023-10-12 17:24:37', NULL),
(182, 'Norah  Namuri', 1, 1, '00239I0146777', 'Client', 0.00, '', '', '', 'Female', '1972-12-20', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'Kyomugisha zain', '', '+256775483856', '', '', '', NULL, NULL, NULL, '$2y$10$SyKJ06H4kHgWJAQXaJQMAexilEM.2mCy4O6JK/bMQ.ngdmvsFNupW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-10', '2023-10-12 17:24:38', '2023-10-12 10:36:27', NULL),
(183, 'Ms. Juliet  Nambooze', 1, 1, '00239I0146800', 'Client', 0.00, '', '+256701170303', '', 'Female', '1972-03-21', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Namubiru Shamira', '', '+256772028701', '', '', '', NULL, NULL, NULL, '$2y$10$bCPoLep08njhJH9TkEaCie5BV1neNjteRQL8k53/z7FKinu3XOYSy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-12', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(184, 'Ms. Annet Nsengamwoyo Namusisi', 1, 1, '00239I0149655', 'Client', 0.00, '', '+256705418807', '', 'Female', '1981-04-12', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Namuli Cissy', '', '+256701435244', '', '', '', NULL, NULL, NULL, '$2y$10$/ixquZVx8IEyb0i69DuNKeE4kQd.fjLj9Q94hNfxHzfr6iW0iXuPe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-13', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(185, 'Ms. Jamawa  Naigaga', 1, 1, '00239I0149656', 'Client', 0.00, '', '+256781681821', '', 'Female', '1981-11-01', '', 'Ugandan', '', '', 'Kyaliwajjala ward', 'Kira town council', '', '', '1970-01-01', 'Kagoya Oliver', '', '+256778991382', '', '', '', NULL, NULL, NULL, '$2y$10$Z7Vp7zJEkuXvl/4OmKhwwOIxG.0gJ3HPFFiA7DDffBjTNPthQvRmu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-13', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(186, 'Faridah  Nabbuye', 1, 1, '00239I0149657', 'Client', 0.00, '', '+256752414972', '', 'Female', '1982-09-01', '', 'Ugandan', '', '', 'Nakulabye', 'lubaga', '', '', '1970-01-01', 'Magoba Regean', '', '+256759894030', '', '', '', NULL, NULL, NULL, '$2y$10$aXn8gNbEzWA7eP6yGVGqJucrZRQk9QZWg2QIMRQAie4ImFJpEHzw6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-13', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(187, 'Shamirah  Nakazibwe', 1, 1, '00239I0149658', 'Client', 0.00, '', '+256753665047', '', 'Female', '1990-08-23', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Nakate Annet', '', '+256702652375', '', '', '', NULL, NULL, NULL, '$2y$10$XtXis.Au/Bbrb45CGaIoBuHnBuCh61AzoBEhltKOQ5oWI6nv6oQT.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-12', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(188, 'yokoub  kiri', 1, 1, '00239I0149680', 'Client', 0.00, '', '+256702414810', '', 'Male', '1972-01-10', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Ddumba Abdul', '', '+256757545883', '', '', '', NULL, NULL, NULL, '$2y$10$1L3vCTINu5giGcBR9mYAb.nnVtoNH5SEGGo2w6/kNO4Meuz7R1YSu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-14', '2023-10-12 17:24:38', '2023-10-12 17:24:38', NULL),
(189, 'Christine  Nambajjwe', 1, 1, '00239I0149723', 'Client', 0.00, '', '+256705296699', '', 'Female', '1968-05-03', '', 'Ugandan', '', '', 'kasubi', 'Rubaga', '', '', '1970-01-01', 'Bbosa Edward', '', '+256752841529', '', '', '', NULL, NULL, NULL, '$2y$10$SdND1MsJkRgOrNTyH.NLMuyLqDHeriWvTgOnHwu0y/jqvp.2Oirpq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-14', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(190, 'lydia  Namuddu', 1, 1, '00239I0149747', 'Client', 0.00, '', '', '', 'Female', '1997-11-19', '', 'Ugandan', '', '', 'kasubi', 'Rubaga', '', '', '1970-01-01', 'Matovu Charlse', '', '+256758276459', '', '', '', NULL, NULL, NULL, '$2y$10$nl27UwOgNJmoY.m3ro16H.gbVtkCEyx6Vvb/W6CiKg8RkT0c88dqW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-17', '2023-10-12 17:24:39', '2023-10-12 10:36:27', NULL),
(191, 'Jesca  Nanyunja', 1, 1, '00239I0149748', 'Client', 0.00, '', '+256703481877', '', 'Female', '1977-07-13', '', 'Ugandan', '', '', 'kasubi', 'Rubaga', '', '', '1970-01-01', 'Namiiro Lydia', '', '+256785161621', '', '', '', NULL, NULL, NULL, '$2y$10$omN7ZG7H3lIbMTXG9FT7SOejM/GFDRLxxi/.bL52UOlT0PMB4q8hi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-17', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(192, 'Ronald  Menya', 1, 1, '00239I0149781', 'Client', 0.00, '', '+256700462393', '', 'Male', '1981-11-25', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'kirigola Gasta', '', '+256700629035', '', '', '', NULL, NULL, NULL, '$2y$10$ka9w/E3XgiKj4Uvdoj9UIuhODusEAOrPkR/GjS32m0xxev0DKk40S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-17', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(193, 'Stella  Namazzi', 1, 1, '00239I0149782', 'Client', 0.00, '', '+256756512434', '', 'Female', '1989-11-12', '', 'Ugandan', '', '', 'nalukolongo', 'mutundwe', '', '', '1970-01-01', 'kambugu Damulira', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$.LqOgUdk8oisyLeaestUA.cZNa2m.XwZPOSqb7Jv3K6RG8XNQn4O2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-17', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(194, 'Ms. Florence  Nabunya', 1, 1, '00239I0149783', 'Client', 0.00, '', '+256701279589', '', 'Female', '1965-01-02', '', 'Ugandan', '', '', 'kira', 'kira town council', '', '', '1970-01-01', 'Mujuni Denis', '', '+256706826069', '', '', '', NULL, NULL, NULL, '$2y$10$BvwzoMpkTfoj.HpmJqQnduh8oFHLXdFtvZdknoJSJ46A5BXVxt0Ie', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-11', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(195, 'Ms. Beatrice  Tibyonza', 1, 1, '00239I0149785', 'Client', 0.00, '', '+256756128468', '', 'Female', '1971-01-01', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'kagoya sarah', '', '+256706238321', '', '', '', NULL, NULL, NULL, '$2y$10$GcZND/uF4.Szwh/LYcBjPO4ruQa4K54FnrnvWUxtWbdr3ZAZxPJ32', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-18', '2023-10-12 17:24:39', '2023-10-12 17:24:39', NULL),
(196, 'slyvia Naava Nakyazze', 1, 1, '00239I0149818', 'Client', 0.00, '', '+256757826579', '', 'Female', '1979-10-12', '', 'Ugandan', '', '', 'kasubi', 'kasubi', '', '', '1970-01-01', 'kaaya Florence', '', '+256701023180', '', '', '', NULL, NULL, NULL, '$2y$10$CYVPuIzMu263SR3t4OHf9.8J3XtuTl/Yz2N6NJC1z9Rw79uMJA7QK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-18', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(197, 'corina  mbabazi', 1, 1, '00239I0149844', 'Client', 0.00, '', '+256754946237', '', 'Female', '1972-12-05', '', 'Ugandan', '', '', 'nalukolongo', 'nalukolongo', '', '', '1970-01-01', 'mbabazi  monica', '', '+256782424943', '', '', '', NULL, NULL, NULL, '$2y$10$YwgMWcDk8Sury4dCD4Ps1ujlz3vuxbDiW1/zAOoQ7duVU801oz6ou', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-19', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(198, 'Mr. john baptist  Kayemba', 1, 1, '00239I0149867', 'Client', 0.00, '', '+256751635708', '', 'Male', '1975-01-05', '', 'Ugandan', '', '', 'kyaliwajjala ward', 'kira town council', '', '', '1970-01-01', 'musisi kigongo Edward', '', '+256772343874', '', '', '', NULL, NULL, NULL, '$2y$10$v4Ad6J1wuS8tU31Ng6.OWuc7Wn4rRvNJOd7Hlj3EeQSW6JG2SUzU6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-21', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(199, 'Ms. Shamim  Nanfuka', 1, 1, '00239I0149868', 'Client', 0.00, '', '+256703972908', '', 'Female', '1982-03-06', '', 'Ugandan', '', '', 'kyaliwajjala ward', 'kira town council', '', '', '1970-01-01', 'Namyenya Manvuwa madinah', '', '+256703404049', '', '', '', NULL, NULL, NULL, '$2y$10$uGquGSC77qjlgU4NqzBUvuAo7c0N6aAibqb7lHf1cOWbv19Gpi0jG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-21', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(200, 'Thomas Katelega Mukisa', 1, 1, '00239I0149869', 'Client', 0.00, '', '+256752243843', '', 'Male', '1994-03-09', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'kusiima annet', '', '+256752726484', '', '', '', NULL, NULL, NULL, '$2y$10$6MgBtbuJrV0XTjF5VPS1Ruh578HgUQvDys2/gZBl4aYsTAn26Z7jq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-21', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(201, 'Mr. Davis  Akankwasa', 1, 1, '00239I0149892', 'Client', 0.00, '', '+256773552220', '', 'Male', '1988-03-21', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Kamuntu lawrence', '', '+256702615541', '', '', '', NULL, NULL, NULL, '$2y$10$puHgRQlgHg3yla0ja7XPu.UOXKLKruDhQA9tcR7xNCFMdWLMW1ZNq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-22', '2023-10-12 17:24:40', '2023-10-12 17:24:40', NULL),
(202, 'Ms. Cissy  Nawagi', 1, 1, '00239I0149893', 'Client', 0.00, '', '+256783399131', '', 'Female', '1969-12-25', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Namiiro lukia', '', '+256754388543', '', '', '', NULL, NULL, NULL, '$2y$10$aN2Jnqf8YDALVQ46XhDUMOgj5eMG2p94B2LHY9JPhM1cawU0mOq8u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-22', '2023-10-12 17:24:41', '2023-10-12 17:24:41', NULL),
(203, 'Joan  Nakayiwa', 1, 1, '00239I0149894', 'Client', 0.00, '', '+256751647464', '', 'Female', '1992-11-21', '', 'Ugandan', '', '', 'Nalukolongo', 'nalukolongo', '', '', '1970-01-01', 'kyomugisha lydia', '', '+256753638404', '', '', '', NULL, NULL, NULL, '$2y$10$O3jYED1wDZgCtq6875QPbub3jNbOQiqaAnOfuqe5ESzzROV.NWf.a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-21', '2023-10-12 17:24:41', '2023-10-12 17:24:41', NULL),
(204, 'yeko  December', 1, 1, '00239I0149895', 'Client', 0.00, '', '+256782243217', '', 'Male', '1974-12-23', '', 'Ugandan', '', '', 'Nalukolongo', 'Nalukolongo', '', '', '1970-01-01', 'kyomugisha lydia', '', '+256753638404', '', '', '', NULL, NULL, NULL, '$2y$10$fAR8ojcIC1yYMUWkP6UZJOaYqECrAdlSCNolMTzVfU3M3TIVNu2TO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-21', '2023-10-12 17:24:41', '2023-10-12 17:24:41', NULL),
(205, 'Ms. suzan  Namatovu', 1, 1, '00239I0149913', 'Client', 0.00, '', '+256703469895', '', 'Female', '1982-10-22', '', 'Ugandan', '', '', 'kirinya ward', 'kira town council', '', '', '1970-01-01', 'Mwebe IVAN', '', '+256752660775', '', '', '', NULL, NULL, NULL, '$2y$10$IIOxqJzRwGIi92ge2GlFA.r/8bO6KEMJ/VMb20DAm.MKUlIARrT8m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-24', '2023-10-12 17:24:41', '2023-10-12 17:24:41', NULL),
(206, 'Ms. kellen  Abaho', 1, 1, '00239I0149914', 'Client', 0.00, '', '+256780899733', '', 'Female', '1983-08-10', '', 'Ugandan', '', '', 'banda', 'nakawa', '', '', '1970-01-01', 'Asasira Gorret', '', '+256758971860', '', '', '', NULL, NULL, NULL, '$2y$10$S1ZtZIxnlk.lUR/mco2tVeKDncsfmSgH217IyWKnIZm5UxcHyOMCG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-24', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(207, 'John  Ssegirinya', 1, 1, '00239I0149959', 'Client', 0.00, '', '+256756662020', '', 'Male', '1975-04-19', '', 'Ugandan', '', '', 'Nasambya', 'Makindye', '', '', '1970-01-01', 'kiwewa Isma', '', '+256705234425', '', '', '', NULL, NULL, NULL, '$2y$10$1gCi0Hs39ZAmFgwl/Nu6buKyuFk81Yx9750a5FXgjhxa1WIUEhhNu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-24', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(208, 'Mr. Anthony kyeyune Ssozi', 1, 1, '00239I0149964', 'Client', 0.00, '', '+256705950522', '', 'Male', '1985-07-23', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Busulwa cranemar', '', '+256701691284', '', '', '', NULL, NULL, NULL, '$2y$10$xIH8rIEYU2yJVS7zzQXlr.d/9naMQeQMB8RgishzXWKjokxgkchIi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-25', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(209, 'Prossy  Nagawa', 1, 1, '00239I0150019', 'Client', 0.00, '', '+256753136362', '', 'Female', '1984-05-03', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Aryomusi Ismail', '', '+256759927314', '', '', '', NULL, NULL, NULL, '$2y$10$jo/k1Kwrdy7o7rgG0Lq/F.0xam9.rkY1i.K6O1kdUbDcUQS9lqQyK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-26', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(210, 'Ms. Annitah Bernadatte Mumbere', 1, 1, '00239I0150020', 'Client', 0.00, '', '+256750658733', '', 'Female', '1990-12-15', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Namyenya Manvuwa Madiina', '', '+256703404049', '', '', '', NULL, NULL, NULL, '$2y$10$y7ctxAzUW0CcZt8JHxIkRuv9D5hgecI1.fq7xeEtbd12hvjeEMZwq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-26', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(211, 'Ms. Rebecca  Nakalinda', 1, 1, '00239I0150061', 'Client', 0.00, '', '+256701010317', '', 'Female', '1991-04-12', '', 'Ugandan', '', '', 'kirinya', 'kira town council', '', '', '1970-01-01', 'Namatovu suzan', '', '+256703469895', '', '', '', NULL, NULL, NULL, '$2y$10$Enu1t6ZSRMyg85eWbWFSBeeYUV4dxotKmMKsQYG2jHn1N6Et6X62C', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-27', '2023-10-12 17:24:42', '2023-10-12 17:24:42', NULL),
(212, 'Florence  Nanungi', 1, 1, '00239I0150062', 'Client', 0.00, '', '+256703749978', '', 'Female', '1984-07-27', '', 'Ugandan', '', '', 'wakiso', 'wakiso', '', '', '1970-01-01', 'Namujju Ruth', '', '+256704573535', '', '', '', NULL, NULL, NULL, '$2y$10$3KmjlnHs14WuxgGWMWjUUugl7TTMrQd/uK19MQgLpecQ7Y9Ez/zmm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-27', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(213, 'Mr. Jonathan  Baguma', 1, 1, '00239I0150063', 'Client', 0.00, '', '+256776752592', '', 'Male', '1972-03-15', '', 'Ugandan', '', '', 'mbuya', 'nakawa', '', '', '1970-01-01', 'Oundo Moses', '', '+256756911064', '', '', '', NULL, NULL, NULL, '$2y$10$USFqYllhqpEtQfo9VMngEeqb4T2mhGCTjHUOboH1XOkGvhaZjuwcK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-27', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(214, 'Lydia  Namiiro', 1, 1, '00239I0150106', 'Client', 0.00, '', '+256752726484', '', 'Female', '1987-05-02', '', 'Ugandan', '', '', 'kasubi', 'kasubi', '', '', '1970-01-01', 'kusiima Annet', '', '+256701032090', '', '', '', NULL, NULL, NULL, '$2y$10$M9QwyzcsjROTwAy0f5N46ekilCGFunHmE8eaP3CBd96tO6UQqy3S.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-02-27', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(215, 'Lydia Feibe Namugosa', 1, 1, '00239I0150126', 'Client', 0.00, '', '+256705381048', '', 'Female', '1984-06-08', '', 'Ugandan', '', '', 'kasubi', 'Rubaga', '', '', '1970-01-01', 'Natumbwe Gozanga', '', '+256750520683', '', '', '', NULL, NULL, NULL, '$2y$10$9HXIgYk1MitJr5J39Q4kQuIfQiZsb8gFN05uQlcpAXo2Oaqtdu85e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-02', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(216, 'Ms. Annet  Nakiito', 1, 1, '00239I0150177', 'Client', 0.00, '', '+256773573283', '', 'Female', '1996-10-10', '', 'Ugandan', '', '', 'kavule ward', 'luweero town council', '', '', '1970-01-01', 'Nangobi Draina', '', '+256770577377', '', '', '', NULL, NULL, NULL, '$2y$10$Ky0s2GrPsLcIWZmJl.IfDO3lPmxK7eC/432SwvkCoXrEERc7P.bkK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-03', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(217, 'Magret  Tumusiime', 1, 1, '00239I0150178', 'Client', 0.00, '', '+256784339586', '', 'Female', '1970-08-28', '', 'Ugandan', '', '', 'Ganda', 'Ganda', '', '', '1970-01-01', 'Akatukunda Aminah', '', '+256701332871', '', '', '', NULL, NULL, NULL, '$2y$10$t2pYjj2xXXH.lc4acBVuhuiWe6eFBD3rRZU.G/gL4IjGgRTtbexQO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-03', '2023-10-12 17:24:43', '2023-10-12 17:24:43', NULL),
(218, 'Justine  Mbabazi', 1, 1, '00239I0150179', 'Client', 0.00, '', '+256701771132', '', 'Female', '1991-11-23', '', 'Ugandan', '', '', 'nakulabye', 'Rubanga', '', '', '1970-01-01', 'Nakayijja sarah', '', '+256706488433', '', '', '', NULL, NULL, NULL, '$2y$10$2S0Q8oABVADZHgqdVP.omeS9lIUmtoK/7SBREM5i2oW3q.TtaRXGq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-03', '2023-10-12 17:24:44', '2023-10-12 17:24:44', NULL),
(219, 'Ms. Morren Nanteza Nalongo', 1, 1, '00239I0150237', 'Client', 0.00, '', '+256754844299', '', 'Female', '1983-11-27', '', 'Ugandan', '', '', 'Misindye ward', 'Goma Division', '', '', '1970-01-01', 'Nambuya Hellen Magezi', '', '+256701962611', '', '', '', NULL, NULL, NULL, '$2y$10$KtD5kGUYR50bulogjEhRt.gWwItBiGmXo45hLbaCoiA4c09Ksk4Qa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-04', '2023-10-12 17:24:44', '2023-10-12 17:24:44', NULL),
(220, 'Roy  Namukwaya', 1, 1, '00239I0150297', 'Client', 0.00, '', '+256703054908', '', 'Female', '1974-12-10', '', 'Ugandan', '', '', 'kabusu', 'kabusu', '', '', '1970-01-01', 'kambungu Ddamulira', '', '+256752958520', '', '', '', NULL, NULL, NULL, '$2y$10$GJ5RVOtGjRfMJaR/RmZqrugd2jeDHnbvqInjsCZN7XMFvqLWwuoe.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-05', '2023-10-12 17:24:44', '2023-10-12 17:24:44', NULL),
(221, 'Irene  Namayanja', 1, 1, '00239I0150298', 'Client', 0.00, '', '+256702364284', '', 'Female', '1979-07-30', '', 'Ugandan', '', '', 'nalukolongo', 'mutumwe', '', '', '1970-01-01', 'Najjuma Rose', '', '+256702364284', '', '', '', NULL, NULL, NULL, '$2y$10$WpRr/yCFMJIokIBRWefsDeZyktqUJ1B.gq0LeIyzx7zkkof3PAB7e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-05', '2023-10-12 17:24:44', '2023-10-12 17:24:44', NULL),
(222, 'Rose  Najjuma', 1, 1, '00239I0150299', 'Client', 0.00, '', '+256702364284', '', 'Female', '1979-07-20', '', 'Ugandan', '', '', 'nalukolongo', 'mutumwe', '', '', '1970-01-01', 'Nakabungo Betty', '', '+256756418927', '', '', '', NULL, NULL, NULL, '$2y$10$gFK9k5mtn098xVFNUerKze567Os/t39MnPeuS7QFrKCb.H0K2TZhS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-05', '2023-10-12 17:24:45', '2023-10-12 17:24:45', NULL),
(223, 'Teddy  Nakkazi', 1, 1, '00239I0150301', 'Client', 0.00, '', '+256776464808', '', 'Female', '1963-09-11', '', 'Ugandan', '', '', 'mutumwe', 'mutumwe', '', '', '1970-01-01', 'Nassali sharon', '', '+256708157448', '', '', '', NULL, NULL, NULL, '$2y$10$E1IOLriHyr9GaRBQpxsxwOmGdqPqiJWkB3n1euonBUUobxxwpVj82', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-05', '2023-10-12 17:24:45', '2023-10-12 17:24:45', NULL),
(224, 'Mr. Johnpaul Pendence Kibirige', 1, 1, '00239I0150415', 'Client', 0.00, '', '+256773062753', '', 'Male', '1990-09-10', '', 'Ugandan', '', '', 'Bweyogerere', 'kyadondo', '', '', '1970-01-01', 'Nanteza Nalongo Morren', '', '+256703554030', '', '', '', NULL, NULL, NULL, '$2y$10$21HtmmgTZZY9RmC9Qxsd/Ocy4hvQFOv0kcCcCb8ZjecdDh326eqje', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-09', '2023-10-12 17:24:45', '2023-10-12 17:24:45', NULL),
(225, 'TEST  TEST', 1, 1, '00239I0150467', 'Client', 0.00, '', '+256756111111', '', 'Male', '2020-01-01', '', 'Ugandan', '', '', 'TEST', 'TEST', '', '', '1970-01-01', 'TEST', '', '+256756111111', '', '', '', NULL, NULL, NULL, '$2y$10$jm2I6oaQErUMmLKmCPK2RuskMRhCoRCwK6ZxWtBFvwwp94BFZvYsu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-10', '2023-10-12 17:24:45', '2023-10-12 17:24:45', NULL),
(226, 'Ms. Abigail Davis Lola', 1, 1, '00239I0150538', 'Client', 0.00, '', '+256772744736', '', 'Male', '1984-05-02', '', 'Ugandan', '', '', 'Kampala District', 'Village 19', '', '', '1970-01-01', 'Abigail Hola Davis', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$dbiIxDt454AJFWHnDNr2EucebJy7OhIawEj7SZfRERROsGY.QE/Uy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-11', '2023-10-12 17:24:45', '2023-10-12 10:36:58', NULL),
(227, 'Naome  Tukundane', 1, 1, '00239I0150572', 'Client', 0.00, '', '+256754013887', '', 'Female', '1982-09-05', '', 'Ugandan', '', '', 'kasubi', 'kasubi', '', '', '1970-01-01', 'kitandwe shafik', '', '+256753180293', '', '', '', NULL, NULL, NULL, '$2y$10$04GUmKAuNR86957Pei8wBuzut4jJJyG6aaerOKukZWWrirU9N4dAe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-13', '2023-10-12 17:24:45', '2023-10-12 17:24:45', NULL),
(228, 'Getrude Nalugwa Babirye', 1, 1, '00239I0150573', 'Client', 0.00, '', '+256752567177', '', 'Female', '1963-10-23', '', 'Ugandan', '', '', 'seeta', 'seeta', '', '', '1970-01-01', 'Nantongo Justine', '', '+256757295739', '', '', '', NULL, NULL, NULL, '$2y$10$rqgsCmhBZT9lqhok50QFv.yN2foxYyIEaznjtWgyZjh2huC.9Mq/q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-13', '2023-10-12 17:24:46', '2023-10-12 17:24:46', NULL),
(229, 'Justus Kojax Koojo', 1, 1, '00239I0150597', 'Client', 0.00, '', '+256772181928', '', 'Male', '1988-11-10', '', 'Ugandan', '', '', 'Bugolobi', 'Bugolobi', '', '', '1970-01-01', 'Jacklyne Brenda', '', '+256782823737', '', '', '', NULL, NULL, NULL, '$2y$10$I5Zn0dkk.W0PryZMzPPgVe71GCUzTznCe0h.EytuwFs1f6Y/Q1jtC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-13', '2023-10-12 17:24:46', '2023-10-12 17:24:46', NULL);
INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(230, 'Mr. Jowasi  Tugaineyo', 1, 1, '00239I0150609', 'Client', 0.00, '', '+256753270085', '', 'Male', '1978-01-01', '', 'Ugandan', '', '', 'Kyebando', 'Wakiso', '', '', '1970-01-01', 'Agaba Merina', '', '+256752847670', '', '', '', NULL, NULL, NULL, '$2y$10$oPTggewIRnbWLhRFiMobOuIAvW1J9A476qljeMu8tNCWPE13flehO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-14', '2023-10-12 17:24:46', '2023-10-12 17:24:46', NULL),
(231, 'Mrs. Beatrice  Kisakye', 1, 1, '00239I0150611', 'Client', 0.00, '', '+256781485971', '', 'Female', '1983-07-02', '', 'Ugandan', '', '', 'kyaliwajjala', 'kira town council', '', '', '1970-01-01', 'Nanfuka Shamim', '', '+256703972908', '', '', '', NULL, NULL, NULL, '$2y$10$DpRG.dH1cRdQY4x4fS3CPuCfYSXrRAsBb6axYKyjMHmtKl5N.Bk9G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-14', '2023-10-12 17:24:47', '2023-10-12 17:24:47', NULL),
(232, 'lawrence  Sserunjogi', 1, 1, '00239I0151329', 'Client', 0.00, '', '+256752381052', '', 'Male', '1972-12-27', '', 'Ugandan', '', '', 'nakulabye', 'Rubaga', '', '', '1970-01-01', 'Monday Gad', '', '+256759353552', '', '', '', NULL, NULL, NULL, '$2y$10$bXQvYJiZh.kHaDTp7ygMVOKBc5KVx1I731N5vFT2QwnKLCh8jF2v.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-17', '2023-10-12 17:24:47', '2023-10-12 17:24:47', NULL),
(233, 'Irene  Namono', 1, 1, '00239I0151330', 'Client', 0.00, '', '+256773890194', '', 'Female', '1986-11-21', '', 'Ugandan', '', '', 'Banda', 'Banda', '', '', '1970-01-01', 'Namubiru shamira', '', '+256755419187', '', '', '', NULL, NULL, NULL, '$2y$10$d4AO7ABnNt4PLAw6ajrLC.LYwFVY54mjW2B74v25Ok0VcCG.6Lq5G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-18', '2023-10-12 17:24:47', '2023-10-12 17:24:47', NULL),
(234, 'Hanifah Jamila Nagawa', 1, 1, '00239I0151331', 'Client', 0.00, '', '+256705438484', '', 'Female', '1993-05-13', '', 'Ugandan', '', '', 'bweyogerere', 'Bweyogerere', '', '', '1970-01-01', 'Namagembe Rose', '', '+256754458999', '', '', '', NULL, NULL, NULL, '$2y$10$KI8SKMiPYk8gTLMc4Wi0N.GLJStIvAWeS.NEGP0ZabkIiYNHN264S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-18', '2023-10-12 17:24:48', '2023-10-12 17:24:48', NULL),
(235, 'Mrs. SSEBULIBA  Harriet', 1, 1, '00239I0151632', 'Client', 0.00, '', '+256704905515', '', 'Female', '1960-09-12', '', 'Ugandan', '', '', 'najjera', 'nakawa', '', '', '1970-01-01', 'Sajjabi Oscar', '', '+256772464946', '', '', '', NULL, NULL, NULL, '$2y$10$5iMUWdrslbnTZfGmw7HIS...RqFGnkH8j.9hWNMLzS6IS9DUAwT7K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-03-01', '2023-10-12 17:24:48', '2023-10-12 17:24:48', NULL),
(236, 'Mr. Rogers  Taremwa', 1, 1, '00239I0159582', 'Client', 0.00, '', '', '', 'Male', '1980-02-26', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Kabahumuza Agnes', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$I1mK5FSIEZslATuJqrDrJeSOTf.Jj7WLM6NmD0zs7Mxis0heU0QS2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-05-19', '2023-10-12 17:24:48', '2023-10-12 10:36:58', NULL),
(237, 'Mrs. Justine  Aliba', 1, 1, '00239I0176757', 'Client', 0.00, '', '+256702093253', '', 'Female', '1979-01-01', '', 'Ugandan', '', '', 'Kampala', 'Kampala', '', '', '1970-01-01', 'Joseph Lukula', '', '+256702093253', '', '', '', NULL, NULL, NULL, '$2y$10$504F3yFc/sQKP91VFj3dtu8J5acA7gcJ7cNdJhBFiwSTtZtXksIf6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-05-11', '2023-10-12 17:24:49', '2023-10-12 17:24:49', NULL),
(238, 'Suluman  Keeya', 1, 1, '00239I0176829', 'Client', 0.00, '', '+256705045074', '', 'Male', '1979-04-04', '', 'Ugandan', '', '', 'Bwaise', 'Bwaise', '', '', '1970-01-01', 'Sebambulidde Robert', '', '+256772685791', '', '', '', NULL, NULL, NULL, '$2y$10$6SPEz9VmEH0TQ30VIc95Z.amf.abrsVv8eLvmZql3zse6UVrDa37K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-06-05', '2023-10-12 17:24:49', '2023-10-12 17:24:49', NULL),
(239, 'Mr. Joseph  Lugemwa', 1, 1, '00239I0176831', 'Client', 0.00, '', '+256702093253', '', 'Male', '1986-05-05', '', 'Ugandan', '', '', 'Naalya', 'Naalya', '', '', '1970-01-01', 'Joseph Lukula', '', '+256702093253', '', '', '', NULL, NULL, NULL, '$2y$10$TeGSXH4sd5hyauTn59JHAu/V8UJDWw/PrLxVAu.HHHew2Xh4yzWYa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-06-05', '2023-10-12 17:24:49', '2023-10-12 17:24:49', NULL),
(240, 'Joselyn  Kalembe', 1, 1, '00239I0180522', 'Client', 0.00, '', '+256782865359', '', 'Female', '1989-10-01', '', 'Ugandan', '', '', 'Ntinda', 'Kampala', '', '', '1970-01-01', 'Lukula Joseph', '', '+256702093253', '', '', '', NULL, NULL, NULL, '$2y$10$Xzb5rbCHI4mUjGiFnspMVevzLHo9K1JicRw4FizpvD9s02fHE4LAm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-07-01', '2023-10-12 17:24:49', '2023-10-12 17:24:49', NULL),
(241, 'Sarah  Nalubowa', 1, 1, '00239I0183460', 'Client', 0.00, '', '+256703285129', '', 'Female', '1975-05-02', '', 'Ugandan', '', '', 'Kikoni', 'Lubaga', '', '', '1970-01-01', 'Mayinja amir', '', '+256752548602', '', '', '', NULL, NULL, NULL, '$2y$10$sL.z4KLJqZZIgsxbjFeWR.3zG98/tlMDGg3sjjvMsoF9kFvT82ACK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-07-14', '2023-10-12 17:24:50', '2023-10-12 17:24:50', NULL),
(242, 'Mr. Jackson  Nuwagaba', 1, 1, '00239I0183937', 'Client', 0.00, '', '+256771565363', '', 'Male', '2020-07-24', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kamuntu Lawrence', '', '+256702615541', '', '', '', NULL, NULL, NULL, '$2y$10$txgI2lrmjxLS42sUVhJoleewziyDV0tz0A6LP.VArKEA8ZRJmesRm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-07-24', '2023-10-12 17:24:50', '2023-10-12 17:24:50', NULL),
(243, 'Mr. Herny  Nyanja', 1, 1, '00239I0184970', 'Client', 0.00, '', '+256772259510', '', 'Male', '1987-07-04', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakyesswe Juliet', '', '+256776636480', '', '', '', NULL, NULL, NULL, '$2y$10$yW8PkHDFHJp9ncASbE1uXuYQOp5n6lzWKEboXOZaVxaCBS5OCFbJi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-08-03', '2023-10-12 17:24:50', '2023-10-12 17:24:50', NULL),
(244, 'Mrs. Christine  Nakasumba', 1, 1, '00239I0185139', 'Client', 0.00, '', '+256753176533', '', 'Female', '2020-08-06', '', 'Ugandan', '', '', 'Nakulabye', 'Lubaga', '', '', '1970-01-01', 'Basemera Harriet', '', '+256754417701', '', '', '', NULL, NULL, NULL, '$2y$10$P8.Ot3zO05gN9QGhHD3egebzvgdBil6UJ1aweCv77TOHGxeobL3GW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-08-06', '2023-10-12 17:24:50', '2023-10-12 17:24:50', NULL),
(245, 'Ms. Prossy  Nanfuka', 1, 1, '00239I0185287', 'Client', 0.00, '', '+256759668425', '', 'Female', '2020-08-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakabira Robina', '', '+256781844245', '', '', '', NULL, NULL, NULL, '$2y$10$mxRO17H/3XnjPtTFfw0SD.7zeloKSY7Q.ngyiykUAKnpf9TlbGM5i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-08-07', '2023-10-12 17:24:50', '2023-10-12 17:24:50', NULL),
(246, 'Ms. Dorothy  Asiimwe', 1, 1, '00239I0187601', 'Client', 0.00, '', '', '', 'Female', '1988-01-04', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Joseph Lukula', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$uw/tbwkIivJhLyJY34zTiu5rrjlb7WyP/VbO5.Ux2D806wDuNwUI2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-08-19', '2023-10-12 17:24:50', '2023-10-12 10:36:58', NULL),
(247, 'Mr. MICHEAL RINEX UNEGIU', 1, 1, '00239I0207829', 'Client', 0.00, '', '+256757065485', '', 'Male', '1986-08-23', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'HENRY NYANJA', '', '+256772259510', '', '', '', NULL, NULL, NULL, '$2y$10$4cITrEasr27RtjDEhQO25eixY0kzilNPGsC83ES0PyyoGzdO75eUm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-01', '2023-10-12 17:24:51', '2023-10-12 17:24:51', NULL),
(248, 'Mrs. margret  Nayiga', 1, 1, '00239I0227449', 'Client', 0.00, '', '+256783118579', '', 'Female', '1985-04-03', '', 'Ugandan', '', '', 'Rubaga', 'kabusu', '', '', '1970-01-01', 'Namukwaya Roy', '', '+256703054908', '', '', '', NULL, NULL, NULL, '$2y$10$4Ddndxm.lUit5QVKJycYwuJr/RB90WH.Zu/lxLtibPfC9L6If7II6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-11', '2023-10-12 17:24:51', '2023-10-12 17:24:51', NULL),
(249, 'Ms. Oliver  Tusiime', 1, 1, '00239I0227995', 'Client', 0.00, '', '+256705474336', '', 'Female', '1989-12-22', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Babirye proscovia', '', '+256700879102', '', '', '', NULL, NULL, NULL, '$2y$10$3cft3H6Ls3c.9IToQ4VBzuKxZ4krmwWCLbpO/jzY4rwM6lk44ozdW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-14', '2023-10-12 17:24:51', '2023-10-12 17:24:51', NULL),
(250, 'Ms. Suzan  Nayinza', 1, 1, '00239I0247953', 'Client', 0.00, '', '+256758105273', '', 'Female', '1990-12-25', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namagembe Rose', '', '+256754458999', '', '', '', NULL, NULL, NULL, '$2y$10$GYQcxbq9iYMPdexUOhTtDu2kgqFaxP9CURbikV1o.4tQ7ZgQLp0NO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-21', '2023-10-12 17:24:51', '2023-10-12 17:24:51', NULL),
(251, 'Mr. ROBERT  ISUMBA', 1, 1, '00239I0248083', 'Client', 0.00, '', '+256758496106', '', 'Male', '1976-08-21', '', 'Ugandan', '', '', '', 'NAKAWA', '', '', '1970-01-01', 'ATUHAIRE', '', '+256706393899', '', '', '', NULL, NULL, NULL, '$2y$10$Vs/Tybz/Jddby8swT927LOxLxeeQp9gUp8nzaEK49CY6hXpzbaZ2e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-25', '2023-10-12 17:24:51', '2023-10-12 17:24:51', NULL),
(252, 'Mrs. Gloria  Kemigisha', 1, 1, '00239I0249483', 'Client', 0.00, '', '+256779451336', '', 'Female', '2020-09-29', '', 'Ugandan', '', '', 'Nalukolongo', 'Mutumwe', '', '', '1970-01-01', 'Julius kweyamba', '', '+256751076249', '', '', '', NULL, NULL, NULL, '$2y$10$GA3plCIY96uUeeq0qleAF.fydGBOuovhFAPtd9.EcSfZmyV.THcV6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-29', '2023-10-12 17:24:52', '2023-10-12 17:24:52', NULL),
(253, 'Mr. Hakim  Ssewanyana', 1, 1, '00239I0249484', 'Client', 0.00, '', '+256706231853', '', 'Male', '1982-10-23', '', 'Ugandan', '', '', 'Nalukolongo', 'Mutundwe', '', '', '1970-01-01', 'Kambugu damurila', '', '+256702005643', '', '', '', NULL, NULL, NULL, '$2y$10$z0Kl4MURdNnTUNe9KKo8euj/yTIXIJI3lPgUeeCm2misPfsg1F53q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-09-29', '2023-10-12 17:24:52', '2023-10-12 17:24:52', NULL),
(254, 'Mrs. Christine  Nalwoga', 1, 1, '00239I0250018', 'Client', 0.00, '', '', '', 'Female', '1973-02-09', '', 'Ugandan', '', '', 'Kasubi', 'Wakiso', '', '', '1970-01-01', 'Peggy nazziwa', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$AJaZ67dD/C8Oro/5kecY4uAoPpQjd/.//UzN1M6eKcqkqS19y7vdq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-06', '2023-10-12 17:24:52', '2023-10-12 10:36:58', NULL),
(255, 'Mr. Ssebuuma  Kizito', 1, 1, '00239I0250046', 'Client', 0.00, '', '+256751917349', '', 'Male', '1990-08-03', '', 'Ugandan', '', '', 'Naguru2', 'Nakawa', '', '', '1970-01-01', 'Nakabiito cissy', '', '+256774424822', '', '', '', NULL, NULL, NULL, '$2y$10$Vxeew/ggBe0qSImuBfvs6e.dP3sHmvsZUG/8/qaulKTCuWCtema4a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-06', '2023-10-12 17:24:52', '2023-10-12 17:24:52', NULL),
(256, 'Mr. Ramathan  Kibirige', 1, 1, '00239I0250097', 'Client', 0.00, '', '+256703083258', '', 'Male', '2020-10-07', '', 'Ugandan', '', '', 'Nalukolongo', 'Nalukolongo', '', '', '1970-01-01', 'Kambugu damurila', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$0tEhAIcBU7d292/e0Ai1bOkcgtR3yapPmSbufN2oEsMGHUxjDyHca', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-07', '2023-10-12 17:24:52', '2023-10-12 17:24:52', NULL),
(257, 'Mr. Evalist  Yiga', 1, 1, '00239I0250098', 'Client', 0.00, '', '+256755275158', '', 'Male', '2020-10-07', '', 'Ugandan', '', '', 'Nalukolongo', 'Mutumwe', '', '', '1970-01-01', 'Najjingo haddijah', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$UFgP2yp7E2RRxnpGqmEbYeBl3Y7BnuhTjXcSJXg9EUM1fy4IZZSOi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-07', '2023-10-12 17:24:52', '2023-10-12 17:24:52', NULL),
(258, 'Mr. Farooq  Mudoma', 1, 1, '00239I0250099', 'Client', 0.00, '', '+256775700289', '', 'Male', '2020-10-07', '', 'Ugandan', '', '', 'Mutumwe', 'Mutumwe', '', '', '1970-01-01', 'Kambugu damurila', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$RJNk7AhlMhwRmTWf2VvATu2183PgU2sEtLknq3Mv9Ekz3IsQ9guym', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-07', '2023-10-12 17:24:53', '2023-10-12 17:24:53', NULL),
(259, 'Mr. Moses  Batwala', 1, 1, '00239I0250100', 'Client', 0.00, '', '+256775910646', '', 'Male', '2020-10-07', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Nambi Edith', '', '+256703835484', '', '', '', NULL, NULL, NULL, '$2y$10$SYgTEJgEwxPojmXkeL1DKeL1A6vUw8aY7M2mfQrLWo5Xn7T8HKUl2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-07', '2023-10-12 17:24:53', '2023-10-12 17:24:53', NULL),
(260, 'Ms. Harriet  Buliiro', 1, 1, '00239I0252107', 'Client', 0.00, '', '+256757938270', '', 'Female', '1980-01-01', '', 'Ugandan', '', '', 'Kira', 'Kira', '', '', '1970-01-01', 'Nakimuli Lydia', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$7h5CSP5Z.MtnNo0gmBmnwujQNte6tD4WE.xDvrJGVqnc4axeOli5.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-12', '2023-10-12 17:24:53', '2023-10-12 10:36:58', NULL),
(261, 'Mrs. Grace  Nayinga', 1, 1, '00239I0252108', 'Client', 0.00, '', '', '', 'Female', '2020-10-13', '', 'Ugandan', '', '', 'Kasubi', 'Kasubi', '', '', '1970-01-01', 'Tumwine Sylvia', '', '+256704157368', '', '', '', NULL, NULL, NULL, '$2y$10$oqHUrCSTbxypKltEKC/CsOq6YMIvnBJJL1pl811v5.c396Ysjfdm.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-13', '2023-10-12 17:24:53', '2023-10-12 10:36:27', NULL),
(262, 'Mrs. Sylvia  Tumwine', 1, 1, '00239I0252109', 'Client', 0.00, '', '+256704157368', '', 'Female', '2020-10-13', '', 'Ugandan', '', '', 'Kasubi', 'Kasubi', '', '', '1970-01-01', 'Akatukunda Amina', '', '+256701332871', '', '', '', NULL, NULL, NULL, '$2y$10$uODrdrfgk.yezQUPG4HG9.LlWDzTMQ.WWTP2M.Jbxry/F/6NgILPO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-13', '2023-10-12 17:24:53', '2023-10-12 17:24:53', NULL),
(263, 'Mr. Noah  Mukisa', 1, 1, '00239I0252131', 'Client', 0.00, '', '+256786416770', '', 'Male', '1979-06-06', '', 'Ugandan', '', '', 'mutumwe', 'mutumwe', '', '', '1970-01-01', 'kambungu Damulira', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$ib0pev8ciKy2mfuxLn/nxu.ARCAPUosvGEmofqKsIb1.UqKZMTNYW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-14', '2023-10-12 17:24:53', '2023-10-12 17:24:53', NULL),
(264, 'Mr. Kakembo  Jamiru', 1, 1, '00239I0253450', 'Client', 0.00, '', '+256788949644', '', 'Male', '1981-02-28', '', 'Ugandan', '', '', 'Ntinda', 'Nakawa', '', '', '1970-01-01', 'Kabuye Sulaiman', '', '+256704199063', '', '', '', NULL, NULL, NULL, '$2y$10$BD7bngfdOb2MN4JSJIcJAucjvKunLODGJRfcesG0AaKIBfg2tazJG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-20', '2023-10-12 17:24:54', '2023-10-12 17:24:54', NULL),
(265, 'Mrs. Oliver  Nanyonjo', 1, 1, '00239I0253480', 'Client', 0.00, '', '+256759951620', '', 'Female', '2020-10-21', '', 'Ugandan', '', '', 'Kyebando', 'Kyebando', '', '', '1970-01-01', 'Akampulira Angle', '', '+256755770172', '', '', '', NULL, NULL, NULL, '$2y$10$uW93TBND0Yb9VHKYlwtwru87.AY8t1E9OK5kcB2zY5MIJhUKvnvTu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-21', '2023-10-12 17:24:54', '2023-10-12 17:24:54', NULL),
(266, 'Mr. Wilberforce  Mutatiina', 1, 1, '00239I0256920', 'Client', 0.00, '', '+256701371033', '', 'Male', '1981-01-01', '', 'Ugandan', '', '', 'Kira', 'Kira', '', '', '1970-01-01', 'Aijuka Herbert', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$dO3ej8WXd975bzJ8c39fPe0CH.6Wlu4uzsCyBJeYT9/Lvu3fh1W6W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-28', '2023-10-12 17:24:54', '2023-10-12 10:36:58', NULL),
(267, 'Mr. Ronald  Tumuhimbise', 1, 1, '00239I0256951', 'Client', 0.00, '', '+256701444267', '', 'Male', '1994-01-12', '', 'Ugandan', '', '', 'Kanyansheko', 'Nyabuhikye', '', '', '1970-01-01', 'Aijuka Herbert', '', '+256702444267', '', '', '', NULL, NULL, NULL, '$2y$10$.hKPxeGXqASDuNKwVwXd0.q2nRpAQ9hq6OF.29MxQwxqEoE.tx1M2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-30', '2023-10-12 17:24:54', '2023-10-12 17:24:54', NULL),
(268, 'Mr. IDI  KIKOMA', 1, 1, '00239I0256952', 'Client', 0.00, '', '+256774888413', '', 'Male', '1996-10-30', '', 'Ugandan', '', '', 'Mbulamuti', 'Mbulamuti', '', '', '1970-01-01', 'Nalubowa Rehema', '', '+256705244430', '', '', '', NULL, NULL, NULL, '$2y$10$JT4nMGBVueD2ACrZ9F3OeOkHAqDSc0FXHnmHsHWaECujlxZig7Roy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-30', '2023-10-12 17:24:54', '2023-10-12 17:24:54', NULL),
(269, 'Ms. Joanita Namyalo Kayaga', 1, 1, '00239I0256953', 'Client', 0.00, '', '+256779763449', '', 'Female', '1990-09-09', '', 'Ugandan', '', '', 'Nantabulirwa Ward', 'Goma Division', '', '', '1970-01-01', 'Nantume cissy', '', '+256784178865', '', '', '', NULL, NULL, NULL, '$2y$10$QAZxgw2Z3luQH3hgM6o84ewJo0Cz2wAv.nkEWQwwCMYWgI4Z2T/jK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-10-30', '2023-10-12 17:24:55', '2023-10-12 17:24:55', NULL),
(270, 'Mr. STEVEN  MUGANZA', 1, 1, '00239I0257320', 'Client', 0.00, '', '+256702548273', '', 'Male', '1978-06-06', '', 'Ugandan', '', '', 'ntinda', 'nakawa', '', '', '1970-01-01', 'Isumba Robert', '', '+256758146106', '', '', '', NULL, NULL, NULL, '$2y$10$JRR2OindtTPQRb4V/u.PAez540E9bAW38XqFxxgwnUdQeA2O2OPea', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-04', '2023-10-12 17:24:55', '2023-10-12 17:24:55', NULL),
(271, 'Mrs. Edith  Nambi', 1, 1, '00239I0257470', 'Client', 0.00, '', '+256775910646', '', 'Female', '2020-11-06', '', 'Ugandan', '', '', 'Kasubi', 'Kasubi', '', '', '1970-01-01', 'Batwala Moses', '', '+256775910646', '', '', '', NULL, NULL, NULL, '$2y$10$7pk1K8XbwauGur6DCQbnAO89AnZkpb1P0OyuhIXwdhRzTTjfeljrm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-06', '2023-10-12 17:24:55', '2023-10-12 17:24:55', NULL),
(272, 'Mr. Eldard  Ssenkooto', 1, 1, '00239I0258201', 'Client', 0.00, '', '+256701923876', '', 'Male', '1989-05-10', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'Nazziwa Peggy', '', '+256758586995', '', '', '', NULL, NULL, NULL, '$2y$10$0ofsln1vZfQ7g1T.Khr7OOoAWVT6S58PJI7G9YWnhJcWQwKw1uHNm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-11', '2023-10-12 17:24:55', '2023-10-12 17:24:55', NULL),
(273, 'Ms. Marion Santa Kiden', 1, 1, '00239I0258247', 'Client', 0.00, '', '+256704477061', '', 'Female', '1985-03-16', '', 'Ugandan', '', '', 'Mukono', 'Buikwe', '', '', '1970-01-01', 'Aijuka Herbert', '', '+256702444267', '', '', '', NULL, NULL, NULL, '$2y$10$UPPMroxmeZWEdhexEkx0cOiUCqtSTGr0LCQQahdxzbGtRvWOlhawa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-11', '2023-10-12 17:24:55', '2023-10-12 17:24:55', NULL),
(274, 'Mr. Henery Manter Ssali', 1, 1, '00239I0258248', 'Client', 0.00, '', '+256771264015', '', 'Male', '1988-10-02', '', 'Ugandan', '', '', 'Nalukolongo', 'Nalukolongo', '', '', '1970-01-01', 'Kambugu damurila', '', '+256756543166', '', '', '', NULL, NULL, NULL, '$2y$10$x9jisHRoaT/a4LaHHv7nfupzXOHaIo/9D/QXnbHKuVkMpXJ0QRbcO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-12', '2023-10-12 17:24:56', '2023-10-12 17:24:56', NULL),
(275, 'Mrs. faridah  Namatovu', 1, 1, '00239I0258603', 'Client', 0.00, '', '+256774224904', '', 'Female', '2020-11-23', '', 'Ugandan', '', '', 'nasana', 'nasana', '', '', '1970-01-01', 'Arango jesca', '', '+256756602760', '', '', '', NULL, NULL, NULL, '$2y$10$FYRD.w0pni00tohih5q.eO69tFNo2JGFfFNQjBIt7vUsT4BM1T.k2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-23', '2023-10-12 17:24:56', '2023-10-12 17:24:56', NULL),
(276, 'Ms. Prossy  Namata', 1, 1, '00239I0258604', 'Client', 0.00, '', '+256704259937', '', 'Female', '1988-02-02', '', 'Ugandan', '', '', 'Seeta', 'Goma', '', '', '1970-01-01', 'Namagembe Rose', '', '+256754458999', '', '', '', NULL, NULL, NULL, '$2y$10$aelfyZV.6vzdVMfQNySfcOnB9fBcsfjEnmw39FykeagtJu3rIjc0u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-23', '2023-10-12 17:24:56', '2023-10-12 17:24:56', NULL),
(277, 'Ms. Claremont  Arinaitwe', 1, 1, '00239I0258896', 'Client', 0.00, '', '+256702093253', '', 'Female', '1988-04-06', '', 'Ugandan', '', '', 'Nakawa', 'Ntinda', '', '', '1970-01-01', 'Lukula Joseph', '', '+256702093253', '', '', '', NULL, NULL, NULL, '$2y$10$ijKTZdLxA2B9YWGqCNaUYuRxNP6dpcdh9ezVFet84QPr4lzLBGdda', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-11-27', '2023-10-12 17:24:57', '2023-10-12 17:24:57', NULL),
(278, 'Mr. OWOR  FRANCIS', 1, 1, '00239I0259962', 'Client', 0.00, '', '+256785899166', '', 'Male', '1986-04-09', '', 'Ugandan', '', '', 'ntinda', 'nakawa', '', '', '1970-01-01', 'CLAIRE ALINAITWE', '', '+256785899166', '', '', '', NULL, NULL, NULL, '$2y$10$R5MfZH3FyQyrfa39.sawJOtsZ9vUB72kqpevxOpnWP82wxkjSTcua', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-12-01', '2023-10-12 17:24:57', '2023-10-12 17:24:57', NULL),
(279, 'Mr. Wilberforce Musisi Mayanja', 1, 1, '00239I0260094', 'Client', 0.00, '', '+256772369280', '', 'Male', '1975-07-21', '', 'Ugandan', '', '', 'Naguru 2', 'Nakawa', '', '', '1970-01-01', 'Nambalirwa Milly', '', '+256755930174', '', '', '', NULL, NULL, NULL, '$2y$10$gqSO81sJKKPXfJ.esryC/uKJp4exciShTjEzl0U0f0nUZlQV0xnr2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-12-07', '2023-10-12 17:24:57', '2023-10-12 17:24:57', NULL),
(280, 'Ms. Marion  Kyohirwe', 1, 1, '00239I0260344', 'Client', 0.00, '', '+256774824850', '', 'Female', '1985-01-01', '', 'Ugandan', '', '', 'kirinya', 'kira', '', '', '1970-01-01', 'Nambalirwa Milly', '', '+256755930174', '', '', '', NULL, NULL, NULL, '$2y$10$pRYfWlgcYJdr43V3mPIAO.VrIqJvQTn.nGOUPL3J/AzvPxHuZv4ye', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2020-12-11', '2023-10-12 17:24:58', '2023-10-12 17:24:58', NULL),
(281, 'Mrs. sharon  zawedde', 1, 1, '00239I0263277', 'Client', 0.00, '', '+256703134071', '', 'Female', '1986-01-05', '', 'Ugandan', '', '', 'nasana', 'wakiso', '', '', '1970-01-01', 'kirigola gasta', '', '+256788660826', '', '', '', NULL, NULL, NULL, '$2y$10$Gwk7H9Lx0d9F6dpk.N5Q8OPJnirJrPHca.LoN9C5Xa9ZbVVNt.ef.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-08', '2023-10-12 17:24:58', '2023-10-12 17:24:58', NULL),
(282, 'Mr. John  Kizito', 1, 1, '00239I0263480', 'Client', 0.00, '', '+256759043016', '', 'Male', '1976-02-04', '', 'Ugandan', '', '', 'Kira', 'Kira', '', '', '1970-01-01', 'Namubiru shamira', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$xPlH1s5T8f49yiK186ngPew/TDoj/IBEIOkeweoteaq9AL5TTp73m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-18', '2023-10-12 17:24:58', '2023-10-12 10:36:58', NULL),
(283, 'Mr. ismail  wakabi', 1, 1, '00239I0263483', 'Client', 0.00, '', '+256757036088', '', 'Male', '1986-12-23', '', 'Ugandan', '', '', 'kawaala', 'kawaala', '', '', '1970-01-01', 'Nshiyimana xvio', '', '+256786262284', '', '', '', NULL, NULL, NULL, '$2y$10$N1su68Xd8ofl0wSOyveutebSkC7f.XPuwShneADqE.Nu4oQVzUs0S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-19', '2023-10-12 17:24:59', '2023-10-12 17:24:59', NULL),
(284, 'Mr. Ivan  Mandela', 1, 1, '00239I0263487', 'Client', 0.00, '', '+256752067719', '', 'Male', '1991-05-04', '', 'Ugandan', '', '', 'ntinda', 'central', '', '', '1970-01-01', 'Nanono Philitar', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$ViAWmMH5k3Ub32tS3Er8ruIutn3zNiOk6N8qATl9KOF/a2GJaT0Qm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-19', '2023-10-12 17:24:59', '2023-10-12 10:36:58', NULL),
(285, 'Ms. Magret  Namubiru', 1, 1, '00239I0263686', 'Client', 0.00, '', '+256706632462', '', 'Female', '1988-03-09', '', 'Ugandan', '', '', 'Lubaga', 'Lubaga', '', '', '1970-01-01', 'Ndagire getrude', '', '+256756665688', '', '', '', NULL, NULL, NULL, '$2y$10$vOA8YBBiubFs5d0DzVqMoORcHKOjGwN8PUAVeASafZHOds9BpSqSq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-25', '2023-10-12 17:24:59', '2023-10-12 17:24:59', NULL),
(286, 'Mrs. Dainah  mweteise', 1, 1, '00239I0263698', 'Client', 0.00, '', '+256776601667', '', 'Female', '1982-08-01', '', 'Ugandan', '', '', 'kosovo', 'lubaga', '', '', '1970-01-01', 'harriet nandaula', '', '+256757969779', '', '', '', NULL, NULL, NULL, '$2y$10$AmlRAH/Trn/cvwOtD12LgOUOs8x5P9iGBHiEqVPpAPUMcA4m.5wNG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-01-27', '2023-10-12 17:24:59', '2023-10-12 17:24:59', NULL),
(287, 'Mrs. Nantege  Sylvia', 1, 1, '00239I0263981', 'Client', 0.00, '', '+256705342964', '', 'Female', '2021-02-02', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Nanungi Florence', '', '+256706321894', '', '', '', NULL, NULL, NULL, '$2y$10$0vPCaMKkyCCDujK6KCNA3uuZicXBDTKoe.43rG88xrMFZP4TdAswu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-02', '2023-10-12 17:24:59', '2023-10-12 17:24:59', NULL),
(288, 'Mrs. Hellen  Komushana', 1, 1, '00239I0263982', 'Client', 0.00, '', '+256756110124', '', 'Female', '2021-02-02', '', 'Ugandan', '', '', 'Lubaga', 'Lubaga', '', '', '1970-01-01', 'Nanungi Florence', '', '+256706390582', '', '', '', NULL, NULL, NULL, '$2y$10$Dv7py49vgV3C0d.ZcL4s0ucaEfKatY9W9FLgN.QJZOS/kVWm/KfTW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-02', '2023-10-12 17:24:59', '2023-10-12 17:24:59', NULL),
(289, 'Mrs. immaculate  kugoza', 1, 1, '00239I0264014', 'Client', 0.00, '', '+256754750475', '', 'Female', '1975-01-20', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Atuhaire rodgers', '', '+256701305989', '', '', '', NULL, NULL, NULL, '$2y$10$enxQ2XAlhsovugylnDT6T.yt0hT9qsyj6sEmTL7ICDcCBXlO8dC5m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-03', '2023-10-12 17:25:00', '2023-10-12 17:25:00', NULL),
(290, 'Ms. Annet Mbabazi Ssetoogo', 1, 1, '00239I0264175', 'Client', 0.00, '', '+256782818898', '', 'Female', '1971-08-28', '', 'Ugandan', '', '', 'Kireka ward', 'kira town council', '', '', '1970-01-01', 'Nankumbi Robina', '', '+256772959679', '', '', '', NULL, NULL, NULL, '$2y$10$7gz41cygHO8mwZk5pfXdpe6YzH/xa0ewPGVy0XPC4KZyHgW.4nRyK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-08', '2023-10-12 17:25:00', '2023-10-12 17:25:00', NULL),
(291, 'Ms. Martha  Nalumansi', 1, 1, '00239I0264911', 'Client', 0.00, '', '+256702422463', '', 'Female', '1984-05-02', '', 'Ugandan', '', '', 'Goma', 'Goma', '', '', '1970-01-01', 'Nandyonse lukiya', '', '+256701605032', '', '', '', NULL, NULL, NULL, '$2y$10$Op1xgcVUZQ5bwel.xp6Y1unCfkziOFi7QJunnCk2pEkU1xE56Je8a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-11', '2023-10-12 17:25:00', '2023-10-12 17:25:00', NULL),
(292, 'Ms. Christine  Busingye', 1, 1, '00239I0264936', 'Client', 0.00, '', '+256706414288', '', 'Female', '1987-10-19', '', 'Ugandan', '', '', 'Kiswa', 'Nakawa', '', '', '1970-01-01', 'Lugayizi Patrick', '', '+256776956809', '', '', '', NULL, NULL, NULL, '$2y$10$F5Xn0EFmQYRrs8VOhUK7KubuOZL9YLIKl.eMjmZ4KV4PmYbprDTxm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-12', '2023-10-12 17:25:00', '2023-10-12 17:25:00', NULL),
(293, 'Mrs. Teddy  Nanyonjo', 1, 1, '00239I0264937', 'Client', 0.00, '', '+256706430134', '', 'Female', '1976-01-20', '', 'Ugandan', '', '', 'lubaga', 'kasubi', '', '', '1970-01-01', 'Namuzungu luuba', '', '+256776722448', '', '', '', NULL, NULL, NULL, '$2y$10$9US1YdmNwMAspwlxU5OUoOoubaBN8ySVYU4ANk1yuYHyB7VrEgM7G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-12', '2023-10-12 17:25:00', '2023-10-12 17:25:00', NULL),
(294, 'Mrs. Catherine  Nabachwa', 1, 1, '00239I0264938', 'Client', 0.00, '', '+256700139499', '', 'Female', '2021-02-12', '', 'Ugandan', '', '', 'Kasubi', 'Uganda', '', '', '1970-01-01', 'Nakibuuka teopista', '', '+256708352118', '', '', '', NULL, NULL, NULL, '$2y$10$FXa/EBqfcqZv8zfVL4fHKuWqMVaq2Ak9kI5MNJBhIO.fbWA6XHedS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-12', '2023-10-12 17:25:01', '2023-10-12 17:25:01', NULL),
(295, 'Mr. Alex  Agaba', 1, 1, '00239I0265017', 'Client', 0.00, '', '+256759908546', '', 'Male', '1984-07-05', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Tumusiime magret', '', '+256708352118', '', '', '', NULL, NULL, NULL, '$2y$10$AVpFbj/jZN104upR09/2..YN.JDUdStkJH21FL/aqNreJpOLEfGke', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-15', '2023-10-12 17:25:01', '2023-10-12 17:25:01', NULL),
(296, 'Mr. Julius  Tunywane', 1, 1, '00239I0265044', 'Client', 0.00, '', '+256783130334', '', 'Male', '1986-10-09', '', 'Ugandan', '', '', 'Kira', 'Nakawa', '', '', '1970-01-01', 'Kyampeire Pamela', '', '+256703464795', '', '', '', NULL, NULL, NULL, '$2y$10$2L4DIwJep6m0zoTWYegXgO8T8iQsnecFSLc5cGVcAoEaepfrz/DSS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-17', '2023-10-12 17:25:01', '2023-10-12 17:25:01', NULL),
(297, 'Mrs. Jane  Mirembe', 1, 1, '00239I0265084', 'Client', 0.00, '', '+256772352696', '', 'Female', '1980-12-13', '', 'Ugandan', '', '', 'Kyebando', 'Nasana', '', '', '1970-01-01', 'Tukahirwa assumpta', '', '+256778340121', '', '', '', NULL, NULL, NULL, '$2y$10$9zUQy5fTY3jRv1nSF1Dfl.mM.lq9BGcTXefZaxH2F8b6Yr8d/O2.i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-18', '2023-10-12 17:25:01', '2023-10-12 17:25:01', NULL),
(298, 'Mr. Muhumuza Humphery Cliff', 1, 1, '00239I0265199', 'Client', 0.00, '', '+256700139499', '', 'Male', '1979-02-19', '', 'Ugandan', '', '', 'lugala', 'mengo', '', '', '1970-01-01', 'namata barbara', '', '+256787411789', '', '', '', NULL, NULL, NULL, '$2y$10$YAQqZSsgacmVS9cul4amOu2/Ur3koC1ZHdWclI6d6h4YO/RCwZ7xe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-22', '2023-10-12 17:25:01', '2023-10-12 17:25:01', NULL),
(299, 'Mrs. Jane  Katana', 1, 1, '00239I0265217', 'Client', 0.00, '', '+256705491753', '', 'Female', '1977-09-07', '', 'Ugandan', '', '', 'Kasubi', 'Kasubi', '', '', '1970-01-01', 'Nantege Sylvia', '', '+256705342964', '', '', '', NULL, NULL, NULL, '$2y$10$cvRa2MUUXY1gL7X7OycAee6scIXP7yTMeXoekmOWBpT4U9ffDRIWq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-23', '2023-10-12 17:25:02', '2023-10-12 17:25:02', NULL),
(300, 'Ms. Zaituni  Kaitumu', 1, 1, '00239I0265244', 'Client', 0.00, '', '+256702772244', '', 'Female', '1974-08-16', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Mbabazi Annet Ssetogo', '', '+256782818898', '', '', '', NULL, NULL, NULL, '$2y$10$4IA29DSemc8ZtVAUew/VBOQqCJS6M51866LdLPjHYTSbm3Rx7Jfce', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-24', '2023-10-12 17:25:02', '2023-10-12 17:25:02', NULL),
(301, 'Mr. Sammy  Kandie', 1, 1, '00239I0265340', 'Client', 0.00, '', '+256702924515', '', 'Male', '1980-02-06', '', 'Ugandan', '', '', 'Kisasi', 'Kisasi', '', '', '1970-01-01', 'Beatrice Chumo', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$PgsgqedYimrAm70ZrVIRWOihBzRPR8EMCvk6mozF9Zk7o/RxOM7Hq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-02-27', '2023-10-12 17:25:02', '2023-10-12 10:36:58', NULL),
(302, 'Mrs. Rehema  Namubiru', 1, 1, '00239I0265478', 'Client', 0.00, '', '+256755524044', '', 'Female', '1984-12-20', '', 'Ugandan', '', '', 'Kabusu', 'Kabusu', '', '', '1970-01-01', 'Nalumansi Florence', '', '+256759859340', '', '', '', NULL, NULL, NULL, '$2y$10$24jNd2c46Lh8ucagSggpf.JEmicxOHPb1M68qbRM2Xcj4wibNJHzm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-03-03', '2023-10-12 17:25:02', '2023-10-12 17:25:02', NULL),
(303, 'Mrs. Aisha  Nvanungi', 1, 1, '00239I0265767', 'Client', 0.00, '', '+256750925027', '', 'Female', '1980-05-08', '', 'Ugandan', '', '', 'Nalukolongo', 'Nalukolongo', '', '', '1970-01-01', 'Namubiru rehema', '', '+256755524044', '', '', '', NULL, NULL, NULL, '$2y$10$ys3USJFBGO1cMx/QFIpPF.g3awCZIOGvGG93wFz/YrEMQtymWiLFy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-03-11', '2023-10-12 17:25:03', '2023-10-12 17:25:03', NULL),
(304, 'Ms. Grace  Nabiwande', 1, 1, '00239I0268117', 'Client', 0.00, '', '+256753569772', '', 'Female', '1978-05-03', '', 'Ugandan', '', '', 'Lubaga', 'Lubaga', '', '', '1970-01-01', 'Nakibuuka teopista', '', '+256754895412', '', '', '', NULL, NULL, NULL, '$2y$10$V3f.K7hu8myJWBrmtsKjku168hr43WXi7W3POunsZdqcotj3YUYQK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-03-19', '2023-10-12 17:25:03', '2023-10-12 17:25:03', NULL),
(305, 'Mr. Ahmed  Katongole', 1, 1, '00239I0301333', 'Client', 0.00, '', '+256759807020', '', 'Male', '2021-04-08', '', 'Ugandan', '', '', 'Kireka ward', 'Kira town council', '', '', '1970-01-01', 'Kizza Francis', '', '+256785770350', '', '', '', NULL, NULL, NULL, '$2y$10$oe73C0vscZl4ey3BVP13qO2G6lvUvHhMy0fcuUvi6Lqctl9MkLrRm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-04-08', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(306, 'Mr. bruno  byaruhanga', 1, 1, '00239I0351806', 'Client', 0.00, '', '+256750884692', '', 'Male', '1985-05-05', '', 'Ugandan', '', '', 'Gganda', 'nasana', '', '', '1970-01-01', 'lule seniorjohnson', '', '+256700800635', '', '', '', NULL, NULL, NULL, '$2y$10$6af3zYf8DWqcJ.KAC5NUKOM5Il3Dp73ymsysZQUiNw/FvLnFQPvqC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-19', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(307, 'Ms. Catherine  Nakku', 1, 1, '00239I0351808', 'Client', 0.00, '', '+256706913759', '', 'Female', '1988-10-10', '', 'Ugandan', '', '', 'Nantabulirwa ward', 'GOMA division', '', '', '1970-01-01', 'Namubiru Shamira', '', '+256755419187', '', '', '', NULL, NULL, NULL, '$2y$10$MdMs58FUhT3X7YbgK6GdFO.STUIF6de2EeAi.wfdK2AqTVggqbR8e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-19', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(308, 'Mr. Sseluwo  Robert', 1, 1, '00239I0351886', 'Client', 0.00, '', '+256752658836', '', 'Male', '1973-01-01', '', 'Ugandan', '', '', 'Natete', 'Rubaga', '', '', '1970-01-01', 'Nabadduka zam', '', '+256753757621', '', '', '', NULL, NULL, NULL, '$2y$10$Hntx.B0Ha4F1a1s2dEqmfeIEkDJnhV1Gfh/U..WaoVXjrF5G0j1T2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-19', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(309, 'Ms. Eva  Olishaba', 1, 1, '00239I0357310', 'Client', 0.00, '', '+256706472028', '', 'Female', '2021-11-11', '', 'Ugandan', '', '', 'Kyadondo', 'Bwegogerere ward', '', '', '1970-01-01', 'Nambalirwa Milly', '', '+256755930174', '', '', '', NULL, NULL, NULL, '$2y$10$94oGW.7MVkJbgRzib9VMeuNWUX2WXVPcaMwndaiJcBu.SAurEg4.C', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-24', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(310, 'Mrs. Sarah  Kabonesa', 1, 1, '00239I0357346', 'Client', 0.00, '', '+256754646201', '', 'Female', '1968-04-03', '', 'Ugandan', '', '', 'Kikoni', 'Kikoni', '', '', '1970-01-01', 'Nsereko Joseph', '', '+256752695153', '', '', '', NULL, NULL, NULL, '$2y$10$GbseWv7xHYBliBxr8pKM5OUbA6jkubqVrph0v6vo1aye/OiBTYHZC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-25', '2023-10-12 17:25:04', '2023-10-12 17:25:04', NULL),
(311, 'Mrs. dainah  namuli', 1, 1, '00239I0357620', 'Client', 0.00, '', '+256754552118', '', 'Female', '1975-06-18', '', 'Ugandan', '', '', 'kabusu', 'kabusu', '', '', '1970-01-01', 'kambungu Damulira', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$k7ldFrg07qI/QdygdS0AuuCChiJGplNVFVCpE2RQWsfynM/s68xfC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-05-28', '2023-10-12 17:25:05', '2023-10-12 17:25:05', NULL),
(312, 'Mr. moses  muyingo', 1, 1, '00239I0357788', 'Client', 0.00, '', '+256752843439', '', 'Male', '1988-12-24', '', 'Ugandan', '', '', 'nsumbi', 'kyebando', '', '', '1970-01-01', 'Nkeera moses', '', '+256756751440', '', '', '', NULL, NULL, NULL, '$2y$10$wBL32lR8z0d3PBjOp.PVtOqfHsX13BU8Xfru/7pTJOYo1fYYr0Du6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-06-02', '2023-10-12 17:25:05', '2023-10-12 17:25:05', NULL),
(313, 'Mrs. Namubiru Oliver Mary', 1, 1, '00239I0360732', 'Client', 0.00, '', '+256752862509', '', 'Female', '2021-07-05', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Nalwoga Christine', '', '+256752934512', '', '', '', NULL, NULL, NULL, '$2y$10$.L10ENf79XULoFOaixekdeQbJlcrVZ8zKs.rz4JZystV/YHlbvKzq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-07-05', '2023-10-12 17:25:05', '2023-10-12 17:25:05', NULL),
(314, 'Ms. Joslin  Namala', 1, 1, '00239I0361793', 'Client', 0.00, '', '+256758654915', '', 'Female', '1987-02-05', '', 'Ugandan', '', '', 'Lugujja', 'Rubaga', '', '', '1970-01-01', 'Namubiru magret', '', '+256706632462', '', '', '', NULL, NULL, NULL, '$2y$10$77itW11VF9OEEsFDQo6BuOQJJcx/UQ1nS0OnbjfC8BzrSpdWaBO.W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-08-04', '2023-10-12 17:25:05', '2023-10-12 17:25:05', NULL),
(315, 'Mr. Micheal  Olanya', 1, 1, '00239I0361844', 'Client', 0.00, '', '+256772868529', '', 'Male', '2021-08-06', '', 'Ugandan', '', '', 'kasangati', 'kira town council', '', '', '1970-01-01', 'Opio Walter', '', '+256702636264', '', '', '', NULL, NULL, NULL, '$2y$10$bMqvyzbnglqroVJefJYE1Ok4dq0saIX6TC4Z4THzQhcHVWSu1CF/u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-08-06', '2023-10-12 17:25:05', '2023-10-12 17:25:05', NULL),
(316, 'Mr. Bosco  kawuki', 1, 1, '00239I0361902', 'Client', 0.00, '', '+256754692425', '', 'Male', '1982-05-05', '', 'Ugandan', '', '', 'nasana', 'central', '', '', '1970-01-01', 'nagawa prossy', '', '+256755635963', '', '', '', NULL, NULL, NULL, '$2y$10$jQkemqo6S4THD6fRAkI2xen.u3ESkPAM4l7Geszn/L0AHggci8MZW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-08-09', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(317, 'Mrs. Hanifah  Basemera', 1, 1, '00239I0362565', 'Client', 0.00, '', '+256759375933', '', 'Female', '1996-01-12', '', 'Ugandan', '', '', 'kasubi', 'Rubanga', '', '', '1970-01-01', 'Nalwoga christine', '', '+256703637356', '', '', '', NULL, NULL, NULL, '$2y$10$hfYHcCFVZQU7uF2CDWZlvOKHjXP4ahXX3yyx8RaPXCp0Rkt4uQloW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-08-20', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(318, 'Ms. Edith  Tugabairwe', 1, 1, '00239I0363161', 'Client', 0.00, '', '+256754271191', '', 'Female', '1986-08-15', '', 'Ugandan', '', '', 'Ggulu Ward', 'mukono DIVISION', '', '', '1970-01-01', 'Olishaba Eva', '', '+256706472028', '', '', '', NULL, NULL, NULL, '$2y$10$4f1QBrWD5nITbz45EscDkeCDPdkQcPosxRHOMcbKrKCnSKKwmBdeu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-09-06', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(319, 'Mr. Noah  Konde', 1, 1, '00239I0363214', 'Client', 0.00, '', '+256759577027', '', 'Male', '1996-06-02', '', 'Ugandan', '', '', 'Kyebando', 'Kyebando', '', '', '1970-01-01', 'Kawuchi Bosco', '', '+256784284546', '', '', '', NULL, NULL, NULL, '$2y$10$2zZZKRXFrdq48v4VVF417eYJbAySMdCy8SwpuxT1o17C4igbu5EQS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-09-06', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(320, 'Mr. Peter junior  Kasozi', 1, 1, '00239I0363216', 'Client', 0.00, '', '+256757720761', '', 'Male', '1991-10-09', '', 'Ugandan', '', '', 'Namumira Anthony ward', 'Mukono division', '', '', '1970-01-01', 'Isingoma Wilson', '', '+256700153403', '', '', '', NULL, NULL, NULL, '$2y$10$S6v8wU/23pZBym7ehxWk0.OIhklya1m10MrqyIchec6to3eeNuYOa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-09-06', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(321, 'Mrs. Brenda  Nsangi', 1, 1, '00239I0364984', 'Client', 0.00, '', '+256759751200', '', 'Female', '1983-05-06', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'kakayi gift', '', '+256708663249', '', '', '', NULL, NULL, NULL, '$2y$10$ZwrKX0tn/mxQXkg8QL6Yz.JF59B/OYTxEj36iHofp8XiIkpwNxwwa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-09-20', '2023-10-12 17:25:06', '2023-10-12 17:25:06', NULL),
(322, 'Mr. Sam Keith  Mukasa', 1, 1, '00239I0365578', 'Client', 0.00, '', '+256750407260', '', 'Male', '1983-12-21', '', 'Ugandan', '', '', 'Bukoto', 'Nakawa', '', '', '1970-01-01', 'Muganza steven', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$4u3g8FWBPfXHGy40XgukPO6wkU7CWQUyeP3LbSNt03wuTmotpBsl6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-10-12', '2023-10-12 17:25:07', '2023-10-12 10:36:58', NULL),
(323, 'Mrs. habiiba  Nakamatte', 1, 1, '00239I0365752', 'Client', 0.00, '', '+256709926640', '', 'Female', '1980-05-03', '', 'Ugandan', '', '', 'kasubi', 'lubanga', '', '', '1970-01-01', 'nambooze sofia', '', '+256750118536', '', '', '', NULL, NULL, NULL, '$2y$10$nNBIZTV5c2ugqlLP9o3RPuorIcRBJeJfR.lrjfLlmm7l4tUQZacUy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-10-18', '2023-10-12 17:25:07', '2023-10-12 17:25:07', NULL),
(324, 'Mr. Muhumuza  Andrew', 1, 1, '00239I0365817', 'Client', 0.00, '', '+256754441245', '', 'Male', '1989-10-05', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Agaba Alex', '', '+256759908546', '', '', '', NULL, NULL, NULL, '$2y$10$xdrz9CwcbYU1vuBBKDHfGeDBTgNxvJ5L4EepsH629nNV4TQ3kc5Bq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-10-18', '2023-10-12 17:25:07', '2023-10-12 17:25:07', NULL),
(325, 'Ms. Sharifa  Sentongo', 1, 1, '00239I0366509', 'Client', 0.00, '', '+256784414754', '', 'Female', '1983-06-03', '', 'Ugandan', '', '', 'Kabusu', 'Kabusu', '', '', '1970-01-01', 'Ssentongo faruk', '', '+256784414754', '', '', '', NULL, NULL, NULL, '$2y$10$FTw5nFTv1tmwuitZLviNHujO1DPazOerzi.GL2sZpGn0s6iXb8p9m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-11-08', '2023-10-12 17:25:07', '2023-10-12 17:25:07', NULL),
(326, 'Ms. Linsen  Masia', 1, 1, '00239I0367264', 'Client', 0.00, '', '+256757155123', '', 'Female', '2021-11-18', '', 'Ugandan', '', '', 'kla', 'kla', '', '', '1970-01-01', 'Lisen', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$LxN67bhvkKx4VR9Ui/Bi0uiquNO/CM7IhPEnUR8HayDa.RYwXxHFe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-11-18', '2023-10-12 17:25:07', '2023-10-12 10:36:58', NULL),
(327, 'Mrs. Prossy  Nakyobe', 1, 1, '00239I0367267', 'Client', 0.00, '', '+256704852892', '', 'Female', '1989-12-11', '', 'Ugandan', '', '', 'Nataburiwa', 'Kiwabga', '', '', '1970-01-01', 'Nambi grace', '', '+256704852892', '', '', '', NULL, NULL, NULL, '$2y$10$SyNWfRF0nhDKr/NGawPgg.wkT6sHHnPmbGw.v6jeYAGM1pJ7eMdRC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-11-18', '2023-10-12 17:25:07', '2023-10-12 17:25:07', NULL),
(328, 'Mr. Musisi  Maatovu', 1, 1, '00239I0368325', 'Client', 0.00, '', '+256709819332', '', 'Male', '1984-02-01', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nagawa prossy', '', '+256752863210', '', '', '', NULL, NULL, NULL, '$2y$10$bQB4XgRnlJ5N66jp4gwECeiUHbnkuLA487BWeC/PO37vYkBoPPHtC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-12-02', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(329, 'Mrs. Aisha  Ndagire', 1, 1, '00239I0368355', 'Client', 0.00, '', '+256758979813', '', 'Female', '1979-01-02', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'Nambi Grace', '', '+256700855128', '', '', '', NULL, NULL, NULL, '$2y$10$Nl2wQRj7Uk8k60llGskHmOS0LbCDYWCnxXoESQq5nJkLRi4aPnbWO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-12-03', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(330, 'Mrs. monica  namukisa', 1, 1, '00239I0369076', 'Client', 0.00, '', '+256782746477', '', 'Female', '1976-05-03', '', 'Ugandan', '', '', 'rubanga', 'rubaga', '', '', '1970-01-01', 'talemwa rodgers', '', '+256703169745', '', '', '', NULL, NULL, NULL, '$2y$10$CYBuf0tLHf4ErG8RNFQan.xPr1asBp8OPEBVUrQWhJI0rYsfPBMEa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2021-12-20', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(331, 'Ms. Agnes  Namugaya', 1, 1, '00239I0369563', 'Client', 0.00, '', '+256751244688', '', 'Female', '1994-12-12', '', 'Ugandan', '', '', 'Naguru', 'Nakawa', '', '', '1970-01-01', 'Olishaba Eva', '', '+256706472028', '', '', '', NULL, NULL, NULL, '$2y$10$XTc4aDyT0/XXDQ4lm2FhN.uVKUohxMZqCBhiXgZL9pCnc92UujFN6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-06', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(332, 'Mr. Guard  Kamaradi', 1, 1, '00239I0369564', 'Client', 0.00, '', '+256784488903', '', 'Male', '1981-06-27', '', 'Ugandan', '', '', 'makindye', 'Rubaga', '', '', '1970-01-01', 'Muhangi Evaristo', '', '+256753469886', '', '', '', NULL, NULL, NULL, '$2y$10$/vbY3n0QB4FbmHGPsNtONeIls.t9s9nf3VDq5BafvlNQk6X/ph7fu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-03', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(333, 'Ms. Agnes  Namugaya', 1, 1, '00239I0369565', 'Client', 0.00, '', '+256751244688', '', 'Female', '1994-12-12', '', 'Ugandan', '', '', 'Naguru', 'Nakawa', '', '', '1970-01-01', 'Olishaba Eva', '', '+256706472028', '', '', '', NULL, NULL, NULL, '$2y$10$pZUUXmDOT/Y3bANgKP/0Ge6nc9UCiYYuV02L1cm15UFTHK7/Uz4a6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-03', '2023-10-12 17:25:08', '2023-10-12 17:25:08', NULL),
(334, 'Mrs. mary  katushabe', 1, 1, '00239I0369981', 'Client', 0.00, '', '+256708916170', '', 'Female', '1992-01-13', '', 'Ugandan', '', '', 'lubaga', 'lugguja', '', '', '1970-01-01', 'ndagire getrude', '', '+256756665688', '', '', '', NULL, NULL, NULL, '$2y$10$OI7Y62AOi.liNdDuurUeweQ3b2LA/NCuVqASgsw7VZ1rcox2SzlYK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-14', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(335, 'Mrs. maria  nakivumbi', 1, 1, '00239I0369983', 'Client', 0.00, '', '+256754677834', '', 'Female', '1991-08-03', '', 'Ugandan', '', '', 'lubaga', 'luguja', '', '', '1970-01-01', 'ndagire getrude', '', '+256756665688', '', '', '', NULL, NULL, NULL, '$2y$10$ALF3GesSj6QjA45Ej1bq/.CgzRCu/qRPe1bSlcB1MRsHMx4i0bjYS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-14', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(336, 'Mrs. Eveline  Musinguzi', 1, 1, '00239I0370663', 'Client', 0.00, '', '+256704336520', '', 'Female', '1982-04-29', '', 'Ugandan', '', '', 'Lubaga', 'Lubaga', '', '', '1970-01-01', 'Birungi Deus', '', '+256758337150', '', '', '', NULL, NULL, NULL, '$2y$10$DWOGK2dKKSf1nJ917co77O1KFGIpIMBGvk5h63dtKAUYNYMTEs4B2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-21', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(337, 'Mr. Issa  Idilo', 1, 1, '00239I0370668', 'Client', 0.00, '', '+256759285070', '', 'Male', '1990-03-06', '', 'Ugandan', '', '', 'Kirinya Ward', 'Kira Town Council', '', '', '1970-01-01', 'kIKOMA IDD', '', '+256708224732', '', '', '', NULL, NULL, NULL, '$2y$10$YsjVrw6sUCb2KCj2b.tk4.VZg3SZJuNfIVf3lCt01976BOOdZU2Te', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-21', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(338, 'Ms. Sarah  Nakayizi', 1, 1, '00239I0371439', 'Client', 0.00, '', '+256705025037', '', 'Female', '1971-09-06', '', 'Ugandan', '', '', 'Bukoto 2', 'Nakawa', '', '', '1970-01-01', 'Nakajiri Joyce', '', '+256752191663', '', '', '', NULL, NULL, NULL, '$2y$10$5v/wGux45gQkRXh6MGDDrOtgxsBPfCLHHjZ5IfMOxfYr0kA/tFpai', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-27', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(339, 'Ms. Joyce  Nakajiri', 1, 1, '00239I0371441', 'Client', 0.00, '', '+256752191663', '', 'Female', '1984-03-12', '', 'Ugandan', '', '', 'Bukoto', 'Nakawa', '', '', '1970-01-01', 'Nakayizi SARAH', '', '+256705025037', '', '', '', NULL, NULL, NULL, '$2y$10$yJ6JrKuhp3MDc9vs3KcyEuhjSTYJyhIltvTVKmoMns2eMROHgcjDC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-27', '2023-10-12 17:25:09', '2023-10-12 17:25:09', NULL),
(340, 'Mrs. Magret  Nakanjako', 1, 1, '00239I0371442', 'Client', 0.00, '', '+256752136136', '', 'Female', '1981-05-15', '', 'Ugandan', '', '', 'mutundwe', 'kabusu', '', '', '1970-01-01', 'Kambungu Damulira', '', '+256752958420', '', '', '', NULL, NULL, NULL, '$2y$10$Cbi5h3Bh1Rx4ixt9d0yfbuQxAXDLMAuMeIDkxQdbB6R8CGQ7L5Us.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-27', '2023-10-12 17:25:10', '2023-10-12 17:25:10', NULL),
(341, 'Ms. Rose  Nabawanuka', 1, 1, '00239I0371443', 'Client', 0.00, '', '+256757744721', '', 'Female', '1977-06-06', '', 'Ugandan', '', '', 'Bukoto2', 'Nakawa', '', '', '1970-01-01', 'Nakajiri Joyce', '', '+256752191663', '', '', '', NULL, NULL, NULL, '$2y$10$JTfVIpMF8h.YMd.Etoqfj.c/JEzmTLjI0uLG6iNOud0NwxbErVgcK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-27', '2023-10-12 17:25:10', '2023-10-12 17:25:10', NULL),
(342, 'Mr. Ivan  Kasanvu', 1, 1, '00239I0371501', 'Client', 0.00, '', '+256700384297', '', 'Male', '1989-11-11', '', 'Ugandan', '', '', 'Nasana', 'Nasana', '', '', '1970-01-01', 'Nkeera moses', '', '+256756751440', '', '', '', NULL, NULL, NULL, '$2y$10$MmQAy5zTpQ3PbarPE.oyeefjpB0eHBWLs4aUvZGMjNhqIsb3RPrOi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-01-28', '2023-10-12 17:25:10', '2023-10-12 17:25:10', NULL),
(343, 'Mr. Geoffrey  Suubi', 1, 1, '00239I0373130', 'Client', 0.00, '', '+256782046399', '', 'Male', '1982-03-10', '', 'Ugandan', '', '', 'Nakawa', 'CENTRAL', '', '', '1970-01-01', 'Otim Ronald', '', '+256722972226', '', '', '', NULL, NULL, NULL, '$2y$10$ldcxo9ZIaV1e/SdIT3NdnuPoyfVPrNHeqRhGibGbmVe//yz5svd0a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-03', '2023-10-12 17:25:10', '2023-10-12 17:25:10', NULL);
INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(344, 'Mr. Kamurali Peter Asaba', 1, 1, '00239I0374262', 'Client', 0.00, '', '+256701247617', '', 'Male', '1986-01-17', '', 'Ugandan', '', '', 'Kyadondo', 'Kira', '', '', '1970-01-01', 'Namirembe joanita', '', '+254799647160', '', '', '', NULL, NULL, NULL, '$2y$10$3jmg8DRWv44w9JTpgopktuCR89a4KnuPXXz15EwiU6CquJGoT08Gm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-07', '2023-10-12 17:25:10', '2023-10-12 17:25:10', NULL),
(345, 'Mrs. Sylivia  Makoora', 1, 1, '00239I0374406', 'Client', 0.00, '', '+256782501617', '', 'Female', '1979-02-12', '', 'Ugandan', '', '', 'kamwokya 11', 'Bukooto', '', '', '1970-01-01', 'Kisembo Benon', '', '+256788339520', '', '', '', NULL, NULL, NULL, '$2y$10$P9OvZye3ydQmzTNaY5Por.LCTABAJi3Wn4k2YMg6w4G0B2syMMFrW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-08', '2023-10-12 17:25:11', '2023-10-12 17:25:11', NULL),
(346, 'Mr. Duncan  Kawuma', 1, 1, '00239I0374407', 'Client', 0.00, '', '+256704539456', '', 'Male', '1988-11-22', '', 'Ugandan', '', '', 'Ndeeba', 'Wankulukuku', '', '', '1970-01-01', 'victoria', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$wfUSgT6VErU1gRbu5nUAF.HIt8Az8oyxIY2AUFgS/yzpKzukZPD5W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-04', '2023-10-12 17:25:11', '2023-10-12 10:36:58', NULL),
(347, 'Ms. Annet  Nabunya', 1, 1, '00239I0374408', 'Client', 0.00, '', '+256752681664', '', 'Female', '1982-09-29', '', 'Ugandan', '', '', 'Kamwokya 11', 'kampala central', '', '', '1970-01-01', 'Nakibuule tracy', '', '+256706377278', '', '', '', NULL, NULL, NULL, '$2y$10$h.hyTVuh4yCfC0EZP/l1Deqdw/EdX5TSfwWluiGL8ea.3999iGpvm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-08', '2023-10-12 17:25:11', '2023-10-12 17:25:11', NULL),
(348, 'Rita  Lubega', 1, 1, '00239I0374515', 'Client', 0.00, '', '+256753872665', '', 'Female', '1977-12-23', '', 'Ugandan', '', '', 'Bwaise 111', 'Kawempe', '', '', '1970-01-01', 'Muwonge Derick', '', '+256754806903', '', '', '', NULL, NULL, NULL, '$2y$10$5YhKfU6PzmrPG1aiE1sNE.jRt6lOAhIg8Fx5T0K1DWFQOsP0/mfv.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-10', '2023-10-12 17:25:11', '2023-10-12 17:25:11', NULL),
(349, 'Ms. Betty  Nakawuki', 1, 1, '00239I0374516', 'Client', 0.00, '', '+256703603494', '', 'Female', '1990-03-15', '', 'Ugandan', '', '', 'kyadondo', 'kira', '', '', '1970-01-01', 'Nakajiri Joyce', '', '+256752191663', '', '', '', NULL, NULL, NULL, '$2y$10$c6VrRBaAc5YbiBDzbFZ.9OuFoJ4kD6/0k/fbh9IqGSsQh6BCmAkfW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-10', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(350, 'Mrs. Oliver  Nakayaga', 1, 1, '00239I0374566', 'Client', 0.00, '', '+256756138595', '', 'Female', '1982-01-01', '', 'Ugandan', '', '', 'kasubi', 'Rubaga', '', '', '1970-01-01', 'Nakate Annet', '', '+256702652375', '', '', '', NULL, NULL, NULL, '$2y$10$JnRFZMywQwWIfweoHMK/uetu3x.bmZvzPLzSbssRvgNbs0PrVLCJm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-11', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(351, 'Mrs. Mariam  Nakalo', 1, 1, '00239I0374731', 'Client', 0.00, '', '+256772874752', '', 'Female', '1981-08-07', '', 'Ugandan', '', '', 'Mpunga ward', 'Wakiso Town council', '', '', '1970-01-01', 'Kiyimba Nuhu', '', '+256772527951', '', '', '', NULL, NULL, NULL, '$2y$10$dXqFhdXpjRnaLIn9YzP.bel9YSK./JqJbHfBTpkuD.DwR971i1cOm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-17', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(352, 'Mrs. Oliver  Nabayunga', 1, 1, '00239I0374732', 'Client', 0.00, '', '+256701628621', '', 'Female', '1976-07-07', '', 'Ugandan', '', '', 'Lusaze', 'Rubaga', '', '', '1970-01-01', 'Nabadduka zam', '', '+256701942118', '', '', '', NULL, NULL, NULL, '$2y$10$J8bFqWDKgh3tt0EN4sZ7RuidcW6w6lmD6ZO9T9Ga5UW5EIHGeglNO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-17', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(353, 'Mr. Yusuf  Ssenyondo', 1, 1, '00239I0375103', 'Client', 0.00, '', '+256703769296', '', 'Male', '2022-02-23', '', 'Ugandan', '', '', 'Lubya', 'CENTRAL', '', '', '1970-01-01', 'Sumaya Nakazi', '', '+256772670881', '', '', '', NULL, NULL, NULL, '$2y$10$yibo.71.Ff.CzKOqJ0vvTu.wWoIqtBqNvk2cU04vqIxFI.CvYoydC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-23', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(354, 'Mrs. Diana  Nakachwa', 1, 1, '00239I0375255', 'Client', 0.00, '', '+256703937441', '', 'Female', '1990-05-30', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Lunkusse Betty', '', '+256740922053', '', '', '', NULL, NULL, NULL, '$2y$10$7xwUDMqOzNLQClfQWTU9yeJS821JLODK3lqi06LxnuIOjzt4iOOh.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-28', '2023-10-12 17:25:12', '2023-10-12 17:25:12', NULL),
(355, 'Mrs. Nakachwa  Diana', 1, 1, '00239I0375259', 'Client', 0.00, '', '+256703937441', '', 'Female', '1990-05-30', '', 'Ugandan', '', '', 'Rubaga', 'Rubaga', '', '', '1970-01-01', 'Lunkuse Betty', '', '+256740922053', '', '', '', NULL, NULL, NULL, '$2y$10$t9rwhSuj0ZjktNGnet0x1eaMks8AbkHdC8d8bq1YND7RiICPozoJe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-02-28', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(356, 'Mrs. Samalie Kivumbi Nabachwa', 1, 1, '00239I0375355', 'Client', 0.00, '', '+256701020529', '', 'Female', '1992-01-17', '', 'Ugandan', '', '', 'Kitala', 'Katabi', '', '', '1970-01-01', 'Nkwasibwe Moses', '', '+256708425275', '', '', '', NULL, NULL, NULL, '$2y$10$ujnfIHuFdH1tieKjyXWzieJUrxFReWOsk0FLNJufmxpFAQn/rvjO2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-02', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(357, 'Ms. Annet Juliet Nambassa', 1, 1, '00239I0375356', 'Client', 0.00, '', '+256754130434', '', 'Female', '2022-03-02', '', 'Ugandan', '', '', 'kamwokya 11', 'kampala central', '', '', '1970-01-01', 'Nakajjubi Moreen', '', '+256786917071', '', '', '', NULL, NULL, NULL, '$2y$10$OeFwS/AI4rAIfJFrV.eLiehflmFCGMk5c8Mj31djdCvySJYHbdyZO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-02', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(358, 'Faith  Nabukenya', 1, 1, '00239I0375357', 'Client', 0.00, '', '+256773828507', '', 'Female', '2022-03-02', '', 'Ugandan', '', '', 'Kazo ward', 'Nansana Town conucil', '', '', '1970-01-01', 'Sewanyana Eddy', '', '+256703467615', '', '', '', NULL, NULL, NULL, '$2y$10$JzGcU0e1wx3zlL4dL4c6o.VG5lSQC10JMUgN2D7nSmzgsjcpwe/fy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-02', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(359, 'Mrs. Eva  Namukose', 1, 1, '00239I0375360', 'Client', 0.00, '', '+256756264535', '', 'Female', '1986-10-17', '', 'Ugandan', '', '', 'kirinya ward', 'kira town council', '', '', '1970-01-01', 'Nampina Janerfer', '', '+256756264535', '', '', '', NULL, NULL, NULL, '$2y$10$M.5jFNZEKuQs4iCpg3yrt..KllMx4wQMMe0YBgBhdnKHePRuzPeh6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-02', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(360, 'Mrs. Norah  Nanyange', 1, 1, '00239I0375393', 'Client', 0.00, '', '+256701879405', '', 'Female', '2022-03-03', '', 'Ugandan', '', '', 'kawempe', 'Kawempe division', '', '', '1970-01-01', 'Ssempera George and Namayanja Edith', '', '+256758982546', '', '', '', NULL, NULL, NULL, '$2y$10$.CGBHogYgg6Iib6nEW7Lm.vsT.Z5XS1vlKxVY3vVBxrMYp.OCI3fi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-03', '2023-10-12 17:25:13', '2023-10-12 17:25:13', NULL),
(361, 'Ms. Siddy  Amongi', 1, 1, '00239I0375454', 'Client', 0.00, '', '+256751531119', '', 'Female', '2022-03-04', '', 'Ugandan', '', '', 'Bwaise 111', 'kawempe division', '', '', '1970-01-01', 'Opio micheal', '', '+256777866672', '', '', '', NULL, NULL, NULL, '$2y$10$sDC5qAmLxWdyWFKaHiYYEe1KXpK3ydai445d1aBsG9iT5gy/oXkmm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-04', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(362, 'Daphine  Namubiru', 1, 1, '00239I0375455', 'Client', 0.00, '', '+256778877447', '', 'Female', '2022-03-04', '', 'Ugandan', '', '', 'Bukoto', 'Nakawa division', '', '', '1970-01-01', 'Ahibisibwe Godfrey', '', '+256796421000', '', '', '', NULL, NULL, NULL, '$2y$10$/rGjS0AN0kX/XGCBdH3vwOz4WZ5bIerkepkKauAP.y.qn02rmCS0W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-04', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(363, 'Ms. Joyce  Nabakema', 1, 1, '00239I0375456', 'Client', 0.00, '', '+256755531675', '', 'Female', '2022-03-04', '', 'Ugandan', '', '', 'kasubi', 'Rubaga division', '', '', '1970-01-01', 'Nanyange Nolah', '', '+256751879405', '', '', '', NULL, NULL, NULL, '$2y$10$gox78TwJyhYT/RHi/oDQk.A9i782LKfmyDpJ/xb5D4IApu4.QQiWW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-04', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(364, 'Ms. Joyce  Nabankema', 1, 1, '00239I0375460', 'Client', 0.00, '', '+256755531675', '', 'Female', '2022-03-04', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Nanyange Nolah', '', '+256701879405', '', '', '', NULL, NULL, NULL, '$2y$10$bVtIltADNbka7t.usW6N8eB4DoF3yhf0st/CkqwcQnDdG8blblhKq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-04', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(365, 'Mr. OPOLOT  ABRAHAM', 1, 1, '00239I0375523', 'Client', 0.00, '', '+256783352371', '', 'Male', '1987-12-15', '', 'Ugandan', '', '', 'NTAWO WARD', 'MUKONO DIVISION', '', '', '1970-01-01', 'MUNDUA SUNDAY SAM', '', '+256779700700', '', '', '', NULL, NULL, NULL, '$2y$10$QPOfnoT79ivjEBF8B2FV7uMIIT01yeCg.hjUN0YMRq458l.ByV1XC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-07', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(366, 'Mrs. TWIKIRIZE  FLORENCE', 1, 1, '00239I0375685', 'Client', 0.00, '', '+256779266318', '', 'Female', '2022-03-11', '', 'Ugandan', '', '', 'BUKOTO B', 'NAKAWA', '', '', '1970-01-01', 'KABASHAMBU ALICE', '', '+256787818632', '', '', '', NULL, NULL, NULL, '$2y$10$pc0iKwhXvsos5JbVvj/s8OgC6Ylax7nfOS9vtYSIUjM/irPfiI7IC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-11', '2023-10-12 17:25:14', '2023-10-12 17:25:14', NULL),
(367, 'Mr. muhangi  michael', 1, 1, '00239I0375686', 'Client', 0.00, '', '+256757787216', '', 'Male', '1988-05-03', '', 'Ugandan', '', '', 'kireka ward', 'kira town council', '', '', '1970-01-01', 'ahisiibwe rongino', '', '+256700336800', '', '', '', NULL, NULL, NULL, '$2y$10$GApXIo2PPK3VY6vd8MEWqu5Lq443jgbhr.ue7bBzXN8SFFYnn4KTy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-09', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(368, 'Ms. Ruth  Nakayi', 1, 1, '00239I0375720', 'Client', 0.00, '', '+256708276120', '', 'Female', '2022-03-12', '', 'Ugandan', '', '', 'Kyebando', 'Wakiso', '', '', '1970-01-01', 'Nabatanzi fatumah', '', '+256703198117', '', '', '', NULL, NULL, NULL, '$2y$10$u1/lrFSHbasRSUBugQIWXO734Pdlp1jor6wGCF0YaOCukBcXtgiAS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-12', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(369, 'Mr. NKAMUSIIMA  NICHODEMUS', 1, 1, '00239I0375737', 'Client', 0.00, '', '+256760797152', '', 'Male', '1996-01-03', '', 'Ugandan', '', '', 'MBUYA', 'NAKAWA DIVISION', '', '', '1970-01-01', 'OLIVIA AINEMBABAZI', '', '+256786788260', '', '', '', NULL, NULL, NULL, '$2y$10$K3jU75k7QjhEMZsg.YG3S.gse5vPSNZTjG1P96M7lzbPHSrwQIoBu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-14', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(370, 'Mr. Lukwago  Tadious', 1, 1, '00239I0375782', 'Client', 0.00, '', '+256706692794', '', 'Male', '1986-12-01', '', 'Ugandan', '', '', 'Kiira', 'Kiira', '', '', '1970-01-01', 'Aturinda Deborah', '', '+256758212936', '', '', '', NULL, NULL, NULL, '$2y$10$JKmBCBHqLjqAqkvMahyWoeMthYU4os5XDmlR6yhu3fbHw7mc.gIga', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-15', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(371, 'Ms. Faridah  Nanyonjo', 1, 1, '00239I0375824', 'Client', 0.00, '', '+256705628828', '', 'Female', '1993-05-02', '', 'Ugandan', '', '', 'kito', 'kira division', '', '', '1970-01-01', 'olishba Eva', '', '+256706472028', '', '', '', NULL, NULL, NULL, '$2y$10$RDUGCJwBJYUYnTu5G9NrHeaK1/BDiiD4VqiOiwXkR/kBAKk1SohiW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-16', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(372, 'Mr. Kauta  Bosco', 1, 1, '00239I0375825', 'Client', 0.00, '', '+256752177208', '', 'Male', '1985-10-05', '', 'Ugandan', '', '', 'Kireka Ward', 'Kira Town Council', '', '', '1970-01-01', 'Magret Namusisi', '', '+256776305085', '', '', '', NULL, NULL, NULL, '$2y$10$H.8rx.iFHazDynG5x.oI/OuL0l0KUpap.dSqgxIM35kSA3GBI3UgK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-16', '2023-10-12 17:25:15', '2023-10-12 17:25:15', NULL),
(373, 'Mr. muhwezi  michael', 1, 1, '00239I0375827', 'Client', 0.00, '', '+256704712566', '', 'Male', '1986-01-01', '', 'Ugandan', '', '', 'banda', 'nakawa', '', '', '1970-01-01', 'nandinda moses', '', '+256784979952', '', '', '', NULL, NULL, NULL, '$2y$10$93joeuAM.MYiwWbTIGANDOWDYDuGGdCKx/BMs2Bty2mn8iNgA2yAS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-16', '2023-10-12 17:25:16', '2023-10-12 17:25:16', NULL),
(374, 'Mrs. Susan  Luswiga', 1, 1, '00239I0375865', 'Client', 0.00, '', '+256706410445', '', 'Female', '1992-03-03', '', 'Ugandan', '', '', 'Nabidongha ward', 'Cntral division', '', '', '1970-01-01', 'Namukasa Mariam Mulyazawo', '', '+256774684559', '', '', '', NULL, NULL, NULL, '$2y$10$SuVSv23bGHWx0KA3pgBsKebukyAKoLiJSq.tjISr00AK.g9QCV1Fe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-17', '2023-10-12 17:25:16', '2023-10-12 17:25:16', NULL),
(375, 'Mrs. Ruth  Nabatanzi', 1, 1, '00239I0377233', 'Client', 0.00, '', '+256701116650', '', 'Female', '1982-05-25', '', 'Ugandan', '', '', 'Lubya', 'Rubaga division', '', '', '1970-01-01', 'Ssebidde Morise', '', '+256740775704', '', '', '', NULL, NULL, NULL, '$2y$10$eQah2UYVd51iQvbxUdhKQuflYAD.TUT9PHv4oRnOc0.ZrAJ87cJGG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-21', '2023-10-12 17:25:16', '2023-10-12 17:25:16', NULL),
(376, 'Mr. SSEGAWA  IDD', 1, 1, '00239I0380192', 'Client', 0.00, '', '+256754669328', '', 'Male', '1997-07-14', '', 'Ugandan', '', '', 'Magezi Kizungu Ward', 'Lukaya Town Council', '', '', '1970-01-01', 'Kyasimire Saidat', '', '+256753207583', '', '', '', NULL, NULL, NULL, '$2y$10$zQ8tWQptdP9BTxL.YnvmTukHko.VEjqDd/3NFeWD.Q.So9FOqU6MK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-28', '2023-10-12 17:25:16', '2023-10-12 17:25:16', NULL),
(377, 'Mr. Nsubuga Fred Sebagala', 1, 1, '00239I0380272', 'Client', 0.00, '', '+256708613512', '', 'Male', '1976-03-03', '', 'Ugandan', '', '', 'Mbuya', 'Nakawa', '', '', '1970-01-01', 'Kiwanuka Andrew', '', '+256754727017', '', '', '', NULL, NULL, NULL, '$2y$10$1PGmqvgJAG38CoJnlnJtlux.Y7MQvOR.s7Cyr8V3ghN3l6M2aRpPq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-03-30', '2023-10-12 17:25:16', '2023-10-12 17:25:16', NULL),
(378, 'Mr. Muhammad  Mutasa', 1, 1, '00239I0382938', 'Client', 0.00, '', '+256753432205', '', 'Male', '2022-04-02', '', 'Ugandan', '', '', 'Katwe', 'Makindye', '', '', '1970-01-01', 'Zalwango Shamim', '', '+256701094446', '', '', '', NULL, NULL, NULL, '$2y$10$bbnTg/DGCV6bSUB5CwN2mOU2rhenBBwx1OB15CuIMWTdp4DaWcIHa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-02', '2023-10-12 17:25:17', '2023-10-12 17:25:17', NULL),
(379, 'Ms. Milly  Nazziwa', 1, 1, '00239I0384300', 'Client', 0.00, '', '+256787046024', '', 'Female', '1979-10-23', '', 'Ugandan', '', '', 'Bweyogerere', 'Kira Town council', '', '', '1970-01-01', 'Mwiduka Ahamadha Ali', '', '+256752809860', '', '', '', NULL, NULL, NULL, '$2y$10$HEfEGFpxt77otpSDH5rUr.LhQWoSmwKSw7Ls3NFjqC8zNSE9U7WvG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-07', '2023-10-12 17:25:17', '2023-10-12 17:25:17', NULL),
(380, 'Mr. paul  matrin', 1, 1, '00239I0384302', 'Client', 0.00, '', '+256758218600', '', 'Male', '1978-12-12', '', 'Ugandan', '', '', 'Kasenge Ward', 'Kyengera Town Council', '', '', '1970-01-01', 'Sewulo Robert', '', '+256752658836', '', '', '', NULL, NULL, NULL, '$2y$10$6Zs9Uj0vjgpGi1MnwbKn4.xsHhgnYa7JeXPINrKZzinaz.p3PzVSO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-07', '2023-10-12 17:25:17', '2023-10-12 17:25:17', NULL),
(381, 'Mr. Semakula Martin Paul', 1, 1, '00239I0384305', 'Client', 0.00, '', '+256758218600', '', 'Male', '1978-12-12', '', 'Ugandan', '', '', 'Kasenge', 'Bandwe', '', '', '1970-01-01', 'Namulondo shamim', '', '+256753402969', '', '', '', NULL, NULL, NULL, '$2y$10$xj0Zgtcvm68v4JYyPCyz2.4c1RmRfsGbty4TVb0j81wh/c4XBr4yC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-07', '2023-10-12 17:25:17', '2023-10-12 17:25:17', NULL),
(382, 'Mrs. kyalisima  peace', 1, 1, '00239I0384359', 'Client', 0.00, '', '+256757889860', '', 'Female', '1980-11-21', '', 'Ugandan', '', '', 'Gganda', 'Nansana', '', '', '1970-01-01', 'Nakayi Ruth', '', '+256708276120', '', '', '', NULL, NULL, NULL, '$2y$10$ExOYW5bym2aW0eityPBqK.fHs9TNfxVbcgi1zTsugy/0trZTTgMJS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-08', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(383, 'Mr. Musisi Kamadah Sharifu', 1, 1, '00239I0384361', 'Client', 0.00, '', '+256752958219', '', 'Male', '1994-03-18', '', 'Ugandan', '', '', 'Kirinya', 'Namboole', '', '', '1970-01-01', 'Kamanyire Zubair,Mayanja Fred Kambugu', '', '+256751974491', '', '', '', NULL, NULL, NULL, '$2y$10$4Wgt71PP7SxmdX6PeicwZe9JuJ05GIpDjvoiaoJhIyya2Ia2xKgT2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-08', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(384, 'Mr. Kamanyire  Zubair', 1, 1, '00239I0384506', 'Client', 0.00, '', '+256751974491', '', 'Male', '1990-01-16', '', 'Ugandan', '', '', 'Namboole', 'kirnya', '', '', '1970-01-01', 'Kidhiki Peter', '', '+256773855216', '', '', '', NULL, NULL, NULL, '$2y$10$PJWtF.kXYxAoUVNJpaEODeUC21ATDcFZakTnGTVuMHR15Xf3zepQu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-09', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(385, 'Mr. Sebulime  Swaibu', 1, 1, '00239I0384934', 'Client', 0.00, '', '+256701366252', '', 'Male', '1965-02-10', '', 'Ugandan', '', '', 'Nansana', 'Nansana', '', '', '1970-01-01', 'Sseruyombya Peter', '', '+256706458629', '', '', '', NULL, NULL, NULL, '$2y$10$c/xZzbRlG9hZHfb9dtVSEufNmSIu5QRYsqs0.8pazVRcaZdZfEq7u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-12', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(386, 'Mr. AGAPITUS  KEDI', 1, 1, '00239I0384937', 'Client', 0.00, '', '+256772087327', '', 'Male', '1985-01-05', '', 'Ugandan', '', '', 'goma', 'kira', '', '', '1970-01-01', 'Kahaama Elijah', '', '+256702163482', '', '', '', NULL, NULL, NULL, '$2y$10$i6/vDcQQduUM.Kc8ElEWu.UxdLsiRoAhQJt0PMImQscSSBgRyy0WO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-12', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(387, 'Mrs. Nakamya Elizabeth Kuganja', 1, 1, '00239I0385090', 'Client', 0.00, '', '+256752008871', '', 'Female', '2022-04-13', '', 'Ugandan', '', '', 'Kiira', 'Kiira', '', '', '1970-01-01', 'Lubega Brian', '', '+256700417919', '', '', '', NULL, NULL, NULL, '$2y$10$DpkLcMgHoUcS9AMrsUs7HOlgK1guVh2OSun.KXtUB4FUM3uVYb.2q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-13', '2023-10-12 17:25:18', '2023-10-12 17:25:18', NULL),
(388, 'Mr. Kkeeya Ivan Joel', 1, 1, '00239I0385092', 'Client', 0.00, '', '+256758217659', '', 'Male', '1996-07-29', '', 'Ugandan', '', '', 'Nansana', 'Nabweru', '', '', '1970-01-01', 'Nalwoga Florance', '', '+256784700789', '', '', '', NULL, NULL, NULL, '$2y$10$9E/OA572jhuGY9idqHspruVM3HMj2uBT3hof/uq2ZE/a5PPuWAsb2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-13', '2023-10-12 17:25:19', '2023-10-12 17:25:19', NULL),
(389, 'Mr. Bbale  Gerald', 1, 1, '00239I0385967', 'Client', 0.00, '', '+256754352341', '', 'Male', '1977-01-14', '', 'Ugandan', '', '', 'Nansana', 'Nabweru', '', '', '1970-01-01', 'Ssemagera Francis', '', '+256758797885', '', '', '', NULL, NULL, NULL, '$2y$10$LtGUJcu6i81LMm0sM7ZmSOrj4fGI78zmPbS1RXzkTakdx7KYhUqG2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-14', '2023-10-12 17:25:19', '2023-10-12 17:25:19', NULL),
(390, 'Mrs. Atuhaire  Talent', 1, 1, '00239I0385971', 'Client', 0.00, '', '+256705145390', '', 'Female', '1995-05-15', '', 'Ugandan', '', '', 'Kira', 'Kira', '', '', '1970-01-01', 'Atugabirwe Daphine', '', '+256784537011', '', '', '', NULL, NULL, NULL, '$2y$10$7NGU/oW0/j53lfSmllr.Ge.Z7CanGLL2skD5LU9/zj3hxBVPH/VPO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-14', '2023-10-12 17:25:19', '2023-10-12 17:25:19', NULL),
(391, 'Mr. KAKAYIRE  HAMUZA', 1, 1, '00239I0389301', 'Client', 0.00, '', '+256756013948', '', 'Male', '1982-07-05', '', 'Ugandan', '', '', 'Kiwatule', 'Nakawa', '', '', '1970-01-01', 'Kayiira Hassan', '', '+256709634412', '', '', '', NULL, NULL, NULL, '$2y$10$5iZxtwgpwWsgJFlqFg6SMu9SrEABhB66/o13FOBBPpRPO4x9DtC9e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-16', '2023-10-12 17:25:19', '2023-10-12 17:25:19', NULL),
(392, 'Mr. MBULAMUKO  APOLLO', 1, 1, '00239I0389310', 'Client', 0.00, '', '+256704363789', '', 'Male', '2022-04-19', '', 'Ugandan', '', '', 'Seguku', 'Makindye', '', '', '1970-01-01', 'Bulage Frida', '', '+256754090211', '', '', '', NULL, NULL, NULL, '$2y$10$SBnd5Wacedj3fGD48IAV9OHVEDZj1pGq8BMCqtVIND3F26vSaEU9G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-19', '2023-10-12 17:25:19', '2023-10-12 17:25:19', NULL),
(393, 'Mrs. Harriet  Nakanwagi', 1, 1, '00239I0389311', 'Client', 0.00, '', '+256756789496', '', 'Female', '2022-04-19', '', 'Ugandan', '', '', 'Bweyogerere', 'Kira', '', '', '1970-01-01', 'Saka Semkakula', '', '+256774278000', '', '', '', NULL, NULL, NULL, '$2y$10$/wm1Q50dCiVMyoFkB7AVjuPqM7kdwxktlgAYKsMIUAJA8bpRWl6EO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-19', '2023-10-12 17:25:20', '2023-10-12 17:25:20', NULL),
(394, 'Mrs. Mahoro Joy Kamukama', 1, 1, '00239I0389312', 'Client', 0.00, '', '+256771569866', '', 'Female', '1985-10-10', '', 'Ugandan', '', '', 'Bweyogerere ward', 'Kiira Town Council', '', '', '1970-01-01', 'Feza Beatrice', '', '+256760773858', '', '', '', NULL, NULL, NULL, '$2y$10$YolORlWVhISLp4V.vQD6huwFjGWT34pqddZNaQ5UDyPtAXA.8Yz9a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-19', '2023-10-12 17:25:20', '2023-10-12 17:25:20', NULL),
(395, 'Mrs. Gloria  Nabada', 1, 1, '00239I0389315', 'Client', 0.00, '', '+256758513453', '', 'Female', '1988-12-20', '', 'Ugandan', '', '', 'Bweyogerere', 'Kira', '', '', '1970-01-01', 'Nansamba Florence', '', '+256771830692', '', '', '', NULL, NULL, NULL, '$2y$10$gRYkIyXrU8W3uoYC6UgjEOct6a0cOHjPqSOQ3gefh/ZyYRaFAhhXK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-19', '2023-10-12 17:25:20', '2023-10-12 17:25:20', NULL),
(396, 'Namulondo Haddy Vanesa', 1, 1, '00239I0389355', 'Client', 0.00, '', '+256702232367', '', 'Female', '1986-02-02', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Turyasingura Winnie', '', '+256702605385', '', '', '', NULL, NULL, NULL, '$2y$10$Z/t3a3mjRs451/1fN9PmgepjqHhLiVPShQWGaGc1dSZHcDgSoWwnC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-20', '2023-10-12 17:25:20', '2023-10-12 17:25:20', NULL),
(397, 'KAKA  ALEX', 1, 1, '00239I0389356', 'Client', 0.00, '', '+256761596141', '', 'Male', '1988-08-12', '', 'Ugandan', '', '', 'Katete Ward', 'Nyamitanga', '', '', '1970-01-01', 'Namara Catherine', '', '+256701036154', '', '', '', NULL, NULL, NULL, '$2y$10$U5DxeHT4lCcf3tu85PxKLulufL5QOtEOLik7Va61W6QREGgrHFfpK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-20', '2023-10-12 17:25:20', '2023-10-12 17:25:20', NULL),
(398, 'Mr. Nasir  Luutu', 1, 1, '00239I0389822', 'Client', 0.00, '', '+256701361318', '', 'Male', '1988-01-01', '', 'Ugandan', '', '', 'Kireka Ward', 'Kira Town Council', '', '', '1970-01-01', 'Wasubira David', '', '+256702589523', '', '', '', NULL, NULL, NULL, '$2y$10$5PT3BZiQ01SSvM9pRPsn8.OiUfp/v31h7hixaFyodpw1N5a7bfF9G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-21', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(399, 'Alinda Daphine Williams', 1, 1, '00239I0389829', 'Client', 0.00, '', '+256758983058', '', 'Female', '1996-02-19', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kalibbala Mugabi', '', '+256700260084', '', '', '', NULL, NULL, NULL, '$2y$10$/u8PPhCQN0aT12RoE6AL8e96WAcBlS.FBbgsbiiNtdEr8h9m/hBzu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-21', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(400, 'Nabiryo  Lydia', 1, 1, '00239I0389911', 'Client', 0.00, '', '+256788623683', '', 'Female', '1984-01-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nanfuka Yudaaya', '', '+256758617314', '', '', '', NULL, NULL, NULL, '$2y$10$RjtqRcmWob5gu31MTgyemuZ2ub.2GGFZczYbcvdCS5t/PQD9F/ACq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-23', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(401, 'Mrs. Emmanuel  Mwanje', 1, 1, '00239I0389912', 'Client', 0.00, '', '+256706631911', '', 'Male', '1992-12-25', '', 'Ugandan', '', '', 'Kireka ward', 'Kira town council', '', '', '1970-01-01', 'Kizza Francis', '', '+256753954928', '', '', '', NULL, NULL, NULL, '$2y$10$r2.8pxC3K4WAXfVLv7CEbOqTOXMIWW1Emjvi07r5mXSpho/2a3Jvu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-23', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(402, 'Mr. MUJJUZI  ALEX', 1, 1, '00239I0389913', 'Client', 0.00, '', '+256771075091', '', 'Male', '1991-01-23', '', 'Ugandan', '', '', 'Kira', 'Kira', '', '', '1970-01-01', 'Nabirye Mercy', '', '+256757631226', '', '', '', NULL, NULL, NULL, '$2y$10$98ye/KpxY9vAdtsMZUcwBuIu0poRrvEAVcwg4ldb2haf4RqhQneT.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-23', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(403, 'Kiviri  Haruna', 1, 1, '00239I0389914', 'Client', 0.00, '', '+256782323165', '', 'Male', '1965-01-22', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namayanja Betty', '', '+256755064085', '', '', '', NULL, NULL, NULL, '$2y$10$hBh2Pd2oWQhlKEmVZvGJuODWrEdPv99CTUovG73r2aRv.pDknQarK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-23', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(404, 'Cissy  Namaganda', 1, 1, '00239I0389915', 'Client', 0.00, '', '+256761067188', '', 'Female', '1990-10-16', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Janice Faruq', '', '+256700387783', '', '', '', NULL, NULL, NULL, '$2y$10$BUGzRfruYeTFb1Cv2OJo9Ox5AwvPxGzUWIahXfZ8m0aDccyXpbv/q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-23', '2023-10-12 17:25:21', '2023-10-12 17:25:21', NULL),
(405, 'Mr. Joseph  Kayongo', 1, 1, '00239I0393346', 'Client', 0.00, '', '+256701314007', '', 'Male', '1992-11-03', '', 'Ugandan', '', '', 'Ntebetebe', 'kira town council', '', '', '1970-01-01', 'Muwonge Peter', '', '+256702167229', '', '', '', NULL, NULL, NULL, '$2y$10$7UY5Y3XJW.E1Avc6FDIIQ.wrevuw8.Kq4AdDp7wabuoHxkPMOdIMG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-28', '2023-10-12 17:25:22', '2023-10-12 17:25:22', NULL),
(406, 'Nushipha  Nakitende', 1, 1, '00239I0393380', 'Client', 0.00, '', '+256751891162', '', 'Female', '2022-04-29', '', 'Ugandan', '', '', 'Nantabulirwa', 'Goma', '', '', '1970-01-01', 'Aminah Nampima', '', '+256782694569', '', '', '', NULL, NULL, NULL, '$2y$10$rc/K0XIONR2/AotuomAtgeJoVKH9WmLnjErmhSEncLcyOVFlWextG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:22', '2023-10-12 17:25:22', NULL),
(407, 'Bulyaba  Harriet', 1, 1, '00239I0393383', 'Client', 0.00, '', '+256705602047', '', 'Female', '1979-06-05', '', 'Ugandan', '', '', 'Kyaliwajala', 'Kira', '', '', '1970-01-01', 'Giibwa Prima', '', '+256707584697', '', '', '', NULL, NULL, NULL, '$2y$10$rixASanL8CUbGPPIlkTECu5LbIxPa4fWV3kEUhfQX5GYH1M0uc07S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:22', '2023-10-12 17:25:22', NULL),
(408, 'Muzibira  Ivan', 1, 1, '00239I0393384', 'Client', 0.00, '', '+256772060465', '', 'Male', '2022-04-29', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namatovu Amina', '', '+256777800241', '', '', '', NULL, NULL, NULL, '$2y$10$/D4LOC0zBo4JRiAUuOO3CeJsBWdQso2p2sc0peY0OoaTlkrBgmdxa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:22', '2023-10-12 17:25:22', NULL),
(409, 'Mrs. Noelna  Namukwaya', 1, 1, '00239I0393385', 'Client', 0.00, '', '+256703218478', '', 'Female', '1972-06-04', '', 'Ugandan', '', '', 'Nankuwadde', 'Nasana', '', '', '1970-01-01', 'B able Gerald', '', '+256754352341', '', '', '', NULL, NULL, NULL, '$2y$10$D4A9QAI2F3dtelt.SacTou11a807evRXTzvpangKmwc0.OphW/rlC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(410, 'Nakamya  Robinah', 1, 1, '00239I0393386', 'Client', 0.00, '', '+256752410820', '', 'Female', '1985-02-21', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakaayi Madiina', '', '+256705146940', '', '', '', NULL, NULL, NULL, '$2y$10$5dhVPYqrakbbZ0tVwXnQK.IKUHL2uUm.xORQ0.Wblsk1uzYTdgD5W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(411, 'Mrs. Madina  Nakayi', 1, 1, '00239I0393387', 'Client', 0.00, '', '+256705146940', '', 'Female', '1982-12-23', '', 'Ugandan', '', '', 'Busiro', 'Nasana', '', '', '1970-01-01', 'Sserywagi  shafik', '', '+256756006581', '', '', '', NULL, NULL, NULL, '$2y$10$gRlGYq1SAnQGJZmcioNHEOlhYFy7mye81e4YF/K1x1toymxUR5HKC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-04-29', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(412, 'Akankwasa  Zainab', 1, 1, '00239I0393456', 'Client', 0.00, '', '+256700309291', '', 'Female', '1986-01-05', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'safiina Kiiza', '', '+256702962701', '', '', '', NULL, NULL, NULL, '$2y$10$OCJOuhkqW9VUTAtc4IaLuutnrQGWLD5yhrQH8l5M2LgCd1IpURzfW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-03', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(413, 'Joan  Nantongo', 1, 1, '00239I0393522', 'Client', 0.00, '', '+256753240942', '', 'Female', '1992-12-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Milly Nalukwago', '', '+256759996600', '', '', '', NULL, NULL, NULL, '$2y$10$1Lgy8UA0s3cLwGCAHcPpFeGwJ3V40csFXDqgNj.L.sLVW2HYnu6g6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-04', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(414, 'Ms. Hussein  Afuwa', 1, 1, '00239I0393606', 'Client', 0.00, '', '+256703471051', '', 'Female', '1982-10-09', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nanteza Hasifa', '', '+256782940828', '', '', '', NULL, NULL, NULL, '$2y$10$CWeua2aGvO13q1tEhX/RAeV3b3/Y60uue5recEBcvkYVcYzVNs0tm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-06', '2023-10-12 17:25:23', '2023-10-12 17:25:23', NULL),
(415, 'Naiga  Miriam', 1, 1, '00239I0393608', 'Client', 0.00, '', '+256706854597', '', 'Female', '1997-11-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Wabomba Munil', '', '+256704977747', '', '', '', NULL, NULL, NULL, '$2y$10$sI71QAFPd30H9qRjfHWkGuSkU51dm3UW.f5Ak6uWEjq1PnjXazY9K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-06', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(416, 'Ssebuuma Ivan Kakinda', 1, 1, '00239I0393609', 'Client', 0.00, '', '+256756524055', '', 'Male', '1986-07-18', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Ssenoga Bonny', '', '+256758329578', '', '', '', NULL, NULL, NULL, '$2y$10$oSab2W96Ko9uI9M2rLaOU.Ic82pFBt.dOhSuUGAXbWP3n4poBcGcm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-06', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(417, 'Mrs. Kabasinguzi Safiina Kiiza', 1, 1, '00239I0393664', 'Client', 0.00, '', '+256702962702', '', 'Female', '1980-04-30', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mbabali Robert', '', '+256771286523', '', '', '', NULL, NULL, NULL, '$2y$10$SjHxqeOlOmG97K4CZAP/KO/1zEBv6765DtzfxsGlHGfKZMjFpCRRW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-07', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(418, 'Mrs. Florence  Namugenyi', 1, 1, '00239I0393692', 'Client', 0.00, '', '+256700232058', '', 'Female', '1946-05-25', '', 'Ugandan', '', '', 'Kiwatule', 'Nakawa', '', '', '1970-01-01', 'Nakiwu Jackie', '', '+256773337843', '', '', '', NULL, NULL, NULL, '$2y$10$A65TV5kHSt31TX0DIUJ.QuZUtbBWtB8NmbjWaQWCiwpaATqIX96X2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-09', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(419, 'Mrs. Agnes  Nankya', 1, 1, '00239I0393694', 'Client', 0.00, '', '+256773094143', '', 'Female', '1974-12-07', '', 'Ugandan', '', '', 'Kiwatule', 'Nakawa', '', '', '1970-01-01', 'Ssekito Micheal', '', '+256773094143', '', '', '', NULL, NULL, NULL, '$2y$10$ZpypY4YanJeuFbeIALEvve074PAmlbnSh01OJKQj3JOVubPctfkV2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-09', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(420, 'Mrs. Phionah  Ainembabazi', 1, 1, '00239I0393695', 'Client', 0.00, '', '+256787437417', '', 'Female', '1979-09-11', '', 'Ugandan', '', '', 'Kiwatule', 'Nakawa', '', '', '1970-01-01', 'Kanyesigye blessing', '', '+256760630168', '', '', '', NULL, NULL, NULL, '$2y$10$kkGAvyiGXIImFf7OVK/8OeFp.nIsxO6GULD5LVrKuRyExeMYQ09eq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-09', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(421, 'Betty Twinomugisha Kabihiirwa', 1, 1, '00239I0393750', 'Client', 0.00, '', '+256700320268', '', 'Female', '2022-05-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kayiga Richard', '', '+256783420704', '', '', '', NULL, NULL, NULL, '$2y$10$qzD.lc3W7MUhvcKOKtpdWuI2UMn1FOvbkc.wzK.oyBKPKqMWkbXfG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-10', '2023-10-12 17:25:24', '2023-10-12 17:25:24', NULL),
(422, 'Mr. Yazidi  Tenywa', 1, 1, '00239I0393756', 'Client', 0.00, '', '+256706703721', '', 'Male', '1990-12-23', '', 'Ugandan', '', '', 'Banda', 'Nakawa', '', '', '1970-01-01', 'Babirye suzan', '', '+256787757588', '', '', '', NULL, NULL, NULL, '$2y$10$ZhBgQ2dgaCOt.v85DDt3v.EA7JCRcIWj8VcrGE15NBzly.6pHqim6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-10', '2023-10-12 17:25:25', '2023-10-12 17:25:25', NULL),
(423, 'Ms. PHYLLIS ERINA NAAMA', 1, 1, '00239I0393795', 'Client', 0.00, '', '+256758906813', '', 'Female', '1988-09-03', '', 'Ugandan', '', '', 'Najjera', 'Najjera', '', '', '1970-01-01', 'ELVIS NIMARO', '', '+256757064896', '', '', '', NULL, NULL, NULL, '$2y$10$GaNe06Iw8cnYVwhnN3Kn5.oO.bkdDHQKrAdiMq1Xv46GumgE3Ppqa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-10', '2023-10-12 17:25:25', '2023-10-12 17:25:25', NULL),
(424, 'Mr. Paul  kizito', 1, 1, '00239I0393831', 'Client', 0.00, '', '+256752576361', '', 'Male', '1973-09-28', '', 'Ugandan', '', '', 'kira ward', 'Kira town council', '', '', '1970-01-01', 'kizito miracle', '', '+256752576361', '', '', '', NULL, NULL, NULL, '$2y$10$7ORAC9.P/pzttYP73vKZV.uUTyskAXmo7zL5LQUf75gyLCOn8fz8.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-11', '2023-10-12 17:25:25', '2023-10-12 17:25:25', NULL),
(425, 'Mrs. JOYCE  ESAETE', 1, 1, '00239I0393879', 'Client', 0.00, '', '+256701000628', '', 'Female', '1976-08-28', '', 'Ugandan', '', '', 'Mbuya', 'Nakawa', '', '', '1970-01-01', 'Odongo Elijah', '', '+256775267300', '', '', '', NULL, NULL, NULL, '$2y$10$YJo8QqhtyD/mAntF/TGQ8O7Yn6RT6uF.IKBuF4jTrJDzWRL643hra', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-11', '2023-10-12 17:25:25', '2023-10-12 17:25:25', NULL),
(426, 'Mrs. Bacurire Sylivia Mukasa', 1, 1, '00239I0393887', 'Client', 0.00, '', '+256751983549', '', 'Female', '1976-10-30', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kaweesa Charles', '', '+256706484619', '', '', '', NULL, NULL, NULL, '$2y$10$y2MeHn/rf4j9m4cjO8429.gh1tjVRtWWc/OyPbsytlVGAKbSCDWda', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-12', '2023-10-12 17:25:25', '2023-10-12 17:25:25', NULL),
(427, 'Mr. Samuel  Ssenyonga', 1, 1, '00239I0393890', 'Client', 0.00, '', '+256700485763', '', 'Male', '1994-09-07', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Nantongo sarah', '', '+256754461281', '', '', '', NULL, NULL, NULL, '$2y$10$Iv2myjWwIPCLYWsevfTDhumMPX6Laxh2A34TOZWe4TvNvsLFEGyju', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-12', '2023-10-12 17:25:26', '2023-10-12 17:25:26', NULL),
(428, 'Mrs. sarah  Nantongo', 1, 1, '00239I0393891', 'Client', 0.00, '', '+256754461281', '', 'Female', '1986-12-20', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Kabaale Godfrey', '', '+256751777063', '', '', '', NULL, NULL, NULL, '$2y$10$A6oxDcVqll2iuGiZ1/cQbOoa/02tEJYazatXSLgJChc3qVOkCEpV6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-12', '2023-10-12 17:25:26', '2023-10-12 17:25:26', NULL),
(429, 'Immaculate  Nayiga', 1, 1, '00239I0393894', 'Client', 0.00, '', '+256754723689', '', 'Female', '1972-06-12', '', 'Ugandan', '', '', 'Banda', 'Nakawa division', '', '', '1970-01-01', 'NABUMA FRAVIA', '', '+256751088741', '', '', '', NULL, NULL, NULL, '$2y$10$VGqp6hguDwfXyZMqUnF9Vumenbm5nwH1Ww4KytEVKBzzx2o0UM04y', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-12', '2023-10-12 17:25:26', '2023-10-12 17:25:26', NULL),
(430, 'Mrs. Namukuve Prossy Ibanda', 1, 1, '00239I0393934', 'Client', 0.00, '', '+256704581673', '', 'Female', '1979-10-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakku Florance', '', '+256705703093', '', '', '', NULL, NULL, NULL, '$2y$10$ydIM5iTJlXDZEt5dgHhyYuhj4WiPyMyZ31QuBwZz00JiKZU/jEMPa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-13', '2023-10-12 17:25:26', '2023-10-12 17:25:26', NULL),
(431, 'Ms. Kasifah  Nakagiri', 1, 1, '00239I0393935', 'Client', 0.00, '', '+256776978710', '', 'Female', '1976-01-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mugenyi John Paul', '', '+256776116538', '', '', '', NULL, NULL, NULL, '$2y$10$LDendFiWxa97zwuTCIavQed.fF0ZelytpGqp1tpAE0i0X29XipLkG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-13', '2023-10-12 17:25:26', '2023-10-12 17:25:26', NULL),
(432, 'Mrs. Agaba  Catherine', 1, 1, '00239I0394012', 'Client', 0.00, '', '+256751348093', '', 'Female', '1990-10-21', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kabasinguzi Safiina Kiiza', '', '+256702962702', '', '', '', NULL, NULL, NULL, '$2y$10$bC4WDSBY4pkEy9wmnSRnA.ovRzFz3R6xMEu.SrAIKUkKO94rYrJBO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-16', '2023-10-12 17:25:27', '2023-10-12 17:25:27', NULL),
(433, 'Mrs. Nakitto  Shamsa', 1, 1, '00239I0394058', 'Client', 0.00, '', '+256702936730', '', 'Female', '2022-05-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namukwaya Neoline', '', '+256703218478', '', '', '', NULL, NULL, NULL, '$2y$10$8JQojzGKNbdPwFzl5gVR9evUJnToLbmFESmJGl0XorJiFddkvuIby', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-17', '2023-10-12 17:25:27', '2023-10-12 17:25:27', NULL),
(434, 'Mr. Daniel  Mulondo', 1, 1, '00239I0394060', 'Client', 0.00, '', '+256703001230', '', 'Male', '1972-07-01', '', 'Ugandan', '', '', 'Buziga', 'Makindye division', '', '', '1970-01-01', 'Brian Nakinge', '', '+256703001230', '', '', '', NULL, NULL, NULL, '$2y$10$H0AfYG8d7gfutwfYtYjArOjR2PmOEEDzPgivQ7KNiLGustezY1S0m', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-17', '2023-10-12 17:25:27', '2023-10-12 17:25:27', NULL),
(435, 'Ms. Rosemary  Ajilo', 1, 1, '00239I0394062', 'Client', 0.00, '', '+256753487080', '', 'Female', '1975-08-22', '', 'Ugandan', '', '', 'Kireka Ward', 'Kira Town council', '', '', '1970-01-01', 'Nagudi Patience', '', '+256789089048', '', '', '', NULL, NULL, NULL, '$2y$10$COFYw9OmGOCx7GR9qBUyP.Q4y6vXJ1VM.kDKEu2hcOxH6AcqdPSU2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-17', '2023-10-12 17:25:27', '2023-10-12 17:25:27', NULL),
(436, 'Ms. Teddy  Bauza', 1, 1, '00239I0394117', 'Client', 0.00, '', '+256700959094', '', 'Female', '1982-08-08', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nambalirwa milly', '', '+256755930174', '', '', '', NULL, NULL, NULL, '$2y$10$t0cY4kup5Jxu2G8A7pDqcOIaAfFSIGIUyOn7gs8SkpGCwj5rVkbbO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-18', '2023-10-12 17:25:27', '2023-10-12 17:25:27', NULL),
(437, 'Paska  Awinjju', 1, 1, '00239I0394118', 'Client', 0.00, '', '+256757558352', '', 'Female', '1964-04-09', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Achirocan Fiona', '', '+256751030240', '', '', '', NULL, NULL, NULL, '$2y$10$ETclCF1oHtU/g9Y8n.Uxguh7c5vk4KIiddldjw2LIKb28W7L39r5O', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-18', '2023-10-12 17:25:28', '2023-10-12 17:25:28', NULL),
(438, 'Mr. Twesigye  Christopher', 1, 1, '00239I0394120', 'Client', 0.00, '', '+256704928150', '', 'Male', '1962-06-19', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mbabazi', '', '+256756897526', '', '', '', NULL, NULL, NULL, '$2y$10$DR/Ujg.kzng0LrpXgAi2f.ZN3TnmvdBfxB0OYnjtzrXUt1y65GDrG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-18', '2023-10-12 17:25:28', '2023-10-12 17:25:28', NULL),
(439, 'Mr. kindiki  Peter', 1, 1, '00239I0394122', 'Client', 0.00, '', '+256701231819', '', 'Male', '1985-01-14', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kamanyire zubair', '', '+256751974491', '', '', '', NULL, NULL, NULL, '$2y$10$ftBhty/XTkPF7/86q8mmfumvdShJ3IANGkQpJDr1n7YddrINyxWbO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-18', '2023-10-12 17:25:28', '2023-10-12 17:25:28', NULL),
(440, 'Rogers  Mawanda', 1, 1, '00239I0394201', 'Client', 0.00, '', '+256705243949', '', 'Male', '1989-11-11', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namuyanja Noreen', '', '+256705029909', '', '', '', NULL, NULL, NULL, '$2y$10$wiOaLKI2nBLu6GkWNbvrsuAwIFrwQOUVNNkMwfg39EXWrAy4iBUBq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-19', '2023-10-12 17:25:28', '2023-10-12 17:25:28', NULL),
(441, 'Mrs. NAMAGEMBE  MILLY', 1, 1, '00239I0394205', 'Client', 0.00, '', '+256705031924', '', 'Female', '2022-05-19', '', 'Ugandan', '', '', 'kireka ward', 'kiira town council', '', '', '1970-01-01', 'Babirye justine', '', '+256752672194', '', '', '', NULL, NULL, NULL, '$2y$10$2RNSogyPh.82du4VOrquB.xhuZAzo0EL95ScJTBIFPZgmjobuPx1e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-19', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(442, 'Mrs. Eve Julliet Ndugwa', 1, 1, '00239I0394218', 'Client', 0.00, '', '+256703808866', '', 'Female', '2022-05-19', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Ndugwa Ivan', '', '+256776844428', '', '', '', NULL, NULL, NULL, '$2y$10$A.ZMm5DPA./.L4SiG9A/VObH.2NVCUp.sWGcaGgK7pQWX.6uospRK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-19', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(443, 'Mr. Mugunya  Junior', 1, 1, '00239I0394697', 'Client', 0.00, '', '+256752032945', '', 'Male', '1983-03-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Ntege Wilber', '', '+256753931945', '', '', '', NULL, NULL, NULL, '$2y$10$SUEVZptwsbVXMYyWM5OS4.PELSyCsdLMgnbaWmIxb.m3RG/RW74Fa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-20', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(444, 'Ms. Harriet  Batuuka', 1, 1, '00239I0394699', 'Client', 0.00, '', '+256758635199', '', 'Female', '1984-10-16', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Bauza Teddy', '', '+256700959094', '', '', '', NULL, NULL, NULL, '$2y$10$y5.0pMdxL0TGsQyxVAF4.uv3IPxDW3MvkliygJKjwPgQ1VLRmk/Rq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-20', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(445, 'Ms. Sylivia  Ngonzi', 1, 1, '00239I0394746', 'Client', 0.00, '', '+256772474465', '', 'Female', '1962-09-29', '', 'Ugandan', '', '', 'Ntinda', 'Nakawa', '', '', '1970-01-01', 'Mitchell Najjingo', '', '+256772474465', '', '', '', NULL, NULL, NULL, '$2y$10$Cnid2/1orpAoRhVr/TFE6.DC.L3jy6p04vbcMa66vQh28f4S1EzJy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-21', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(446, 'Ms. Hasifa  Nawatti', 1, 1, '00239I0394803', 'Client', 0.00, '', '+256771184963', '', 'Female', '1987-03-25', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Olishaba Eva', '', '+256706472028', '', '', '', NULL, NULL, NULL, '$2y$10$dQmHVRWsF8atC4ngT5G8P.l6TqlZI4FiOLvvHOEh2usmk0ErCYdg.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-24', '2023-10-12 17:25:29', '2023-10-12 17:25:29', NULL),
(447, 'Ms. Benson  Abura', 1, 1, '00239I0394806', 'Client', 0.00, '', '+256770325664', '', 'Male', '1992-02-05', '', 'Ugandan', '', '', 'Ntinda', 'Ntinda', '', '', '1970-01-01', 'Apia norrah', '', '+256761201944', '', '', '', NULL, NULL, NULL, '$2y$10$0dRfW3iQ3zLGdWYkWshDM.tut7a2UsRNi5ORQUmObGdZ06ZKAY/FC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-24', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(448, 'Mrs. Namugalu Rashida Joyce', 1, 1, '00239I0394809', 'Client', 0.00, '', '+256752321024', '', 'Female', '1986-08-25', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kaweesa Charles', '', '+256706484619', '', '', '', NULL, NULL, NULL, '$2y$10$pdhRFBrKYIuRLozPZIeYe.pX35RzAbLsUAvCPi9NO5x8pCedktpce', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-24', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(449, 'Natuhwera  Gilbert', 1, 1, '00239I0394810', 'Client', 0.00, '', '+256702911057', '', 'Male', '1989-04-04', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Rukundo Brendah', '', '+256761124546', '', '', '', NULL, NULL, NULL, '$2y$10$3OQMCN2JNeQRmhHYH27rw.WNJz.3aBr5uCsSQgaBsalR6QzBdbggy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-24', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(450, 'Mrs. PEACE MONICA PIMER', 1, 1, '00239I0394821', 'Client', 0.00, '', '+256785199199', '', 'Female', '2022-05-24', '', 'Ugandan', '', '', 'Central Ward', 'Paidha TOwn Council', '', '', '1970-01-01', 'Ayela Isaac Toya', '', '+256776973344', '', '', '', NULL, NULL, NULL, '$2y$10$bjcgBjeYLxcPfOTlwD5WneH/rsohhDulICYYKkEc8ud3Z3vE8ys46', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-24', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(451, 'Mrs. Twinomugisha  Zaina', 1, 1, '00239I0394852', 'Client', 0.00, '', '+256709565328', '', 'Female', '1984-01-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakato Fatuma', '', '+256703568514', '', '', '', NULL, NULL, NULL, '$2y$10$qhicvanLDuYGvk5az8wMCexz5xvKaBxl6vSaiM0FV3gjIMAsRGwMS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-25', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(452, 'Mr. Mulindwa  Musa', 1, 1, '00239I0394895', 'Client', 0.00, '', '+256705720035', '', 'Male', '2022-05-26', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mpanga Ronald', '', '+256752631801', '', '', '', NULL, NULL, NULL, '$2y$10$SUgHRUxRaWxk7WN1o7AZSu17zrUu3s9iniqZZua9oA5k1kqyymcI2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-26', '2023-10-12 17:25:30', '2023-10-12 17:25:30', NULL),
(453, 'Ivan BBAALE KAYEMBA', 1, 1, '00239I0394896', 'Client', 0.00, '', '+256708738045', '', 'Male', '2022-05-26', '', 'Ugandan', '', '', 'MAKIDYE 11', 'MAKIDYE DIVISION', '', '', '1970-01-01', 'KAYEMBA IBRAHIM', '', '+256708738045', '', '', '', NULL, NULL, NULL, '$2y$10$DVLrj7gOxO0uuBu0sLCYB./27qLELPLkiBYHZSCeIEzu7i.huESBe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-26', '2023-10-12 17:25:31', '2023-10-12 17:25:31', NULL),
(454, 'KANEZA  EOZENSI', 1, 1, '00239I0394897', 'Client', 0.00, '', '+256783417326', '', 'Female', '1984-08-15', '', 'Ugandan', '', '', 'KAMWOKYA 2', 'KAMPALA CENTRAL', '', '', '1970-01-01', 'KYOMUHENDO MIDRESS', '', '+256757124644', '', '', '', NULL, NULL, NULL, '$2y$10$vUo/f2nO.wDCnc0wxeSlMe2QVPHlZXVRClMrLRaTl3ASoIYzggONy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-26', '2023-10-12 17:25:31', '2023-10-12 17:25:31', NULL),
(455, 'Andrew  Kaggwa', 1, 1, '00239I0394924', 'Client', 0.00, '', '+256706909527', '', 'Male', '1989-05-20', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakitende Phoebe', '', '+256702928356', '', '', '', NULL, NULL, NULL, '$2y$10$Fn94WrBoPvy.pYPA9VEAFeHbzQJvkEhpcwdQgZ1ZSVb2fV62dchuG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-27', '2023-10-12 17:25:31', '2023-10-12 17:25:31', NULL),
(456, 'Ms. Ritah  Nakabonge', 1, 1, '00239I0394949', 'Client', 0.00, '', '+256755529492', '', 'Female', '1990-08-08', '', 'Ugandan', '', '', 'Nakawa', 'Nakawa', '', '', '1970-01-01', 'Suulwe Joseph', '', '+256757940552', '', '', '', NULL, NULL, NULL, '$2y$10$q87jFjLexgjXsKGz8zcEVOSPqf7pPBjhezqV1dpafitkXc41D5rFi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-28', '2023-10-12 17:25:31', '2023-10-12 17:25:31', NULL),
(457, 'Mrs. Katende Amutuhire Rona', 1, 1, '00239I0394950', 'Client', 0.00, '', '+256775373348', '', 'Female', '1977-07-04', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Katushabe Doreen', '', '+256778569190', '', '', '', NULL, NULL, NULL, '$2y$10$LWv20oHVl83l//X6vCxR0udD79xRqgmzBIpigv0TO6kHosItwKI5a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-28', '2023-10-12 17:25:31', '2023-10-12 17:25:31', NULL);
INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(458, 'Mr. Mbabali  Robert', 1, 1, '00239I0394970', 'Client', 0.00, '', '+256702281598', '', 'Male', '1980-06-24', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Boonabaana Sophia', '', '+256788175621', '', '', '', NULL, NULL, NULL, '$2y$10$05X0.QeZcSVXPTsjrjC.juaIbJWWLZAJd6aqss2IZbNrfybzCRda6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-30', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(459, 'Mr. Nicholus  Luboyera', 1, 1, '00239I0395002', 'Client', 0.00, '', '+256701194064', '', 'Male', '1973-03-15', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga', '', '', '1970-01-01', 'Naava nakyaze sylvia', '', '+256757826579', '', '', '', NULL, NULL, NULL, '$2y$10$XuA1F6Gdxa2xvvjQEZpm6OHSYn4iU8lWG.B6RS.kZ1J7ry3oz8KW2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-31', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(460, 'Bakabulindi  Lawrence', 1, 1, '00239I0395003', 'Client', 0.00, '', '+256704610334', '', 'Male', '1989-10-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kasule Robert', '', '+256706096374', '', '', '', NULL, NULL, NULL, '$2y$10$ZdB9kWZuL/ttar6o2RH/5u5B.tdCH74JK3ZL2q90O1lI3es1.GoFi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-05-31', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(461, 'Mrs. MARIAM  JUWA', 1, 1, '00239I0395068', 'Client', 0.00, '', '+256778800059', '', 'Female', '1997-07-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'ATIM ELTON', '', '+256751812162', '', '', '', NULL, NULL, NULL, '$2y$10$PK4KLqBhwZf3POtq7jlyyOewUfEYvI1hEPRJVc95h2.cuWEUMLaN6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-01', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(462, 'Mrs. Kanakulya  Jonathan', 1, 1, '00239I0395111', 'Client', 0.00, '', '+256701843809', '', 'Male', '1983-07-20', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kwagala kevin', '', '+256753000923', '', '', '', NULL, NULL, NULL, '$2y$10$MABkgAbH2/tL/Ej4ra.8NuToBnXs4aAz0xXm6w2Mb6uwYY2/k62yS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-02', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(463, 'Mr. John  Ssegawa', 1, 1, '00239I0395211', 'Client', 0.00, '', '+256751196489', '', 'Male', '1989-05-10', '', 'Ugandan', '', '', 'Kira Ward', 'Kira Town Council', '', '', '1970-01-01', 'Sentongo Ernest', '', '+256777392226', '', '', '', NULL, NULL, NULL, '$2y$10$kEbjpn3U2fzlcffDREF9sOtKTnO3yn4PXsUrbiWb5U97TVTcKj46W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-04', '2023-10-12 17:25:32', '2023-10-12 17:25:32', NULL),
(464, 'Namuwonge  Bunjo', 1, 1, '00239I0395251', 'Client', 0.00, '', '+256708962551', '', 'Female', '2022-06-06', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nsubuga Ibrahim', '', '+256708962551', '', '', '', NULL, NULL, NULL, '$2y$10$TeuD1kPX2N0446a7snpeqOhJ0M8p9OCqdywY0YRgjqmifbSnaoV0O', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-06', '2023-10-12 17:25:33', '2023-10-12 17:25:33', NULL),
(465, 'Mr. Kaweesa  Charles', 1, 1, '00239I0395289', 'Client', 0.00, '', '+256706484619', '', 'Male', '1982-10-09', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Bacurire Sylivia', '', '+256751983549', '', '', '', NULL, NULL, NULL, '$2y$10$apmFwIM/NkMcaL/e.xN0luac8mDoxvuVOe4mZfGChqgik8RB6eXlK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-07', '2023-10-12 17:25:33', '2023-10-12 17:25:33', NULL),
(466, 'Nanyanzi  Christine', 1, 1, '00239I0395290', 'Client', 0.00, '', '+256755245507', '', 'Female', '1981-02-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakalo Mariam', '', '+256705582047', '', '', '', NULL, NULL, NULL, '$2y$10$oq86DLL1CqU8cr4M1sAjCu9Ja1iw2Z198eKWpUSpH/eZUo1iXyrn6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-07', '2023-10-12 17:25:33', '2023-10-12 17:25:33', NULL),
(467, 'Mr. Kabegambire  David', 1, 1, '00239I0395347', 'Client', 0.00, '', '+256772840595', '', 'Male', '1976-07-22', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Arinawe Jolly', '', '+256787927227', '', '', '', NULL, NULL, NULL, '$2y$10$5D4VMQLZY8zVGTnvkhiTkeADBQk3JUKu0W/I6IaIu5t5In08wNi5a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-08', '2023-10-12 17:25:33', '2023-10-12 17:25:33', NULL),
(468, 'Mrs. Apiding  Barbara', 1, 1, '00239I0395348', 'Client', 0.00, '', '+256701065216', '', 'Female', '1987-04-28', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Baijanebera fred', '', '+256752826850', '', '', '', NULL, NULL, NULL, '$2y$10$y5E7muBmgzQBdJccywwQ2OzeQDbWOjeLSopMe6QJnHkTxQabYk52e', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-08', '2023-10-12 17:25:34', '2023-10-12 17:25:34', NULL),
(469, 'Ms. Emily  Mukandutiye', 1, 1, '00239I0395351', 'Client', 0.00, '', '+256772323109', '', 'Female', '1974-10-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Ndyomugyenyi Noble', '', '+256783064214', '', '', '', NULL, NULL, NULL, '$2y$10$wyn1PZEoB0qkoSqQUQomGuy16km7ZdynK8cIcHXD7d/Gey9kra8pe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-08', '2023-10-12 17:25:34', '2023-10-12 17:25:34', NULL),
(470, 'Ms. Nangonzi Betty Kibombo', 1, 1, '00239I0395435', 'Client', 0.00, '', '+256754003847', '', 'Female', '2022-06-10', '', 'Ugandan', '', '', 'ntinda', '', '', '', '1970-01-01', 'Namugayi Veronica', '', '+256704556475', '', '', '', NULL, NULL, NULL, '$2y$10$XcA1.2CCLfSPPRJE0tWXOeu1tkz8BfBwgwrSgzrtXehVjsXtEGAzC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-10', '2023-10-12 17:25:34', '2023-10-12 17:25:34', NULL),
(471, 'Ms. Claire Kezia  Gitta', 1, 1, '00239I0395440', 'Client', 0.00, '', '+256772880967', '', 'Female', '1984-06-06', '', 'Ugandan', '', '', 'Kawempe', 'Kikaaya', '', '', '1970-01-01', 'Birungi Eva', '', '+256753969753', '', '', '', NULL, NULL, NULL, '$2y$10$xr5c.pwRujdJ1Iji11YuT.LYOsaFUp.PRX0WSKd3Lnc2nxf0fdedq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-10', '2023-10-12 17:25:34', '2023-10-12 17:25:34', NULL),
(472, 'Ms. Iredah  Nalubaale', 1, 1, '00239I0395442', 'Client', 0.00, '', '+256706353373', '', 'Female', '1972-02-28', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mukandutiye Emily', '', '+256772323109', '', '', '', NULL, NULL, NULL, '$2y$10$/t2.8Qg3zOOpFowGIGFvzOkVMogE0xb0M3mBopH7A2Ox/yypz0ZJy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-10', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(473, 'Ms. ClaireKezia  Gitta', 1, 1, '00239I0395444', 'Client', 0.00, '', '+256772880967', '', 'Female', '1984-06-10', '', 'Ugandan', '', '', 'Kikaaya', 'Kawempe', '', '', '1970-01-01', 'Birungi Eva', '', '+256753969753', '', '', '', NULL, NULL, NULL, '$2y$10$XqgX.j.wKYsqUCGk/yHVvOAQNwomn54MyuQ5YiVAiPt.NoyUkFTp.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-10', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(474, 'Mrs. Hadija  Nalumansi', 1, 1, '00239I0395509', 'Client', 0.00, '', '+256775240920', '', 'Female', '1991-12-18', '', 'Ugandan', '', '', 'Nakasero', 'Nakasero', '', '', '1970-01-01', 'Lucy Laker', '', '+256757320514', '', '', '', NULL, NULL, NULL, '$2y$10$iQ2KuZdpCm9UfIFRqGX.Q.jnMUiPYeDHPUAUjJ.Gu/tvQ8CjSRr1q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-11', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(475, 'Kyomugisha  Grace', 1, 1, '00239I0395513', 'Client', 0.00, '', '+256784612931', '', 'Female', '1969-03-08', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namugerwa Patricia', '', '+256761429750', '', '', '', NULL, NULL, NULL, '$2y$10$HS2ZzaDPTXWR1M4lL5/Bw.SdCXqPikzUKeTlWuWzR2KtDTn65m1b2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-11', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(476, 'Orishaba  Lovansi', 1, 1, '00239I0395514', 'Client', 0.00, '', '+256704640839', '', 'Female', '1989-05-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Hope Buzaare', '', '+256789226060', '', '', '', NULL, NULL, NULL, '$2y$10$s6dZ6FHbxe3jaXbvHVOuWuK6Kw3nGf0EYw8WLLkmBMpuzKclLCzA6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-11', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(477, 'Nakamya  Elizabeth', 1, 1, '00239I0395515', 'Client', 0.00, '', '+256752008871', '', 'Female', '1980-05-28', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Lubega Brian Israel', '', '+256700417919', '', '', '', NULL, NULL, NULL, '$2y$10$2HBphNB21piK/qmhnIqNTOiAXbZS5RlDAPNBT90z3JKFcbzVmwacW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-11', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(478, 'Mbabazi  Doreen', 1, 1, '00239I0395555', 'Client', 0.00, '', '+256778765336', '', 'Female', '1978-02-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Tusimwe John', '', '+256778765336', '', '', '', NULL, NULL, NULL, '$2y$10$aR8ugmPvM6mpyf3492COAOK74GgPM61tMTIAB506czW7aCX0e1Tca', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-13', '2023-10-12 17:25:35', '2023-10-12 17:25:35', NULL),
(479, 'Mr. Francis kizito Mubiru', 1, 1, '00239I0395557', 'Client', 0.00, '', '+256701724502', '', 'Male', '1974-04-14', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Natenge sylvia', '', '+256705342964', '', '', '', NULL, NULL, NULL, '$2y$10$o9nle.4RwRCjmCZ05tKOC.sKcKL7qslNAQtMRVgCMm/v/5NIx5x4.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-13', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(480, 'Akankwasa  Joan', 1, 1, '00239I0395649', 'Client', 0.00, '', '+256705115965', '', 'Female', '1997-12-15', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kizito Henery', '', '+256703050898', '', '', '', NULL, NULL, NULL, '$2y$10$yaFxwEy2xf7kG2Dc5..8cOoO4FK.hY0MdiirmMEl8dYQB88ZS5jum', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(481, 'Mr. Mbazira  muhammad', 1, 1, '00239I0395650', 'Client', 0.00, '', '+256705664727', '', 'Male', '2022-06-14', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kanakulya Jonathan', '', '+256701843809', '', '', '', NULL, NULL, NULL, '$2y$10$sCkGO.BsB66OLv3QbXDok.jGFMv1sZOvWfhSiXxzoo51KH55AndM.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(482, 'Mr. Zubairi  Lyada', 1, 1, '00239I0395658', 'Client', 0.00, '', '+256704462310', '', 'Male', '2022-06-14', '', 'Ugandan', '', '', 'BUKOTO II', 'NTINDA TRADING CENTER', '', '', '1970-01-01', 'Nabyela Edilisa', '', '+256755050902', '', '', '', NULL, NULL, NULL, '$2y$10$a9D/JzagHfE7qTq6FnePPePaE0ccxLu8I0A.4VaGryorGcSYB8YNu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(483, 'Zubairi  Lyada', 1, 1, '00239I0395660', 'Client', 0.00, '', '+256704462310', '', 'Male', '1990-06-01', '', 'Ugandan', '', '', 'Bukoto  11', 'Ntinda', '', '', '1970-01-01', 'Nabyela Edilisa', '', '+256755050902', '', '', '', NULL, NULL, NULL, '$2y$10$uB7JSLDTsFPR7t6JI69G..7Yoff4yxOJ7JIX2tLCDW7ODXHARjZo6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(484, 'Mr. Issa  Idilo', 1, 1, '00239I0395692', 'Client', 0.00, '', '+256759285070', '', 'Male', '1990-03-06', '', 'Ugandan', '', '', 'Kirirnya ward', 'Kira Town council', '', '', '1970-01-01', 'Lugula Gerald', '', '+256705202752', '', '', '', NULL, NULL, NULL, '$2y$10$/V2QvAXRhQmqhP7o5HCJI.FHeOZzUMZyVBTiUDU6580PnR0pDD8uC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:36', '2023-10-12 17:25:36', NULL),
(485, 'Mr. Kafeero  oliva', 1, 1, '00239I0395698', 'Client', 0.00, '', '+256702166849', '', 'Female', '1979-09-23', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nanfuka Namusisi', '', '+256755848885', '', '', '', NULL, NULL, NULL, '$2y$10$wQJtMI4OVb9f6dcUXCsEJO425Z7KtV6uTt48WGY2f.CmkTGbuaZBy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:37', '2023-10-12 17:25:37', NULL),
(486, 'Charles  Ssempa', 1, 1, '00239I0395702', 'Client', 0.00, '', '+256770794957', '', 'Male', '1986-07-02', '', 'Ugandan', '', '', 'Najjera', 'kira', '', '', '1970-01-01', 'Nabukomemeko Irene', '', '+256753916392', '', '', '', NULL, NULL, NULL, '$2y$10$piyOBYPL290U54425NC9D.2ybvtMYCe2YT7JE7GkWKisn3MPfvXkK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-14', '2023-10-12 17:25:37', '2023-10-12 17:25:37', NULL),
(487, 'Mrs. EUNICE RUTH KABIRA', 1, 1, '00239I0395717', 'Client', 0.00, '', '+256756829404', '', 'Female', '2022-06-15', '', 'Ugandan', '', '', 'Kira', 'Kyadondo', '', '', '1970-01-01', 'Tusiime Jenny', '', '+256773821944', '', '', '', NULL, NULL, NULL, '$2y$10$OHoSyblMbm2Awmww3v.1Yefk7TAV5uOXYJKXSARqxyPhxoHLFwzUa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:37', '2023-10-12 17:25:37', NULL),
(488, 'Nakatudde  Roset', 1, 1, '00239I0395718', 'Client', 0.00, '', '+256781794993', '', 'Female', '1980-09-10', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namuwonge Bunjo', '', '+256754242437', '', '', '', NULL, NULL, NULL, '$2y$10$RLjkOfSFw4oVL/wqYbT9huq/Ns2GhG7l8F8wDmdjuX5fy.8XDe/Fq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:37', '2023-10-12 17:25:37', NULL),
(489, 'Mr. Alexander  Kiiza', 1, 1, '00239I0395720', 'Client', 0.00, '', '+256773991562', '', 'Male', '2022-06-15', '', 'Ugandan', '', '', 'Najjera', 'Kira', '', '', '1970-01-01', 'Soki Nuria', '', '+256773991562', '', '', '', NULL, NULL, NULL, '$2y$10$cP3Il914ozXX/bclXvMuA.NXyL0eiFdnkAXObrLVQvWxFflv1x2LC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:37', '2023-10-12 17:25:37', NULL),
(490, 'Mr. Alexander  Kiiza', 1, 1, '00239I0395722', 'Client', 0.00, '', '+256773991562', '', 'Male', '2022-06-15', '', 'Ugandan', '', '', 'Najjera', 'Kira', '', '', '1970-01-01', 'Soki Nuria', '', '+256773991562', '', '', '', NULL, NULL, NULL, '$2y$10$mq.yBKfSmIKpjlQ9l4AuheOu7nzwJW9obkDpprUCY/42xdnDvCIWO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:38', '2023-10-12 17:25:38', NULL),
(491, 'Mr. Alexander  Kiiza', 1, 1, '00239I0395723', 'Client', 0.00, '', '+256773991562', '', 'Male', '1985-06-18', '', 'Ugandan', '', '', 'Najjera', 'Kira', '', '', '1970-01-01', 'Soki Nuria', '', '+256773991562', '', '', '', NULL, NULL, NULL, '$2y$10$9f2v5aX4EfivTJ0t/vWgJeMAGBFhmDwuyfQO8RHVfVgyL1PW2MbGe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:38', '2023-10-12 17:25:38', NULL),
(492, 'Mrs. Promise  Rominate', 1, 1, '00239I0395725', 'Client', 0.00, '', '+256706529549', '', 'Female', '1982-09-19', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga', '', '', '1970-01-01', 'Assume Robert', '', '+256758872297', '', '', '', NULL, NULL, NULL, '$2y$10$XTx7DMUw2yolV.8gnRt1vO.7AE68FZA3tRM6tPPZVd1OX5RlEixwy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:38', '2023-10-12 17:25:38', NULL),
(493, 'Mrs. Oliva  Kyomuhendo', 1, 1, '00239I0395726', 'Client', 0.00, '', '+256704159912', '', 'Female', '1982-02-02', '', 'Ugandan', '', '', 'Nakulabye', 'Rubaga', '', '', '1970-01-01', 'Nantale Jennifer', '', '+256759884635', '', '', '', NULL, NULL, NULL, '$2y$10$YZb31HKf1QxItj0Xmiq35eagcl9QQejJoMUX7.UtMnrYeibOib6sS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-15', '2023-10-12 17:25:38', '2023-10-12 17:25:38', NULL),
(494, 'Mr. kakumba Abdu Samulu', 1, 1, '00239I0395856', 'Client', 0.00, '', '+256742640426', '', 'Male', '1962-06-01', '', 'Ugandan', '', '', 'Ntinda', 'Bukoto', '', '', '1970-01-01', 'Nantale Kasifa', '', '+256750981197', '', '', '', NULL, NULL, NULL, '$2y$10$YagHBAADTgPmi.Oas4FrIe89gV0WClQoW9zgyzVKpRUFjlUH0YToG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-17', '2023-10-12 17:25:38', '2023-10-12 17:25:38', NULL),
(495, 'Kibukayire  Edith', 1, 1, '00239I0395857', 'Client', 0.00, '', '+256701370356', '', 'Female', '2022-06-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Edimond Mugisha', '', '+256701370356', '', '', '', NULL, NULL, NULL, '$2y$10$s1sIxRldyrdYW3/c5eRXc.uV/yStEb/Pa0CtuXac9SDt8xbI9YCUe', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-17', '2023-10-12 17:25:39', '2023-10-12 17:25:39', NULL),
(496, 'Mrs. Kibukayire  Edith', 1, 1, '00239I0395859', 'Client', 0.00, '', '+256701370356', '', 'Female', '1975-10-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Edimond', '', '+256701370356', '', '', '', NULL, NULL, NULL, '$2y$10$vruIXUeqEBqhJWrgGLpBde1yqBctrzrjrNhQ66W5aKqIoemTvO5/y', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-17', '2023-10-12 17:25:39', '2023-10-12 17:25:39', NULL),
(497, 'Mrs. oyella brenda obina', 1, 1, '00239I0395860', 'Client', 0.00, '', '+256777194579', '', 'Female', '2022-06-17', '', 'Ugandan', '', '', 'seeta ward', 'goma', '', '', '1970-01-01', 'john obina', '', '+256784217514', '', '', '', NULL, NULL, NULL, '$2y$10$h8b2Kp3xu/uDSaoIht40zewImF2ZMIHiVCQ2P.dKml2P25LumE7qy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-17', '2023-10-12 17:25:39', '2023-10-12 17:25:39', NULL),
(498, 'Mr. JEAN PAUL MOHAMED MAMPASIOUTTRA', 1, 1, '00239I0396365', 'Client', 0.00, '', '+256777228585', '', 'Male', '1978-09-10', '', 'Ugandan', '', '', 'NAGURU', 'NAKAWA', '', '', '1970-01-01', 'JOSEPH LUKULA', '', '+256702093253', '', '', '', NULL, NULL, NULL, '$2y$10$75lSLP5yym4ykf9lf6Dnw.brPlr9rTGSF1/1Dcciv88lG8LU8cj4O', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-20', '2023-10-12 17:25:39', '2023-10-12 17:25:39', NULL),
(499, 'Mrs. Birungi  Florence Kate', 1, 1, '00239I0396402', 'Client', 0.00, '', '+256783555638', '', 'Female', '1964-05-23', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mugenyi Reuben', '', '+256700633903', '', '', '', NULL, NULL, NULL, '$2y$10$oS3zCd6lkaEO0rVo6.8tJ.OcazHGzlCNov1KPkejs8NEanhe2nGf.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-21', '2023-10-12 17:25:40', '2023-10-12 17:25:40', NULL),
(500, 'Mrs. Twinomugisha  Barbara', 1, 1, '00239I0396479', 'Client', 0.00, '', '+256701072791', '', 'Female', '1975-10-26', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kabegambire David', '', '+256772840595', '', '', '', NULL, NULL, NULL, '$2y$10$IS5VS7tj/PCgiDr2T57vp.sFa1uQecSjxe5.g5BBLwrlzbdfc5V/i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-22', '2023-10-12 17:25:40', '2023-10-12 17:25:40', NULL),
(501, 'Mrs. Namuwonge  Agnes', 1, 1, '00239I0396480', 'Client', 0.00, '', '+256787290264', '', 'Female', '2022-06-22', '', 'Ugandan', '', '', 'nakawa', '', '', '', '1970-01-01', 'katahoire phillip', '', '+256782561867', '', '', '', NULL, NULL, NULL, '$2y$10$7ApUMEvCA44jSCr2SSIfs.17gxP0AG3K9HiBxGryvW1/nd7071tUG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-22', '2023-10-12 17:25:40', '2023-10-12 17:25:40', NULL),
(502, 'Mr. Herny  Kizito', 1, 1, '00239I0396484', 'Client', 0.00, '', '+256761224508', '', 'Male', '1989-06-16', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nalongo mikaga', '', '+256700330083', '', '', '', NULL, NULL, NULL, '$2y$10$JLqeg7ndJIKP3joOkflvyOeEewBFFtDBK/wCMu6bziueL5JjIthSO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-22', '2023-10-12 17:25:40', '2023-10-12 17:25:40', NULL),
(503, 'Mr. Fred  Aleni', 1, 1, '00239I0396555', 'Client', 0.00, '', '+256787150840', '', 'Male', '1990-07-14', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Driciu Lilian', '', '+256775705905', '', '', '', NULL, NULL, NULL, '$2y$10$qpxC/SZ/tCxV0/BdSMql9u.pKBHHHL8UB3Gp9i.X.WRWP.RLewu.y', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-23', '2023-10-12 17:25:40', '2023-10-12 17:25:40', NULL),
(504, 'Mr. Calvin pius  Ojakol', 1, 1, '00239I0396556', 'Client', 0.00, '', '+256705991058', '', 'Male', '1990-06-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Ojakol Daniel', '', '+256774451058', '', '', '', NULL, NULL, NULL, '$2y$10$QZvwSYg5AI5TQVET6VIRYO56X19iDj6mi/wKn.tY18m1QepUqMqo.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-23', '2023-10-12 17:25:41', '2023-10-12 17:25:41', NULL),
(505, 'Arigye  Sarah', 1, 1, '00239I0396605', 'Client', 0.00, '', '+256758939600', '', 'Female', '1988-05-05', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nantale Jenipher', '', '+256759884635', '', '', '', NULL, NULL, NULL, '$2y$10$tKrecX2bP.6agkXrJbXw4uIepehoqC0UWGUA/lZ8HUokOuxN2IvKa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-24', '2023-10-12 17:25:41', '2023-10-12 17:25:41', NULL),
(506, 'Mrs. Julian  Nyirahirwa', 1, 1, '00239I0396673', 'Client', 0.00, '', '+256777028467', '', 'Female', '1991-10-27', '', 'Ugandan', '', '', 'Najjera', 'NajjerA', '', '', '1970-01-01', 'Nabowa Rovicer Faith', '', '+256777075791', '', '', '', NULL, NULL, NULL, '$2y$10$hbs3tCzaO4/bKG0SlTU.EOkBgUCuULvkgqUb6d4R4QGUIRYTDtiwO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-25', '2023-10-12 17:25:41', '2023-10-12 17:25:41', NULL),
(507, 'Mr. Ronald  Sserumaga', 1, 1, '00239I0396687', 'Client', 0.00, '', '+256705075516', '', 'Female', '1994-08-04', '', 'Ugandan', '', '', 'kawaala', 'Rubaga', '', '', '1970-01-01', 'Nantongo sarah', '', '+256754461281', '', '', '', NULL, NULL, NULL, '$2y$10$x8BYigAIL5/xMoX6e.M6F.zRpwNlDEZJf.knu38Bq4aRhpwBkmyra', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-06-27', '2023-10-12 17:25:41', '2023-10-12 17:25:41', NULL),
(508, 'Mr. JOHN BOSCO TUMWEBAZE', 1, 1, '00239I0397032', 'Client', 0.00, '', '+256772625029', '', 'Male', '1977-02-15', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'NAFUNA  HARRIET', '', '+256700725502', '', '', '', NULL, NULL, NULL, '$2y$10$T3JItxrSDdXP3pl6M1hPRuKGf9rlLEAvu.FSH.NEMmuggngI9D7Ta', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-07', '2023-10-12 17:25:41', '2023-10-12 17:25:41', NULL),
(509, 'Mr. Kizito Luwagga  Moses', 1, 1, '00239I0397038', 'Client', 0.00, '', '+256752447733', '', 'Male', '2022-07-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namukwaya Neoline', '', '+256703218478', '', '', '', NULL, NULL, NULL, '$2y$10$cEjnr85EPVdym4fUhE9l8uQhFdcqILneY.RQlf3j5ahVjFbMrYiiy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-07', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(510, 'Mrs. Birungi Justine  Sewaya', 1, 1, '00239I0397040', 'Client', 0.00, '', '+256784339325', '', 'Female', '2022-07-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Twesigye Chistopher', '', '+256774254750', '', '', '', NULL, NULL, NULL, '$2y$10$63pdiImehBFpC6zAvCF9Xeo/vHUoSWB7vv3qpJmQBznE0b1Jzjyp6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-07', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(511, 'Mrs. Kemilembe  Winnie', 1, 1, '00239I0397087', 'Client', 0.00, '', '+256757934663', '', 'Female', '2022-07-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Bamwesigye  Meikiedes', '', '+256756936612', '', '', '', NULL, NULL, NULL, '$2y$10$h2R7nQVIumnMAlSrE4vILudmBJU08rk/X6jUXvdqaDsZ7wj3CNoyO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-07', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(512, 'Mrs. Grace Ikoru sonia', 1, 1, '00239I0397122', 'Client', 0.00, '', '+256705110922', '', 'Female', '2022-07-11', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kalinda edriisa', '', '+256701634516', '', '', '', NULL, NULL, NULL, '$2y$10$qjd3kOADR4TqOiAc9BJ6DOc7SPt1ml1fSCSGtq55Vqc/3b2iYuWQi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-11', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(513, 'Mr. Joseph  Ssenduli', 1, 1, '00239I0397166', 'Client', 0.00, '', '+256785941077', '', 'Male', '2022-07-12', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Okwalinga Isaac', '', '+256755945073', '', '', '', NULL, NULL, NULL, '$2y$10$.UZDAdmohgMBt.kJccDhrehEAN9PJaeRK1rOHiOVtThCyPjaqZtJu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-12', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(514, 'Nyanvura  Maureen', 1, 1, '00239I0397207', 'Client', 0.00, '', '+256701402571', '', 'Female', '1982-06-02', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Hadijja Kanyangu', '', '+256709392500', '', '', '', NULL, NULL, NULL, '$2y$10$7ovVh4TwFS0NrWVwSKOwW.GAxjv9EcL/ewTMLXHA8208vXD0Pukdm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-13', '2023-10-12 17:25:42', '2023-10-12 17:25:42', NULL),
(515, 'Muliika  Bruno', 1, 1, '00239I0397275', 'Client', 0.00, '', '+256787501823', '', 'Male', '1968-11-23', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nyonyintono Richard', '', '+256756584072', '', '', '', NULL, NULL, NULL, '$2y$10$dhN6T9KlsI9H7Rs3HUijSuvv2vYWrHh1V67i6scBkcuyWoDXfOtnq', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-14', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(516, 'Mrs. christine  Namuwonge', 1, 1, '00239I0397276', 'Client', 0.00, '', '+256703593018', '', 'Female', '1987-11-12', '', 'Ugandan', '', '', 'Nakulabye', 'Rubanga', '', '', '1970-01-01', 'Ssegirinya John', '', '+256756662020', '', '', '', NULL, NULL, NULL, '$2y$10$xyzNz4HkTt0dqzKPifcqFeBWs0e2JsrKQ.xPjyPIVXjsRNTPhC26i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-14', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(517, 'Mr. Erick  Gaalya', 1, 1, '00239I0399111', 'Client', 0.00, '', '+256705225545', '', 'Male', '2022-07-18', '', 'Ugandan', '', '', 'Kireka', 'kireka', '', '', '1970-01-01', 'Agatha Pyambogo', '', '+256706398769', '', '', '', NULL, NULL, NULL, '$2y$10$fAHrgmj5jeQQMsXj4.7BluhLN2OSMVJwoEI7gmj3PCBpZBL1FpIVS', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-18', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(518, 'Zula  Nandudu', 1, 1, '00239I0399188', 'Client', 0.00, '', '+256702449329', '', 'Female', '1989-05-25', '', 'Ugandan', '', '', 'seeta ward', 'goma division', '', '', '1970-01-01', 'Nalule Magret', '', '+256759640594', '', '', '', NULL, NULL, NULL, '$2y$10$yNVxseOJPjLi7mQPKt1gz.jM.5CjQrayPg14cDa6LJkywfWabK2Za', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-20', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(519, 'Mr. Julius  Behangana', 1, 1, '00239I0399241', 'Client', 0.00, '', '+256759559612', '', 'Male', '1971-01-15', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nandudu zula', '', '+256702449329', '', '', '', NULL, NULL, NULL, '$2y$10$Y1vhQMQG9sF7DOSnkMIUVeiXXIi/lmaxmPW4WYk3d4S2.LztX3GFO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-21', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(520, 'Bisirikkwa  Stella', 1, 1, '00239I0399245', 'Client', 0.00, '', '+256788928112', '', 'Female', '1992-02-02', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Babirye Sarah', '', '+256704467326', '', '', '', NULL, NULL, NULL, '$2y$10$n0YI/0j7bjduEjOsgub.de0ieUW02MUMmEUV6mh6A/YxG8g1E6oaC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-21', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(521, 'Nambuya  Susan', 1, 1, '00239I0399339', 'Client', 0.00, '', '+256753159675', '', 'Female', '1984-06-11', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Munyinyi', '', '+256772860284', '', '', '', NULL, NULL, NULL, '$2y$10$JjNEkgj/viApUQxc87sxJOTQLtyv0jgpaSbm/0aDQ.I06E2Ca6gb.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-23', '2023-10-12 17:25:43', '2023-10-12 17:25:43', NULL),
(522, 'Mrs. Atuhaire  Catherine', 1, 1, '00239I0399362', 'Client', 0.00, '', '+256708567037', '', 'Female', '1985-11-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Twesigye christopher', '', '+256704928150', '', '', '', NULL, NULL, NULL, '$2y$10$0WtIFSFRSbzPYpxPqoRUE..Wexq2XfGuG/6HR28ZJ9Xvy3tcu/D3i', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-25', '2023-10-12 17:25:44', '2023-10-12 17:25:44', NULL),
(523, 'Mrs. Deborah Mirembe Ganyana', 1, 1, '00239I0399578', 'Client', 0.00, '', '+256702713362', '', 'Female', '2022-07-26', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Mubiru Joseph', '', '+256751900373', '', '', '', NULL, NULL, NULL, '$2y$10$uaaYkMmhJiRzm41g9QkUjuR1ILWtmnqBYsVLJ4ooIp.9rqnPCpA7u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-26', '2023-10-12 17:25:44', '2023-10-12 17:25:44', NULL),
(524, 'Mrs. sabiano  christine', 1, 1, '00239I0399647', 'Client', 0.00, '', '+256787081403', '', 'Female', '2022-07-28', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nazziwa Annet', '', '+256788895013', '', '', '', NULL, NULL, NULL, '$2y$10$qyYGNjtdz9MmzuXsKySYEO3ibhvXHPl0qrOM2bMke.xjf20mIMEha', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-28', '2023-10-12 17:25:44', '2023-10-12 17:25:44', NULL),
(525, 'Mr. Emmanuel  kawalya', 1, 1, '00239I0399648', 'Client', 0.00, '', '+256752527514', '', 'Male', '2022-07-28', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kimbowa jamiru', '', '+256708991412', '', '', '', NULL, NULL, NULL, '$2y$10$iFu0u48XhGtR6dZhSfo.3uw5.lD4n1.JfxEChAxFSHTPRFiITtP2a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-28', '2023-10-12 17:25:44', '2023-10-12 17:25:44', NULL),
(526, 'Natukunda  Charity', 1, 1, '00239I0399649', 'Client', 0.00, '', '+256784855633', '', 'Female', '1982-01-09', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'idd musana', '', '+256757554487', '', '', '', NULL, NULL, NULL, '$2y$10$UQcxKzO4uzh0nWqooO5psO6qQBfO.qVqRErc8Q5jTihIseZCQuoqG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-28', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(527, 'Mrs. Nalunga Hanifah Florence', 1, 1, '00239I0399687', 'Client', 0.00, '', '+256776671266', '', 'Female', '1983-07-04', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kasirye Lawrence Byekwaso', '', '+256772575732', '', '', '', NULL, NULL, NULL, '$2y$10$PSUj6/lK/dDuyVjthdZK3OJQ9LRx2uJzhJJrJILG5Klha0kYdMQ3C', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-29', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(528, 'Mr. Evaristo  Muhangi', 1, 1, '00239I0399707', 'Client', 0.00, '', '+256783469886', '', 'Male', '1978-02-20', '', 'Ugandan', '', '', 'Makindye', 'CENTRAL', '', '', '1970-01-01', 'Atwiine Christopher', '', '+256700803744', '', '', '', NULL, NULL, NULL, '$2y$10$/Y3g4UXezeG7KppK6LzpO.TrqX3c22zyj2GKYsXrDuadxzRwSlPkG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-07-29', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(529, 'Mrs. Namayanja  Betty', 1, 1, '00239I0399886', 'Client', 0.00, '', '+256755064085', '', 'Female', '1981-07-14', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kiviri Haruna', '', '+256701523165', '', '', '', NULL, NULL, NULL, '$2y$10$NLV4ps1W5LGlxyMiU9Db8.I1SPs4TUQuS7xNU2y7oBOinPFnSYkcO', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-03', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(530, 'Mrs. Nabukenya Maria Maldrine', 1, 1, '00239I0399981', 'Client', 0.00, '', '+256706349137', '', 'Female', '1973-07-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Byarugaba Jonathan', '', '+256706581409', '', '', '', NULL, NULL, NULL, '$2y$10$MA.xosfzuFnzCtrzKJL96.YXtSqm1AzEUv1jskyI3GhCWu/UL.MLa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-05', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(531, 'Mr. Kasibante  Patrick', 1, 1, '00239I0400012', 'Client', 0.00, '', '+256705973234', '', 'Male', '1977-07-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kaddu Francis', '', '+256702112209', '', '', '', NULL, NULL, NULL, '$2y$10$okQO2cHfDLEqckmN66MYu.A5Jj0pnZAVT1TxZI9aRLAVHYuOFVLqu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-06', '2023-10-12 17:25:45', '2023-10-12 17:25:45', NULL),
(532, 'Mrs. Nalukwago  Joyce', 1, 1, '00239I0400135', 'Client', 0.00, '', '+256753734735', '', 'Female', '1971-11-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Birungi Justine', '', '+256784339325', '', '', '', NULL, NULL, NULL, '$2y$10$iD0UbIjKQ1ScGltN/NhTM.hoFUcIFQwvXo3FQD2pwDsWQDe.R3imK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-10', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(533, 'Mrs. sylvia  Namakula', 1, 1, '00239I0400172', 'Client', 0.00, '', '+256705438690', '', 'Female', '1969-12-12', '', 'Ugandan', '', '', 'kasubi', 'lubaga', '', '', '1970-01-01', 'Nantale jenifer', '', '+256759884635', '', '', '', NULL, NULL, NULL, '$2y$10$K86.biVi8Ag581S5PrhhmOHDkKHVwWYrQVWRIrwnpEkQnB9djqhY6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-11', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(534, 'Muhaye  Betty', 1, 1, '00239I0400173', 'Client', 0.00, '', '+256755333493', '', 'Female', '1972-01-11', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namaroma Majeri', '', '+256757067776', '', '', '', NULL, NULL, NULL, '$2y$10$a2O9Z1QxZ08GieBfUi/81emO63pbmVmne7SvFZmXq.egE42oa/PIu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-11', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(535, 'Mr. Mugisha  Bernard', 1, 1, '00239I0400300', 'Client', 0.00, '', '+256703734839', '', 'Male', '2022-08-12', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namukwaya neoline', '', '+256703218478', '', '', '', NULL, NULL, NULL, '$2y$10$J9/KyoUA8.lJ/Wg6Za00M.nKRuEzCpqdaYhnwnJas4IWX3g9r5/su', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-12', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(536, 'Mrs. Nakazibwe  Annet', 1, 1, '00239I0400301', 'Client', 0.00, '', '+256758841942', '', 'Female', '1984-12-12', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nabiryo Lydia', '', '+256706161675', '', '', '', NULL, NULL, NULL, '$2y$10$BIeCQ5eTqWE0Ek5e79KiPewiljBTTskvxRWjmCksKcCrTxWy54hXC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-12', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(537, 'Mr. Mabirizi  Abbas', 1, 1, '00239I0400302', 'Client', 0.00, '', '+256740931184', '', 'Male', '2022-08-12', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namayanja Milly', '', '+256708045748', '', '', '', NULL, NULL, NULL, '$2y$10$gHhZkn.l9zGDfej5SK66yOex5Tz25Ow5Mk5p6BJtDom/9uHY.9Or6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-12', '2023-10-12 17:25:46', '2023-10-12 17:25:46', NULL),
(538, 'Mrs. proscovia ssekirembe Nabyeyo', 1, 1, '00239I0400415', 'Client', 0.00, '', '+256758171662', '', 'Female', '1983-11-12', '', 'Ugandan', '', '', 'kayunga', 'kayunga', '', '', '1970-01-01', 'Nanyonjo oliver', '', '+256709454723', '', '', '', NULL, NULL, NULL, '$2y$10$Q5wwge2M77tvO2M5CJRqz.rrT.cff6P5z3MpK80/2g1yVszKItPIC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-16', '2023-10-12 17:25:47', '2023-10-12 17:25:47', NULL),
(539, 'Mrs. Ester  Nalumansi', 1, 1, '00239I0400416', 'Client', 0.00, '', '+256700927558', '', 'Female', '1978-04-09', '', 'Ugandan', '', '', 'kayunga', 'kayunga', '', '', '1970-01-01', 'Nabyeyo', '', '+256758171662', '', '', '', NULL, NULL, NULL, '$2y$10$8uwX/tSrQXWml6DxNP8uCusFFaNhqnEG8vB.AZgsz./0DHBMcbWR.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-16', '2023-10-12 17:25:47', '2023-10-12 17:25:47', NULL),
(540, 'Mpagi  Muhammed', 1, 1, '00239I0400417', 'Client', 0.00, '', '+256702845475', '', 'Male', '1993-07-31', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nsereko Joseph', '', '+256754084083', '', '', '', NULL, NULL, NULL, '$2y$10$XT/hZ4yjBnrBxZzhph8WSeIeJ0axhTPUHoCQi0kQ9irfJMLxh6O8u', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-16', '2023-10-12 17:25:47', '2023-10-12 17:25:47', NULL),
(541, 'Mr. Seruyombya  peter', 1, 1, '00239I0400446', 'Client', 0.00, '', '+256706458629', '', 'Male', '1980-10-24', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nabatanzi     Jane', '', '+256752120503', '', '', '', NULL, NULL, NULL, '$2y$10$fHt1tijDQyvEjWP7JS6DMu7/7rTzfB5ne7Zh7cMaHcaFw1rvMdr/K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-16', '2023-10-12 17:25:47', '2023-10-12 17:25:47', NULL),
(542, 'Mrs. Semugera  Francis', 1, 1, '00239I0400466', 'Client', 0.00, '', '+256758797885', '', 'Male', '2022-08-17', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'kizito Moses', '', '+256752447733', '', '', '', NULL, NULL, NULL, '$2y$10$V0WE/ygJEG6yGCUTkk8hY.DH8rsSRT3wmrC2O/lQ1FL9bL0pJqfYK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-17', '2023-10-12 17:25:47', '2023-10-12 17:25:47', NULL),
(543, 'Mrs. Nyembo  Tausi', 1, 1, '00239I0403230', 'Client', 0.00, '', '+256761040874', '', 'Female', '1989-02-02', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakato Aidah', '', '+256708831360', '', '', '', NULL, NULL, NULL, '$2y$10$STvWeGmioa5hLDUzEwh3dOUzupeR15Srh1S.jO.UyfqrpOkZB9z1y', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-19', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(544, 'Mawaggali  Edward', 1, 1, '00239I0403231', 'Client', 0.00, '', '+256700719770', '', 'Male', '1997-08-18', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Namutebi Deborah', '', '+256702844098', '', '', '', NULL, NULL, NULL, '$2y$10$.Z66VJab0WQTii0WuH6Yu.cigrlCgiPuUqryTT5bXuOSOKTBhZf56', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-19', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(545, 'Mr. SAMUEL  MBULAMUKUNGI', 1, 1, '00239I0404666', 'Client', 0.00, '', '+256780880782', '', 'Male', '1991-03-03', '', 'Ugandan', '', '', 'BUMIZA', 'KIBUBU', '', '', '1970-01-01', 'MANDELA IVAN', '', '+256752067719', '', '', '', NULL, NULL, NULL, '$2y$10$7FTK/m9Fg2N9zptoLzEt/Ok27qCu3Stu0dETqePnSnKcsoPGrVFZ2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-24', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(546, 'Nabwire  Caroline', 1, 1, '00239I0410444', 'Client', 0.00, '', '+256774372130', '', 'Female', '1983-10-20', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nafula morine', '', '+256787726697', '', '', '', NULL, NULL, NULL, '$2y$10$tUsj.WtjKnptLTSBgP1GT.apJ1Q.zitmJsPT4L.mQ.ROoU2BYhzD6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-29', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(547, 'Mrs. Joyce Katongole Namukasa', 1, 1, '00239I0410485', 'Client', 0.00, '', '+256776954138', '', 'Female', '1965-11-15', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nalubaale Iredah', '', '+256706353373', '', '', '', NULL, NULL, NULL, '$2y$10$BuHey6mXrfvQ2RZlZKH7SeTHTBy5HUppWG9VUFSZRno8nbwk4rsvC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-08-30', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(548, 'FORMEDA MEDICAL LIMITED', 1, 1, '00239X0012933', 'Client', 0.00, '', '+256756062232', '', '', '1970-01-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', '', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$9RzX9Q./IF2EyVeCJyP63eW0PMqOurtqfclFJDDxS3UkyYX8iP5Iy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-02', '2023-10-12 17:25:48', '2023-10-18 22:20:52', '2023-10-18 22:20:52'),
(549, 'Mrs. Kyalimpa Kigongo Mary', 1, 1, '00239I0412100', 'Client', 0.00, '', '+256753812078', '', 'Female', '1976-12-13', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nanjala Emilly', '', '+256704178540', '', '', '', NULL, NULL, NULL, '$2y$10$hW19R4t9ZfdtWDBRmLt5Re/LwQ2GkT7cK2E2QAvLwb3YBt8oGXcz.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-08', '2023-10-12 17:25:48', '2023-10-12 17:25:48', NULL),
(550, 'Mrs. Namulema Gasuza Annet', 1, 1, '00239I0412359', 'Client', 0.00, '', '+256704740727', '', 'Female', '2021-10-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Jjunju Joseph', '', '+256755557266', '', '', '', NULL, NULL, NULL, '$2y$10$Aryx5RTUPDbzwcH7y8GdaeU6ZAaVeO/z2iVARYyPubPBFgdJorNFC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-13', '2023-10-12 17:25:49', '2023-10-12 17:25:49', NULL),
(551, 'Mr. Lugalambi  Bonney', 1, 1, '00239I0413338', 'Client', 0.00, '', '+256774855976', '', 'Male', '1972-07-20', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Katende Ronah', '', '+256773373348', '', '', '', NULL, NULL, NULL, '$2y$10$JVfaZvsrd/Chz3tjLfnRJeGaDwX74RziAR62v5zgmrf27px1oyUca', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-15', '2023-10-12 17:25:49', '2023-10-12 17:25:49', NULL),
(552, 'Nassozi Phyllis Kitooke', 1, 1, '00239I0413340', 'Client', 0.00, '', '+256772451742', '', 'Female', '1973-12-19', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nansamba florence', '', '+256756846234', '', '', '', NULL, NULL, NULL, '$2y$10$7On2OTyQ1X5GeizyAFOIkulUTjXSTBOwot2Z4qzZtkC8R.pctgEVu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-15', '2023-10-12 17:25:49', '2023-10-12 17:25:49', NULL),
(553, 'Aboot Mary Betty', 1, 1, '00239I0413538', 'Client', 0.00, '', '+256700728219', '', 'Female', '1989-04-29', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Akiteng Phoebe', '', '+256702551324', '', '', '', NULL, NULL, NULL, '$2y$10$jnhIIu6V70rNetlvp6Q9U..b6ADfbPQtjrkrpIfPgBezGa3tsd4E6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-17', '2023-10-12 17:25:49', '2023-10-12 17:25:49', NULL),
(554, 'Nalwoga  Sarah', 1, 1, '00239I0413539', 'Client', 0.00, '', '+256782104135', '', 'Female', '1980-09-05', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'mawaggali Edward', '', '+256700719770', '', '', '', NULL, NULL, NULL, '$2y$10$SGvuOK4fpqS4wmeUAgYLwuC2FlLHzixso5q1Ud0g/t5K16VyOCC/a', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-17', '2023-10-12 17:25:49', '2023-10-12 17:25:49', NULL),
(555, 'Mrs. Ainomugisha  Hawah', 1, 1, '00239I0413615', 'Client', 0.00, '', '+256704719440', '', 'Female', '1984-03-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Akampulira Amos', '', '+256784750230', '', '', '', NULL, NULL, NULL, '$2y$10$rE0M4.7rPQhVCDV1CH5e4uhtbFsXIRl58pJpjUCLPW./04hZFH/7S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-20', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(556, 'Ms. UCHUNGI  SAUDA', 1, 1, '00239I0414919', 'Client', 0.00, '', '+256784293950', '', 'Female', '1976-09-01', '', 'Ugandan', '', '', 'nakawa', 'nakawa', '', '', '1970-01-01', 'Uchungi', '', '+256700949913', '', '', '', NULL, NULL, NULL, '$2y$10$Fkaty04p.x572/Vb2SWTreP24IAAnEBctGm8pLnvQS5ZmKQPPbpQm', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-30', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(557, 'Mrs. Beatrice Lucy Lamunu', 1, 1, '00239I0414924', 'Client', 0.00, '', '+256752720502', '', 'Female', '1963-01-08', '', 'Ugandan', '', '', 'Ntinda', 'Nakawa', '', '', '1970-01-01', 'Emily Mara', '', '+256704023677', '', '', '', NULL, NULL, NULL, '$2y$10$GI69WqCLj.4iYGtZPpCePumEQ1GxO1xa5HbGhd6n1uCLkLymiuie6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-09-30', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(558, 'Mrs. Nabbosa  Sylvia', 1, 1, '00239I0417660', 'Client', 0.00, '', '+256751860158', '', 'Female', '1976-11-11', '', 'Ugandan', '', '', 'Kasubi', 'Lubaga', '', '', '1970-01-01', 'Kusiima Annet', '', '+256701032090', '', '', '', NULL, NULL, NULL, '$2y$10$UHaG9ZQGFdb87KfO.xwNCODfaGi/rFqgJ3WKPNh1k/eZ3QCL7Isgi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-10-13', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(559, 'Ms. Sylivia  Nabbosa', 1, 1, '00239I0417661', 'Client', 0.00, '', '+256751860158', '', 'Female', '1969-11-30', '', 'Ugandan', '', '', 'Kasubi', 'Rubaga Division', '', '', '1970-01-01', 'Kusiima Annet', '', '+256701032090', '', '', '', NULL, NULL, NULL, '$2y$10$.nXPBSP4dAyAB/RFiqMxcuMjDgsP4Xm3M/oVUjNNMe3vPI7g5vmqy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-10-13', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(560, 'Mrs. miriam  Namazzi', 1, 1, '00239I0419797', 'Client', 0.00, '', '+256756602358', '', 'Female', '1990-03-09', '', 'Ugandan', '', '', 'kitebi', 'lubaga', '', '', '1970-01-01', 'Mawagali Edward', '', '+256700719770', '', '', '', NULL, NULL, NULL, '$2y$10$coipED7iCupylKE2jvj/9Oi3qjs1Rsa1VStQbJYjx8CF7zCDFhYrG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-10-17', '2023-10-12 17:25:50', '2023-10-12 17:25:50', NULL),
(561, 'Mrs. safra Kemigisha Tugume', 1, 1, '00239I0428290', 'Client', 0.00, '', '+256702765897', '', 'Female', '1986-12-15', '', 'Ugandan', '', '', 'mulago', 'mulago', '', '', '1970-01-01', 'Mutatina wilberforce', '', '+256701371033', '', '', '', NULL, NULL, NULL, '$2y$10$mRNAPbyMu44TPCrdb6llqeD92w2tFyl8OJFaEya/1kbcxib2FMU/K', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-11-10', '2023-10-12 17:25:51', '2023-10-12 17:25:51', NULL),
(562, 'Nakate  Faridah', 1, 1, '00239I0428378', 'Client', 0.00, '', '+256708769927', '', 'Female', '2022-11-05', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakimuli Hafiza', '', '+256778791109', '', '', '', NULL, NULL, NULL, '$2y$10$xNmtrZjrFl41ZEZa.kxQ5uI4pI6cQU8WZtq0kbeOtOWpQnIxMxF6W', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-11-14', '2023-10-12 17:25:51', '2023-10-12 17:25:51', NULL),
(563, 'Mrs. Namakula  Haawa', 1, 1, '00239I0428459', 'Client', 0.00, '', '+256786374474', '', 'Female', '1980-11-23', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nakate Faridah', '', '+256753876274', '', '', '', NULL, NULL, NULL, '$2y$10$22ZeEvMfo3awCaP2ZQ9bgu3pLl/Elk1Ogu4sWk8K1bKjBrkUf9SDy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2022-11-16', '2023-10-12 17:25:51', '2023-10-12 17:25:51', NULL),
(564, 'Mrs. Namyalo  Shakira', 1, 1, '00239I0431396', 'Client', 0.00, '', '+256700587519', '', 'Female', '1999-09-27', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Kasumba Faizal', '', '+256704057334', '', '', '', NULL, NULL, NULL, '$2y$10$T8UZVRaywG1WANNuJkQfoe25yzy1RzDbZsVioLVI.oyzL8NF9WrCW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-01-06', '2023-10-12 17:25:51', '2023-10-12 17:25:51', NULL),
(565, 'DHERO GROUP', 1, 1, '00239X0013598', 'Client', 0.00, '', '+256778887094', '', '', '1970-01-01', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', '', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Y.YA4zPa.TdNFpoJBwEk3.a.8jQgrWhyBFCfYshZ/pVQxVhxC6MQW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-02-02', '2023-10-12 17:25:51', '2023-10-12 10:36:58', NULL),
(566, 'Mrs. Namubiru  Sylivia', 1, 1, '00239I0435668', 'Client', 0.00, '', '+256752479519', '', 'Female', '1981-02-24', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nandutu ShARON', '', '+256701061498', '', '', '', NULL, NULL, NULL, '$2y$10$wbF6eDs8QgdOfM06lPMnQeuoc.hL6XlUkNNwL6Gj2CcEFfov.QQ9.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-02-07', '2023-10-12 17:25:51', '2023-10-12 17:25:51', NULL),
(567, 'Ms. Kyakuwaire  Tabisa', 1, 1, '00239I0435983', 'Client', 0.00, '', '+256777699980', '', 'Female', '1993-03-13', '', 'Ugandan', '', '', '', 'bweyogerere', '', '', '1970-01-01', 'Nakyagaba Masituula', '', '+256703826197', '', '', '', NULL, NULL, NULL, '$2y$10$x/ZbtJYO3QekfK5erFaFzezbRVrsBY833TBW31svRwO34C3/2hBS6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-02-13', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(568, 'Mr. STEPHEN PADDE ODARO', 1, 1, '00239I0436733', 'Client', 0.00, '', '+256782987901', '', 'Male', '2023-02-17', '', 'Ugandan', '', '', 'SEETA', 'MUKONO', '', '', '1970-01-01', 'OTIM RONALD', '', '+256772972226', '', '', '', NULL, NULL, NULL, '$2y$10$.IGgG8phTqSOTk.M1g040eL8BLuMpleH0CMSRuolRgiR/AoSGf7NW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-02-17', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(569, 'Mr. Serutega  Charles', 1, 1, '00239I0449707', 'Client', 0.00, '', '+256702797026', '', 'Male', '1985-07-25', '', 'Ugandan', '', '', '', 'NANSANA', '', '', '1970-01-01', 'Nalubega Safiinah', '', '+256700954793', '', '', '', NULL, NULL, NULL, '$2y$10$.lp6J6BzDkaWwyuobgqWbew8UjfeAP0ixt7NOP0GTfv/ir8f9gvvy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-02', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(570, 'Mrs. DOREEN  NANVULE', 1, 1, '00239I0449708', 'Client', 0.00, '', '+256782849018', '', 'Female', '1985-04-22', '', 'Ugandan', '', '', 'Seeta', 'Seeta', '', '', '1970-01-01', 'ELVIS NIMARO', '', '+256757064896', '', '', '', NULL, NULL, NULL, '$2y$10$mXWwVWGFDBh1jTuvjys1i.9OKJkL6Nq6.zgtPIrRGNr0jtL9MhNm.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-02', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(571, 'Mrs. Nakimuli  Agnes', 1, 1, '00239I0450333', 'Client', 0.00, '', '+256704296062', '', 'Female', '1972-02-07', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Nantongo Angella', '', '+256703299442', '', '', '', NULL, NULL, NULL, '$2y$10$uFEQHGf04II9t5JXlS3UXOeAr4JMqPMUjsfFsoKlf19/4OGmWPtDG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-03', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(572, 'Mr. Julius  Nahurira', 1, 1, '00239I0450334', 'Client', 0.00, '', '+256756752629', '', 'Male', '2023-03-03', '', 'Ugandan', '', '', 'Nakulabye', 'lubaga', '', '', '1970-01-01', 'Ayebazibwe christine', '', '+256754582657', '', '', '', NULL, NULL, NULL, '$2y$10$Y2GRQVNpg3ymImqgTosoROyB8kxIYhtzHUO4QUfyAw5pcb3tk/jMG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-03', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL),
(573, 'Mr. CHARLES  OCHWO', 1, 1, '00239I0457152', 'Client', 0.00, '', '+256774027210', '', 'Male', '1985-05-09', '', 'Ugandan', '', '', 'KITEZI', '', '', '', '1970-01-01', 'NANTALE PROSSY', '', '+256706027210', '', '', '', NULL, NULL, NULL, '$2y$10$7irr4EoHfboydqszJ4WQjO0zQFmHhsoLEcMRxJ0WgyqNR714JMoBu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-14', '2023-10-12 17:25:52', '2023-10-12 17:25:52', NULL);
INSERT INTO `clients` (`id`, `name`, `branch_id`, `staff_id`, `account_no`, `account_type`, `account_balance`, `email`, `mobile`, `alternate_no`, `gender`, `dob`, `religion`, `nationality`, `marital_status`, `occupation`, `job_location`, `residence`, `id_type`, `id_number`, `id_expiry_date`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_contact`, `next_of_kin_alternate_contact`, `nok_email`, `nok_address`, `photo`, `id_photo_front`, `id_photo_back`, `password`, `token`, `token_expire_date`, `2fa`, `signature`, `account`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(574, 'Mr. Joshua  Ariganyira', 1, 1, '00239I0471270', 'Client', 0.00, '', '+256756144840', '', 'Male', '1980-04-25', '', 'Ugandan', '', '', 'lubaga', 'lubaga', '', '', '1970-01-01', 'kobusingye Ruth', '', '+256702060554', '', '', '', NULL, NULL, NULL, '$2y$10$m8iNiPWJj23Ki6cic6nhkuxIVlugZC8.Nvy.vG7aVgKJdVek/jmE.', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-30', '2023-10-12 17:25:53', '2023-10-12 17:25:53', NULL),
(575, 'Mr. Semulema  Twaha', 1, 1, '00239I0471272', 'Client', 0.00, '', '+256705826790', '', 'Male', '1990-11-16', '', 'Ugandan', '', '', 'Kyengera', 'Kyengera', '', '', '1970-01-01', 'Nabacwa Josephine', '', '+256750456029', '', '', '', NULL, NULL, NULL, '$2y$10$vhpCSfhG8yQogwPifxMm/e.H/gbAY3i/whlEnEy3ffj34.JMSuF5G', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-30', '2023-10-12 17:25:53', '2023-10-12 17:25:53', NULL),
(576, 'Mrs. phoebe  kyomugisha', 1, 1, '00239I0471736', 'Client', 0.00, '', '+256750814470', '', 'Female', '1980-02-25', '', 'Ugandan', '', '', 'kyegera', 'kyegera', '', '', '1970-01-01', 'mawaggali Edward', '', '+256700719770', '', '', '', NULL, NULL, NULL, '$2y$10$JB6xTVhabf2gpXAohufPHeJWUbqHF2PdYdHStZTeEc6Tq7QAG/y5S', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-04-03', '2023-10-12 17:25:53', '2023-10-12 17:25:53', NULL),
(577, 'Mr. Mark  Ssenabulya', 1, 1, '00239I0486039', 'Client', 0.00, '', '+256704979742', '', 'Other', '1989-11-24', '', 'Ugandan', '', '', '', '', '', '', '1970-01-01', 'Sekiranda Soul', '', '+256774373221', '', '', '', NULL, NULL, NULL, '$2y$10$4qdURkaE0hQU3lOwttFoZePA1tIfjIthyhRrKW8A8HlqPZzxqYxfW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-04-18', '2023-10-12 17:25:53', '2023-10-12 17:25:53', NULL),
(578, 'Mr. Mugisha  Anthony', 1, 1, '00239I0524620', 'Client', 0.00, '', '', '', 'Male', '1983-05-03', '', 'Ugandan', '', '', 'Kireka', 'Kira', '', '', '1970-01-01', 'Namaline Farida', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$K1HI.QSp7PxlTNpBKC2QtOVIQ/M1HUxB4KFyd0Y/tkGnYHZo4/Xo2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-03', '2023-10-12 17:25:54', '2023-10-12 10:36:58', NULL),
(579, 'Mr. Rutaro  Moses', 1, 1, '00239I0524629', 'Client', 0.00, '', '', '', 'Male', '1987-08-06', '', 'Ugandan', '', '', 'Bweyogere Ward', 'kira Town council', '', '', '1970-01-01', 'Namutebi Edith', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$UaHyAzIDKjxPcqcFC09vWOw0U1KoY2lBSEta3IfcbPCSU6ATtHjeu', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-06', '2023-10-12 17:25:54', '2023-10-12 10:36:58', NULL),
(580, 'Mr. Twinomugisha  Christopher', 1, 1, '00239I0524630', 'Client', 0.00, '', '', '', 'Male', '1992-02-08', '', 'Ugandan', '', '', 'Nakawa', 'Nakawa Town Council', '', '', '1970-01-01', 'Ayokuru Desire', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$mwWU.Ert3IC53H4GIxdHe.Mg1e.fX.LywNnRRbITgI5.Hnc2c5MG2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-15', '2023-10-12 17:25:54', '2023-10-12 10:36:58', NULL),
(581, 'Mr. Nakachwa  Edith', 1, 1, '00239I0524639', 'Client', 0.00, '', '', '', 'Female', '1994-01-04', '', 'Ugandan', '', '', 'Nakawa', 'Nakawa Division', '', '', '1970-01-01', 'Nantudu Farida', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$uVwXQMqitx4TWNkl2VrRIurKqmaymf1jz8j1qKRBqHD0n6d8UDtMi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-14', '2023-10-12 17:25:54', '2023-10-12 10:36:58', NULL),
(582, 'Mrs. Shalot  Namugenyi', 1, 1, '00239I0524643', 'Client', 0.00, '', '', '', 'Female', '1980-05-01', '', 'Ugandan', '', '', 'mukono', 'mukono', '', '', '1970-01-01', 'Nabachwa Ruth', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$za.b3B/9jJmVUYSobpwh3eG/FQ7TlPPMltkj1/xUbmiHishblEvmW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-01', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(583, 'Mrs. Sylivia  Namutebi', 1, 1, '00239I0524644', 'Client', 0.00, '', '', '', 'Female', '1993-05-06', '', 'Ugandan', '', '', 'busega', 'busega', '', '', '1970-01-01', 'Nagai rita', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$QWdD6hj1Mgwneqdz/eBijuokV5S9Oa4otpUQwP7fKgBD8DthqX.G2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-23', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(584, 'Ms. Joan  Atukwase', 1, 1, '00239I0524650', 'Client', 0.00, '', '', '', 'Female', '1993-05-07', '', 'Ugandan', '', '', 'natete', 'natete', '', '', '1970-01-01', 'Abaho', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Q5N1RMESyDIYwHFeUcj9luU07hT39P21zxW.i6gZVj5SvSo234232', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-07', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(585, 'Mr. Grandford  Tumuhaise', 1, 1, '00239I0524654', 'Client', 0.00, '', '', '', 'Male', '2017-05-04', '', 'Ugandan', '', '', 'wakiso', 'wakiso', '', '', '1970-01-01', 'Agaba Benon', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$xyoAgJAXHS7tPPjgQSQmMu0J4bBUfvjkxttSjUbK0XHzE53kj8Fm6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-02', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(586, 'Mrs. Christine  Ndolirire', 1, 1, '00239I0524655', 'Client', 0.00, '', '', '', 'Female', '2003-05-01', '', 'Ugandan', '', '', 'kiwatule', 'kiwatule', '', '', '1970-01-01', 'kugonza', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$K9S4ye4NxYXCQ.dXTF9t/.Ea7dMJWTbwZatpOwTxGGH6SX8qGiuka', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-28', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(587, 'Mrs. Adongo  Jesca', 1, 1, '00239I0524657', 'Client', 0.00, '', '', '', 'Female', '1993-04-03', '', 'Ugandan', '', '', 'Nakawa', 'Nakawa Division', '', '', '1970-01-01', 'Nakayaga Rose', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$1Vl82705kcCqKzNlZFtn6e2I8nwoZw1GOkU3m307GNIx18Sq6Z3uW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-23', '2023-10-12 17:25:55', '2023-10-12 10:36:58', NULL),
(588, 'Mr. Nanfuka  Justine', 1, 1, '00239I0524660', 'Client', 0.00, '', '', '', 'Male', '1990-01-02', '', 'Ugandan', '', '', 'Kireka', 'Kiira', '', '', '1970-01-01', 'Nantale Joan', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Ed2TONu7ghTFmoW073MLb..M7AZKwhaDAV0fOkrZQlinm4kcgwCGW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-23', '2023-10-12 17:25:56', '2023-10-12 10:36:58', NULL),
(589, 'Mrs. Kunihira  Mary', 1, 1, '00239I0524665', 'Client', 0.00, '', '', '', 'Female', '1995-06-01', '', 'Ugandan', '', '', 'Nakawa Town Council', 'Nakawa Devision', '', '', '1970-01-01', 'Keeya Ivan', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$xpJM09Szp9dbAuxxbs1BAe0VmA.Y1gPgLjYIeDqTZhxj3WWEkmfTG', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-03-25', '2023-10-12 17:25:56', '2023-10-12 10:36:58', NULL),
(590, 'Mrs. Karungi  Annet', 1, 1, '00239I0524666', 'Client', 0.00, '', '', '', 'Female', '1993-04-02', '', 'Ugandan', '', '', 'Nakawa Town Counci', 'Nakawa Division', '', '', '1970-01-01', 'Nakato Joan', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$CTJib9lgjqXuFwv24LnaH.vsNaFhycucrbX1Y4OcSMEkn8.FIW0Sa', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-04-03', '2023-10-12 17:25:56', '2023-10-12 10:36:58', NULL),
(591, 'Mrs. Acham  Sophie', 1, 1, '00239I0524670', 'Client', 0.00, '', '', '', 'Female', '1990-01-03', '', 'Ugandan', '', '', 'Nakawa Town Council', 'Nakawa Division', '', '', '1970-01-01', 'Namaro Faith', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$4zGoQNnPu2SytHp066Daru./0d6ykk3IVuO84jdAf.3lHiVMsYq5q', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-04-03', '2023-10-12 17:25:56', '2023-10-12 10:36:58', NULL),
(592, 'Mrs. Akello  Deborah', 1, 1, '00239I0524671', 'Client', 0.00, '', '', '', 'Female', '1992-03-06', '', 'Ugandan', '', '', 'Nakawa', 'Nakawa Division', '', '', '1970-01-01', 'Kawota David', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$Grc8eKV1wCWCuQwtYbPXhOK02in2OOQ3rOlEuuwiH7d0D/u1jf1p6', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-04-04', '2023-10-12 17:25:56', '2023-10-12 10:36:58', NULL),
(593, 'Mrs. Nalumu  Sophie', 1, 1, '00239I0534566', 'Client', 0.00, '', '+256701765595', '', 'Female', '2021-06-04', '', 'Ugandan', '', '', 'Nansana Parish', 'Nansana', '', '', '1970-01-01', 'Kangawo Richard', '', '+256776908023', '', '', '', NULL, NULL, NULL, '$2y$10$W0V0wiCdDy8RzpGmV9/0n.vdOnt6wZFYkkU/jlq5RuYvMlhLbzvxK', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-06-14', '2023-10-12 17:25:57', '2023-10-12 17:25:57', NULL),
(594, 'Mrs. Oliver  Katusiime', 1, 1, '00239I0537513', 'Client', 0.00, '', '+256702913151', '', 'Female', '1985-05-06', '', 'Ugandan', '', '', 'Wakiso', 'wakiso', '', '', '1970-01-01', 'Nanyonga Teddy', '', '+256788983294', '', '', '', NULL, NULL, NULL, '$2y$10$BsDEuyWo3yq4I3KXE3URJOii1b9K/XKD.CnLCS5quQx0x83No36T2', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-06-20', '2023-10-12 17:25:57', '2023-10-12 17:25:57', NULL),
(595, 'Mr. Mugwanya  Alex', 1, 1, '00239I0544586', 'Client', 0.00, '', '', '', 'Male', '1990-12-15', '', 'Ugandan', '', '', 'Nansana', 'Masanafu', '', '', '1970-01-01', 'Bulinyo Godfrey', '', '+256709219858', '', '', '', NULL, NULL, NULL, '$2y$10$y11JZe9qQXLHtAmjgp5spOGdftXpbWk7hriWhldDatAuL9zLRXduW', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-07-27', '2023-10-12 17:25:57', '2023-10-12 10:36:27', NULL),
(596, 'Mr. Kato  Antonio', 1, 1, '00239I0552152', 'Client', 0.00, '', '+256752810568', '', 'Female', '1986-12-31', '', 'Ugandan', '', '', 'Masanafu', 'Masanafu', '', '', '1970-01-01', 'Namusuula Joeria', '', '+256757698564', '', '', '', NULL, NULL, NULL, '$2y$10$cL4eCEoKtaA/c3TMmBbwCupMLVd1biN34vwByEYO0OvdWtOvJr0Qi', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-09-20', '2023-10-12 17:25:57', '2023-10-12 17:25:57', NULL),
(597, 'Mr. ANGUMA  WALTER', 1, 1, '00239I0552478', 'Client', 0.00, '', '+256758466655', '', 'Male', '1980-01-01', '', 'Ugandan', '', '', 'NAKAWA', 'NAKAWA', '', '', '1970-01-01', 'AUMA MAGRET', '', '+256758331650', '', '', '', NULL, NULL, NULL, '$2y$10$ymNfNPU6.i1QGNhIM9aHWO66YSxohmliO18parvUKgqOUGlP/xoFy', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-09-23', '2023-10-12 17:25:58', '2023-10-12 17:25:58', NULL),
(598, 'Jusper Atwongyeirwe', 1, 14, 'C2310170598', 'Client', 0.00, '', '+256754663596', '', 'Female', '1977-06-06', 'Protestant', 'Ugandan', 'Single', 'photo studio', 'kyaliwajjara', 'kwaliwajjara', 'National ID', 'CF77009102N10G', '2024-03-03', 'Byabashaija Silver', 'Mother', '+256772429099', '', '', 'kwaliwajjara', NULL, NULL, NULL, '$2y$10$VYzhVwOeo.wz1nw6k.AxxOJnAdzxlw16wBsgNu8VrCH75oBXQZdSC', NULL, NULL, 'False', NULL, 'Approved', 'Active', '2023-10-17', '2023-10-17 10:13:44', '2023-10-17 10:13:44', NULL),
(599, 'Okot Paul Peter', 1, 1, 'C2310230599', 'Client', 0.00, 'okotpaulpeter@gmail.com', '+256778547484', '', 'Male', '1995-02-20', 'Catholic', 'Ugandan', 'Single', 'Graphics Designer', 'Kampala', 'Gulu Senior Quators', 'National ID', 'CM125665677Y98', '2023-10-31', 'Obbo S', 'Brother', '+256777237927', '', '', 'Kampala', 'BpOnRGrSXb.jpg', NULL, NULL, '$2y$10$h80b7w8MMcVVlx1LDKDyS.NcY/UlNd4tzn3VXa1PDXZo9dXotV6MW', '$2y$10$4wu6gBAvDRHn1wsNcUUCcOJ3BbooaISQ0LTnhvPUBWoRobDEt.vBO', '2023-10-23 22:21:42', 'False', NULL, 'Approved', 'Active', '2023-10-23', '2023-10-23 22:21:25', '2023-10-23 22:27:12', NULL),
(600, 'Danfodio', 1, 1, 'C2311250600', 'Client', 0.00, 'daniondanfodio@gmail.com', '+256706551841', '', 'Male', '2000-11-01', 'Protestant', 'Ugandan', 'Single', 'Engineer', 'Kasubi, Mengo, Kampala', 'Kasubi, Mengo, Kampala', 'National ID', 'CM256706551841', '2025-11-29', 'Danfodio', 'Brother', '+256706551841', '', '', 'Kasubi, Mengo, Kampala', NULL, NULL, NULL, '$2y$10$FxPiMwszFaFCS/dE6xKy7O6qTGsTsLumCvxiJbWWaDIO5sjYULvne', '$2y$10$T4CZZIQLN3xEdKHbcCEztOFSyXbPrgx5EI97lY9IH8p1obdvyis/S', '2023-11-27 17:15:03', 'False', NULL, 'Approved', 'Active', '2023-11-25', '2023-11-25 20:56:37', '2023-11-27 17:15:03', NULL),
(601, 'Danfodio', 1, 1, '', 'Client', 0.00, '', '+256774649441', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', NULL, NULL, NULL, '$2y$10$qoQ02Xpyq6lg079Cv3WYlO04Svb6M7bSTaxyKnJe77czH2/Mv9h4i', '$2y$10$HLl.loT26kLVmwgdbIL.cuFUI2IqD2zaFj1IkSJ5yT9BlXVJWrZU.', '2023-11-27 11:53:09', 'False', NULL, 'Pending', 'Active', '2023-11-27', '2023-11-27 11:36:28', '2023-11-27 11:38:09', NULL),
(602, 'Shalom', 1, 1, 'C2311270602', 'Client', 0.00, 'mshalomdave@gmail.com', '+256755023794', '', 'Male', '2000-11-01', 'Protestant', 'Ugandan', 'Married', 'Engineer', 'Mengo, Kampala', 'Kampala', 'National ID', 'CM256755023794', '0000-00-00', 'Danfodio', 'Colleague', '+256706551841', '', 'daniondanfodio@gmail.com', 'Mukon, Kampala', NULL, NULL, NULL, '$2y$10$1s9T3tg0gGmP7.TrqPQmz.cszGkHUgdWir07lzzLX80t6v/Un/wz.', '$2y$10$l6A/4qAvQ/1DWonCRbzq.uiHVsRBManrP.HMf23lzUDc5yucwC6iK', '2023-11-27 11:39:30', 'False', NULL, 'Approved', 'Active', '2023-11-27', '2023-11-27 11:39:02', '2023-11-27 11:41:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) UNSIGNED NOT NULL,
  `currency` varchar(10) NOT NULL,
  `symbol` varchar(40) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `symbol`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AED', '&#1583;.&#1573;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(2, 'AFN', '&#65;&#102;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(3, 'ALL', '&#76;&#101;&#107;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(4, 'AMD', '&#1423;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(5, 'ANG', '&#402;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(6, 'AOA', '&#75;&#122;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(7, 'ARS', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(8, 'AUD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(9, 'AWG', '&#402;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(10, 'AZN', '&#1084;&#1072;&#1085;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(11, 'BAM', '&#75;&#77;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(12, 'BBD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(13, 'BDT', '&#2547;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(14, 'BGN', '&#1083;&#1074;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(15, 'BHD', '.&#1583;.&#1576;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(16, 'BIF', '&#70;&#66;&#117;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(17, 'BMD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(18, 'BND', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(19, 'BOB', '&#36;&#98;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(20, 'BRL', '&#82;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(21, 'BSD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(22, 'BTN', '&#78;&#117;&#46;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(23, 'BWP', '&#80;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(24, 'BYR', '&#112;&#46;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(25, 'BZD', '&#66;&#90;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(26, 'CAD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(27, 'CDF', '&#70;&#67;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(28, 'CHF', '&#67;&#72;&#70;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(29, 'CLF', 'CLF', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(30, 'CLP', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(31, 'CNY', '&#165;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(32, 'COP', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(33, 'CRC', '&#8353;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(34, 'CUP', '&#8396;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(35, 'CVE', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(36, 'CZK', '&#75;&#269;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(37, 'DJF', '&#70;&#100;&#106;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(38, 'DKK', '&#107;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(39, 'DOP', '&#82;&#68;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(40, 'DZD', '&#1583;&#1580;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(41, 'EGP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(42, 'ETB', '&#66;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(43, 'EUR', '&#8364;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(44, 'FJD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(45, 'FKP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(46, 'GBP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(47, 'GEL', '&#4314;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(48, 'GHS', '&#162;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(49, 'GIP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(50, 'GMD', '&#68;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(51, 'GNF', '&#70;&#71;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(52, 'GTQ', '&#81;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(53, 'GYD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(54, 'HKD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(55, 'HNL', '&#76;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(56, 'HRK', '&#107;&#110;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(57, 'HTG', '&#71;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(58, 'HUF', '&#70;&#116;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(59, 'IDR', '&#82;&#112;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(60, 'ILS', '&#8362;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(61, 'INR', '&#8377;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(62, 'IQD', '&#1593;.&#1583;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(63, 'IRR', '&#65020;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(64, 'ISK', '&#107;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(65, 'JEP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(66, 'JMD', '&#74;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(67, 'JOD', '&#74;&#68;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(68, 'JPY', '&#165;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(69, 'KES', '&#75;&#83;&#104;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(70, 'KGS', '&#1083;&#1074;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(71, 'KHR', '&#6107;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(72, 'KMF', '&#67;&#70;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(73, 'KPW', '&#8361;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(74, 'KRW', '&#8361;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(75, 'KWD', '&#1583;.&#1603;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(76, 'KYD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(77, 'KZT', '&#1083;&#1074;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(78, 'LAK', '&#8365;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(79, 'LBP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(80, 'LKR', '&#8360;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(81, 'LRD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(82, 'LSL', '&#76;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(83, 'LTL', '&#76;&#116;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(84, 'LVL', '&#76;&#115;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(85, 'LYD', '&#1604;.&#1583;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(86, 'MAD', '&#1583;.&#1605;.', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(87, 'MDL', '&#76;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(88, 'MGA', '&#65;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(89, 'MKD', '&#1076;&#1077;&#1085;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(90, 'MMK', '&#75;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(91, 'MNT', '&#8366;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(92, 'MOP', '&#77;&#79;&#80;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(93, 'MRO', '&#85;&#77;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(94, 'MUR', '&#8360;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(95, 'MVR', '.&#1923;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(96, 'MWK', '&#77;&#75;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(97, 'MXN', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(98, 'MYR', '&#82;&#77;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(99, 'MZN', '&#77;&#84;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(100, 'NAD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(101, 'NGN', '&#8358;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(102, 'NIO', '&#67;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(103, 'NOK', '&#107;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(104, 'NPR', '&#8360;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(105, 'NZD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(106, 'OMR', '&#65020;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(107, 'PAB', '&#66;&#47;&#46;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(108, 'PEN', '&#83;&#47;&#46;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(109, 'PGK', '&#75;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(110, 'PHP', '&#8369;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(111, 'PKR', '&#8360;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(112, 'PLN', '&#122;&#322;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(113, 'PYG', '&#71;&#115;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(114, 'QAR', '&#65020;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(115, 'RON', '&#108;&#101;&#105;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(116, 'RSD', '&#1044;&#1080;&#1085;&#46;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(117, 'RUB', '&#1088;&#1091;&#1073;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(118, 'RWF', '&#1585;.&#1587;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(119, 'SAR', '&#65020;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(120, 'SBD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(121, 'SCR', '&#8360;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(122, 'SDG', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(123, 'SEK', '&#107;&#114;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(124, 'SGD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(125, 'SHP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(126, 'SLL', '&#76;&#101;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(127, 'SOS', '&#83;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(128, 'SRD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(129, 'STD', '&#68;&#98;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(130, 'SVC', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(131, 'SYP', '&#163;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(132, 'SZL', '&#76;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(133, 'THB', '&#3647;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(134, 'TJS', '&#84;&#74;&#83;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(135, 'TMT', '&#109;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(136, 'TND', '&#1583;.&#1578;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(137, 'TOP', '&#84;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(138, 'TRY', '&#8356;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(139, 'TTD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(140, 'TWD', '&#78;&#84;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(141, 'TZS', 'TZS', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(142, 'UAH', '&#8372;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(143, 'UGX', '&#85;&#83;&#104;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(144, 'USD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(145, 'UYU', '&#36;&#85;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(146, 'UZS', '&#1083;&#1074;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(147, 'VEF', '&#66;&#115;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(148, 'VND', '&#8363;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(149, 'VUV', '&#86;&#84;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(150, 'WST', '&#87;&#83;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(151, 'XAF', '&#70;&#67;&#70;&#65;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(152, 'XCD', '&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(153, 'XDR', 'XDR', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(154, 'XOF', 'XOF', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(155, 'XPF', '&#70;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(156, 'YER', '&#65020;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(157, 'ZAR', '&#82;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(158, 'ZMK', '&#90;&#77;&#87;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL),
(159, 'ZWL', '&#90;&#36;', 'Active', '2023-10-03 08:32:14', '2023-10-03 08:32:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) UNSIGNED NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `department_slug` varchar(100) NOT NULL,
  `department_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_slug`, `department_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administration', 'administration', 'Active', '2023-10-03 08:33:13', '2023-10-03 08:33:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disbursements`
--

CREATE TABLE `disbursements` (
  `id` int(11) UNSIGNED NOT NULL,
  `disbursement_code` varchar(25) NOT NULL,
  `cycle` int(6) NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `particular_id` int(11) UNSIGNED NOT NULL,
  `payment_id` int(11) UNSIGNED NOT NULL,
  `disbursed_by` enum('Deposited into Client Account','Paid in Cash','Client Mobile Money Account','Client Bank Account') NOT NULL DEFAULT 'Deposited into Client Account',
  `principal` double NOT NULL,
  `computed_interest` double NOT NULL,
  `actual_interest` double NOT NULL,
  `computed_installment` double NOT NULL,
  `actual_installment` double NOT NULL,
  `principal_installment` double NOT NULL,
  `interest_installment` double NOT NULL,
  `computed_repayment` double NOT NULL,
  `actual_repayment` double NOT NULL,
  `loan_period_days` int(6) NOT NULL,
  `days_covered` varchar(10) NOT NULL,
  `days_remaining` varchar(6) NOT NULL,
  `grace_period` int(6) NOT NULL,
  `installments_num` int(6) NOT NULL,
  `installments_covered` int(6) NOT NULL,
  `first_recovery` date NOT NULL,
  `loan_expiry_date` date NOT NULL,
  `expiry_day` varchar(10) NOT NULL,
  `expected_interest_recovered` double NOT NULL,
  `expected_principal_recovered` double NOT NULL,
  `expected_amount_recovered` double NOT NULL,
  `expected_loan_balance` double NOT NULL,
  `interest_collected` double NOT NULL,
  `principal_collected` double NOT NULL,
  `total_collected` double NOT NULL,
  `interest_balance` double NOT NULL,
  `principal_balance` double NOT NULL,
  `total_balance` double NOT NULL,
  `arrears` double NOT NULL,
  `principal_due` double NOT NULL,
  `interest_due` double NOT NULL,
  `installments_due` varchar(6) NOT NULL DEFAULT ' - ',
  `days_due` varchar(10) NOT NULL DEFAULT ' - ',
  `status` enum('Open','Fully Paid','Defaulted') NOT NULL DEFAULT 'Open',
  `class` enum('Running','Arrears','Cleared','Expired','Defaulted') NOT NULL DEFAULT 'Running',
  `comments` varchar(100) NOT NULL,
  `date_disbursed` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailattachments`
--

CREATE TABLE `emailattachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `email_id` int(11) UNSIGNED NOT NULL,
  `attachment` varchar(50) NOT NULL,
  `extension` varchar(11) NOT NULL,
  `size` int(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) UNSIGNED NOT NULL,
  `label` enum('draft','spam','important','trash','archive','starred') DEFAULT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL,
  `type` enum('Cc','Bcc') DEFAULT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `sender_account` enum('Super','Administrator','Employee','Client') DEFAULT NULL,
  `recipient_id` int(11) UNSIGNED NOT NULL,
  `recipient_account` enum('Super','Administrator','Employee','Client') DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtags`
--

CREATE TABLE `emailtags` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag_name` varchar(20) NOT NULL,
  `slug` varchar(20) NOT NULL,
  `color` enum('primary','info','secondary','success','warning','danger') NOT NULL DEFAULT 'primary',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `emailtags`
--

INSERT INTO `emailtags` (`id`, `tag_name`, `slug`, `color`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Info', 'info', 'info', 'active', '2023-10-03 08:36:01', '2023-10-03 08:36:01', NULL),
(2, 'Promo', 'promo', 'primary', 'active', '2023-10-03 08:36:01', '2023-10-03 08:36:01', NULL),
(3, 'Social', 'social', 'success', 'active', '2023-10-03 08:36:01', '2023-10-03 08:36:01', NULL),
(4, 'Notice', 'notice', 'warning', 'active', '2023-10-03 08:36:01', '2023-10-03 08:36:01', NULL),
(5, 'Reminder', 'reminder', 'danger', 'active', '2023-10-03 08:36:01', '2023-10-03 08:36:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE `entries` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `particular_id` int(11) UNSIGNED NOT NULL,
  `payment_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `application_id` int(11) UNSIGNED DEFAULT NULL,
  `disbursement_id` int(11) UNSIGNED DEFAULT NULL,
  `account_typeId` int(11) UNSIGNED DEFAULT NULL,
  `entry_typeId` int(11) UNSIGNED DEFAULT NULL,
  `amount` double NOT NULL,
  `ref_id` varchar(20) NOT NULL,
  `entry_menu` enum('financing','expense','transfer','investment') NOT NULL DEFAULT 'financing',
  `entry_details` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `status` enum('debit','credit') DEFAULT NULL,
  `balance` double NOT NULL,
  `remarks` text NOT NULL DEFAULT 'none',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `entries`
--

INSERT INTO `entries` (`id`, `date`, `particular_id`, `payment_id`, `branch_id`, `staff_id`, `client_id`, `application_id`, `disbursement_id`, `account_typeId`, `entry_typeId`, `amount`, `ref_id`, `entry_menu`, `entry_details`, `contact`, `status`, `balance`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2023-10-19', 12, 2, 1, 1, NULL, NULL, NULL, 8, 9, 500000000, '22301919', 'investment', '<p>invested</p>', '', 'credit', 500000000, '', '2023-10-19 12:06:39', '2023-10-19 12:06:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `entrytypes`
--

CREATE TABLE `entrytypes` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `part` enum('debit','credit') NOT NULL,
  `entry_menu` enum('financing','expense','transfer','investment') NOT NULL,
  `account_typeId` int(11) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `entrytypes`
--

INSERT INTO `entrytypes` (`id`, `type`, `part`, `entry_menu`, `account_typeId`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Transfer', 'credit', 'transfer', NULL, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(2, 'Expense', 'debit', 'expense', NULL, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(3, 'Repayment', 'credit', 'financing', 3, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(4, 'Disbursement', 'debit', 'financing', 3, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(5, 'Deposit', 'credit', 'financing', 12, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(6, 'Withdraw', 'debit', 'financing', 12, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(7, 'Payment', 'credit', 'financing', 18, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(8, 'Payment', 'credit', 'financing', 24, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL),
(9, 'Investment', 'credit', 'investment', NULL, 'active', '2023-10-03 08:35:36', '2023-10-03 08:35:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `type` enum('collateral','income','expense') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `application_id`, `file_name`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'H415cu9soB.jpg', 'collateral', '2023-10-21 12:49:09', '2023-10-21 12:49:09', NULL),
(2, 2, 'OJHt0TzyXU.jpg', 'income', '2023-10-21 12:49:09', '2023-10-21 12:49:09', NULL),
(3, 2, 'PJO6KG5z9H.jpg', 'expense', '2023-10-21 12:49:09', '2023-10-21 12:49:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loanapplications`
--

CREATE TABLE `loanapplications` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_code` varchar(20) NOT NULL,
  `application_date` date DEFAULT NULL,
  `client_id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `principal` float NOT NULL,
  `purpose` text NOT NULL,
  `status` enum('Pending','Processing','Declined','Approved','Disbursed','Cancelled') NOT NULL DEFAULT 'Pending',
  `level` enum('Credit Officer','Supervisor','Operations Officer','Accounts Officer') DEFAULT NULL,
  `action` enum('Processing','Review','Approved','Disbursed','Declined') DEFAULT NULL,
  `overall_charges` text NOT NULL,
  `total_charges` varchar(12) NOT NULL,
  `reduct_charges` enum('Principal','Savings') NOT NULL DEFAULT 'Principal',
  `applicant_products` text DEFAULT NULL,
  `security_item` varchar(100) NOT NULL,
  `security_info` text NOT NULL,
  `est_value` float NOT NULL,
  `ref_name` varchar(100) NOT NULL,
  `ref_address` varchar(100) NOT NULL,
  `ref_job` varchar(100) NOT NULL,
  `ref_contact` varchar(100) NOT NULL,
  `ref_alt_contact` varchar(100) NOT NULL,
  `ref_email` varchar(100) NOT NULL,
  `ref_relation` varchar(100) NOT NULL,
  `ref_name2` varchar(100) NOT NULL,
  `ref_address2` varchar(100) NOT NULL,
  `ref_job2` varchar(100) NOT NULL,
  `ref_contact2` varchar(100) NOT NULL,
  `ref_alt_contact2` varchar(100) NOT NULL,
  `ref_email2` varchar(100) NOT NULL,
  `ref_relation2` varchar(100) NOT NULL,
  `net_salary` double NOT NULL,
  `farming` double NOT NULL,
  `business` double NOT NULL,
  `others` double NOT NULL,
  `rent` double NOT NULL,
  `education` double NOT NULL,
  `medical` double NOT NULL,
  `transport` double NOT NULL,
  `exp_others` double NOT NULL,
  `difference` double NOT NULL,
  `dif_status` enum('Surplus','Balanced','Deficit') NOT NULL,
  `institute_name` varchar(100) NOT NULL,
  `institute_branch` varchar(100) NOT NULL,
  `account_type` varchar(100) NOT NULL,
  `institute_name2` varchar(100) NOT NULL,
  `institute_branch2` varchar(100) NOT NULL,
  `account_type2` varchar(100) NOT NULL,
  `amt_advance` varchar(100) NOT NULL,
  `date_advance` date DEFAULT NULL,
  `loan_duration` varchar(100) NOT NULL,
  `amt_outstanding` varchar(100) NOT NULL,
  `amt_advance2` varchar(100) NOT NULL,
  `date_advance2` date DEFAULT NULL,
  `loan_duration2` varchar(100) NOT NULL,
  `amt_outstanding2` varchar(100) NOT NULL,
  `loan_agreement` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `loanapplications`
--

INSERT INTO `loanapplications` (`id`, `application_code`, `application_date`, `client_id`, `staff_id`, `product_id`, `branch_id`, `principal`, `purpose`, `status`, `level`, `action`, `overall_charges`, `total_charges`, `reduct_charges`, `applicant_products`, `security_item`, `security_info`, `est_value`, `ref_name`, `ref_address`, `ref_job`, `ref_contact`, `ref_alt_contact`, `ref_email`, `ref_relation`, `ref_name2`, `ref_address2`, `ref_job2`, `ref_contact2`, `ref_alt_contact2`, `ref_email2`, `ref_relation2`, `net_salary`, `farming`, `business`, `others`, `rent`, `education`, `medical`, `transport`, `exp_others`, `difference`, `dif_status`, `institute_name`, `institute_branch`, `account_type`, `institute_name2`, `institute_branch2`, `account_type2`, `amt_advance`, `date_advance`, `loan_duration`, `amt_outstanding`, `amt_advance2`, `date_advance2`, `loan_duration2`, `amt_outstanding2`, `loan_agreement`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'A2310190001', '2023-10-19', 1, 1, 6, 1, 30000000, 'for business', 'Declined', 'Accounts Officer', 'Declined', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:6;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '1800000', 'Principal', 'a:6:{s:9:\"ProductID\";s:1:\"6\";s:12:\"InterestRate\";s:4:\"4.50\";s:14:\"InterestPeriod\";s:5:\"month\";s:12:\"InterestType\";s:4:\"Flat\";s:13:\"LoanFrequency\";s:7:\"Monthly\";s:15:\"RepaymentPeriod\";s:1:\"5\";}', 'land', '<p>in bweyos</p>', 50000000, 'Shadrach Obbo', 'Kampala', 'engineer', '+256777237827', '', 'shadrachobbo4@gmail.com', 'Brother', 'Shadrach Obbo', 'Kampala', 'teacher', '+256702999488', '', 'shadrachobbo4@gmail.com', 'Brother', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', '0000-00-00', '', '', '', '2023-10-19 14:03:54', '2023-10-19 15:23:07', NULL),
(2, 'A2310210002', '2023-10-21', 1, 1, 5, 1, 5000000, 'for business', 'Pending', NULL, NULL, 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:6;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '300000', 'Principal', 'a:6:{s:9:\"ProductID\";s:1:\"5\";s:12:\"InterestRate\";s:4:\"5.00\";s:14:\"InterestPeriod\";s:5:\"month\";s:12:\"InterestType\";s:4:\"Flat\";s:13:\"LoanFrequency\";s:7:\"Monthly\";s:15:\"RepaymentPeriod\";s:1:\"5\";}', 'car', '<p>toyota raum</p>', 50000000, 'Shadrach Obbo', 'Kampala', 'teacher', '+256777237827', '', 'shadrachobbo4@gmail.com', 'Brother', 'Shadrach Obbo', 'Kampala', 'engineer', '+256752294420', '', 'shadrachobbo4@gmail.com', 'Brother', 50000000, 0, 0, 0, 0, 0, 50000, 0, 0, 49950000, 'Surplus', '', '', '', '', '', '', '', '0000-00-00', '', '', '', '0000-00-00', '', '', '', '2023-10-21 12:49:09', '2023-10-21 12:59:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loanproducts`
--

CREATE TABLE `loanproducts` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `interest_rate` double(10,2) NOT NULL,
  `interest_period` enum('day','week','month','year') NOT NULL DEFAULT 'year',
  `interest_type` enum('Flat','Reducing') NOT NULL DEFAULT 'Reducing',
  `repayment_period` int(10) NOT NULL,
  `repayment_duration` enum('day(s)','week(s)','month(s)','year(s)') NOT NULL DEFAULT 'month(s)',
  `repayment_freq` enum('Weekly','Bi-Weekly','Monthly','Bi-Monthly','Quarterly','Termly','Bi-Annual','Annually') NOT NULL DEFAULT 'Monthly',
  `product_desc` text NOT NULL,
  `product_charges` text NOT NULL,
  `product_features` text NOT NULL,
  `min_principal` float DEFAULT NULL,
  `max_principal` float DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `loanproducts`
--

INSERT INTO `loanproducts` (`id`, `product_name`, `interest_rate`, `interest_period`, `interest_type`, `repayment_period`, `repayment_duration`, `repayment_freq`, `product_desc`, `product_charges`, `product_features`, `min_principal`, `max_principal`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Flat Demo Product', 5.00, 'year', 'Flat', 6, 'month(s)', 'Monthly', 'Demonstration', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:0;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', NULL, NULL, 'Inactive', '2023-10-03 08:35:48', '2023-10-18 14:08:32', '2023-10-18 21:10:18'),
(2, 'Reducing Demo Product', 5.00, 'year', 'Reducing', 6, 'month(s)', 'Monthly', 'Demonstration', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:0;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', NULL, NULL, 'Inactive', '2023-10-03 08:35:48', '2023-10-18 14:09:07', '2023-10-18 21:10:55'),
(3, 'Weyagale Loan', 3.00, 'month', 'Flat', 12, 'month(s)', 'Monthly', 'business loan which takes three days to process', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:6;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 1000000, 0, 'Active', '2023-10-03 10:09:04', '2023-10-18 14:04:01', '2023-10-10 16:03:12'),
(4, 'Karibu Loan', 8.00, 'month', 'Flat', 12, 'month(s)', 'Monthly', 'Karibu Loan', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;d:5.5;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 1500000, 10000000, 'Active', '2023-10-10 11:43:58', '2023-10-18 14:04:14', NULL),
(5, 'Tambula Loan', 5.00, 'month', 'Flat', 12, 'month(s)', 'Monthly', 'Tambula is a business loan from 5m to 29m', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:6;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 5000000, 29000000, 'Active', '2023-10-10 16:03:02', '2023-10-18 14:05:59', NULL),
(6, 'Weyagale', 4.50, 'month', 'Flat', 12, 'month(s)', 'Monthly', 'This is a business loan from 30m and above', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:6;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 30000000, 100000000, 'Active', '2023-10-10 16:06:47', '2023-10-18 21:11:10', NULL),
(7, 'Kagwilawo', 20.00, 'week', 'Flat', 4, 'week(s)', 'Weekly', 'This loan has no application fees but its 20% upfront deducted on disbursement', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;i:0;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 0, 2000000, 'Active', '2023-10-10 16:12:47', '2023-10-18 21:12:50', NULL),
(8, 'Nexen Express', 12.00, 'week', 'Flat', 12, 'week(s)', 'Weekly', 'this is a weekly loan', 'a:3:{s:12:\"ParticularID\";a:1:{i:0;i:25;}s:16:\"ParticularCharge\";a:1:{i:0;d:5.5;}s:22:\"ParticularChargeMethod\";a:1:{i:0;s:7:\"Percent\";}}', '', 500000, 2000000, 'Active', '2023-10-10 16:16:50', '2023-10-18 14:05:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) UNSIGNED NOT NULL,
  `order` int(6) NOT NULL,
  `parent_id` int(6) NOT NULL DEFAULT 0,
  `title` varchar(150) NOT NULL,
  `slug` varchar(25) NOT NULL,
  `menu` varchar(20) NOT NULL,
  `url` text NOT NULL,
  `accounts` text NOT NULL,
  `icon` varchar(150) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `create` enum('on','off') DEFAULT NULL,
  `import` enum('on','off') DEFAULT NULL,
  `view` enum('on','off') DEFAULT NULL,
  `update` enum('on','off') DEFAULT NULL,
  `delete` enum('on','off') DEFAULT NULL,
  `bulkDelete` enum('on','off') DEFAULT NULL,
  `export` enum('on','off') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `order`, `parent_id`, `title`, `slug`, `menu`, `url`, `accounts`, `icon`, `status`, `create`, `import`, `view`, `update`, `delete`, `bulkDelete`, `export`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, 'Dashboard', 'Dashboard', 'dashboard', 'admin/dashboard', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-tachometer-alt', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(2, 2, 0, 'Menu', 'Menu', 'menu', 'admin/menu', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-bars', 'Inactive', NULL, NULL, NULL, 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(3, 3, 0, 'Company', 'Company', 'company', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-building', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(4, 4, 3, 'Settings', 'Settings', 'company', 'admin/company/settings', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-cogs', 'Active', NULL, NULL, 'on', 'on', NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(5, 5, 3, 'Branches', 'Branches', 'company', 'admin/company/branch', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-code-branch', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(6, 6, 3, 'Departments', 'Departments', 'company', 'admin/company/department', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-users-rectangle', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(7, 7, 3, 'Positions', 'Positions', 'company', 'admin/company/position', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-tags', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(8, 8, 0, 'Accounting', 'Accounting', 'accounting', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-chart-simple', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(9, 9, 8, 'Chart of Accounts', 'ChartofAccounts', 'accounting', 'admin/accounts/categories', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-object-group', 'Active', NULL, NULL, 'on', 'on', NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(10, 10, 8, 'Subcategories', 'Subcategories', 'accounting', 'admin/accounts/subcategory', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-object-group', 'Active', 'on', NULL, 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(11, 11, 8, 'Particulars', 'Particulars', 'accounting', 'admin/accounts/particular', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-hand-pointer', 'Active', 'on', NULL, 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(12, 12, 0, 'Staffs', 'Staffs', 'staff', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-user-friends', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(13, 13, 12, 'Administrators', 'Administrators', 'staff', 'admin/staff/administrator', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-user-tie', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(14, 14, 12, 'Employees', 'Employees', 'staff', 'admin/staff/employee', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-user-tag', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(15, 15, 0, 'Clients', 'Clients', 'clients', 'admin/clients/client', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-users', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(16, 16, 0, 'User Management', 'UserManagement', 'users', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-users-rays', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(17, 17, 16, 'Users', 'Users', 'users', 'admin/user', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-users-line', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(18, 18, 16, 'Logins', 'Logins', 'users', 'admin/user-pages/logs', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-sign-in', 'Active', NULL, 'off', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(19, 19, 16, 'Activity', 'Activity', 'users', 'admin/user-pages/activity', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-person-digging', 'Active', NULL, 'off', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(20, 20, 0, 'Loans', 'Loans', 'loans', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-trend-up', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(21, 21, 20, 'Products', 'Products', 'loans', 'admin/loans/product', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-cart-flatbed-suitcase', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(22, 22, 20, 'Applications', 'Applications', 'loans', 'admin/loans/application', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-clipboard', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(23, 23, 20, 'Disbursements', 'Disbursements', 'loans', 'admin/loans/disbursement', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-trend-up', 'Active', NULL, 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(24, 24, 0, 'Transactions', 'Transactions', 'transactions', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-usd', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(25, 25, 24, 'Savings', 'Savings', 'transactions', 'admin/transactions/type/savings', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-bank', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(26, 26, 24, 'Repayments', 'Repayments', 'transactions', 'admin/transactions/type/repayments', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-trend-up', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(27, 27, 24, 'Applications', 'Applications', 'transactions', 'admin/transactions/type/applications', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-clipboard-check', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(28, 28, 24, 'Membership', 'Membership', 'transactions', 'admin/transactions/type/membership', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-users', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(29, 29, 24, 'Expenses', 'Expenses', 'transactions', 'admin/transactions/type/expenses', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-1-wave', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(30, 30, 24, 'Transfer', 'Transfer', 'transactions', 'admin/transactions/type/transfer', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-transfer', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(31, 31, 24, 'Investment', 'Investment', 'transactions', 'admin/transactions/type/investment', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-money-bill-transfer', 'Active', 'on', 'on', 'on', 'on', 'on', 'on', 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(32, 32, 0, 'Statements', 'Statements', 'statements', 'javascript: void(0)', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-clipboard-list', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(33, 33, 32, 'Balance Sheet', 'BalanceSheet', 'statements', 'admin/statements/statement', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'far fa-circle', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(34, 34, 32, 'Profit Loss', 'ProfitLoss', 'statements', 'admin/statements/view-statement/profitLoss', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'far fa-circle', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(35, 35, 32, 'Trial Balance', 'TrialBalance', 'statements', 'admin/statements/view-statement/trialbalance', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'far fa-circle', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(36, 36, 32, 'Cash Flow', 'CashFlow', 'statements', 'admin/statements/view-statement/cashflow', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'far fa-circle', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(37, 37, 0, 'Email', 'Email', 'emails', 'admin/mailing/emails', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'ti ti-mail', 'Inactive', 'on', NULL, 'on', NULL, 'on', 'on', NULL, '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL),
(38, 38, 0, 'Reports', 'Reports', 'reports', 'admin/reports/report', '{\"Administrator\":\"Administrator\",\"Employee\":\"Employee\",\"Client\":\"Client\"}', 'fas fa-clipboard-question', 'Active', NULL, NULL, 'on', NULL, NULL, NULL, 'on', '2023-10-03 08:31:59', '2023-10-03 08:31:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2022-06-07-101218', 'App\\Database\\Migrations\\CreateMenuTable', 'default', 'App', 1696311085, 1),
(2, '2022-06-07-151827', 'App\\Database\\Migrations\\CreateCurrenciesTable', 'default', 'App', 1696311085, 1),
(3, '2022-06-08-144017', 'App\\Database\\Migrations\\CreateSettingsTable', 'default', 'App', 1696311086, 1),
(4, '2022-06-08-144027', 'App\\Database\\Migrations\\NationalitiesListTable', 'default', 'App', 1696311087, 1),
(5, '2022-06-18-144049', 'App\\Database\\Migrations\\CreateBranches', 'default', 'App', 1696311088, 1),
(6, '2022-06-19-140353', 'App\\Database\\Migrations\\CreateDepartmentsTable', 'default', 'App', 1696311089, 1),
(7, '2022-06-19-141215', 'App\\Database\\Migrations\\CreatePositionsTable', 'default', 'App', 1696311090, 1),
(8, '2022-06-20-095725', 'App\\Database\\Migrations\\CreateStaffsTable', 'default', 'App', 1696311092, 1),
(9, '2022-06-20-095820', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1696311092, 1),
(10, '2022-06-20-100014', 'App\\Database\\Migrations\\CreateClients', 'default', 'App', 1696311093, 1),
(11, '2022-06-20-100016', 'App\\Database\\Migrations\\CreateUserLogsTable', 'default', 'App', 1696311094, 1),
(12, '2022-06-20-110018', 'App\\Database\\Migrations\\CreateActivityLogs', 'default', 'App', 1696311094, 1),
(13, '2022-06-21-081938', 'App\\Database\\Migrations\\StatementsTable', 'default', 'App', 1696311094, 1),
(14, '2022-06-21-084954', 'App\\Database\\Migrations\\CashFlowTypes', 'default', 'App', 1696311095, 1),
(15, '2022-06-21-094546', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1696311095, 1),
(16, '2022-06-21-094547', 'App\\Database\\Migrations\\AccountTypes', 'default', 'App', 1696311095, 1),
(17, '2022-06-21-095005', 'App\\Database\\Migrations\\CreateSubcategoriesTable', 'default', 'App', 1696311099, 1),
(18, '2022-06-21-095945', 'App\\Database\\Migrations\\CreateParticularsTable', 'default', 'App', 1696311100, 1),
(19, '2022-06-23-211830', 'App\\Database\\Migrations\\CreateLoanProducts', 'default', 'App', 1696311101, 1),
(20, '2022-06-29-140954', 'App\\Database\\Migrations\\CreateLoanApplications', 'default', 'App', 1696311101, 1),
(21, '2022-07-02-074946', 'App\\Database\\Migrations\\CreateFilesTable', 'default', 'App', 1696311102, 1),
(22, '2022-07-02-101446', 'App\\Database\\Migrations\\CreateApplicationRemarks', 'default', 'App', 1696311103, 1),
(23, '2022-07-04-075658', 'App\\Database\\Migrations\\CreateDisbursements', 'default', 'App', 1696311103, 1),
(24, '2022-10-03-110218', 'App\\Database\\Migrations\\EntryTypesTable', 'default', 'App', 1696311104, 1),
(25, '2022-10-03-110310', 'App\\Database\\Migrations\\CreateEntriesTable', 'default', 'App', 1696311104, 1),
(26, '2023-08-29-110429', 'App\\Database\\Migrations\\EmailTagsTable', 'default', 'App', 1696311105, 1),
(27, '2023-08-29-121049', 'App\\Database\\Migrations\\EmailsTable', 'default', 'App', 1696311105, 1),
(28, '2023-09-05-103033', 'App\\Database\\Migrations\\EmailAttachmentsTable', 'default', 'App', 1696311107, 1),
(29, '2023-11-26-201613', 'App\\Database\\Migrations\\CreateApiRequestsTable', 'default', 'App', 1701031599, 2);

-- --------------------------------------------------------

--
-- Table structure for table `nationalitieslist`
--

CREATE TABLE `nationalitieslist` (
  `id` int(11) UNSIGNED NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `alpha_2_code` varchar(10) NOT NULL,
  `alpha_3_code` varchar(10) NOT NULL,
  `num_code` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `nationalitieslist`
--

INSERT INTO `nationalitieslist` (`id`, `country_name`, `nationality`, `alpha_2_code`, `alpha_3_code`, `num_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Afghanistan', 'Afghan', 'AF', 'AFG', '4', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(2, 'Albania', 'Albanian', 'AL', 'ALB', '8', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(3, 'Algeria', 'Algerian', 'DZ', 'DZA', '12', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(4, 'Andorra', 'Andorran', 'AD', 'AND', '20', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(5, 'Angola', 'Angolan', 'AO', 'AGO', '24', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(6, 'Anguilla', 'Anguillan', 'AI', 'AIA', '660', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(7, 'Argentina', 'Argentine', 'AR', 'ARG', '32', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(8, 'Armenia', 'Armenian', 'AM', 'ARM', '51', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(9, 'Australia', 'Australian', 'AU', 'AUS', '36', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(10, 'Austria', 'Austrian', 'AT', 'AUT', '40', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(11, 'Azerbaijan', 'Azerbaijani', 'AZ', 'AZE', '31', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(12, 'Bahamas', 'Bahamian', 'BS', 'BHS', '44', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(13, 'Bahrain', 'Bahraini', 'BH', 'BHR', '48', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(14, 'Bangladesh', 'Bangladeshi', 'BD', 'BGD', '50', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(15, 'Belarus', 'Belarusian', 'BY', 'BLR', '112', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(16, 'Belgium', 'Belgian', 'BE', 'BEL', '56', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(17, 'Belize', 'Belizean', 'BZ', 'BLZ', '84', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(18, 'Benin', 'Beninese', 'BJ', 'BEN', '204', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(19, 'Bermuda', 'Bermudian', 'BM', 'BMU', '60', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(20, 'Bhutan', 'Bhutanese', 'BT', 'BTN', '64', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(21, 'Bolivia (Plurinational State of)', 'Bolivian', 'BO', 'BOL', '68', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(22, 'Bosnia and Herzegovina', 'Bosnian/Herzegovinian', 'BA', 'BIH', '70', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(23, 'Botswana', 'Botswanan', 'BW', 'BWA', '72', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(24, 'Brazil', 'Brazilian', 'BR', 'BRA', '76', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(25, 'Bulgaria', 'Bulgarian', 'BG', 'BGR', '100', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(26, 'Burkina Faso', 'Burkinabé', 'BF', 'BFA', '854', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(27, 'Burundi', 'Burundian', 'BI', 'BDI', '108', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(28, 'Cambodia', 'Cambodian', 'KH', 'KHM', '116', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(29, 'Cameroon', 'Cameroonian', 'CM', 'CMR', '120', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(30, 'Canada', 'Canadian', 'CA', 'CAN', '124', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(31, 'Cape Verde', 'Cape Verdean', 'CV', 'CPV', '132', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(32, 'Cayman Islands', 'Cayman Islander', 'KY', 'CYM', '136', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(33, 'Central African Republic', 'Central African', 'CF', 'CAF', '140', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(34, 'Chad', 'Chadian', 'TD', 'TCD', '148', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(35, 'Chile', 'Chilean', 'CL', 'CHL', '152', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(36, 'China', 'Chinese', 'CN', 'CHN', '156', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(37, 'Christmas Island', 'Christmas Island', 'CX', 'CXR', '162', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(38, 'Cocos (Keeling) Islands', 'Cocos Island', 'CC', 'CCK', '166', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(39, 'Colombia', 'Colombian', 'CO', 'COL', '170', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(40, 'Comoros', 'Comoran', 'KM', 'COM', '174', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(41, 'Congo (Republic of the)', 'Congolese(Congo)', 'CG', 'COG', '178', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(42, 'Congo (Democratic Republic of the)', 'Congolese(DRC)', 'CD', 'COD', '180', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(43, 'Cook Islands', 'Cook Islander', 'CK', 'COK', '184', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(44, 'Costa Rica', 'Costa Rican', 'CR', 'CRI', '188', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(45, 'Côte d\'Ivoire', 'Ivorian', 'CI', 'CIV', '384', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(46, 'Croatia', 'Croatian', 'HR', 'HRV', '191', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(47, 'Cuba', 'Cuban', 'CU', 'CUB', '192', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(48, 'Curaçao', 'Curaçaoan', 'CW', 'CUW', '531', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(49, 'Cyprus', 'Cypriot', 'CY', 'CYP', '196', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(50, 'Czech Republic', 'Czech', 'CZ', 'CZE', '203', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(51, 'Denmark', 'Danish', 'DK', 'DNK', '208', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(52, 'Djibouti', 'Djiboutian', 'DJ', 'DJI', '262', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(53, 'Dominica', 'Dominican', 'DM', 'DMA', '212', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(54, 'Dominican Republic', 'Dominican', 'DO', 'DOM', '214', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(55, 'Ecuador', 'Ecuadorian', 'EC', 'ECU', '218', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(56, 'Egypt', 'Egyptian', 'EG', 'EGY', '818', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(57, 'El Salvador', 'Salvadoran', 'SV', 'SLV', '222', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(58, 'Equatorial Guinea', 'Equatorial Guinean', 'GQ', 'GNQ', '226', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(59, 'Eritrea', 'Eritrean', 'ER', 'ERI', '232', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(60, 'Estonia', 'Estonian', 'EE', 'EST', '233', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(61, 'Ethiopia', 'Ethiopian', 'ET', 'ETH', '231', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(62, 'Falkland Islands (Malvinas)', 'Falkland Islander', 'FK', 'FLK', '238', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(63, 'Faroe Islands', 'Faroese', 'FO', 'FRO', '234', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(64, 'Fiji', 'Fijian', 'FJ', 'FJI', '242', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(65, 'Finland', 'Finnish', 'FI', 'FIN', '246', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(66, 'France', 'French', 'FR', 'FRA', '250', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(67, 'French Guiana', 'French Guianese', 'GF', 'GUF', '254', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(68, 'French Polynesia', 'French Polynesian', 'PF', 'PYF', '258', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(69, 'French Southern Territories', 'French Southern Territories', 'TF', 'ATF', '260', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(70, 'Gabon', 'Gabonese', 'GA', 'GAB', '266', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(71, 'Gambia', 'Gambian', 'GM', 'GMB', '270', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(72, 'Georgia', 'Georgian', 'GE', 'GEO', '268', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(73, 'Germany', 'German', 'DE', 'DEU', '276', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(74, 'Ghana', 'Ghanaian', 'GH', 'GHA', '288', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(75, 'Gibraltar', 'Gibraltar', 'GI', 'GIB', '292', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(76, 'Great Britain', 'British', 'GB', 'GBR', '826', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(77, 'Greece', 'Greek', 'GR', 'GRC', '300', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(78, 'Greenland', 'Greenlandic', 'GL', 'GRL', '304', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(79, 'Grenada', 'Grenadian', 'GD', 'GRD', '308', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(80, 'Guadeloupe', 'Guadeloupe', 'GP', 'GLP', '312', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(81, 'Guam', 'Guamanian', 'GU', 'GUM', '316', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(82, 'Guatemala', 'Guatemalan', 'GT', 'GTM', '320', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(83, 'Guernsey', 'Channel Islander', 'GG', 'GGY', '831', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(84, 'Guinea', 'Guinean', 'GN', 'GIN', '324', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(85, 'Guinea-Bissau', 'Bissau-Guinean', 'GW', 'GNB', '624', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(86, 'Guyana', 'Guyanese', 'GY', 'GUY', '328', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(87, 'Haiti', 'Haitian', 'HT', 'HTI', '332', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(88, 'Honduras', 'Honduran', 'HN', 'HND', '340', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(89, 'Hong Kong', 'Hong Konger', 'HK', 'HKG', '344', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(90, 'Hungary', 'Hungarian', 'HU', 'HUN', '348', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(91, 'Iceland', 'Icelandic', 'IS', 'ISL', '352', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(92, 'India', 'Indian', 'IN', 'IND', '356', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(93, 'Indonesia', 'Indonesian', 'ID', 'IDN', '360', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(94, 'Iran', 'Iranian', 'IR', 'IRN', '364', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(95, 'Iraq', 'Iraqi', 'IQ', 'IRQ', '368', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(96, 'Ireland', 'Irish', 'IE', 'IRL', '372', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(97, 'Israel', 'Israeli', 'IL', 'ISR', '376', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(98, 'Italy', 'Italian', 'IT', 'ITA', '380', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(99, 'Jamaica', 'Jamaican', 'JM', 'JAM', '388', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(100, 'Japan', 'Japanese', 'JP', 'JPN', '392', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(101, 'Jordan', 'Jordanian', 'JO', 'JOR', '400', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(102, 'Kazakhstan', 'Kazakh', 'KZ', 'KAZ', '398', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(103, 'Kenya', 'Kenyan', 'KE', 'KEN', '404', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(104, 'Kiribati', 'I-Kiribati', 'KI', 'KIR', '296', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(105, 'Korea (Republic of)', 'South Korean', 'KR', 'KOR', '410', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(106, 'Kuwait', 'Kuwaiti', 'KW', 'KWT', '414', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(107, 'Kyrgyzstan', 'Kyrgyz', 'KG', 'KGZ', '417', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(108, 'Lao People\'s Democratic Republic', 'Lao', 'LA', 'LAO', '418', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(109, 'Latvia', 'Latvian', 'LV', 'LVA', '428', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(110, 'Lebanon', 'Lebanese', 'LB', 'LBN', '422', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(111, 'Lesotho', 'Mosotho', 'LS', 'LSO', '426', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(112, 'Liberia', 'Liberian', 'LR', 'LBR', '430', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(113, 'Libya', 'Libyan', 'LY', 'LBY', '434', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(114, 'Liechtenstein', 'Liechtenstein', 'LI', 'LIE', '438', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(115, 'Lithuania', 'Lithuanian', 'LT', 'LTU', '440', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(116, 'Luxembourg', 'Luxembourger', 'LU', 'LUX', '442', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(117, 'Macao', 'Macanese', 'MO', 'MAC', '446', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(118, 'Macedonia (the former Yugoslav Republic of)', 'Macedonian', 'MK', 'MKD', '807', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(119, 'Madagascar', 'Malagasy', 'MG', 'MDG', '450', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(120, 'Malawi', 'Malawian', 'MW', 'MWI', '454', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(121, 'Malaysia', 'Malaysian', 'MY', 'MYS', '458', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(122, 'Maldives', 'Maldivian', 'MV', 'MDV', '462', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(123, 'Mali', 'Malian', 'ML', 'MLI', '466', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(124, 'Malta', 'Maltese', 'MT', 'MLT', '470', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(125, 'Marshall Islands', 'Marshallese', 'MH', 'MHL', '584', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(126, 'Martinique', 'Martiniquais', 'MQ', 'MTQ', '474', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(127, 'Mauritania', 'Mauritanian', 'MR', 'MRT', '478', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(128, 'Mauritius', 'Mauritian', 'MU', 'MUS', '480', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(129, 'Mayotte', 'Mahoran', 'YT', 'MYT', '175', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(130, 'Mexico', 'Mexican', 'MX', 'MEX', '484', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(131, 'Micronesia (Federated States of)', 'Micronesian', 'FM', 'FSM', '583', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(132, 'Moldova (Republic of)', 'Moldovan', 'MD', 'MDA', '498', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(133, 'Mongolia', 'Mongolian', 'MN', 'MNG', '496', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(134, 'Montenegro', 'Montenegrin', 'ME', 'MNE', '499', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(135, 'Montserrat', 'Montserratian', 'MS', 'MSR', '500', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(136, 'Morocco', 'Moroccan', 'MA', 'MAR', '504', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(137, 'Mozambique', 'Mozambican', 'MZ', 'MOZ', '508', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(138, 'Myanmar', 'Burmese', 'MM', 'MMR', '104', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(139, 'Namibia', 'Namibian', 'NA', 'NAM', '516', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(140, 'Nauru', 'Nauruan', 'NR', 'NRU', '520', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(141, 'Nepal', 'Nepalese', 'NP', 'NPL', '524', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(142, 'Netherlands', 'Dutch', 'NL', 'NLD', '528', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(143, 'New Caledonia', 'New Caledonian', 'NC', 'NCL', '540', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(144, 'New Zealand', 'New Zealander', 'NZ', 'NZL', '554', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(145, 'Nicaragua', 'Nicaraguan', 'NI', 'NIC', '558', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(146, 'Niger', 'Nigerien', 'NE', 'NER', '562', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(147, 'Nigeria', 'Nigerian', 'NG', 'NGA', '566', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(148, 'Niue', 'Niuean', 'NU', 'NIU', '570', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(149, 'Norfolk Island', 'Norfolk Islander', 'NF', 'NFK', '574', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(150, 'North Korea', 'North Korean', 'KP', 'PRK', '408', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(151, 'Northern Mariana Islands', 'Northern Marianan', 'MP', 'MNP', '580', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(152, 'Norway', 'Norwegian', 'NO', 'NOR', '578', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(153, 'Oman', 'Omani', 'OM', 'OMN', '512', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(154, 'Pakistan', 'Pakistani', 'PK', 'PAK', '586', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(155, 'Palau', 'Palauan', 'PW', 'PLW', '585', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(156, 'Palestine', 'Palestinian', 'PS', 'PSE', '275', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(157, 'Panama', 'Panamanian', 'PA', 'PAN', '591', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(158, 'Papua New Guinea', 'Papua New Guinean', 'PG', 'PNG', '598', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(159, 'Paraguay', 'Paraguayan', 'PY', 'PRY', '600', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(160, 'Peru', 'Peruvian', 'PE', 'PER', '604', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(161, 'Philippines', 'Filipino', 'PH', 'PHL', '608', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(162, 'Pitcairn', 'Pitcairn Islander', 'PN', 'PCN', '612', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(163, 'Poland', 'Polish', 'PL', 'POL', '616', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(164, 'Portugal', 'Portuguese', 'PT', 'PRT', '620', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(165, 'Puerto Rico', 'Puerto Rican', 'PR', 'PRI', '630', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(166, 'Qatar', 'Qatari', 'QA', 'QAT', '634', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(167, 'Réunion', 'Réunionese', 'RE', 'REU', '638', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(168, 'Romania', 'Romanian', 'RO', 'ROU', '642', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(169, 'Russian Federation', 'Russian', 'RU', 'RUS', '643', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(170, 'Rwanda', 'Rwandan', 'RW', 'RWA', '646', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(171, 'Saint Barthélemy', 'Barthélemois', 'BL', 'BLM', '652', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(172, 'Saint Helena', 'Saint Helenian', 'SH', 'SHN', '654', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(173, 'Saint Kitts and Nevis', 'Kittitian/Nevisian', 'KN', 'KNA', '659', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(174, 'Saint Lucia', 'Saint Lucian', 'LC', 'LCA', '662', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(175, 'Saint Martin (French part)', 'Saint-Martinoise', 'MF', 'MAF', '663', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(176, 'Saint Pierre and Miquelon', 'Saint-Pierrais/Miquelonnais', 'PM', 'SPM', '666', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(177, 'Saint Vincent and the Grenadines', 'Vincentian\"', 'VC', 'VCT', '670', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(178, 'Samoa', 'Samoan', 'WS', 'WSM', '882', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(179, 'San Marino', 'Sammarinese', 'SM', 'SMR', '674', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(180, 'Sao Tome and Principe', 'São Toméan', 'ST', 'STP', '678', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(181, 'Saudi Arabia', 'Saudi Arabian', 'SA', 'SAU', '682', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(182, 'Senegal', 'Senegalese', 'SN', 'SEN', '686', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(183, 'Serbia', 'Serbian', 'RS', 'SRB', '688', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(184, 'Seychelles', 'Seychellois', 'SC', 'SYC', '690', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(185, 'Sierra Leone', 'Sierra Leonean', 'SL', 'SLE', '694', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(186, 'Singapore', 'Singaporean', 'SG', 'SGP', '702', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(187, 'Sint Maarten (Dutch part)', 'Sint Maarten', 'SX', 'SXM', '534', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(188, 'Slovakia', 'Slovak', 'SK', 'SVK', '703', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(189, 'Slovenia', 'Slovenian', 'SI', 'SVN', '705', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(190, 'Solomon Islands', 'Solomon Islander', 'SB', 'SLB', '90', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(191, 'Somalia', 'Somali', 'SO', 'SOM', '706', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(192, 'South Africa', 'South African', 'ZA', 'ZAF', '710', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(193, 'South Georgia and the South Sandwich Islands', 'South Georgia/South', 'GS', 'SGS', '239', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(194, 'South Sudan', 'South Sudanese', 'SS', 'SSD', '728', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(195, 'Spain', 'Spanish', 'ES', 'ESP', '724', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(196, 'Sri Lanka', 'Sri Lankan', 'LK', 'LKA', '144', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(197, 'Sudan', 'Sudanese', 'SD', 'SDN', '729', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(198, 'Suriname', 'Surinamese', 'SR', 'SUR', '740', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(199, 'Svalbard and Jan Mayen', 'Svalbard', 'SJ', 'SJM', '744', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(200, 'Swaziland', 'Swazi', 'SZ', 'SWZ', '748', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(201, 'Sweden', 'Swedish', 'SE', 'SWE', '752', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(202, 'Switzerland', 'Swiss', 'CH', 'CHE', '756', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(203, 'Syrian Arab Republic', 'Syrian', 'SY', 'SYR', '760', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(204, 'Taiwan, Province of China', 'Taiwanese', 'TW', 'TWN', '158', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(205, 'Tajikistan', 'Tajikistani', 'TJ', 'TJK', '762', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(206, 'Tanzania, United Republic of', 'Tanzanian', 'TZ', 'TZA', '834', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(207, 'Thailand', 'Thai', 'TH', 'THA', '764', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(208, 'Timor-Leste', 'Timorese', 'TL', 'TLS', '626', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(209, 'Togo', 'Togolese', 'TG', 'TGO', '768', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(210, 'Tokelau', 'Tokelauan', 'TK', 'TKL', '772', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(211, 'Tonga', 'Tongan', 'TO', 'TON', '776', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(212, 'Trinidad and Tobago', 'Trinidadian/Tobagonian', 'TT', 'TTO', '780', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(213, 'Tunisia', 'Tunisian', 'TN', 'TUN', '788', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(214, 'Turkey', 'Turkish', 'TR', 'TUR', '792', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(215, 'Turkmenistan', 'Turkmen', 'TM', 'TKM', '795', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(216, 'Turks and Caicos Islands', 'Turks and Caicos Islander', 'TC', 'TCA', '796', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(217, 'Tuvalu', 'Tuvaluan', 'TV', 'TUV', '798', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(218, 'Uganda', 'Ugandan', 'UG', 'UGA', '800', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(219, 'Ukraine', 'Ukrainian', 'UA', 'UKR', '804', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(220, 'United Arab Emirates', 'Emirati', 'AE', 'UAE', '784', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(221, 'United States of America', 'American', 'US', 'USA', '840', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(222, 'Uruguay', 'Uruguayan', 'UY', 'URY', '858', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(223, 'Uzbekistan', 'Uzbek', 'UZ', 'UZB', '860', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(224, 'Vanuatu', 'Vanuatuan', 'VU', 'VUT', '548', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(225, 'Vatican City State', 'Vatican citizen', 'VA', 'VAT', '336', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(226, 'Venezuela (Bolivarian Republic of)', 'Venezuelan', 'VE', 'VEN', '862', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(227, 'Vietnam', 'Vietnamese', 'VN', 'VNM', '704', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(228, 'Virgin Islands (British)', 'British Virgin Islander', 'VG', 'VGB', '92', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(229, 'Virgin Islands (U.S.)', 'U.S. Virgin Islander', 'VI', 'VIR', '850', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(230, 'Wallis and Futuna', 'Wallisian/Futunan', 'WF', 'WLF', '876', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(231, 'Western Sahara', 'Sahrawi', 'EH', 'ESH', '732', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(232, 'Yemen', 'Yemeni', 'YE', 'YEM', '887', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(233, 'Zambia', 'Zambian', 'ZM', 'ZMB', '894', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL),
(234, 'Zimbabwe', 'Zimbabwean', 'ZW', 'ZWE', '716', '2023-10-03 08:32:47', '2023-10-03 08:32:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `particulars`
--

CREATE TABLE `particulars` (
  `id` int(11) UNSIGNED NOT NULL,
  `particular_name` varchar(100) NOT NULL,
  `opening_balance` float NOT NULL,
  `debit` float NOT NULL,
  `credit` float NOT NULL,
  `particular_slug` varchar(100) NOT NULL,
  `particular_type` enum('System','Custom') NOT NULL DEFAULT 'System',
  `particular_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `charged` enum('Yes','No') NOT NULL DEFAULT 'No',
  `charge` double DEFAULT NULL,
  `charge_method` enum('Amount','Percent') DEFAULT NULL,
  `charge_mode` enum('Auto','Manual') DEFAULT NULL,
  `charge_frequency` enum('One-Time','Weekly','Bi-Weekly','Monthly','Bi-Monthly','Quarterly','Termly','Bi-Annual','Annually') DEFAULT NULL,
  `grace_period` int(6) NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `subcategory_id` int(11) UNSIGNED NOT NULL,
  `account_typeId` int(11) UNSIGNED NOT NULL,
  `cash_flow_typeId` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `particulars`
--

INSERT INTO `particulars` (`id`, `particular_name`, `opening_balance`, `debit`, `credit`, `particular_slug`, `particular_type`, `particular_status`, `charged`, `charge`, `charge_method`, `charge_mode`, `charge_frequency`, `grace_period`, `category_id`, `subcategory_id`, `account_typeId`, `cash_flow_typeId`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cash', 0, 0, 0, 'cash', 'System', 'Inactive', '', 0, '', '', '', 0, 1, 1, 1, 2, '2023-10-03 08:35:24', '2023-10-10 01:12:24', NULL),
(2, 'Equity Bank', 0, 500000000, 0, 'equity-bank', 'System', 'Active', '', 0, '', '', '', 0, 1, 1, 1, 2, '2023-10-03 08:35:24', '2023-11-27 13:24:04', NULL),
(3, 'Lender Investments', 0, 0, 0, 'lender-investments', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 2, 2, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(4, 'Gross Loans ~ Principal', 0, 0, 0, 'gross-loans-principal', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 3, 1, '2023-10-03 08:35:24', '2023-10-19 04:29:14', NULL),
(5, 'Receivable ~ Interest on Loans', 0, 0, 0, 'receivable-interest-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(6, 'Receivable ~ Fee and Commission Loans', 0, 0, 0, 'receivable-fee-and-commission-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(7, 'Receivable ~ Penalty Loans', 0, 0, 0, 'receivable-penalty-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(8, 'Receivable ~ Revenue on Client Saving Deposit', 0, 0, 0, 'receivable-revenue-on-client-saving-deposit', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(9, 'Receivable ~ Revenue on Investor Deposit', 0, 0, 0, 'receivable-revenue-on-investor-deposit', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(10, 'Receivable ~ Non Operating Revenue', 0, 0, 0, 'receivable-non-operating-revenue', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 1, 4, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(11, 'Computers', 0, 0, 0, 'computers', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 1, 2, 7, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(12, 'Share Capital', 0, 0, 500000000, 'share-capital', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 2, 3, 8, 3, '2023-10-03 08:35:24', '2023-11-27 13:24:04', NULL),
(13, 'Retained Earnings', 0, 0, 0, 'retained-earnings', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 2, 4, 8, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(14, 'Payable ~ Client Saving Deposit', 0, 0, 0, 'payable-client-saving-deposit', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(15, 'Payable ~ Investor Deposit', 0, 0, 0, 'payable-investor-deposit', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(16, 'Payable ~ Merchant Borrowings', 0, 0, 0, 'payable-merchant-borrowings', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 5, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(17, 'Payable ~ Payroll Expenses', 0, 0, 0, 'payable-payroll-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(18, 'Payable ~ Non Operating Expenses', 0, 0, 0, 'payable-non-operating-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(19, 'Payable ~ Miscellaneous Expenses', 0, 0, 0, 'payable-miscellaneous-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 16, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(20, 'Client Savings', 0, 0, 0, 'client-savings', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 12, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(21, 'Investor Deposit', 0, 0, 0, 'investor-deposit', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 13, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(22, 'Accumulated Loan Impairment', 0, 0, 0, 'accumulated-loan-impairment', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 5, 11, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(23, 'Accumulated Depreciation and Amortization', 0, 0, 0, 'accumulated-depreciation-and-amortization', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 5, 10, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(24, 'Income Tax Liability', 0, 0, 0, 'icome-tax-liability', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 3, 6, 14, 5, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(25, 'Loan Application Fees', 0, 0, 0, 'loan-application-fees', 'System', 'Active', 'Yes', 20000, 'Amount', 'Auto', NULL, 0, 4, 7, 18, 1, '2023-10-03 08:35:24', '2023-10-19 04:29:14', NULL),
(26, 'Loan Processing Fees', 0, 0, 0, 'loan-processing-fees', 'System', 'Inactive', 'Yes', 1, 'Percent', 'Auto', NULL, 0, 4, 7, 18, 1, '2023-10-03 08:35:24', '2023-10-10 17:19:02', NULL),
(27, 'Loan Appraisal Fees', 0, 0, 0, 'loan-appraisal-fees', 'System', 'Inactive', 'Yes', 1, 'Percent', 'Auto', NULL, 0, 4, 7, 18, 1, '2023-10-03 08:35:24', '2023-10-10 17:19:10', NULL),
(28, 'Loan Cash Cover', 0, 0, 0, 'loan-cash-cover', 'System', 'Inactive', 'Yes', 10, 'Percent', 'Auto', NULL, 0, 3, 6, 18, 1, '2023-10-03 08:35:24', '2023-10-10 17:19:17', NULL),
(29, 'Interest on Loans', 0, 0, 0, 'interest-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 7, 19, 1, '2023-10-03 08:35:24', '2023-10-19 04:29:14', NULL),
(30, 'Fees and Commission on Loans', 0, 0, 0, 'fess-and-commission-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 7, 19, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(31, 'Penalties on Loans', 0, 0, 0, 'penalties-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 7, 19, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(32, 'Revenue from Client Saving Deposits', 0, 0, 0, 'revenue-from-client-saving-deposits', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 7, 20, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(33, 'Revenue from Investor Deposits', 0, 0, 0, 'revenue-from-investor-deposits', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 7, 20, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(34, 'Non Operating Revenue', 0, 0, 0, 'non-operating-revenue', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 8, 22, 4, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(35, 'Subsidy', 0, 0, 0, 'subsidy', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 4, 8, 23, 3, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(36, 'Client Membership', 0, 0, 0, 'client-membership', 'System', 'Active', 'Yes', 20000, 'Amount', 'Manual', 'One-Time', 0, 4, 7, 24, 3, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(37, 'Provision for Impairment ~ Loan Principal', 0, 0, 0, 'provision-for-impairment-loan-principal', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 33, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(38, 'Provision for Impairment ~ Interest loan', 0, 0, 0, 'provision-for-impairment-interest-loan', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 33, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(39, 'Provision for Impairment ~ Fees and Commission on Loans', 0, 0, 0, 'provision-for-impairment-feess-and-commisson-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 33, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(40, 'Provision for Impairment ~ Penalty on Loans', 0, 0, 0, 'provision-for-impairment-loan-penalty-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 33, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(41, 'Default ~ Loans Principal', 0, 0, 0, 'default-loans-principal', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 26, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(42, 'Default ~ Interest on Loans', 0, 0, 0, 'default-interest-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 26, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(43, 'Default ~ Fees and Commission on Loans', 0, 0, 0, 'default-fees-and-commission-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 26, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(44, 'Default ~ Penalty on Loans', 0, 0, 0, 'default-penalty-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 26, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(45, 'Depreciation and Amortization Expenses', 0, 0, 0, 'depreciation-and-amortization-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 27, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(46, 'Restructured ~ Loan Principal', 0, 0, 0, 'restructured-loan-principal', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 34, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(47, 'Restructured ~ Interest on Loans', 0, 0, 0, 'restructured-interest-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 34, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(48, 'Restructured ~ Fees and Commission on Loans', 0, 0, 0, 'restructured-fees and-commission-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 34, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(49, 'Restructured ~ Penalty on Loans', 0, 0, 0, 'restructured-penalty-on-loans', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 34, 6, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(50, 'Loss(or Gain) on Asset Disposal', 0, 0, 0, 'loss(or-gain)-on-asset-disposal', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 25, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(51, 'Payroll Expenses', 0, 0, 0, 'payroll-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 31, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(52, 'Exchange Rete Losses(or Gains)', 0, 0, 0, 'exchange-rate-losses(or-gains)', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 31, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(53, 'Expenses on Client Savings Deposits', 0, 0, 0, 'expenses-on-client-savings-deposits', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 29, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(54, 'Expenses on Investor Deposits', 0, 0, 0, 'expenses-on-investor-deposits', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 29, 1, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(55, 'Expenses on Merchant Borrowings', 0, 0, 0, 'expenses-on-merchant-borrowings', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 30, 3, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(56, 'Non Operating Expenses', 0, 0, 0, 'non-operating-expenses', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 32, 4, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(57, 'Income Tax Expense', 0, 0, 0, 'income-tax-expense', 'System', 'Active', 'No', NULL, NULL, NULL, NULL, 0, 5, 9, 35, 5, '2023-10-03 08:35:24', '2023-10-03 08:35:24', NULL),
(58, 'Centenary Bank', 0, 0, 0, 'centenary-bank', 'Custom', 'Active', '', 0, '', '', '', 0, 1, 1, 1, 3, '2023-10-03 08:46:10', '2023-11-22 12:37:38', NULL),
(59, 'Bank Of Africa', 0, 0, 0, 'bank-of-africa', 'Custom', 'Active', '', 0, '', '', '', 0, 1, 1, 1, 3, '2023-10-03 08:46:44', '2023-10-09 17:46:33', NULL),
(60, 'consulation fees', 0, 0, 0, 'consulation-fees', 'Custom', 'Active', '', 0, '', '', '', 0, 5, 9, 25, 1, '2023-10-17 14:48:25', '2023-10-17 14:53:52', '2023-10-17 14:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) UNSIGNED NOT NULL,
  `department_id` int(11) UNSIGNED NOT NULL,
  `position` varchar(100) NOT NULL,
  `position_slug` varchar(100) NOT NULL,
  `position_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `permissions` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `department_id`, `position`, `position_slug`, `position_status`, `permissions`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Super Admin', 'super-admin', 'Inactive', 's:3:\"all\";', '2023-10-03 08:33:33', '2023-10-03 08:33:33', NULL),
(2, 1, 'Credit Officer', 'credit-officer', 'Active', 'a:28:{i:0;s:13:\"viewDashboard\";i:1;s:13:\"createClients\";i:2;s:11:\"viewClients\";i:3;s:13:\"updateClients\";i:4;s:13:\"exportClients\";i:5;s:9:\"viewLoans\";i:6;s:14:\"createProducts\";i:7;s:14:\"importProducts\";i:8;s:12:\"viewProducts\";i:9;s:14:\"updateProducts\";i:10;s:14:\"deleteProducts\";i:11;s:14:\"exportProducts\";i:12;s:18:\"createApplications\";i:13;s:18:\"importApplications\";i:14;s:16:\"viewApplications\";i:15;s:18:\"updateApplications\";i:16;s:18:\"deleteApplications\";i:17;s:18:\"exportApplications\";i:18;s:17:\"viewDisbursements\";i:19;s:19:\"exportDisbursements\";i:20;s:18:\"createApplications\";i:21;s:18:\"importApplications\";i:22;s:16:\"viewApplications\";i:23;s:18:\"updateApplications\";i:24;s:18:\"deleteApplications\";i:25;s:18:\"exportApplications\";i:26;s:11:\"viewReports\";i:27;s:13:\"exportReports\";}', '2023-10-03 08:33:33', '2023-10-18 11:03:15', NULL),
(3, 1, 'Supervisor', 'supervisor', 'Active', 'a:28:{i:0;s:13:\"viewDashboard\";i:1;s:13:\"createClients\";i:2;s:11:\"viewClients\";i:3;s:13:\"updateClients\";i:4;s:13:\"exportClients\";i:5;s:9:\"viewLoans\";i:6;s:14:\"createProducts\";i:7;s:14:\"importProducts\";i:8;s:12:\"viewProducts\";i:9;s:14:\"updateProducts\";i:10;s:14:\"deleteProducts\";i:11;s:14:\"exportProducts\";i:12;s:18:\"createApplications\";i:13;s:18:\"importApplications\";i:14;s:16:\"viewApplications\";i:15;s:18:\"updateApplications\";i:16;s:18:\"deleteApplications\";i:17;s:18:\"exportApplications\";i:18;s:17:\"viewDisbursements\";i:19;s:19:\"exportDisbursements\";i:20;s:18:\"createApplications\";i:21;s:18:\"importApplications\";i:22;s:16:\"viewApplications\";i:23;s:18:\"updateApplications\";i:24;s:18:\"deleteApplications\";i:25;s:18:\"exportApplications\";i:26;s:11:\"viewReports\";i:27;s:13:\"exportReports\";}', '2023-10-03 08:33:33', '2023-10-18 11:03:36', NULL),
(4, 1, 'Operations Officer', 'operations-officer', 'Active', 'a:13:{i:0;s:13:\"viewDashboard\";i:1;s:11:\"viewClients\";i:2;s:9:\"viewLoans\";i:3;s:12:\"viewProducts\";i:4;s:14:\"updateProducts\";i:5;s:18:\"createApplications\";i:6;s:18:\"importApplications\";i:7;s:16:\"viewApplications\";i:8;s:18:\"updateApplications\";i:9;s:18:\"deleteApplications\";i:10;s:22:\"bulkDeleteApplications\";i:11;s:18:\"exportApplications\";i:12;s:17:\"viewDisbursements\";}', '2023-10-03 08:33:33', '2023-10-18 11:08:10', NULL),
(5, 1, 'Accounts Officer', 'accounts-officer', 'Active', 'a:101:{i:0;s:13:\"viewDashboard\";i:1;s:11:\"viewCompany\";i:2;s:12:\"viewBranches\";i:3;s:14:\"exportBranches\";i:4;s:15:\"viewDepartments\";i:5;s:17:\"exportDepartments\";i:6;s:15:\"exportPositions\";i:7;s:14:\"viewAccounting\";i:8;s:19:\"viewChartofAccounts\";i:9;s:21:\"updateChartofAccounts\";i:10;s:17:\"viewSubcategories\";i:11;s:19:\"updateSubcategories\";i:12;s:19:\"exportSubcategories\";i:13;s:15:\"viewParticulars\";i:14;s:17:\"updateParticulars\";i:15;s:17:\"exportParticulars\";i:16;s:10:\"viewStaffs\";i:17;s:18:\"viewAdministrators\";i:18;s:20:\"exportAdministrators\";i:19;s:13:\"viewEmployees\";i:20;s:15:\"exportEmployees\";i:21;s:11:\"viewClients\";i:22;s:13:\"exportClients\";i:23;s:18:\"viewUserManagement\";i:24;s:11:\"exportUsers\";i:25;s:12:\"exportLogins\";i:26;s:14:\"exportActivity\";i:27;s:9:\"viewLoans\";i:28;s:12:\"viewProducts\";i:29;s:14:\"exportProducts\";i:30;s:18:\"createApplications\";i:31;s:18:\"importApplications\";i:32;s:16:\"viewApplications\";i:33;s:18:\"updateApplications\";i:34;s:18:\"deleteApplications\";i:35;s:22:\"bulkDeleteApplications\";i:36;s:18:\"exportApplications\";i:37;s:19:\"createDisbursements\";i:38;s:17:\"viewDisbursements\";i:39;s:19:\"exportDisbursements\";i:40;s:16:\"viewTransactions\";i:41;s:13:\"createSavings\";i:42;s:13:\"importSavings\";i:43;s:11:\"viewSavings\";i:44;s:13:\"updateSavings\";i:45;s:13:\"deleteSavings\";i:46;s:17:\"bulkDeleteSavings\";i:47;s:13:\"exportSavings\";i:48;s:16:\"createRepayments\";i:49;s:16:\"importRepayments\";i:50;s:14:\"viewRepayments\";i:51;s:16:\"updateRepayments\";i:52;s:16:\"deleteRepayments\";i:53;s:20:\"bulkDeleteRepayments\";i:54;s:16:\"exportRepayments\";i:55;s:18:\"createApplications\";i:56;s:18:\"importApplications\";i:57;s:16:\"viewApplications\";i:58;s:18:\"updateApplications\";i:59;s:18:\"deleteApplications\";i:60;s:22:\"bulkDeleteApplications\";i:61;s:18:\"exportApplications\";i:62;s:16:\"createMembership\";i:63;s:16:\"importMembership\";i:64;s:14:\"viewMembership\";i:65;s:16:\"updateMembership\";i:66;s:16:\"deleteMembership\";i:67;s:20:\"bulkDeleteMembership\";i:68;s:16:\"exportMembership\";i:69;s:14:\"createExpenses\";i:70;s:14:\"importExpenses\";i:71;s:12:\"viewExpenses\";i:72;s:14:\"updateExpenses\";i:73;s:14:\"deleteExpenses\";i:74;s:18:\"bulkDeleteExpenses\";i:75;s:14:\"exportExpenses\";i:76;s:14:\"createTransfer\";i:77;s:14:\"importTransfer\";i:78;s:12:\"viewTransfer\";i:79;s:14:\"updateTransfer\";i:80;s:14:\"deleteTransfer\";i:81;s:18:\"bulkDeleteTransfer\";i:82;s:14:\"exportTransfer\";i:83;s:16:\"createInvestment\";i:84;s:16:\"importInvestment\";i:85;s:14:\"viewInvestment\";i:86;s:16:\"updateInvestment\";i:87;s:16:\"deleteInvestment\";i:88;s:20:\"bulkDeleteInvestment\";i:89;s:16:\"exportInvestment\";i:90;s:14:\"viewStatements\";i:91;s:16:\"viewBalanceSheet\";i:92;s:18:\"exportBalanceSheet\";i:93;s:14:\"viewProfitLoss\";i:94;s:16:\"exportProfitLoss\";i:95;s:16:\"viewTrialBalance\";i:96;s:18:\"exportTrialBalance\";i:97;s:12:\"viewCashFlow\";i:98;s:14:\"exportCashFlow\";i:99;s:11:\"viewReports\";i:100;s:13:\"exportReports\";}', '2023-10-09 18:08:41', '2023-10-15 02:45:33', NULL),
(6, 1, 'Admin', 'admin', 'Active', 'a:69:{i:0;s:13:\"viewDashboard\";i:1;s:14:\"viewAccounting\";i:2;s:19:\"viewChartofAccounts\";i:3;s:21:\"updateChartofAccounts\";i:4;s:19:\"createSubcategories\";i:5;s:17:\"viewSubcategories\";i:6;s:19:\"updateSubcategories\";i:7;s:19:\"exportSubcategories\";i:8;s:17:\"createParticulars\";i:9;s:15:\"viewParticulars\";i:10;s:17:\"updateParticulars\";i:11;s:17:\"exportParticulars\";i:12;s:13:\"createClients\";i:13;s:13:\"importClients\";i:14;s:11:\"viewClients\";i:15;s:13:\"updateClients\";i:16;s:13:\"exportClients\";i:17;s:9:\"viewLoans\";i:18;s:14:\"createProducts\";i:19;s:12:\"viewProducts\";i:20;s:14:\"updateProducts\";i:21;s:14:\"exportProducts\";i:22;s:18:\"createApplications\";i:23;s:18:\"importApplications\";i:24;s:16:\"viewApplications\";i:25;s:18:\"updateApplications\";i:26;s:18:\"exportApplications\";i:27;s:19:\"importDisbursements\";i:28;s:17:\"viewDisbursements\";i:29;s:19:\"updateDisbursements\";i:30;s:19:\"exportDisbursements\";i:31;s:16:\"viewTransactions\";i:32;s:13:\"createSavings\";i:33;s:13:\"importSavings\";i:34;s:11:\"viewSavings\";i:35;s:13:\"updateSavings\";i:36;s:13:\"exportSavings\";i:37;s:16:\"createRepayments\";i:38;s:16:\"importRepayments\";i:39;s:14:\"viewRepayments\";i:40;s:16:\"updateRepayments\";i:41;s:16:\"exportRepayments\";i:42;s:18:\"createApplications\";i:43;s:18:\"importApplications\";i:44;s:16:\"viewApplications\";i:45;s:18:\"updateApplications\";i:46;s:18:\"exportApplications\";i:47;s:16:\"createMembership\";i:48;s:16:\"importMembership\";i:49;s:14:\"viewMembership\";i:50;s:16:\"updateMembership\";i:51;s:16:\"exportMembership\";i:52;s:14:\"createExpenses\";i:53;s:14:\"importExpenses\";i:54;s:12:\"viewExpenses\";i:55;s:14:\"updateExpenses\";i:56;s:14:\"exportExpenses\";i:57;s:14:\"createTransfer\";i:58;s:14:\"importTransfer\";i:59;s:12:\"viewTransfer\";i:60;s:14:\"updateTransfer\";i:61;s:14:\"exportTransfer\";i:62;s:16:\"createInvestment\";i:63;s:16:\"importInvestment\";i:64;s:14:\"viewInvestment\";i:65;s:16:\"updateInvestment\";i:66;s:16:\"exportInvestment\";i:67;s:11:\"viewReports\";i:68;s:13:\"exportReports\";}', '2023-10-18 11:12:56', '2023-10-18 11:12:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `author` varchar(60) NOT NULL,
  `system_name` varchar(50) NOT NULL,
  `system_abbr` varchar(100) NOT NULL,
  `system_slogan` varchar(255) NOT NULL,
  `system_version` varchar(30) NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `business_abbr` varchar(10) NOT NULL,
  `business_slogan` varchar(255) NOT NULL,
  `business_contact` varchar(20) NOT NULL,
  `business_alt_contact` varchar(20) NOT NULL,
  `business_email` varchar(30) NOT NULL,
  `business_pobox` varchar(30) NOT NULL,
  `business_address` varchar(255) NOT NULL,
  `business_web` varchar(255) NOT NULL,
  `business_logo` varchar(30) NOT NULL,
  `business_about` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `background_logo` varchar(100) NOT NULL,
  `email_template_logo` varchar(200) NOT NULL,
  `google_map_iframe` text NOT NULL,
  `whatsapp` text NOT NULL,
  `facebook` text NOT NULL,
  `twitter` text NOT NULL,
  `instagram` text NOT NULL,
  `youtube` text NOT NULL,
  `linkedin` text NOT NULL,
  `tax_rate` double NOT NULL,
  `round_off` int(6) NOT NULL,
  `currency_id` int(6) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `author`, `system_name`, `system_abbr`, `system_slogan`, `system_version`, `business_name`, `business_abbr`, `business_slogan`, `business_contact`, `business_alt_contact`, `business_email`, `business_pobox`, `business_address`, `business_web`, `business_logo`, `business_about`, `description`, `background_logo`, `email_template_logo`, `google_map_iframe`, `whatsapp`, `facebook`, `twitter`, `instagram`, `youtube`, `linkedin`, `tax_rate`, `round_off`, `currency_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Saipali', 'Saipali Micro Credit', 'Saipali', 'Applying Knowledge', '1.0.0.1', 'Saipali Micro Credit', 'Nexen', 'Applying Knowledge', '+256777237827', '+256702999488', 'danfodio@realdailykash.com', 'P.O Box 01, Kampala', 'Mengo, Bakuli Opp Victorious PS', 'nexenmicrocredit.com', '1630914980648.png', 'Saipali', '', 'background.jpg', 'https://microfinance.realdailykash.com/uploads/logo/logo.jpeg', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug\" width=\"100%\" height=\"400\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '', '', '', '', '', '', 30, 100, 143, '2023-10-03 08:32:27', '2023-11-27 05:22:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` int(11) UNSIGNED NOT NULL,
  `staffID` varchar(100) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `alternate_mobile` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(100) NOT NULL,
  `account_type` enum('Employee','Administrator','Super') NOT NULL DEFAULT 'Employee',
  `branch_id` int(11) UNSIGNED NOT NULL,
  `position_id` int(11) UNSIGNED NOT NULL,
  `officer_staff_id` int(11) UNSIGNED DEFAULT NULL,
  `id_type` varchar(100) NOT NULL,
  `id_number` varchar(15) NOT NULL,
  `id_expiry_date` date DEFAULT NULL,
  `qualifications` varchar(100) NOT NULL,
  `salary_scale` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_branch` varchar(100) NOT NULL,
  `bank_account` varchar(100) NOT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `id_photo_front` varchar(100) NOT NULL,
  `id_photo_back` varchar(100) NOT NULL,
  `signature` varchar(50) NOT NULL,
  `access_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `reg_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `staffID`, `staff_name`, `mobile`, `alternate_mobile`, `email`, `gender`, `marital_status`, `religion`, `nationality`, `date_of_birth`, `address`, `account_type`, `branch_id`, `position_id`, `officer_staff_id`, `id_type`, `id_number`, `id_expiry_date`, `qualifications`, `salary_scale`, `bank_name`, `bank_branch`, `bank_account`, `appointment_type`, `photo`, `id_photo_front`, `id_photo_back`, `signature`, `access_status`, `reg_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0001', 'Administrator', '0785632882', '', 'admin@sitm.com', '', '', '', '', '0000-00-00', 'Mengo', 'Super', 1, 1, NULL, '', '', NULL, '', '', '', '', '', '', '', '', '', '', 'Active', NULL, '2023-10-03 08:33:43', '2023-10-03 08:33:43', NULL),
(2, 'A2310100001', 'Doreen K', '+256700139499', '', 'dkyomuhangi@nexenmicrocredit.com', 'Female', 'Married', 'Protestant', 'Ugandan', '1995-10-18', 'Kampala.Uganda', 'Administrator', 1, 4, 1, 'National ID', 'CM25354645288U', '0000-00-00', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 09:43:05', '2023-10-10 10:11:00', '2023-10-10 10:11:00'),
(3, 'A2310100002', 'Ayikoru Jamila', '+256764995503', '', 'jammyayikoru22@gmail.com', 'Female', 'Single', 'Protestant', 'Ugandan', '1999-10-06', 'kampala,Uganda', 'Administrator', 1, 6, 1, 'National ID', 'CF454735735373', '2023-10-19', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 09:48:58', '2023-10-18 05:27:28', NULL),
(12, 'S2310100001', 'Apio Josephine', '+256774649641', '', 'angelinasdaniel@gmail.com', 'Male', 'Single', 'Protestant', 'Ugandan', '2000-10-01', 'Kampala', 'Employee', 1, 2, 1, 'National ID', 'CF209774649641', '2025-10-11', 'Degree', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 11:02:14', '2023-10-10 11:02:14', NULL),
(13, 'S2310100002', 'Doreen K', '+256700139499', '', 'doreenkyoma@gmail.com', 'Female', 'Married', 'Protestant', 'Ugandan', '1995-10-18', 'kampala', 'Employee', 1, 4, 1, 'National ID', 'CM213465654522', '2023-10-10', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 11:07:24', '2023-10-10 11:07:24', NULL),
(14, 'S2310100003', 'Racheal A', '+256701147780', '', 'ampeirerachel788@gmail.com', 'Female', 'Single', 'Protestant', 'Ugandan', '1995-10-18', 'kampala', 'Employee', 1, 3, 1, 'National ID', 'CM235464656455', '2023-10-11', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 11:12:21', '2023-10-10 11:12:21', NULL),
(15, 'S2310100004', 'Lukolokomba Julius', '+256706707683', '', 'kiyimba4445@gmail.com', 'Male', 'Married', 'Protestant', 'Ugandan', '1995-10-18', 'kampala', 'Employee', 1, 2, 1, 'National ID', 'CM215213456456', '2024-10-23', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-10', '2023-10-10 11:15:22', '2023-10-10 11:15:22', NULL),
(16, 'S2310230005', 'loyo boo', '+256778547484', '', 'okotpaulpeter@gmail.com', 'Male', 'Single', 'Catholic', 'Ugandan', '1995-10-10', 'Gulu', 'Employee', 1, 6, 1, 'National ID', 'CM124532465455', '2025-10-08', '', '', '', '', '', 'Full-Time', '', '', '', '', 'Active', '2023-10-23', '2023-10-23 22:14:17', '2023-10-23 22:14:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

CREATE TABLE `statements` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `statements`
--

INSERT INTO `statements` (`id`, `name`, `code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Balance Sheet', 'BS100', '2023-10-03 08:34:19', '2023-10-03 08:34:19', NULL),
(2, 'Income Statement', 'PL200', '2023-10-03 08:34:19', '2023-10-03 08:34:19', NULL),
(3, 'Trial Balance', 'TB300', '2023-10-03 08:34:19', '2023-10-03 08:34:19', NULL),
(4, 'Cash Flow Statement', 'CF400', '2023-10-03 08:34:19', '2023-10-03 08:34:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) UNSIGNED NOT NULL,
  `subcategory_name` varchar(100) NOT NULL,
  `subcategory_slug` varchar(100) NOT NULL,
  `subcategory_type` enum('System','Custom') NOT NULL DEFAULT 'System',
  `subcategory_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `category_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `subcategory_name`, `subcategory_slug`, `subcategory_type`, `subcategory_status`, `category_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Current Assets', 'current-assets', 'System', 'Active', 1, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(2, 'Fixed Assets', 'fixed-assets', 'System', 'Active', 1, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(3, 'Capital', 'capital', 'System', 'Active', 2, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(4, 'Reserves', 'reserves', 'System', 'Active', 2, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(5, 'Long-Term Liabilities', 'long-term-liabilities', 'System', 'Active', 3, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(6, 'Current Liabilities', 'current-liabilities', 'System', 'Active', 3, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(7, 'Primary Income', 'primary-income', 'System', 'Active', 4, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(8, 'Other Income', 'other-income', 'System', 'Active', 4, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(9, 'Direct Expenses', 'direct-expenses', 'System', 'Active', 5, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL),
(10, 'Other Expenses', 'other-expenses', 'System', 'Active', 5, '2023-10-03 08:34:44', '2023-10-03 08:34:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `useractivities`
--

CREATE TABLE `useractivities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `action` enum('create','import','upload','update','delete','bulk-delete') NOT NULL,
  `description` text NOT NULL,
  `module` varchar(20) NOT NULL,
  `referrer_id` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `useractivities`
--

INSERT INTO `useractivities` (`id`, `user_id`, `client_id`, `action`, `description`, `module`, `referrer_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, NULL, 'create', 'New investment Transaction, Ref ID 22301919', 'Transactions', '22301919', '2023-10-19 12:06:39', '2023-10-19 12:06:39', NULL),
(2, 2, NULL, 'create', 'Added remarks for application: A2310190001status: Processing level: Credit OfficeractionApproved', 'applications', '1', '2023-10-19 14:04:50', '2023-10-19 14:04:50', NULL),
(3, 2, NULL, 'create', 'Added remarks for application: A2310190001status: Processing level: SupervisoractionApproved', 'applications', '1', '2023-10-19 14:05:03', '2023-10-19 14:05:03', NULL),
(4, 2, NULL, 'create', 'Added remarks for application: A2310190001status: Processing level: Operations OfficeractionApproved', 'applications', '1', '2023-10-19 14:05:16', '2023-10-19 14:05:16', NULL),
(5, 2, NULL, 'create', 'Added remarks for application: A2310190001status: Approved level: Accounts OfficeractionApproved', 'applications', '1', '2023-10-19 14:05:47', '2023-10-19 14:05:47', NULL),
(6, 1, NULL, 'update', 'Updated Applications, A2310190001', 'applications', '1', '2023-10-19 15:12:08', '2023-10-19 15:12:08', NULL),
(7, 2, NULL, 'update', 'Edited application remarks for application: A2310190001status: Processing level: Accounts OfficeractionDeclined', 'applications', '1', '2023-10-19 15:23:07', '2023-10-19 15:23:07', NULL),
(8, 2, NULL, 'update', 'Updated Applications, A2310210002', 'applications', '2', '2023-10-21 12:59:23', '2023-10-21 12:59:23', NULL),
(9, 2, NULL, 'create', 'Create 1 Employees records', 'Staff', '16', '2023-10-23 22:14:17', '2023-10-23 22:14:17', NULL),
(10, NULL, NULL, 'create', 'Created Client Account Registration, Okot Paul Peter', 'Clients', '599', '2023-10-23 22:21:25', '2023-10-23 22:21:25', NULL),
(11, NULL, NULL, 'update', '', 'Clients', '1', '2023-10-23 22:27:12', '2023-10-23 22:27:12', NULL),
(12, 1, NULL, 'update', 'Updated Particulars, Centenary Bank', 'particulars', '58', '2023-11-22 12:37:38', '2023-11-22 12:37:38', NULL),
(13, NULL, NULL, 'create', 'Created Client Account Registration, Danfodio', 'Clients', '600', '2023-11-25 20:56:37', '2023-11-25 20:56:37', NULL),
(14, NULL, NULL, 'update', '', 'Clients', '1', '2023-11-25 20:59:47', '2023-11-25 20:59:47', NULL),
(15, 1, NULL, 'update', 'Updated Settings, for Nexen Micro Credit ', 'Settings', '1', '2023-11-27 02:30:07', '2023-11-27 02:30:07', NULL),
(16, 1, NULL, 'update', 'Updated Settings, for Saipali Micro Credit ', 'Settings', '1', '2023-11-27 02:31:48', '2023-11-27 02:31:48', NULL),
(17, NULL, NULL, 'create', 'Created Client Account Registration, Danfodio', 'Clients', '601', '2023-11-27 11:36:28', '2023-11-27 11:36:28', NULL),
(18, NULL, NULL, 'create', 'Created Client Account Registration, Shalom', 'Clients', '602', '2023-11-27 11:39:02', '2023-11-27 11:39:02', NULL),
(19, NULL, NULL, 'update', '', 'Clients', '1', '2023-11-27 11:41:34', '2023-11-27 11:41:34', NULL),
(20, 1, NULL, 'update', 'Updated Settings, for Saipali Micro Credit', 'Settings', '1', '2023-11-27 13:22:51', '2023-11-27 13:22:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userlogs`
--

CREATE TABLE `userlogs` (
  `id` int(11) UNSIGNED NOT NULL,
  `loginfo` varchar(100) NOT NULL,
  `login_at` datetime DEFAULT current_timestamp(),
  `logout_at` datetime DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `ip_address` varchar(100) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `browser_version` varchar(100) NOT NULL,
  `operating_system` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'online',
  `token` varchar(100) NOT NULL,
  `referrer_link` varchar(200) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `account` enum('Administrator','Employee','Client') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `userlogs`
--

INSERT INTO `userlogs` (`id`, `loginfo`, `login_at`, `logout_at`, `duration`, `ip_address`, `browser`, `browser_version`, `operating_system`, `location`, `latitude`, `longitude`, `status`, `token`, `referrer_link`, `user_id`, `client_id`, `account`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-09 17:53:18', NULL, NULL, '41.75.191.108', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$KMCESWdKkjbupRXjH00P0enG9pFpZWUP5u3nVXRqYdjjrGLxGNXum', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-10 00:53:18', '2023-10-10 00:53:18', NULL),
(2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-09 17:56:48', '2023-10-10 00:59:05', '07:02:17', '41.75.191.108', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$5beG6mP3yZDY8Xhowibka..wG8T5aFPmEtEKYmqt/X63PiS/5YFcG', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-10 00:56:48', '2023-10-10 00:59:05', NULL),
(3, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Sa', '2023-10-09 18:04:32', '2023-10-10 01:07:20', '07:02:48', '41.75.181.203', 'Opera', '102.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$8T4LS5QhPSUxzAh8AWDcZO9BIf41M98mUbHfApQhAAMJnd5CDANNK', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-10 01:04:32', '2023-10-10 01:07:20', NULL),
(4, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-09 18:07:28', '2023-10-10 01:15:30', '07:08:02', '41.75.181.203', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$BKv4U7bRn54HOfk2zKO0deO2ZOwtEvmhmgiToo.2UShzJRADJEfMW', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-10 01:07:28', '2023-10-10 01:15:30', NULL),
(5, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Sa', '2023-10-09 22:58:06', NULL, NULL, '154.228.109.163', 'Chrome', '116.0.0.0', 'Android', '', '', '', 'online', '$2y$10$iDjpuFUIq0BUWN4d6KleU.oduTZKqLt2xtgmBcw2AOKH.16RMc2Um', 'https://microfinance.realdailykash.com/client/account/token/verification', NULL, 1, NULL, '2023-10-10 05:58:06', '2023-10-10 05:58:06', NULL),
(6, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 01:05:49', '2023-10-10 10:38:59', '09:33:10', '154.228.109.163', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$bndsYKWvW5C5EMemQhiRluHmJbK0Vc37ELLQSYz4QBESSD0Xp1Y6.', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-10 08:05:49', '2023-10-10 10:38:59', NULL),
(7, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 03:06:16', NULL, NULL, '102.214.149.141', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$ciIr2dnh6RIwz1ysj/JNreZCEkHrw4pByx6OBMjlBWc.zmxEbMO1.', 'https://microfinance.realdailykash.com/admin/account/password/code', 4, NULL, NULL, '2023-10-10 10:06:16', '2023-10-10 10:06:16', NULL),
(8, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 03:39:05', NULL, NULL, '102.214.149.141', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$KM6UNAFDa5C5dUIzjZQGt.Ztp67jj3vj8GVLqzd8Tp.Sku6apGpXK', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-10 10:39:05', '2023-10-10 10:39:05', NULL),
(9, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 03:52:27', '2023-10-10 11:06:04', '07:13:37', '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$njEtisYX.RLTLbYZ8VNd8.qMBRzwVWm6vnUw61NpFBiSZgbkU6G0m', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-10 10:52:27', '2023-10-10 11:06:04', NULL),
(10, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/118.0', '2023-10-10 04:14:14', NULL, NULL, '41.75.177.60', 'Firefox', '118.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$.VWfgqt5IhVib6uRuoQ8U.z.SICUtnvCYmiYIORmuSrsyAEGWg2J2', 'https://microfinance.realdailykash.com/admin/login', 5, NULL, NULL, '2023-10-10 11:14:14', '2023-10-10 11:14:14', NULL),
(11, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 04:17:23', '2023-10-10 13:06:12', '08:48:49', '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$9H1ae0wJ61e8JiMQiYDsTeLrcQ5.e1cVU6rIBIK0u6hWhP0IGTSbe', 'https://microfinance.realdailykash.com/admin/login', 5, NULL, NULL, '2023-10-10 11:17:23', '2023-10-10 13:06:12', NULL),
(12, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 04:24:20', NULL, NULL, '102.214.149.141', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$Az3R9JN/Udl0ZyZjj2Ym4.XrShxkEFtO3lCxC14JCQBTAvVjkOm0W', 'https://microfinance.realdailykash.com/admin/account/password/code', 8, NULL, NULL, '2023-10-10 11:24:20', '2023-10-10 11:24:20', NULL),
(13, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 04:26:24', NULL, NULL, '102.214.149.141', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$encQ64ehcIFvEdOLK4.sO.SLtXrOQYCnnyuiNGOXBPZgCj9SYBH7y', 'https://microfinance.realdailykash.com/admin/account/password/code', 6, NULL, NULL, '2023-10-10 11:26:24', '2023-10-10 11:26:24', NULL),
(14, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 04:28:09', NULL, NULL, '102.214.149.141', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$Epl2Mr5j.XCXD8uXqpwnvejU9QW6iboVC8XNRw9MHZREZorHGsVnm', 'https://microfinance.realdailykash.com/admin/account/password/code', 7, NULL, NULL, '2023-10-10 11:28:09', '2023-10-10 11:28:09', NULL),
(15, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 06:06:40', '2023-10-10 13:09:16', '07:02:36', '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$k6QAEPybWwzQUndk3dWgWuv.udmmApS6i8czsNQKsLCUszab7Y3SK', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-10 13:06:40', '2023-10-10 13:09:16', NULL),
(16, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 06:09:21', '2023-10-10 13:11:26', '07:02:05', '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$c9DsdHfFKzPyF5TK2b5fCOdBGSA0awBCxQlsGXramQ2d5fonoPJaO', 'https://microfinance.realdailykash.com/admin/login', 5, NULL, NULL, '2023-10-10 13:09:21', '2023-10-10 13:11:26', NULL),
(17, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 06:12:37', '2023-10-10 13:16:43', '07:04:06', '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$TDiaW1eMgNeF3l7qX3rS9eZXz7RpDVzJVXit/VcdalNieKPMIxaxe', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-10 13:12:37', '2023-10-10 13:16:43', NULL),
(18, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 06:16:53', NULL, NULL, '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$ayIzVh5YzU0VbLVruZtbtuPLYDMlV5/bnB9B0fvv3240ncAIrsOLa', 'https://microfinance.realdailykash.com/admin/login', 5, NULL, NULL, '2023-10-10 13:16:53', '2023-10-10 13:16:53', NULL),
(19, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-10 08:46:56', NULL, NULL, '41.75.177.60', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$vS.ruZzmfqFc3H9oqm/ivekEHq2xqnIKQNYzQNHcJafQOh/Fak/Ry', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-10 15:46:56', '2023-10-10 15:46:56', NULL),
(20, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 17:29:35', '2023-10-11 00:34:57', '07:05:22', '41.75.177.60', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$wYRt7njfzAhlnC9G2bq59./CoFvTVrCqb1faSVLbPqP4kwmTceo9O', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-11 00:29:35', '2023-10-11 00:34:57', NULL),
(21, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 17:35:55', '2023-10-11 00:39:39', '07:03:44', '41.75.171.114', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$EmzGyFqYgFM315BvuPNTLO2SttUtGU.oZacPpp8m94.vV78GiDPYW', 'https://microfinance.realdailykash.com/', 5, NULL, NULL, '2023-10-11 00:35:55', '2023-10-11 00:39:39', NULL),
(22, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 17:55:38', '2023-10-11 01:03:40', '07:08:02', '41.75.171.114', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$dY3YviN04OBUp/eRtu8I6Or8PhtaYsYobm2OclWnJpbWj56dyfkNO', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-11 00:55:38', '2023-10-11 01:03:40', NULL),
(23, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-10 18:03:50', '2023-10-11 01:04:37', '07:00:47', '41.75.171.114', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$oTrRpMDqE2WvQPH4QyikyOiWSodiAxIYDdaf8UWQmBNR.pSaBfIGG', 'https://microfinance.realdailykash.com/', 5, NULL, NULL, '2023-10-11 01:03:50', '2023-10-11 01:04:37', NULL),
(24, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-11 00:08:31', NULL, NULL, '197.239.7.17', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$wPtgw/x84gYV4L6SkmYEJOSX5BoT.esvmjCb17YIwmy2qlx7iX90O', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-11 07:08:31', '2023-10-11 07:08:31', NULL),
(25, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-11 03:35:45', NULL, NULL, '41.210.145.193', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$OYViSzGRGJjIOjt4lgJP4urEDIPA.QfTG0K73G8bLsnC48KFzyVhK', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-11 10:35:45', '2023-10-11 10:35:45', NULL),
(26, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-11 04:44:16', NULL, NULL, '41.75.170.184', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$ISlJNzZjEXG/qkAYZxmfUuWwMAejYfb6TzFivm78pAjNLKEwpdvyG', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-11 11:44:16', '2023-10-11 11:44:16', NULL),
(27, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.3', '2023-10-12 09:52:03', NULL, NULL, '41.75.180.194', 'Chrome', '116.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$OUvMZXL16XwKkOhpqEMXyOdGx1r2TVBIV4Y7hR8hnULCusX/l9pZe', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-12 16:52:03', '2023-10-12 16:52:03', NULL),
(28, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-12 10:41:43', NULL, NULL, '41.210.155.252', 'Chrome', '117.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$ECz69TT4GZlhoVi/HbWHmOzjqxElQIeUaVN.zK8sc0qdGQVm8bOVO', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-12 17:41:43', '2023-10-12 17:41:43', NULL),
(29, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-13 00:36:26', NULL, NULL, '197.239.9.94', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$ATqyKpWVg1cp/t2/MFLCLujS26VY9ErfZRxO/3aLau/oZtDO3elLS', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-13 07:36:26', '2023-10-13 07:36:26', NULL),
(30, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-13 04:48:41', '2023-10-13 13:46:25', '08:57:44', '197.239.9.94', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$VbMs6l9CQkliU7pWbPDbmOaUx7gBXD0JVVbb4E766l/MjMoYps8Xm', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-13 11:48:41', '2023-10-13 13:46:25', NULL),
(31, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-13 06:40:03', '2023-10-13 13:47:05', '07:07:02', '41.75.181.11', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$ix9d78o5ysJGvDYnH9ZDu.p4DJiT4d1PmaUhmvQ28pKksvo8OwfcO', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-13 13:40:03', '2023-10-13 13:47:05', NULL),
(32, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-13 06:47:33', NULL, NULL, '41.75.181.11', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$D6nLp1Fr39bIZDHKwgoCDOpWrbTZHrPHspnstV2AQFJEUEF46c4LW', 'https://microfinance.realdailykash.com/', 2, NULL, NULL, '2023-10-13 13:47:33', '2023-10-13 13:47:33', NULL),
(33, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-13 06:49:23', NULL, NULL, '197.239.9.94', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$nKfLoLKr6804AHt27KVm..kQ./NhHAKZPBQljAzIhIM1lO089p2HC', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-13 13:49:23', '2023-10-13 13:49:23', NULL),
(34, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-13 06:50:59', NULL, NULL, '197.239.9.94', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$fO63PxXQo7LhZmSV5lvW0uL7CsjNxUJhQh1hmr.Km.5vkWRK2aRdS', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-13 13:50:59', '2023-10-13 13:50:59', NULL),
(35, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Sa', '2023-10-13 07:21:42', NULL, NULL, '197.239.9.94', 'Chrome', '117.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$IK0BhjJdPjZry6if.c4miefeIbcqk0MIu3m1IoTL1AbxuPjqNbyWi', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-13 14:21:42', '2023-10-13 14:21:42', NULL),
(36, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-13 10:15:50', NULL, NULL, '41.75.181.11', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$OW4Ws9k8nFsEvnBrryiTw.fT3mRH7ZzJfAlABO0z6oGpJW6144Yyq', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-13 17:15:50', '2023-10-13 17:15:50', NULL),
(37, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 08:29:08', '2023-10-15 15:30:43', '07:01:35', '41.75.181.194', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$ixKRmkk/S2EyIUtsc.ZH4e92ZvrKITDdzDrzAq4W6mVTxJx83.53.', 'https://microfinance.realdailykash.com/', 5, NULL, NULL, '2023-10-15 15:29:08', '2023-10-15 15:30:43', NULL),
(38, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 08:30:48', '2023-11-25 20:53:27', '12:22:39', '41.75.181.194', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$J10m4SYPHqlwaTTrjqF1Ru0nTk4NLJI/9gLW4BjGrvWaf7SvlBi3a', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-15 15:30:48', '2023-11-25 20:53:27', NULL),
(39, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 09:30:06', NULL, NULL, '197.239.12.54', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$cfhPoPU8LP3GiZbAYFO/pODaWUwifgyljzmmUbH491Ns4ynpYKgju', 'https://microfinance.realdailykash.com/', 2, NULL, NULL, '2023-10-15 16:30:06', '2023-10-15 16:30:06', NULL),
(40, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 09:30:41', '2023-10-15 17:16:34', '07:45:53', '197.239.12.54', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$O0eHKjo0WPwDgPXBh4PkVOFgXUHiFe8kqXJO5ALf7tRhf8/VCkVV2', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-15 16:30:41', '2023-10-15 17:16:34', NULL),
(41, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 10:05:11', NULL, NULL, '102.85.149.225', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$CQdfNCf6Lp5B2T2DEKnIrO.d7N0Nfh37Uy1FFEC99J1YpTSJ5yCPq', 'https://microfinance.realdailykash.com/', 2, NULL, NULL, '2023-10-15 17:05:11', '2023-10-15 17:05:11', NULL),
(42, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 10:17:07', '2023-10-15 20:53:25', '10:36:18', '197.239.12.54', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$4z4Ag26nfnPgBlcGfy81P.z4VZ3qs5H9nuQ.lXytTU0/7iikJMeRS', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-15 17:17:07', '2023-10-15 20:53:25', NULL),
(43, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Mobile Sa', '2023-10-15 10:33:03', NULL, NULL, '41.75.181.194', 'Chrome', '118.0.0.0', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$kLDllXxU6SZ0Y2AlpSSFz.alUhY5MAC4Co.INC9EPlf3klyTy9WPO', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-15 17:33:03', '2023-10-15 17:33:03', NULL),
(44, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-15 13:54:52', NULL, NULL, '197.239.12.54', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$bU5FBrfhejUIkm0GRb/l2eEoeG84rBkMM4MmFKR2kAU/WvxRgCj0a', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-15 20:54:52', '2023-10-15 20:54:52', NULL),
(45, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 03:29:55', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$rTNvjAOQ5qDs.sFtJ6Wl9e01Bxq5kUBrjOZB0XcGWMMLR4mak8JYW', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 10:29:55', '2023-10-16 10:29:55', NULL),
(46, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 03:45:22', '2023-10-16 11:38:17', '07:52:55', '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$MuyD9icLv9nGffAHaQ4ao.Rjt8.ujHXfsHZkMVYpuU3Ownoa1RrJa', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 10:45:22', '2023-10-16 11:38:17', NULL),
(47, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 04:38:53', '2023-10-16 11:58:42', '07:19:49', '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$puj3rJINPNKoNbb.uic8PuZbGBURuKVfh1LvnW6Kn.IzCNQDBGlJ2', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 11:38:53', '2023-10-16 11:58:42', NULL),
(48, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 05:06:17', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$ErK8T0Hp8mvX2dHNqrr5reK9Zv4ICgKnb.0g5FV5tbiFh/oheDWFm', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 12:06:17', '2023-10-16 12:06:17', NULL),
(49, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-16 05:42:44', NULL, NULL, '41.75.191.3', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$LjPzOUxY6vYZC826CNjQ2evMUpgo4OhG11jB/F3WdBF750hHuDLS6', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-16 12:42:44', '2023-10-16 12:42:44', NULL),
(50, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-16 07:57:21', '2023-10-16 14:58:32', '07:01:11', '41.75.191.3', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$n7IjRPSSo3ob6/0dnh95YOdJ3M/bLy6wr8Ey9AVap/3RwN1n7mdgK', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 14:57:21', '2023-10-16 14:58:32', NULL),
(51, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Sa', '2023-10-16 08:05:01', '2023-10-16 16:02:30', '07:57:29', '154.227.25.177', 'Chrome', '116.0.0.0', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$F8rYKZIcKQ///9GUFWsnD.nzNLRG12yToF32qXINbuCbFCxs1wr8m', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 15:05:01', '2023-10-16 16:02:30', NULL),
(52, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 08:36:12', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$DcAwmyycYrt7KqU0W8hun.E4vYHy4jzziNAr0kKZ6BIw6qXkhevMy', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 15:36:12', '2023-10-16 15:36:12', NULL),
(53, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 08:41:40', '2023-10-16 15:54:00', '07:12:20', '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$k0W/IJl/uYhdw8w10AA3Qek2uIKgTL.YXh6V0acz75Ml6/4ZUlnYe', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 15:41:40', '2023-10-16 15:54:00', NULL),
(54, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 08:55:19', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$abJsSGXryy3xNWBUYwtP..vz.ASvzn8NMo/pwlb5NXNiYT0nvieXa', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 15:55:19', '2023-10-16 15:55:19', NULL),
(55, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 09:08:26', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$rx3k/QynQkFTIOcyC16MnO6MBMmofWYteReqDAD03CcXjoz0untbC', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 16:08:26', '2023-10-16 16:08:26', NULL),
(56, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 12:51:15', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$R86rKqBOIiG6RzUON4aCIOwmwfQuF16xHO4.WJH.C8Sx5sgpmf.x.', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 19:51:15', '2023-10-16 19:51:15', NULL),
(57, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 13:31:20', NULL, NULL, '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$oNM6mM19ChDh7DtBMhjWVOa05TgWD90J5TIPZh/ME3QKqz2HKELsi', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-16 20:31:20', '2023-10-16 20:31:20', NULL),
(58, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-16 13:32:21', '2023-10-16 20:35:09', '07:02:48', '154.227.25.177', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$wgNFRW/o1IeOLZ5hOK7v8uWSMvBun3KRI4N9J2zoO97fU7bxsHVrG', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-16 20:32:21', '2023-10-16 20:35:09', NULL),
(59, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 02:31:53', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$czVedAK.Vnia77IsWAsCEuGhkLRgPR3t7kEv.ZvmgoJ7F0Na9HvX6', 'https://microfinance.realdailykash.com/admin/account/password/code', 6, NULL, NULL, '2023-10-17 09:31:53', '2023-10-17 09:31:53', NULL),
(60, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 02:58:32', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$1YKUJD8GiduyAq8O1BuG3u1CHYrxNRKGgcm3LvB9vmQhXMqgi0Jny', 'https://microfinance.realdailykash.com/admin/account/password/code', 4, NULL, NULL, '2023-10-17 09:58:32', '2023-10-17 09:58:32', NULL),
(61, 'Mozilla/5.0 (Linux; U; Android 11; zh-cn; Infinix X6511B Build/SP1A.210812.016) AppleWebKit/537.36 (', '2023-10-17 03:04:25', NULL, NULL, '102.220.92.254', 'Chrome', '103.0.5060.129', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$wGHjaRsSDFnttWLLABuI/uc8uwchHx0qkbj2xUVimYLx4KCvdiAKC', 'https://microfinance.realdailykash.com/admin/account/password/code', 7, NULL, NULL, '2023-10-17 10:04:25', '2023-10-17 10:04:25', NULL),
(62, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 03:06:03', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$Oa4FmHKD0tX4K5pGGM0K.eddXiRhCNOtRjWswbIaAzMHhl7kRzkcS', 'https://microfinance.realdailykash.com/admin/account/password/code', 7, NULL, NULL, '2023-10-17 10:06:03', '2023-10-17 10:06:03', NULL),
(63, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 04:22:08', NULL, NULL, '102.85.53.30', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$wcLHR2qXfEkZOU1CzezfZuyCxO3KXMuTU0aSMUdUIesXJVRZf50ju', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-17 11:22:08', '2023-10-17 11:22:08', NULL),
(64, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 04:24:45', '2023-10-17 11:39:05', '07:14:20', '102.85.53.30', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$0fhw.RNFxDQK9ICci.fRkuQsEk6yMF9/5HzzXZ6NGMyH/pTVL8ivi', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-17 11:24:45', '2023-10-17 11:39:05', NULL),
(65, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-17 04:45:30', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$UKYic85khOe.uj/.NhBcFu85j9Fc5GCe789/lTGr.kKVkEroqGrOm', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-17 11:45:30', '2023-10-17 11:45:30', NULL),
(66, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-17 04:53:33', NULL, NULL, '41.75.181.64', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$UZGfiP84QUZvA1EeUc1P1uziJtlI738Td5a4LtN.hNXhnrd0x9mLO', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-17 11:53:33', '2023-10-17 11:53:33', NULL),
(67, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-18 04:00:55', NULL, NULL, '41.75.176.139', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$VeJpDFhununMoQyFH94fdOV4i9/DB4NjdLqbm49tRACwjyS6NTJ9.', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-18 11:00:55', '2023-10-18 11:00:55', NULL),
(68, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 05:29:10', NULL, NULL, '197.239.12.246', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$b7ikNnHoXMqdjS40iNZROOu6zkOvcqNzJpXBtvR0A53PM0toaNe0i', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 12:29:10', '2023-10-18 12:29:10', NULL),
(69, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 11:15:43', '2023-10-18 20:11:28', '08:55:45', '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$Tmb2aMhaOEaMajPhOwBavufAGXVgAibKYkyPtdTDhhuKqEf8G9bye', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 18:15:43', '2023-10-18 20:11:28', NULL),
(70, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Sa', '2023-10-18 11:35:33', NULL, NULL, '154.231.44.127', 'Chrome', '116.0.0.0', 'Android', '', '', '', 'online', '$2y$10$U0q94tFh/oVxYX2IlmdQtuIOnMQg8QtWeP/5f7oKEkth4Z0qBccqa', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 18:35:33', '2023-10-18 18:35:33', NULL),
(71, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-18 12:02:17', '2023-10-18 19:04:20', '07:02:03', '41.75.179.154', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$WcypX9ga6SXP5nsL.EwqEuWHz7SkYEZ7H4uTt27JWmGfC6FJ7FCim', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-18 19:02:17', '2023-10-18 19:04:20', NULL),
(72, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 13:11:56', '2023-10-18 20:12:03', '07:00:07', '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$SWGsjW4M.7Is4WDGzLK47e02oF.f97eE5lYuoRz/d2FcmGD.Rfyb2', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 20:11:56', '2023-10-18 20:12:03', NULL),
(73, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 13:12:07', NULL, NULL, '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$i1QtNbSKUN2wRdCE6g9CTexFWSmkTwZqg42Ywh3LF6FBv6irTj3Wq', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 20:12:07', '2023-10-18 20:12:07', NULL),
(74, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 13:12:21', NULL, NULL, '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$VOaWjzQS2tb/WRIsxz6aveYA.WrFP8.vJJFRy9mThDgiIuY0Laam.', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 20:12:21', '2023-10-18 20:12:21', NULL),
(75, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 13:14:57', '2023-10-18 21:09:10', '07:54:13', '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$ghhCHRwljG56ctJW8CpINOIWx3L4wcJwsjc3AyBHM2yv8hIndUpcy', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-18 20:14:57', '2023-10-18 21:09:10', NULL),
(76, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 14:06:15', '2023-10-18 21:18:10', '07:11:55', '41.75.179.154', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$06XOswtc1U7eLe7R1z81d.G/aD8kSBB3m9yMugMonLowipPrajoJ6', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-18 21:06:15', '2023-10-18 21:18:10', NULL),
(77, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 14:09:39', NULL, NULL, '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$t4lk1xet9yOguNTp49ClX.AcbpjAMT4W3iRyaUOsNr55KaMCFSG/e', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 21:09:39', '2023-10-18 21:09:39', NULL),
(78, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 14:19:20', '2023-10-18 21:22:45', '07:03:25', '41.75.179.154', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$I8NJFWKQrSBcPAddxmOXReIbZKxW6oCNnaEt0a7BRE7rriM.Wizi2', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-18 21:19:20', '2023-10-18 21:22:45', NULL),
(79, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Mobile Sa', '2023-10-18 14:31:38', NULL, NULL, '41.75.179.154', 'Chrome', '118.0.0.0', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$VZKnD067a63yl.uHPLBFEOZQQk1Yu5mTDGEoaiGomIg0I28KE41R6', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-18 21:31:38', '2023-10-18 21:31:38', NULL),
(80, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 14:45:28', '2023-10-18 22:05:47', '07:20:19', '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$NnC4x.Ur8dB.ThStJXwg3.H5Jv.TjTo60PO1B.5qhZR8fR.YnZDtW', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-18 21:45:28', '2023-10-18 22:05:47', NULL),
(81, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 15:00:21', NULL, NULL, '41.75.179.154', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$WnGP2rkFG.9oQmCxqRF1Y.eCkplSlUxCc65sR9pH3FZE5a/JVJE/6', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-18 22:00:21', '2023-10-18 22:00:21', NULL),
(82, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-18 15:06:02', NULL, NULL, '154.231.44.127', 'Chrome', '118.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$QRff4.JMnzAM7B4WuhtBruR4Gx4EMR4mClMuCSTnzQWr6yBz/J4.O', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-18 22:06:02', '2023-10-18 22:06:02', NULL),
(83, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 02:25:56', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$LIbOUJvPbwpUp1Ek2CeUpOOP4xY9RDKpbKE5SQ4ULFKW6Q1sggw/K', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 09:25:56', '2023-10-19 09:25:56', NULL),
(84, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 02:36:13', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$qMUPzbDK9c/iUCWQ7.4QL.hMaWfW2qcDLRUVGiDa3AzOSK7KcZa3e', 'https://microfinance.realdailykash.com/admin/account/password/code', 7, NULL, NULL, '2023-10-19 09:36:13', '2023-10-19 09:36:13', NULL),
(85, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 02:51:15', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$x2uhztbttuO.KKroCexjYe4yY4Ow3SLQ8D5Fgq5z2.kZMqCpPUIia', 'https://microfinance.realdailykash.com/admin/account/password/code', 4, NULL, NULL, '2023-10-19 09:51:15', '2023-10-19 09:51:15', NULL),
(86, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-19 03:59:16', NULL, NULL, '41.75.179.71', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$MdU/OufIyaPFR9mZIjCAIeu9yFHfcYhwiqFYTix2DMofSJdYgbhzq', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-19 10:59:16', '2023-10-19 10:59:16', NULL),
(87, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/118.0', '2023-10-19 04:37:40', NULL, NULL, '41.75.179.71', 'Firefox', '118.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$QLfRffrwPFAMC4E1IqZsvuOxi6hJ1jb0lmDjsTOT9hSFD8hP7ojX2', 'https://microfinance.realdailykash.com/', 5, NULL, NULL, '2023-10-19 11:37:40', '2023-10-19 11:37:40', NULL),
(88, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-19 04:40:53', NULL, NULL, '41.75.179.71', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$kx5pGdn.bgZBBKt.3I.EKu2OhUHCJYfsgBZgOvgJI3NHbhFqHyyKG', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-19 11:40:53', '2023-10-19 11:40:53', NULL),
(89, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 04:59:14', '2023-10-19 11:59:19', '07:00:05', '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$igO6UB.bHn0Z372Ass/qr.dvqVoPuIsVuJjwfE00/zZpHIgPRthve', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 11:59:14', '2023-10-19 11:59:19', NULL),
(90, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 05:05:43', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$M7KNPctvDTTg0vz..GwWCu3z.gBzcPIgsSkM/a4wQoNS5c1L6XzQK', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 12:05:43', '2023-10-19 12:05:43', NULL),
(91, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 05:33:43', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$Wn5nP67mT0MunqrqN22q6OaLayc7sv6AFDw.Nljh/50.eZMsz8MKu', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 12:33:43', '2023-10-19 12:33:43', NULL),
(92, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 07:04:22', '2023-10-19 15:04:56', '08:00:34', '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$L/ZpXeuI0bfh8NGV98aaReMmRvaXAe6FZGsl51WSPYePoN9FtAyAW', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-19 14:04:22', '2023-10-19 15:04:56', NULL),
(93, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-19 07:58:43', NULL, NULL, '41.75.179.71', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$nmEvxWYLSjdXF.1VOnU6bO35DtwhceaMq.ZFOxIwzg4KJdnsQ9/CO', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-19 14:58:43', '2023-10-19 14:58:43', NULL),
(94, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 08:05:08', '2023-10-19 15:23:21', '07:18:13', '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$XX9hkvI7Xp5ZreXynEJZqOSmG.YEhuz5.X5hUbJlgC4C7R26RycTG', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 15:05:08', '2023-10-19 15:23:21', NULL),
(95, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 08:08:14', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$bXKmALXPQviXlcABnTJiGevvEhSdQLVutI9AHweBroubgj2LWSXGK', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-19 15:08:14', '2023-10-19 15:08:14', NULL),
(96, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-19 08:23:32', NULL, NULL, '102.220.92.254', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$1OuwJ6jXSQht0sS8RoXSEegX2xj.cGvMP2ORnWU7U8pJBIDI0eEgK', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-19 15:23:32', '2023-10-19 15:23:32', NULL),
(97, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-19 11:33:59', NULL, NULL, '41.75.179.71', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$coYNEo1UPoNkNgjrTN4aI./VyZlAdgfc76ctwaCNIYkifdO/w5Ptu', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-19 18:33:59', '2023-10-19 18:33:59', NULL),
(98, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-21 05:37:45', NULL, NULL, '41.210.155.15', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$2FtPdisZGebFmEQfKBbKX.9JhGX1YGZgms1KSbtVr0wl804LMD8vG', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-21 12:37:45', '2023-10-21 12:37:45', NULL),
(99, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-21 05:40:14', NULL, NULL, '41.210.155.15', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$ulqRHNpyh.TVlq2YvB.zsOWvRs52Llj0PHY7Ouqhos.9ch0bws/yq', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-21 12:40:14', '2023-10-21 12:40:14', NULL),
(100, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-21 05:52:38', '2023-10-21 13:05:44', '07:13:06', '41.210.155.15', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$Dsk3llIrg3tjn.Z4YKFVTOZCffuwkXJrxd5ZDgjOfdZ/i/jePd4yW', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-21 12:52:38', '2023-10-21 13:05:44', NULL),
(101, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-21 05:52:41', NULL, NULL, '41.75.191.92', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$0OzlwzaotWXGGxfZk00pv.ykRz5bqNMJzGnZds6eLRuSZG1NDTJG2', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-21 12:52:41', '2023-10-21 12:52:41', NULL),
(102, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-21 05:56:35', NULL, NULL, '41.210.155.15', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$pG760ENNZfju4BaIJvQAFO7c8OjwVmFz5cQdjFvTIlIL4Aqhb5V86', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-21 12:56:35', '2023-10-21 12:56:35', NULL),
(103, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-21 06:01:17', '2023-10-21 13:02:53', '07:01:36', '41.210.155.15', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$vcfCqIYe7LSHHNq.ay8O7em5OCMaFnqqc.mTjFqy4IOTW58q04mxm', 'https://microfinance.realdailykash.com/client/login', NULL, 1, NULL, '2023-10-21 13:01:17', '2023-10-21 13:02:53', NULL),
(104, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-23 04:39:01', '2023-10-23 11:40:08', '07:01:07', '41.75.191.21', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$R0DnBP0Vg0K2jMJhlHlm4O9YXMzMla/WFO/a80D16O.rsOJ87bIgC', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-23 11:39:01', '2023-10-23 11:40:08', NULL),
(105, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-23 09:14:09', NULL, NULL, '41.75.191.21', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$bNT6An/tyT4k1XNxQWU0ze5RHt.RNbCxIX9hVZbVUVx9GrE07UVzK', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-23 16:14:09', '2023-10-23 16:14:09', NULL),
(106, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-23 15:10:37', '2023-10-23 22:15:23', '07:04:46', '41.210.155.146', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$iiYAJfYAtNGGyqnDHvYxXeKSQCsHdpu8fB/LEl1Ogb6hbFAdHnVtS', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-23 22:10:37', '2023-10-23 22:15:23', NULL),
(107, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-23 15:15:36', NULL, NULL, '41.210.155.146', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$PKAVuAJ0W2JibFS.XveO0OMoj85nfK1jVWreu1LN4CdZlxyRa2h96', 'https://microfinance.realdailykash.com/admin/login', 2, NULL, NULL, '2023-10-23 22:15:36', '2023-10-23 22:15:36', NULL),
(108, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Sa', '2023-10-23 15:18:16', NULL, NULL, '41.210.143.132', 'Chrome', '117.0.0.0', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$RT0ZRzSr2Tz.fItdpziuQeAKFdxngYJgph1jor8GhDc5NXK0qgkTi', 'https://microfinance.realdailykash.com/admin/account/password/code', 9, NULL, NULL, '2023-10-23 22:18:16', '2023-10-23 22:18:16', NULL),
(109, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Sa', '2023-10-23 15:21:42', NULL, NULL, '41.210.143.132', 'Chrome', '117.0.0.0', 'Android', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$0wJCWhq1p8Aa/5dadmmB/OOIM5tITuaPJf4i3KxjzDhv18lfK3u7e', 'https://microfinance.realdailykash.com/client/account/token/verification', NULL, 599, NULL, '2023-10-23 22:21:42', '2023-10-23 22:21:42', NULL),
(110, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-25 07:44:33', NULL, NULL, '41.75.183.115', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$tdJNJRgSs2i5PoU60k//BefiFAvlwIdMdxNKhm3cV83o90C2uVB6G', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-25 14:44:33', '2023-10-25 14:44:33', NULL);
INSERT INTO `userlogs` (`id`, `loginfo`, `login_at`, `logout_at`, `duration`, `ip_address`, `browser`, `browser_version`, `operating_system`, `location`, `latitude`, `longitude`, `status`, `token`, `referrer_link`, `user_id`, `client_id`, `account`, `created_at`, `updated_at`, `deleted_at`) VALUES
(111, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Sa', '2023-10-25 09:46:32', NULL, NULL, '41.210.155.181', 'Chrome', '118.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$PBXNFFwUBIJCNCD0X7Z07e.cer6zFEcIHN89jOTWH2m3AKvte9DxO', 'https://microfinance.realdailykash.com/', 2, NULL, NULL, '2023-10-25 16:46:32', '2023-10-25 16:46:32', NULL),
(112, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-26 05:00:39', '2023-10-26 12:01:16', '07:00:37', '41.75.185.37', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$Q6i.2.K9MC.jY299.ye1VO5hh4hNzQga/0FRWyvd2kVQxAwwOET4.', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-26 12:00:39', '2023-10-26 12:01:16', NULL),
(113, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-26 08:54:09', NULL, NULL, '41.75.185.37', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$3AuLumGbcX29UboZSgca4.Op4Bi1UkX/.hvttVM.kfg5VTw8mcrki', 'https://microfinance.realdailykash.com/admin/login', 1, NULL, NULL, '2023-10-26 15:54:09', '2023-10-26 15:54:09', NULL),
(114, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.3', '2023-10-30 05:46:25', '2023-10-30 12:53:47', '07:07:22', '41.75.178.123', 'Chrome', '118.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$xJxmKYb5KReSdVuFDW7Y6utanLgDST4Xe9kNJe4i4aLNdZLTv1N96', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-10-30 12:46:25', '2023-10-30 12:53:47', NULL),
(115, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.3', '2023-11-22 04:35:27', NULL, NULL, '41.75.176.164', 'Chrome', '119.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$lfWThST/xnM/gkBG9BsKfedQtTFmQ.Xe8tNsWQOhnWRC53AZmnAgO', 'https://microfinance.realdailykash.com/', 1, NULL, NULL, '2023-11-22 12:35:27', '2023-11-22 12:35:27', NULL),
(116, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-25 20:53:55', '2023-11-25 20:55:35', '00:01:40', '127.0.0.1', 'Chrome', '119.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$FlzHMKs8snMGfALYtK8FgeJFAQP8BI6dED1Oe6hHk/RHZWtyQ7mQe', 'http://smicrofinance.com/admin/login', 1, NULL, NULL, '2023-11-25 20:53:55', '2023-11-25 20:55:35', NULL),
(117, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-25 20:57:09', NULL, NULL, '127.0.0.1', 'Chrome', '119.0.0.0', 'Windows 10', '', '', '', 'online', '$2y$10$jAtwHVm07UiVy76jlUcizuTgcp61s0vGfMgRyOD9UWPEVrOlbHZ06', 'http://smicrofinance.com/client/account/token/verification', NULL, 600, NULL, '2023-11-25 20:57:09', '2023-11-25 20:57:09', NULL),
(118, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-26 14:58:43', '2023-11-26 11:59:34', '-02:59:09', '::1', 'Edge', '119.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$X3QeEApBLKMQH1R1Mat2MejQQuWT/lqojdGvhJjnbI4MATpZc3N2e', 'http://localhost:8080/', 1, NULL, NULL, '2023-11-26 11:58:43', '2023-11-26 11:59:34', NULL),
(119, 'PostmanRuntime/7.35.0', '2023-11-26 18:30:03', '2023-11-26 15:51:48', '-02:38:15', '::1', '', '', 'Unknown Platform', '', '', '', 'offline', '$2y$10$BuMX2xrC7FP1iWMq0XZnSO2xdycD4rRyo7aCkdRKtN1s1g.U1fxq.', '', NULL, 600, NULL, '2023-11-26 15:30:03', '2023-11-26 15:51:48', NULL),
(120, 'PostmanRuntime/7.35.0', '2023-11-26 18:53:54', '2023-11-26 16:38:44', '-02:15:10', '::1', '', '', 'Unknown Platform', '', '', '', 'offline', '$2y$10$BgR7KwRryV1vs8vZxStloe1lidun3D.o94VzqiFZQsw8SiMc48yQC', '', NULL, 600, NULL, '2023-11-26 15:53:54', '2023-11-26 16:38:44', NULL),
(121, 'PostmanRuntime/7.35.0', '2023-11-26 19:42:58', '2023-11-26 16:45:36', '-02:57:22', '::1', '', '', 'Unknown Platform', '', '', '', 'offline', '$2y$10$2onRAsc3zE6qxJfsvM0G5ejt0dTin6H9bmMaMw3TFt.X5/55I70ji', '', NULL, 600, NULL, '2023-11-26 16:42:58', '2023-11-26 16:45:36', NULL),
(122, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-26 19:47:02', '2023-11-26 16:48:20', '-02:58:42', '::1', 'Edge', '119.0.0.0', 'Windows 10', '', '', '', 'offline', '$2y$10$eKdbDJViIhqKj3FaeSXBBefD6nKbydEPm75F2FXhzv/96VdES8bfS', 'http://localhost:8080/client/login', NULL, 600, NULL, '2023-11-26 16:47:02', '2023-11-26 16:48:20', NULL),
(123, 'PostmanRuntime/7.35.0', '2023-11-26 19:53:29', '2023-11-26 16:54:05', '-02:59:24', '::1', '', '', 'Unknown Platform', '', '', '', 'offline', '$2y$10$MWYWa4eZsk7s.dI2TJDCZ.SiBLKEySJ5W36nXaKw8nNgg65gKd7Wy', '', NULL, 600, NULL, '2023-11-26 16:53:29', '2023-11-26 16:54:05', NULL),
(124, 'PostmanRuntime/7.35.0', '2023-11-26 19:54:17', '2023-11-26 16:54:39', '-02:59:38', '::1', '', '', 'Unknown Platform', '', '', '', 'offline', '$2y$10$3JWamoUjUagW4WNGxJPMuuRR9nqsCas4CGQF77UF0XbSDlDQMZDVG', '', NULL, 600, NULL, '2023-11-26 16:54:17', '2023-11-26 16:54:39', NULL),
(125, 'PostmanRuntime/7.35.0', '2023-11-27 00:24:38', NULL, NULL, '::1', '', '', 'Unknown Platform', '', '', '', 'online', '$2y$10$3vo8GWDrmyd/5Ffq3wVwnOcDtqJynM1fXZCg8ql4UX.g4MIZ0uZ7S', '', NULL, 600, NULL, '2023-11-27 00:24:38', '2023-11-27 00:24:38', NULL),
(126, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-26 18:27:24', '2023-11-27 02:33:38', '08:06:14', '41.75.182.137', 'Chrome', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$aJ6ky.jWOXgQM9vuXyoXhOZ4.d8zwIhy00eAtM5suuk6MhSsPZ6QS', 'https://demo.realdailykash.com/', 1, NULL, NULL, '2023-11-27 02:27:24', '2023-11-27 02:33:38', NULL),
(127, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-26 18:52:32', NULL, NULL, '41.75.182.137', 'Chrome', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$xjiU6JchyA/ISUH8HPr/vuBEu09OI5UnlTn6Cq3D61sL9ikxzzp4m', 'https://sacco.realdailykash.com/', 1, NULL, NULL, '2023-11-27 02:52:32', '2023-11-27 02:52:32', NULL),
(128, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-26 18:57:57', '2023-11-27 02:59:12', '08:01:15', '41.75.182.137', 'Edge', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$.ot8BXLcF0rCIM0YzoNr4erEN8ItNf68B4EUQX1rfGZXjno0TlbvC', 'https://sacco.realdailykash.com/', 1, NULL, NULL, '2023-11-27 02:57:57', '2023-11-27 02:59:12', NULL),
(129, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-27 02:57:57', NULL, NULL, '41.75.179.87', 'Chrome', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$0cwOt0yZyW5v0fUVwS2rS.dBSxioynKwgKAURmpHwkLw0xGBbGGfS', 'https://www.sacco.realdailykash.com/client/login', NULL, 600, NULL, '2023-11-27 10:57:57', '2023-11-27 10:57:57', NULL),
(130, 'PostmanRuntime/7.35.0', '2023-11-27 03:04:47', '2023-11-27 11:08:39', '08:03:52', '54.86.50.139', '', '', 'Unknown Platform', 'Ashburn, Virginia, United States North America', '39.0469', '-77.4903', 'offline', '$2y$10$Nbx2GHXjbW6i1hAH10bUHeuHOThCHoy1lgmJBPt7HCecg636gHz9O', '', NULL, 600, NULL, '2023-11-27 11:04:47', '2023-11-27 11:08:39', NULL),
(131, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-27 03:06:23', '2023-11-27 11:09:36', '08:03:13', '41.75.179.87', 'Chrome', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$dzjBNoo9FWn53qVcUcyQp.urP6RqI026mGzKnK3j3irnYQLX4xG2i', 'https://sacco.realdailykash.com/client/login', NULL, 600, NULL, '2023-11-27 11:06:23', '2023-11-27 11:09:36', NULL),
(132, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Sa', '2023-11-27 03:39:30', NULL, NULL, '41.75.179.87', 'Chrome', '119.0.0.0', 'Windows 10', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$FgkMur2P5mRPttnGoGwe7.uO9a0d909V1nibN4UFK9NbUC5E2XgYy', 'https://sacco.realdailykash.com/client/account/token/verification', NULL, 602, NULL, '2023-11-27 11:39:30', '2023-11-27 11:39:30', NULL),
(133, 'PostmanRuntime/7.35.0', '2023-11-27 03:42:44', '2023-11-27 11:43:00', '08:00:16', '54.86.50.139', '', '', 'Unknown Platform', 'Ashburn, Virginia, United States North America', '39.0469', '-77.4903', 'offline', '$2y$10$K2bKNd3GH0nJc9Dy/sqcWu5JvePjBAU9CJXAQT3K/0O58PLLVfW/y', '', NULL, 600, NULL, '2023-11-27 11:42:44', '2023-11-27 11:43:00', NULL),
(134, 'PostmanRuntime/7.35.0', '2023-11-27 03:43:33', NULL, NULL, '54.86.50.139', '', '', 'Unknown Platform', 'Ashburn, Virginia, United States North America', '39.0469', '-77.4903', 'online', '$2y$10$Kpwi777Fkf2CbGiMlDIzaujRSPD1t7mOiGHOoYEnt0atGmnK9kujO', '', NULL, 600, NULL, '2023-11-27 11:43:33', '2023-11-27 11:43:33', NULL),
(135, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.3', '2023-11-27 05:19:20', '2023-11-27 13:20:01', '08:00:41', '41.75.179.87', 'Chrome', '119.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'offline', '$2y$10$T.Yuh6JoJ4cANjJiNMVfCOTf/T0tdLNARfGfUh4Ev2zT0gjBFXbta', 'https://www.sacco.realdailykash.com/', 1, NULL, NULL, '2023-11-27 13:19:20', '2023-11-27 13:20:01', NULL),
(136, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.3', '2023-11-27 05:21:12', NULL, NULL, '41.75.179.87', 'Chrome', '119.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$5WA9FQTfMPZINzP0foRrVehYcMPNRnb6nW01nIDamZVRYE7oOCWf.', 'https://sacco.realdailykash.com/admin/login', 1, NULL, NULL, '2023-11-27 13:21:12', '2023-11-27 13:21:12', NULL),
(137, 'PostmanRuntime/7.35.0', '2023-11-27 09:15:03', NULL, NULL, '54.86.50.139', '', '', 'Unknown Platform', 'Ashburn, Virginia, United States North America', '39.0469', '-77.4903', 'online', '$2y$10$EjBQ/712di3P/OyLHuH4B.nTP7.ym722owUHlXJ0Xt2xbG6Z.gBhe', '', NULL, 600, NULL, '2023-11-27 17:15:03', '2023-11-27 17:15:03', NULL),
(138, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.3', '2023-11-27 09:18:08', NULL, NULL, '41.75.179.87', 'Chrome', '119.0.0.0', 'Linux', 'Kampala, Central Region, Uganda Africa', '0.3162', '32.5657', 'online', '$2y$10$zrTIAbvjcZtoi34Bzdaqievq16nG9vQEKCH9cRbB0tnUpgbCaXo0C', 'https://sacco.realdailykash.com/admin/login', 1, NULL, NULL, '2023-11-27 17:18:08', '2023-11-27 17:18:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `account_type` varchar(100) NOT NULL,
  `permissions` text NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `access_status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `token` varchar(100) NOT NULL,
  `token_expire_date` datetime DEFAULT NULL,
  `2fa` enum('True','False') NOT NULL DEFAULT 'True',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `staff_id`, `branch_id`, `name`, `email`, `mobile`, `address`, `photo`, `account_type`, `permissions`, `password`, `access_status`, `token`, `token_expire_date`, `2fa`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Administrator', 'daniondanfodio@gmail.com', '0785632882', 'Mengo', NULL, 'Super', 's:3:\"all\";', '$2y$10$O1VtHFGXlVqQHvU1EKZcMer.PZtzcgjzphzg1YYUUtsF2dwglTgDm', 'active', '$2y$10$VyS13GtyecFpHB/3PmCRs..4QxQ/mmCprJI3ptm28i6Hx9Bw2XCei', '2023-11-27 17:18:08', 'False', '2023-10-03 08:33:57', '2023-11-27 17:18:08', NULL),
(2, 1, 1, 'Obbo Shradrach', 'shadrachobbo4@gmail.com', '+256777237827', 'Kampala', NULL, 'Super', 's:3:\"all\";', '$2y$10$.NaH9HfNlTWJoJ0U.7V30usi4iHuHzhR/BZdJWDZfJhNhsp9s3y5e', 'active', '$2y$10$/IrsCF83BCJ2DTfaMwsJOe3TkBjUvTKNKM94CdSzrPwDRgCeugxDS', '2023-10-25 16:46:32', 'False', '2023-10-03 02:39:46', '2023-10-25 16:46:32', NULL),
(3, 2, 1, 'Doreen K', 'dkyomuhangi@nexenmicrocredit.com', '+256700139499', 'Kampala.Uganda', NULL, 'Administrator', 'a:13:{i:0;s:13:\"viewDashboard\";i:1;s:11:\"viewClients\";i:2;s:9:\"viewLoans\";i:3;s:12:\"viewProducts\";i:4;s:14:\"updateProducts\";i:5;s:18:\"createApplications\";i:6;s:18:\"importApplications\";i:7;s:16:\"viewApplications\";i:8;s:18:\"updateApplications\";i:9;s:18:\"deleteApplications\";i:10;s:22:\"bulkDeleteApplications\";i:11;s:18:\"exportApplications\";i:12;s:17:\"viewDisbursements\";}', '$2y$10$i5YT14Mb5hTVaGosN0CCS.DrnnadmY0Uz7ALXxYEQg2WCPDMWq51m', 'active', '', NULL, 'True', '2023-10-10 09:43:05', '2023-10-18 04:09:24', '2023-10-10 10:11:00'),
(4, 3, 1, 'Ayikoru Jamila', 'jammyayikoru22@gmail.com', '+256764995503', 'kampala,Uganda', NULL, 'Administrator', 'a:69:{i:0;s:13:\"viewDashboard\";i:1;s:14:\"viewAccounting\";i:2;s:19:\"viewChartofAccounts\";i:3;s:21:\"updateChartofAccounts\";i:4;s:19:\"createSubcategories\";i:5;s:17:\"viewSubcategories\";i:6;s:19:\"updateSubcategories\";i:7;s:19:\"exportSubcategories\";i:8;s:17:\"createParticulars\";i:9;s:15:\"viewParticulars\";i:10;s:17:\"updateParticulars\";i:11;s:17:\"exportParticulars\";i:12;s:13:\"createClients\";i:13;s:13:\"importClients\";i:14;s:11:\"viewClients\";i:15;s:13:\"updateClients\";i:16;s:13:\"exportClients\";i:17;s:9:\"viewLoans\";i:18;s:14:\"createProducts\";i:19;s:12:\"viewProducts\";i:20;s:14:\"updateProducts\";i:21;s:14:\"exportProducts\";i:22;s:18:\"createApplications\";i:23;s:18:\"importApplications\";i:24;s:16:\"viewApplications\";i:25;s:18:\"updateApplications\";i:26;s:18:\"exportApplications\";i:27;s:19:\"importDisbursements\";i:28;s:17:\"viewDisbursements\";i:29;s:19:\"updateDisbursements\";i:30;s:19:\"exportDisbursements\";i:31;s:16:\"viewTransactions\";i:32;s:13:\"createSavings\";i:33;s:13:\"importSavings\";i:34;s:11:\"viewSavings\";i:35;s:13:\"updateSavings\";i:36;s:13:\"exportSavings\";i:37;s:16:\"createRepayments\";i:38;s:16:\"importRepayments\";i:39;s:14:\"viewRepayments\";i:40;s:16:\"updateRepayments\";i:41;s:16:\"exportRepayments\";i:42;s:18:\"createApplications\";i:43;s:18:\"importApplications\";i:44;s:16:\"viewApplications\";i:45;s:18:\"updateApplications\";i:46;s:18:\"exportApplications\";i:47;s:16:\"createMembership\";i:48;s:16:\"importMembership\";i:49;s:14:\"viewMembership\";i:50;s:16:\"updateMembership\";i:51;s:16:\"exportMembership\";i:52;s:14:\"createExpenses\";i:53;s:14:\"importExpenses\";i:54;s:12:\"viewExpenses\";i:55;s:14:\"updateExpenses\";i:56;s:14:\"exportExpenses\";i:57;s:14:\"createTransfer\";i:58;s:14:\"importTransfer\";i:59;s:12:\"viewTransfer\";i:60;s:14:\"updateTransfer\";i:61;s:14:\"exportTransfer\";i:62;s:16:\"createInvestment\";i:63;s:16:\"importInvestment\";i:64;s:14:\"viewInvestment\";i:65;s:16:\"updateInvestment\";i:66;s:16:\"exportInvestment\";i:67;s:11:\"viewReports\";i:68;s:13:\"exportReports\";}', '$2y$10$frvXaT1gzQqM26T6zHgXv.eWuoCmTxMvvjeN7YkuivA8AgCmEW6Gi', 'active', '$2y$10$.a5rzqgzUlwdnn/2obAzLuRL3J4DEiznRe.k0zjsNb6boikiuZ2aq', '2023-10-19 09:51:15', 'True', '2023-10-10 09:48:58', '2023-10-19 09:51:15', NULL),
(5, 12, 1, 'Apio Josephine', 'angelinasdaniel@gmail.com', '+256774649641', 'Kampala', NULL, 'Employee', 'a:28:{i:0;s:13:\"viewDashboard\";i:1;s:13:\"createClients\";i:2;s:11:\"viewClients\";i:3;s:13:\"updateClients\";i:4;s:13:\"exportClients\";i:5;s:9:\"viewLoans\";i:6;s:14:\"createProducts\";i:7;s:14:\"importProducts\";i:8;s:12:\"viewProducts\";i:9;s:14:\"updateProducts\";i:10;s:14:\"deleteProducts\";i:11;s:14:\"exportProducts\";i:12;s:18:\"createApplications\";i:13;s:18:\"importApplications\";i:14;s:16:\"viewApplications\";i:15;s:18:\"updateApplications\";i:16;s:18:\"deleteApplications\";i:17;s:18:\"exportApplications\";i:18;s:17:\"viewDisbursements\";i:19;s:19:\"exportDisbursements\";i:20;s:18:\"createApplications\";i:21;s:18:\"importApplications\";i:22;s:16:\"viewApplications\";i:23;s:18:\"updateApplications\";i:24;s:18:\"deleteApplications\";i:25;s:18:\"exportApplications\";i:26;s:11:\"viewReports\";i:27;s:13:\"exportReports\";}', '$2y$10$RSrRL7dPOTv9Y1hUl3nabuuUFPa5ex725ydIUw2VoHEsbWHetL0xm', 'active', '$2y$10$.HRYRfa0os/lm8d8fPGYRuVZHnLW8AAr0trcKF8rXRmvc/ZUgtxWy', '2023-10-19 11:37:40', 'False', '2023-10-10 11:02:14', '2023-10-19 11:37:40', NULL),
(6, 13, 1, 'Doreen K', 'doreenkyoma@gmail.com', '+256700139499', 'kampala', NULL, 'Employee', 'a:13:{i:0;s:13:\"viewDashboard\";i:1;s:11:\"viewClients\";i:2;s:9:\"viewLoans\";i:3;s:12:\"viewProducts\";i:4;s:14:\"updateProducts\";i:5;s:18:\"createApplications\";i:6;s:18:\"importApplications\";i:7;s:16:\"viewApplications\";i:8;s:18:\"updateApplications\";i:9;s:18:\"deleteApplications\";i:10;s:22:\"bulkDeleteApplications\";i:11;s:18:\"exportApplications\";i:12;s:17:\"viewDisbursements\";}', '$2y$10$zwpeloGkOcymmpjWypAp8eQN/ksdavwMkYz.U2fLNvG9FAtNxrxWC', 'active', '$2y$10$0Xc0yVAk/2t6XoeQyJLEr.MmGF6G1lnJYOPMfYLmPmqd/kmVuBz/y', '2023-10-17 09:31:52', 'True', '2023-10-10 11:07:24', '2023-10-18 04:09:49', NULL),
(7, 14, 1, 'Racheal A', 'ampeirerachel788@gmail.com', '+256701147780', 'kampala', NULL, 'Employee', 'a:28:{i:0;s:13:\"viewDashboard\";i:1;s:13:\"createClients\";i:2;s:11:\"viewClients\";i:3;s:13:\"updateClients\";i:4;s:13:\"exportClients\";i:5;s:9:\"viewLoans\";i:6;s:14:\"createProducts\";i:7;s:14:\"importProducts\";i:8;s:12:\"viewProducts\";i:9;s:14:\"updateProducts\";i:10;s:14:\"deleteProducts\";i:11;s:14:\"exportProducts\";i:12;s:18:\"createApplications\";i:13;s:18:\"importApplications\";i:14;s:16:\"viewApplications\";i:15;s:18:\"updateApplications\";i:16;s:18:\"deleteApplications\";i:17;s:18:\"exportApplications\";i:18;s:17:\"viewDisbursements\";i:19;s:19:\"exportDisbursements\";i:20;s:18:\"createApplications\";i:21;s:18:\"importApplications\";i:22;s:16:\"viewApplications\";i:23;s:18:\"updateApplications\";i:24;s:18:\"deleteApplications\";i:25;s:18:\"exportApplications\";i:26;s:11:\"viewReports\";i:27;s:13:\"exportReports\";}', '$2y$10$UqVhDE/bx2Q4CmOuXh0AnOs0WSZWRcThSN4DfDCaWUS0H7h465Flm', 'active', '$2y$10$gbyJwuSA5/PZHxWqaH52cOFsnWXtJkZjMWtdP1bmtcCah3Wqr./cG', '2023-10-19 12:15:50', 'True', '2023-10-10 11:12:21', '2023-10-19 12:00:50', NULL),
(8, 15, 1, 'Lukolokomba Julius', 'kiyimba4445@gmail.com', '+256706707683', 'kampala', NULL, 'Employee', 'a:28:{i:0;s:13:\"viewDashboard\";i:1;s:13:\"createClients\";i:2;s:11:\"viewClients\";i:3;s:13:\"updateClients\";i:4;s:13:\"exportClients\";i:5;s:9:\"viewLoans\";i:6;s:14:\"createProducts\";i:7;s:14:\"importProducts\";i:8;s:12:\"viewProducts\";i:9;s:14:\"updateProducts\";i:10;s:14:\"deleteProducts\";i:11;s:14:\"exportProducts\";i:12;s:18:\"createApplications\";i:13;s:18:\"importApplications\";i:14;s:16:\"viewApplications\";i:15;s:18:\"updateApplications\";i:16;s:18:\"deleteApplications\";i:17;s:18:\"exportApplications\";i:18;s:17:\"viewDisbursements\";i:19;s:19:\"exportDisbursements\";i:20;s:18:\"createApplications\";i:21;s:18:\"importApplications\";i:22;s:16:\"viewApplications\";i:23;s:18:\"updateApplications\";i:24;s:18:\"deleteApplications\";i:25;s:18:\"exportApplications\";i:26;s:11:\"viewReports\";i:27;s:13:\"exportReports\";}', '$2y$10$Rtp1pHr4lbCxseYT4kyqEeH/g9liQezMPZLpsGVPWrUaOP1nWjGJW', 'active', '$2y$10$qfHJMmURFD21b4fKFWZX8.0iUreriRDMjiz94TkL1HBT0iLONtPsS', '2023-10-10 11:24:20', 'True', '2023-10-10 11:15:22', '2023-10-18 04:05:55', NULL),
(9, 16, 1, 'loyo boo', 'okotpaulpeter@gmail.com', '+256778547484', 'Gulu', NULL, 'Employee', 'a:69:{i:0;s:13:\"viewDashboard\";i:1;s:14:\"viewAccounting\";i:2;s:19:\"viewChartofAccounts\";i:3;s:21:\"updateChartofAccounts\";i:4;s:19:\"createSubcategories\";i:5;s:17:\"viewSubcategories\";i:6;s:19:\"updateSubcategories\";i:7;s:19:\"exportSubcategories\";i:8;s:17:\"createParticulars\";i:9;s:15:\"viewParticulars\";i:10;s:17:\"updateParticulars\";i:11;s:17:\"exportParticulars\";i:12;s:13:\"createClients\";i:13;s:13:\"importClients\";i:14;s:11:\"viewClients\";i:15;s:13:\"updateClients\";i:16;s:13:\"exportClients\";i:17;s:9:\"viewLoans\";i:18;s:14:\"createProducts\";i:19;s:12:\"viewProducts\";i:20;s:14:\"updateProducts\";i:21;s:14:\"exportProducts\";i:22;s:18:\"createApplications\";i:23;s:18:\"importApplications\";i:24;s:16:\"viewApplications\";i:25;s:18:\"updateApplications\";i:26;s:18:\"exportApplications\";i:27;s:19:\"importDisbursements\";i:28;s:17:\"viewDisbursements\";i:29;s:19:\"updateDisbursements\";i:30;s:19:\"exportDisbursements\";i:31;s:16:\"viewTransactions\";i:32;s:13:\"createSavings\";i:33;s:13:\"importSavings\";i:34;s:11:\"viewSavings\";i:35;s:13:\"updateSavings\";i:36;s:13:\"exportSavings\";i:37;s:16:\"createRepayments\";i:38;s:16:\"importRepayments\";i:39;s:14:\"viewRepayments\";i:40;s:16:\"updateRepayments\";i:41;s:16:\"exportRepayments\";i:42;s:18:\"createApplications\";i:43;s:18:\"importApplications\";i:44;s:16:\"viewApplications\";i:45;s:18:\"updateApplications\";i:46;s:18:\"exportApplications\";i:47;s:16:\"createMembership\";i:48;s:16:\"importMembership\";i:49;s:14:\"viewMembership\";i:50;s:16:\"updateMembership\";i:51;s:16:\"exportMembership\";i:52;s:14:\"createExpenses\";i:53;s:14:\"importExpenses\";i:54;s:12:\"viewExpenses\";i:55;s:14:\"updateExpenses\";i:56;s:14:\"exportExpenses\";i:57;s:14:\"createTransfer\";i:58;s:14:\"importTransfer\";i:59;s:12:\"viewTransfer\";i:60;s:14:\"updateTransfer\";i:61;s:14:\"exportTransfer\";i:62;s:16:\"createInvestment\";i:63;s:16:\"importInvestment\";i:64;s:14:\"viewInvestment\";i:65;s:16:\"updateInvestment\";i:66;s:16:\"exportInvestment\";i:67;s:11:\"viewReports\";i:68;s:13:\"exportReports\";}', '$2y$10$xZbLy44gRGZQ8NxUsYZ/8.aTzkHzYmsPkm/u1XmBMnUzY83GKMGbW', 'active', '$2y$10$zCnMCzaVSa5xWduAMsgEE.6cvDU5Fzxy2P04Zp2BomFU5Kw7xBaEm', '2023-10-23 22:18:15', 'True', '2023-10-23 22:14:17', '2023-10-23 22:18:15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `account_types_category_id_foreign` (`category_id`);

--
-- Indexes for table `api_requests`
--
ALTER TABLE `api_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applicationremarks`
--
ALTER TABLE `applicationremarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicationremarks_application_id_foreign` (`application_id`),
  ADD KEY `applicationremarks_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_flow_types`
--
ALTER TABLE `cash_flow_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD KEY `categories_statement_id_foreign` (`statement_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_branch_id_foreign` (`branch_id`),
  ADD KEY `clients_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursements`
--
ALTER TABLE `disbursements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disbursements_application_id_foreign` (`application_id`),
  ADD KEY `disbursements_product_id_foreign` (`product_id`),
  ADD KEY `disbursements_client_id_foreign` (`client_id`),
  ADD KEY `disbursements_staff_id_foreign` (`staff_id`),
  ADD KEY `disbursements_branch_id_foreign` (`branch_id`),
  ADD KEY `disbursements_particular_id_foreign` (`particular_id`),
  ADD KEY `disbursements_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `emailattachments`
--
ALTER TABLE `emailattachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emailattachments_email_id_foreign` (`email_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emails_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `emailtags`
--
ALTER TABLE `emailtags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entries_payment_id_foreign` (`payment_id`),
  ADD KEY `entries_particular_id_foreign` (`particular_id`),
  ADD KEY `entries_entry_typeId_foreign` (`entry_typeId`),
  ADD KEY `entries_account_typeId_foreign` (`account_typeId`),
  ADD KEY `entries_branch_id_foreign` (`branch_id`),
  ADD KEY `entries_staff_id_foreign` (`staff_id`),
  ADD KEY `entries_client_id_foreign` (`client_id`),
  ADD KEY `entries_application_id_foreign` (`application_id`),
  ADD KEY `entries_disbursement_id_foreign` (`disbursement_id`);

--
-- Indexes for table `entrytypes`
--
ALTER TABLE `entrytypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrytypes_account_typeId_foreign` (`account_typeId`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_application_id_foreign` (`application_id`);

--
-- Indexes for table `loanapplications`
--
ALTER TABLE `loanapplications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loanapplications_branch_id_foreign` (`branch_id`),
  ADD KEY `loanapplications_client_id_foreign` (`client_id`),
  ADD KEY `loanapplications_staff_id_foreign` (`staff_id`),
  ADD KEY `loanapplications_product_id_foreign` (`product_id`);

--
-- Indexes for table `loanproducts`
--
ALTER TABLE `loanproducts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nationalitieslist`
--
ALTER TABLE `nationalitieslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `particulars`
--
ALTER TABLE `particulars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `particulars_category_id_foreign` (`category_id`),
  ADD KEY `particulars_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `particulars_account_typeId_foreign` (`account_typeId`),
  ADD KEY `particulars_cash_flow_typeId_foreign` (`cash_flow_typeId`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `positions_department_id_foreign` (`department_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staffID` (`staffID`),
  ADD KEY `staffs_branch_id_foreign` (`branch_id`),
  ADD KEY `staffs_position_id_foreign` (`position_id`),
  ADD KEY `staffs_officer_staff_id_foreign` (`officer_staff_id`);

--
-- Indexes for table `statements`
--
ALTER TABLE `statements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_foreign` (`category_id`);

--
-- Indexes for table `useractivities`
--
ALTER TABLE `useractivities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `useractivities_user_id_foreign` (`user_id`),
  ADD KEY `useractivities_client_id_foreign` (`client_id`);

--
-- Indexes for table `userlogs`
--
ALTER TABLE `userlogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userlogs_user_id_foreign` (`user_id`),
  ADD KEY `userlogs_client_id_foreign` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_branch_id_foreign` (`branch_id`),
  ADD KEY `users_staff_id_foreign` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `api_requests`
--
ALTER TABLE `api_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `applicationremarks`
--
ALTER TABLE `applicationremarks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cash_flow_types`
--
ALTER TABLE `cash_flow_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=603;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disbursements`
--
ALTER TABLE `disbursements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailattachments`
--
ALTER TABLE `emailattachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtags`
--
ALTER TABLE `emailtags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `entries`
--
ALTER TABLE `entries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `entrytypes`
--
ALTER TABLE `entrytypes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loanapplications`
--
ALTER TABLE `loanapplications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loanproducts`
--
ALTER TABLE `loanproducts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `nationalitieslist`
--
ALTER TABLE `nationalitieslist`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `particulars`
--
ALTER TABLE `particulars`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `statements`
--
ALTER TABLE `statements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `useractivities`
--
ALTER TABLE `useractivities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `userlogs`
--
ALTER TABLE `userlogs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_types`
--
ALTER TABLE `account_types`
  ADD CONSTRAINT `account_types_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `applicationremarks`
--
ALTER TABLE `applicationremarks`
  ADD CONSTRAINT `applicationremarks_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications` (`id`),
  ADD CONSTRAINT `applicationremarks_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_statement_id_foreign` FOREIGN KEY (`statement_id`) REFERENCES `statements` (`id`);

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `clients_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `disbursements`
--
ALTER TABLE `disbursements`
  ADD CONSTRAINT `disbursements_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications` (`id`),
  ADD CONSTRAINT `disbursements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `disbursements_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `disbursements_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars` (`id`),
  ADD CONSTRAINT `disbursements_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `particulars` (`id`),
  ADD CONSTRAINT `disbursements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `loanproducts` (`id`),
  ADD CONSTRAINT `disbursements_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `emailattachments`
--
ALTER TABLE `emailattachments`
  ADD CONSTRAINT `emailattachments_email_id_foreign` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`);

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `emails_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `emailtags` (`id`);

--
-- Constraints for table `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `entries_account_typeId_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types` (`id`),
  ADD CONSTRAINT `entries_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications` (`id`),
  ADD CONSTRAINT `entries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `entries_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `entries_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`),
  ADD CONSTRAINT `entries_entry_typeId_foreign` FOREIGN KEY (`entry_typeId`) REFERENCES `entrytypes` (`id`),
  ADD CONSTRAINT `entries_particular_id_foreign` FOREIGN KEY (`particular_id`) REFERENCES `particulars` (`id`),
  ADD CONSTRAINT `entries_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `particulars` (`id`),
  ADD CONSTRAINT `entries_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `entrytypes`
--
ALTER TABLE `entrytypes`
  ADD CONSTRAINT `entrytypes_account_typeId_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types` (`id`);

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `loanapplications` (`id`);

--
-- Constraints for table `loanapplications`
--
ALTER TABLE `loanapplications`
  ADD CONSTRAINT `loanapplications_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `loanapplications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `loanapplications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `loanproducts` (`id`),
  ADD CONSTRAINT `loanapplications_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `particulars`
--
ALTER TABLE `particulars`
  ADD CONSTRAINT `particulars_account_typeId_foreign` FOREIGN KEY (`account_typeId`) REFERENCES `account_types` (`id`),
  ADD CONSTRAINT `particulars_cash_flow_typeId_foreign` FOREIGN KEY (`cash_flow_typeId`) REFERENCES `cash_flow_types` (`id`),
  ADD CONSTRAINT `particulars_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `particulars_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`);

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `staffs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `staffs_officer_staff_id_foreign` FOREIGN KEY (`officer_staff_id`) REFERENCES `staffs` (`id`),
  ADD CONSTRAINT `staffs_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `useractivities`
--
ALTER TABLE `useractivities`
  ADD CONSTRAINT `useractivities_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `useractivities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `userlogs`
--
ALTER TABLE `userlogs`
  ADD CONSTRAINT `userlogs_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `userlogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `users_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
