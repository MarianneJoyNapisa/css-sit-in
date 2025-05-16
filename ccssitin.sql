-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 16, 2025 at 12:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ccssitin`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_name` varchar(100) NOT NULL DEFAULT 'CCS Admin',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `author_name`, `created_at`, `updated_at`) VALUES
(1, 'Announcement #1', 'I love you', 'CCS Admin', '2025-03-23 19:33:39', NULL),
(2, 'Announcement #2', 'I love you too', 'CCS Admin', '2025-03-23 19:39:27', NULL),
(3, 'Announcement #3', 'I love you more', 'CCS Admin', '2025-03-23 19:44:14', NULL),
(4, 'Announcement #4', 'I love you so much', 'CCS Admin', '2025-03-23 20:00:07', NULL),
(5, 'Announcement #5', 'I love you forever', 'CCS Admin', '2025-03-23 20:01:07', NULL),
(6, 'Announcement #6', 'daisuki', 'CCS Admin', '2025-03-23 20:01:40', NULL),
(7, 'Announcement #7', 'I\'M SO TIRED', 'CCS Admin', '2025-03-24 02:07:43', NULL),
(8, 'Announcement #8', 'Hi', 'CCS Admin', '2025-03-24 10:17:55', NULL),
(9, 'Announcement #9', 'Almost there', 'CCS Admin', '2025-03-24 18:57:27', NULL),
(10, 'Announcement # 10', 'AAAAAAAA', 'CCS Admin', '2025-03-25 11:04:21', NULL),
(11, 'Announcement #10', 'ajshajsja', 'CCS Admin', '2025-03-26 12:35:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `sit_in_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `sit_in_id`, `user_id`, `rating`, `comments`, `created_at`) VALUES
(1, 35, 1, 5, 'askjakjska', '2025-04-03 22:44:02'),
(2, 34, 1, 4, 'Lorem ipsum dolor sit amet. Et sint aperiam cum illum dolorem ex aspernatur impedit in dolores autem aut obcaecati repudiandae non officiis maiores quo maxime nemo. Eos eius laborum est dolore modi ad sint unde eos dicta dicta. Non earum consequatur id minus quibusdam et sunt totam sit expedita voluptates non consequatur tenetur. Cum omnis molestias est cumque quae ut asperiores ipsa est officiis aliquid.', '2025-04-04 08:47:47'),
(3, 35, 1, 3, 'Sorry', '2025-04-04 09:10:57'),
(4, 36, 2, 1, 'I love you', '2025-04-04 09:13:32'),
(5, 37, 1, 4, 'Hi', '2025-04-04 11:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `lab_resources`
--

CREATE TABLE `lab_resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_type` enum('pdf','link') NOT NULL,
  `file_path` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_resources`
--

INSERT INTO `lab_resources` (`id`, `title`, `description`, `file_type`, `file_path`, `uploaded_at`) VALUES
(1, 'Lab Ressource 1', 'aaaaa', 'pdf', '../uploads/6801252722621_DATA INTEGRATION.pdf', '2025-04-17 15:58:31'),
(2, 'Lab Resource 2', 'bbbbbb', 'link', 'https://drive.google.com/drive/folders/1lEwLWFgCu3iWq-NiNo7csmXAA6339UUR?usp=drive_link', '2025-04-17 16:16:31'),
(3, 'Resource #1', 'asahsj', 'pdf', '../uploads/6826a09425792_IPT Final 2025.pdf', '2025-05-16 02:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `lab_schedules`
--

CREATE TABLE `lab_schedules` (
  `id` int(11) NOT NULL,
  `lab_number` int(11) NOT NULL,
  `schedule_link` text NOT NULL,
  `availability` enum('available','unavailable') NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_schedules`
--

INSERT INTO `lab_schedules` (`id`, `lab_number`, `schedule_link`, `availability`, `last_updated`) VALUES
(1, 524, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(2, 526, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(3, 528, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(4, 530, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(5, 542, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(6, 544, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08'),
(7, 517, 'https://ph.pinterest.com/', 'unavailable', '2025-05-16 03:06:08');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`) VALUES
(1, 1, 'Your seat reservation has been approved.', 'unread', '2025-05-13 05:40:23'),
(2, 2, 'Your seat reservation has been denied.', 'unread', '2025-05-13 06:19:14'),
(3, 2, 'Your seat reservation has been denied.', 'unread', '2025-05-13 06:27:15'),
(4, 2, 'Your seat reservation has been approved.', 'unread', '2025-05-13 06:43:01'),
(5, 2, 'Your seat reservation has been approved.', 'unread', '2025-05-13 06:45:07'),
(6, 1, 'Your seat reservation has been approved.', 'unread', '2025-05-16 02:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `laboratory` varchar(10) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_slot` varchar(20) DEFAULT NULL,
  `purpose` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','denied') NOT NULL DEFAULT 'pending',
  `processed_date` datetime DEFAULT NULL,
  `processed_by` varchar(100) DEFAULT NULL,
  `sit_in_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `id_number`, `fullname`, `laboratory`, `seat_number`, `date`, `time_slot`, `purpose`, `created_at`, `status`, `processed_date`, `processed_by`, `sit_in_id`) VALUES
(3, 1, '20947230', 'Marianne Napisa', '526', 1, '2025-05-08', '08:00-10:00', 'C# Programming', '2025-05-07 19:23:03', 'approved', NULL, NULL, NULL),
(4, 1, '20947230', 'Marianne Napisa', '526', 8, '2025-05-08', '08:00-10:00', 'C# Programming', '2025-05-07 19:39:13', 'approved', NULL, NULL, NULL),
(5, 1, '20947230', 'Marianne Napisa', '526', 2, '2025-05-08', '08:00-10:00', 'C# Programming', '2025-05-07 19:47:31', 'approved', NULL, NULL, NULL),
(6, 1, '20947230', 'Marianne Napisa', '517', 3, '2025-05-09', '05:00', 'Web Development', '2025-05-08 21:00:30', 'approved', NULL, NULL, NULL),
(7, 1, '20947230', 'Marianne Napisa', '517', 4, '2025-05-09', '05:08', 'C# Programming', '2025-05-08 21:08:11', 'approved', NULL, NULL, NULL),
(8, 1, '20947230', 'Marianne Napisa', '517', 10, '2025-05-09', '05:14', 'Java Programming', '2025-05-08 21:14:43', 'approved', NULL, NULL, NULL),
(9, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 14, '2025-05-09', '05:52', 'PHP Programming', '2025-05-08 21:52:23', 'approved', NULL, NULL, NULL),
(10, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 23, '2025-05-09', '05:53', 'C# Programming', '2025-05-08 21:53:33', 'approved', NULL, NULL, NULL),
(11, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 5, '2025-05-09', '06:04', 'C# Programming', '2025-05-08 22:04:11', 'approved', NULL, NULL, NULL),
(12, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 24, '2025-05-09', '06:13', 'Java Programming', '2025-05-08 22:13:36', 'approved', NULL, NULL, 84),
(13, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 27, '2025-05-09', '06:58', 'Cisco Packet Tracer', '2025-05-08 22:58:45', 'approved', NULL, NULL, 85),
(14, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 1, '2025-05-16', '07:54', 'Java Programming', '2025-05-08 23:54:55', 'approved', NULL, NULL, 86),
(15, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 37, '2025-05-09', '08:00', 'C# Programming', '2025-05-09 00:00:53', 'approved', NULL, NULL, 87),
(16, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 2, '2025-05-09', '08:10', 'Java Programming', '2025-05-09 00:10:14', 'approved', NULL, NULL, 88),
(17, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 36, '2025-05-09', '08:27', 'C# Programming', '2025-05-09 00:27:51', 'approved', NULL, NULL, 89),
(18, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 19, '2025-05-09', '08:43', 'Java Programming', '2025-05-09 00:43:10', 'approved', NULL, NULL, 90),
(19, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 1, '2025-05-09', '10:48', 'C# Programming', '2025-05-09 02:48:27', 'approved', NULL, NULL, 91),
(20, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 1, '2025-05-09', '10:50', 'C# Programming', '2025-05-09 02:50:05', 'approved', NULL, NULL, 92),
(21, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 15, '2025-05-09', '10:56', 'Cisco Packet Tracer', '2025-05-09 02:56:54', 'approved', NULL, NULL, 93),
(22, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 9, '2025-05-09', '11:00', 'C# Programming', '2025-05-09 03:00:07', 'approved', NULL, NULL, 94),
(23, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 2, '2025-05-09', '11:33', 'C# Programming', '2025-05-09 03:34:05', 'approved', NULL, NULL, 95),
(24, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 2, '2025-05-09', '12:09', 'C# Programming', '2025-05-09 04:09:57', 'approved', NULL, NULL, 96),
(25, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 1, '2025-05-09', '12:16', 'C# Programming', '2025-05-09 04:16:14', 'approved', NULL, NULL, 97),
(26, 1, '20947230', 'Marianne Napisa', '524', 4, '2025-05-13', '11:20', 'C# Programming', '2025-05-13 03:20:38', 'denied', NULL, NULL, NULL),
(27, 1, '20947230', 'Marianne Napisa', '517', 3, '2025-05-13', '11:58', 'C# Programming', '2025-05-13 03:58:19', 'denied', NULL, NULL, NULL),
(28, 1, '20947230', 'Marianne Napisa', '524', 3, '2025-05-13', '13:32', 'Java Programming', '2025-05-13 05:32:44', 'approved', NULL, NULL, 98),
(29, 2, '11223344', 'Aaron Mikiel Mondejar', '524', 2, '2025-05-13', '14:18', 'C# Programming', '2025-05-13 06:19:03', 'denied', NULL, NULL, NULL),
(30, 2, '11223344', 'Aaron Mikiel Mondejar', '517', 3, '2025-05-13', '14:25', 'Web Development', '2025-05-13 06:25:43', 'denied', NULL, NULL, NULL),
(31, 2, '11223344', 'Aaron Mikiel Mondejar', '526', 1, '2025-05-13', '14:42', 'C# Programming', '2025-05-13 06:42:52', 'approved', NULL, NULL, 99),
(32, 2, '11223344', 'Aaron Mikiel Mondejar', '528', 2, '2025-05-13', '14:44', 'C# Programming', '2025-05-13 06:44:52', 'approved', NULL, NULL, 100),
(33, 1, '20947230', 'Marianne Napisa', '517', 3, '2025-05-16', '10:22', 'Java Programming', '2025-05-16 02:22:46', 'approved', NULL, NULL, 101);

-- --------------------------------------------------------

--
-- Table structure for table `seat_status`
--

CREATE TABLE `seat_status` (
  `id` int(11) NOT NULL,
  `laboratory` varchar(10) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat_status`
--

INSERT INTO `seat_status` (`id`, `laboratory`, `seat_number`, `status`) VALUES
(3, '517', 1, 'unavailable'),
(4, '517', 2, 'unavailable'),
(5, '517', 3, 'unavailable'),
(6, '517', 4, 'unavailable'),
(7, '517', 5, 'unavailable'),
(8, '517', 39, 'unavailable'),
(9, '517', 40, 'unavailable');

-- --------------------------------------------------------

--
-- Table structure for table `sit_in_history`
--

CREATE TABLE `sit_in_history` (
  `id` int(11) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `lab` varchar(10) NOT NULL,
  `sessions` int(11) NOT NULL,
  `status` enum('Active','Timed Out') DEFAULT 'Active',
  `time_in` datetime DEFAULT current_timestamp(),
  `timeout` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sit_in_history`
--

INSERT INTO `sit_in_history` (`id`, `id_number`, `name`, `purpose`, `lab`, `sessions`, `status`, `time_in`, `timeout`, `created_at`) VALUES
(32, '55667788', 'Mark Joseph Napisa', 'Python Programming', '542', 29, 'Timed Out', '2025-04-01 19:59:16', '2025-04-01 19:51:43', '2025-04-01 11:51:43'),
(33, '55667788', 'Mark Joseph Napisa', 'Web Development', '528', 28, 'Timed Out', '2025-04-02 00:01:49', '2025-04-02 00:01:19', '2025-04-01 16:01:19'),
(34, '20947230', 'Marianne Napisa', 'C# Programming', '530', 29, 'Timed Out', '2025-04-02 00:17:13', '2025-04-02 00:01:39', '2025-04-01 16:01:39'),
(35, '20947230', 'Marianne Napisa', 'PHP Programming', '542', 28, 'Timed Out', '2025-04-02 00:14:37', '2025-04-02 00:14:28', '2025-04-01 16:14:28'),
(36, '11223344', 'Aaron Mikiel Mondejar', 'Python Programming', '524', 27, 'Timed Out', '2025-04-04 09:12:42', '2025-04-04 09:12:30', '2025-04-04 01:12:30'),
(37, '20947230', 'Marianne Napisa', 'PHP Programming', '542', 27, 'Timed Out', '2025-04-04 11:56:53', '2025-04-04 11:56:19', '2025-04-04 03:56:19'),
(38, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '526', 29, 'Timed Out', '2025-04-21 17:57:59', '2025-04-04 12:12:40', '2025-04-04 04:12:40'),
(39, '20947230', 'Marianne Napisa', 'Java Programming', '526', 29, 'Timed Out', '2025-04-21 17:57:54', '2025-04-04 12:13:01', '2025-04-04 04:13:01'),
(40, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '526', 29, 'Timed Out', '2025-04-21 17:56:49', '2025-04-04 12:18:03', '2025-04-04 04:18:03'),
(41, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '542', 29, 'Timed Out', '2025-04-21 17:56:20', '2025-04-04 12:21:54', '2025-04-04 04:21:54'),
(42, '20947230', 'Marianne Napisa', 'Python Programming', '542', 29, 'Timed Out', '2025-04-23 02:31:10', '2025-04-23 02:20:32', '2025-04-22 18:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `sit_in_logs`
--

CREATE TABLE `sit_in_logs` (
  `id` int(11) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `lab` varchar(10) NOT NULL,
  `sessions` int(11) NOT NULL,
  `status` enum('Reserved','Active','Timed Out') NOT NULL DEFAULT 'Reserved',
  `timeout` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_in` datetime DEFAULT current_timestamp(),
  `points_added` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sit_in_logs`
--

INSERT INTO `sit_in_logs` (`id`, `id_number`, `name`, `purpose`, `lab`, `sessions`, `status`, `timeout`, `created_at`, `time_in`, `points_added`) VALUES
(1, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '528', 29, 'Timed Out', '2025-03-24 07:18:14', '2025-03-24 06:02:43', '2025-03-24 15:06:09', 0),
(2, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '526', 28, 'Timed Out', '2025-03-24 07:05:35', '2025-03-24 06:05:24', '2025-03-24 15:06:09', 0),
(3, '20947230', 'Marianne Napisa', 'Web Development', '524', 29, 'Timed Out', '2025-03-24 07:20:45', '2025-03-24 06:20:37', '2025-03-24 15:06:09', 0),
(4, '55667788', 'Mark Joseph Napisa', 'Java Programming', '526', 29, 'Timed Out', '2025-03-24 07:58:15', '2025-03-24 06:58:07', '2025-03-24 15:06:09', 0),
(5, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '542', 27, 'Timed Out', '2025-03-24 09:25:25', '2025-03-24 07:53:31', '2025-03-24 15:53:31', 0),
(6, '20947230', 'Marianne Napisa', 'Java Programming', '528', 29, 'Timed Out', '2025-03-24 13:13:46', '2025-03-24 11:34:07', '2025-03-24 19:34:07', 0),
(7, '55667788', 'Mark Joseph Napisa', 'C# Programming', '524', 29, '', '2025-03-25 03:40:18', '2025-03-25 02:28:30', '2025-03-25 10:28:30', 0),
(8, '11223344', 'Aaron Mikiel Mondejar', 'PHP Programming', '524', 29, '', '2025-03-25 03:42:14', '2025-03-25 02:41:59', '2025-03-25 10:41:59', 0),
(9, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '542', 28, 'Timed Out', '2025-03-25 03:45:33', '2025-03-25 02:43:16', '2025-03-25 10:43:16', 0),
(10, '20947230', 'Marianne Napisa', 'Web Development', '524', 28, 'Timed Out', '2025-03-25 03:46:42', '2025-03-25 02:46:35', '2025-03-25 10:46:35', 0),
(11, '20947230', 'Marianne Napisa', 'Java Programming', '524', 27, 'Timed Out', '2025-03-25 11:03:33', '2025-03-25 03:03:28', '2025-03-25 11:03:28', 0),
(12, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '530', 26, 'Timed Out', '2025-03-25 14:00:33', '2025-03-25 06:00:23', '2025-03-25 14:00:23', 0),
(13, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '524', 25, 'Timed Out', '2025-03-26 12:35:04', '2025-03-26 04:34:54', '2025-03-26 12:34:54', 0),
(14, '20947230', 'Marianne Napisa', 'Python Programming', '526', 24, 'Timed Out', '2025-03-28 03:03:27', '2025-03-27 19:03:13', '2025-03-28 03:03:13', 0),
(15, '20947230', 'Marianne Napisa', 'Python Programming', '526', 23, 'Timed Out', '2025-03-28 03:48:10', '2025-03-27 19:03:17', '2025-03-28 03:03:17', 0),
(16, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '542', 22, 'Timed Out', '2025-04-01 15:49:28', '2025-04-01 07:38:30', '2025-04-01 15:38:30', 0),
(17, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '526', 27, 'Timed Out', '2025-04-01 16:41:28', '2025-04-01 08:41:15', '2025-04-01 16:41:15', 0),
(18, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '526', 26, 'Timed Out', '2025-04-01 16:42:48', '2025-04-01 08:42:40', '2025-04-01 16:42:40', 0),
(19, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '530', 25, 'Timed Out', '2025-04-01 16:57:44', '2025-04-01 08:57:36', '2025-04-01 16:57:36', 0),
(20, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '542', 24, 'Timed Out', '2025-04-01 17:11:06', '2025-04-01 09:09:07', '2025-04-01 17:09:07', 0),
(21, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '528', 28, 'Timed Out', '2025-04-01 17:11:50', '2025-04-01 09:11:40', '2025-04-01 17:11:40', 0),
(22, '55667788', 'Mark Joseph Napisa', 'Java Programming', '542', 27, 'Timed Out', '2025-04-01 17:13:49', '2025-04-01 09:13:42', '2025-04-01 17:13:42', 0),
(23, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '526', 21, 'Timed Out', '2025-04-01 17:19:52', '2025-04-01 09:19:44', '2025-04-01 17:19:44', 0),
(24, '20947230', 'Marianne Napisa', 'Python Programming', '542', 20, 'Timed Out', '2025-04-01 17:49:24', '2025-04-01 09:49:16', '2025-04-01 17:49:16', 0),
(25, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '530', 23, 'Timed Out', '2025-04-01 17:54:33', '2025-04-01 09:54:24', '2025-04-01 17:54:24', 0),
(26, '11223344', 'Aaron Mikiel Mondejar', 'Python Programming', '542', 22, 'Timed Out', '2025-04-01 17:57:41', '2025-04-01 09:57:33', '2025-04-01 17:57:33', 0),
(27, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '544', 21, 'Timed Out', '2025-04-01 18:01:32', '2025-04-01 10:01:24', '2025-04-01 18:01:24', 0),
(28, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '524', 19, 'Timed Out', '2025-04-01 18:02:16', '2025-04-01 10:02:03', '2025-04-01 18:02:03', 0),
(29, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '530', 26, 'Timed Out', '2025-04-01 18:04:50', '2025-04-01 10:04:42', '2025-04-01 18:04:42', 0),
(30, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '524', 29, 'Timed Out', '2025-04-01 19:31:03', '2025-04-01 10:24:02', '2025-04-01 18:24:02', 0),
(31, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '530', 28, 'Timed Out', '2025-04-01 19:33:54', '2025-04-01 11:33:12', '2025-04-01 19:33:12', 0),
(32, '55667788', 'Mark Joseph Napisa', 'Python Programming', '542', 29, 'Timed Out', '2025-04-01 19:59:16', '2025-04-01 11:51:43', '2025-04-01 19:51:43', 0),
(33, '55667788', 'Mark Joseph Napisa', 'Web Development', '528', 28, 'Timed Out', '2025-04-02 00:01:49', '2025-04-01 16:01:19', '2025-04-02 00:01:19', 0),
(34, '20947230', 'Marianne Napisa', 'C# Programming', '530', 29, 'Timed Out', '2025-04-02 00:17:13', '2025-04-01 16:01:39', '2025-04-02 00:01:39', 0),
(35, '20947230', 'Marianne Napisa', 'PHP Programming', '542', 28, 'Timed Out', '2025-04-02 00:14:37', '2025-04-01 16:14:28', '2025-04-02 00:14:28', 0),
(36, '11223344', 'Aaron Mikiel Mondejar', 'Python Programming', '524', 27, 'Timed Out', '2025-04-04 09:12:42', '2025-04-04 01:12:30', '2025-04-04 09:12:30', 0),
(37, '20947230', 'Marianne Napisa', 'PHP Programming', '542', 27, 'Timed Out', '2025-04-04 11:56:53', '2025-04-04 03:56:19', '2025-04-04 11:56:19', 0),
(38, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '526', 29, 'Timed Out', '2025-04-21 17:57:59', '2025-04-04 04:12:40', '2025-04-04 12:12:40', 0),
(39, '20947230', 'Marianne Napisa', 'Java Programming', '526', 29, 'Timed Out', '2025-04-21 17:57:54', '2025-04-04 04:13:01', '2025-04-04 12:13:01', 0),
(40, '20947230', 'Marianne Napisa', 'Cisco Packet Tracer', '526', 29, 'Timed Out', '2025-04-21 17:56:49', '2025-04-04 04:18:03', '2025-04-04 12:18:03', 0),
(41, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '542', 29, 'Timed Out', '2025-04-21 17:56:20', '2025-04-04 04:21:54', '2025-04-04 12:21:54', 0),
(42, '20947230', 'Marianne Napisa', 'Python Programming', '542', 29, 'Timed Out', '2025-04-23 02:31:10', '2025-04-22 18:20:32', '2025-04-23 02:20:32', 0),
(43, '11223344', 'Aaron Mikiel Mondejar', 'Python Programming', '524', 29, 'Timed Out', '2025-04-23 03:00:50', '2025-04-22 18:59:02', '2025-04-23 02:59:02', 0),
(44, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '542', 28, 'Timed Out', '2025-04-23 03:58:32', '2025-04-22 19:56:29', '2025-04-23 03:56:29', 0),
(45, '55667788', 'Mark Joseph Napisa', 'PHP Programming', '526', 29, 'Timed Out', '2025-04-23 04:24:00', '2025-04-22 19:58:22', '2025-04-23 03:58:22', 0),
(46, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '526', 27, 'Timed Out', '2025-04-24 10:51:50', '2025-04-24 02:51:19', '2025-04-24 10:51:19', 1),
(47, '20947230', 'Marianne Napisa', 'Java Programming', '530', 28, 'Timed Out', '2025-04-24 11:14:31', '2025-04-24 02:51:35', '2025-04-24 10:51:35', 1),
(48, '20947230', 'Marianne Napisa', 'Java Programming', '530', 27, 'Timed Out', '2025-04-24 11:29:49', '2025-04-24 03:29:05', '2025-04-24 11:29:05', 0),
(49, '55667788', 'Mark Joseph Napisa', 'Web Development', '524', 28, 'Timed Out', '2025-04-24 11:33:05', '2025-04-24 03:32:51', '2025-04-24 11:32:51', 1),
(50, '20947230', 'Marianne Napisa', 'Java Programming', '526', 26, 'Timed Out', '2025-04-24 12:18:35', '2025-04-24 04:18:17', '2025-04-24 12:18:17', 1),
(51, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '542', 27, 'Timed Out', '2025-04-24 12:22:32', '2025-04-24 04:18:26', '2025-04-24 12:18:26', 0),
(52, '55667788', 'Mark Joseph Napisa', 'C# Programming', '542', 26, 'Timed Out', '2025-04-24 12:38:09', '2025-04-24 04:37:58', '2025-04-24 12:37:58', 1),
(53, '55667788', 'Mark Joseph Napisa', 'Java Programming', '530', 25, 'Timed Out', '2025-04-24 13:00:33', '2025-04-24 05:00:17', '2025-04-24 13:00:17', 1),
(54, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '530', 26, 'Timed Out', '2025-04-24 13:19:12', '2025-04-24 05:19:03', '2025-04-24 13:19:03', 1),
(55, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '526', 24, 'Timed Out', '2025-04-24 14:08:53', '2025-04-24 06:08:44', '2025-04-24 14:08:44', 1),
(56, '20947230', 'Marianne Napisa', 'Java Programming', '530', 25, 'Timed Out', '2025-04-24 23:13:16', '2025-04-24 15:13:10', '2025-04-24 23:13:10', 1),
(57, '11223344', 'Aaron Mikiel Mondejar', 'Web Development', '542', 29, 'Timed Out', '2025-04-24 23:50:42', '2025-04-24 15:50:26', '2025-04-24 23:50:26', 1),
(58, '11223344', 'Aaron Mikiel Mondejar', 'Python Programming', '542', 28, 'Timed Out', '2025-04-24 23:51:47', '2025-04-24 15:50:31', '2025-04-24 23:50:31', 0),
(59, '20947230', 'Marianne Napisa', 'Java Programming', '530', 24, 'Timed Out', '2025-04-25 00:06:18', '2025-04-24 16:05:48', '2025-04-25 00:05:48', 1),
(60, '20947230', 'Marianne Napisa', 'Python Programming', '544', 23, 'Timed Out', '2025-04-25 00:06:14', '2025-04-24 16:05:53', '2025-04-25 00:05:53', 1),
(61, '20947230', 'Marianne Napisa', 'C# Programming', '528', 22, 'Timed Out', '2025-04-25 00:06:07', '2025-04-24 16:06:01', '2025-04-25 00:06:01', 1),
(62, '20947230', 'Marianne Napisa', 'Java Programming', '530', 23, 'Timed Out', '2025-04-25 00:25:11', '2025-04-24 16:24:48', '2025-04-25 00:24:48', 1),
(63, '20947230', 'Marianne Napisa', 'Java Programming', '530', 22, 'Timed Out', '2025-04-25 00:25:04', '2025-04-24 16:24:51', '2025-04-25 00:24:51', 1),
(64, '20947230', 'Marianne Napisa', 'PHP Programming', '530', 21, 'Timed Out', '2025-04-25 00:25:00', '2025-04-24 16:24:54', '2025-04-25 00:24:54', 1),
(65, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '542', 24, 'Timed Out', '2025-04-25 00:26:38', '2025-04-24 16:26:17', '2025-04-25 00:26:17', 1),
(66, '55667788', 'Mark Joseph Napisa', 'C# Programming', '542', 23, 'Timed Out', '2025-04-25 00:26:33', '2025-04-24 16:26:20', '2025-04-25 00:26:20', 1),
(67, '55667788', 'Mark Joseph Napisa', 'Web Development', '542', 22, 'Timed Out', '2025-04-25 00:26:28', '2025-04-24 16:26:23', '2025-04-25 00:26:23', 1),
(68, '55667788', 'Mark Joseph Napisa', 'Java Programming', '530', 21, 'Timed Out', '2025-04-25 00:28:44', '2025-04-24 16:28:24', '2025-04-25 00:28:24', 1),
(69, '55667788', 'Mark Joseph Napisa', 'Cisco Packet Tracer', '530', 20, 'Timed Out', '2025-04-25 00:28:38', '2025-04-24 16:28:26', '2025-04-25 00:28:26', 1),
(70, '55667788', 'Mark Joseph Napisa', 'PHP Programming', '530', 19, 'Timed Out', '2025-04-25 00:28:34', '2025-04-24 16:28:29', '2025-04-25 00:28:29', 1),
(71, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '524', 29, 'Timed Out', '2025-04-25 00:35:55', '2025-04-24 16:33:45', '2025-04-25 00:33:45', 1),
(72, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '524', 28, 'Timed Out', '2025-04-25 00:35:51', '2025-04-24 16:33:48', '2025-04-25 00:33:48', 1),
(73, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '524', 27, 'Timed Out', '2025-04-25 00:35:47', '2025-04-24 16:33:50', '2025-04-25 00:33:50', 1),
(74, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '530', 27, 'Timed Out', '2025-04-28 08:12:31', '2025-04-28 00:12:21', '2025-04-28 08:12:21', 1),
(75, '20947230', 'Marianne Napisa', 'C# Programming', '526', 1, 'Timed Out', '2025-05-09 06:36:21', '2025-05-07 19:52:49', '2025-05-08 08:00:00', 0),
(76, '20947230', 'Marianne Napisa', 'C# Programming', '526', 1, 'Timed Out', '2025-05-09 06:48:09', '2025-05-07 19:52:52', '2025-05-08 08:00:00', 0),
(77, '20947230', 'Marianne Napisa', 'C# Programming', '526', 1, 'Timed Out', '2025-05-09 06:55:45', '2025-05-07 19:52:53', '2025-05-08 08:00:00', 0),
(78, '20947230', 'Marianne Napisa', 'Web Development', '517', 1, 'Timed Out', '2025-05-09 06:58:05', '2025-05-08 21:00:58', '2025-05-09 05:00:00', 0),
(79, '20947230', 'Marianne Napisa', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 06:58:14', '2025-05-08 21:46:37', '2025-05-09 05:08:00', 0),
(80, '20947230', 'Marianne Napisa', 'Java Programming', '517', 1, 'Timed Out', '2025-05-09 06:58:19', '2025-05-08 21:46:40', '2025-05-09 05:14:00', 0),
(81, '11223344', 'Aaron Mikiel Mondejar', 'PHP Programming', '517', 1, 'Timed Out', '2025-05-09 06:36:42', '2025-05-08 21:52:47', '2025-05-09 05:52:00', 0),
(82, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 06:30:35', '2025-05-08 21:54:02', '2025-05-09 05:53:00', 0),
(83, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 06:16:51', '2025-05-08 22:04:18', '2025-05-09 06:04:00', 0),
(84, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '517', 1, 'Timed Out', '2025-05-09 07:52:40', '2025-05-08 23:32:42', '2025-05-09 06:13:00', 0),
(85, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '517', 1, 'Timed Out', '2025-05-09 07:52:46', '2025-05-08 23:32:45', '2025-05-09 06:58:00', 0),
(86, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '517', 1, 'Timed Out', '2025-05-09 07:58:16', '2025-05-08 23:55:03', '2025-05-16 07:54:00', 0),
(90, '11223344', 'Aaron Mikiel Mondejar', 'Java Programming', '517', 1, 'Timed Out', '2025-05-09 08:47:44', '2025-05-09 00:43:20', '2025-05-09 08:47:39', 0),
(91, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 10:51:03', '2025-05-09 02:48:36', '2025-05-09 10:50:59', 0),
(92, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 10:51:30', '2025-05-09 02:50:38', '2025-05-09 10:51:27', 0),
(93, '11223344', 'Aaron Mikiel Mondejar', 'Cisco Packet Tracer', '517', 1, 'Timed Out', '2025-05-09 11:01:13', '2025-05-09 03:00:20', '2025-05-09 11:00:44', 0),
(94, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 11:01:09', '2025-05-09 03:00:21', '2025-05-09 11:00:46', 0),
(95, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 11:34:27', '2025-05-09 03:34:17', '2025-05-09 11:34:23', 0),
(96, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Timed Out', '2025-05-09 12:15:58', '2025-05-09 04:10:04', '2025-05-09 12:15:56', 0),
(97, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '517', 1, 'Reserved', NULL, '2025-05-09 04:16:27', '2025-05-09 12:16:00', 0),
(98, '20947230', 'Marianne Napisa', 'Java Programming', '524', 1, 'Reserved', NULL, '2025-05-13 05:40:23', '2025-05-13 13:32:00', 0),
(99, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '526', 1, 'Reserved', NULL, '2025-05-13 06:43:01', '2025-05-13 14:42:00', 0),
(100, '11223344', 'Aaron Mikiel Mondejar', 'C# Programming', '528', 1, 'Reserved', NULL, '2025-05-13 06:45:07', '2025-05-13 14:44:00', 0),
(101, '20947230', 'Marianne Napisa', 'Java Programming', '517', 1, 'Timed Out', '2025-05-16 10:23:59', '2025-05-16 02:23:17', '2025-05-16 10:23:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `idno` varchar(8) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `course` varchar(20) NOT NULL,
  `yearlvl` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default_image.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remaining_sessions` int(11) NOT NULL DEFAULT 30,
  `points` int(11) NOT NULL DEFAULT 0,
  `bonus_applied_points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `idno`, `lastname`, `firstname`, `middlename`, `email`, `course`, `yearlvl`, `username`, `password`, `image`, `created_at`, `remaining_sessions`, `points`, `bonus_applied_points`) VALUES
(1, '20947230', 'Napisa', 'Marianne', 'Ybrado', 'mariannejoynapisa@gmail.com', 'BSIT', '3rd year', 'MJNapisa', '$2y$10$a/NBwoXC/mhZcsiWL8zdFOFhjZoS5Jef3RgfALwgvQRub6S0OiPla', 'user_1_1742716175.jpg', '2025-03-22 08:02:54', 15, 9, 9),
(2, '11223344', 'Mondejar', 'Aaron Mikiel', 'Bravo', 'aaronmondejar@gmail.com', 'BSIT', '3rd year', 'AaronMondejar', '$2y$10$kC4HbMjRAaG5gT9Qpq4wre3uMh7kdChXoJoM2IPF3cNvzn2lGBKUi', 'default_image.png', '2025-03-22 08:07:20', 28, 7, 6),
(6, '', '', '', NULL, '', '', '', 'admin', '$2y$10$vWRL22coQ9Z1ty.z4cPGs.Y0te6k9gY6qn6oeRyt5K6v4M.yJbMNy', 'default_image.png', '2025-03-23 01:09:49', 0, 0, 0),
(7, '55667788', 'Napisa', 'Mark Joseph', 'Ybrado', 'markjosephnapisa@gmail.com', 'CS', '4th year', 'MarkNapisa', '$2y$10$qd.zHqilASlxi2HO.dJ4JO1bsMRgZoNwkXwGTCYPTtGrw1SfdNRjK', 'default_image.png', '2025-03-23 01:30:39', 21, 10, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_awards`
--

CREATE TABLE `user_awards` (
  `id` int(11) NOT NULL,
  `idno` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `award_type` enum('most_active','top_performing') NOT NULL,
  `awarded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_awards`
--

INSERT INTO `user_awards` (`id`, `idno`, `user_name`, `award_type`, `awarded_at`) VALUES
(1, '55667788', 'Mark Joseph Napisa', 'most_active', '2025-05-01 16:50:03'),
(2, '20947230', 'Marianne Napisa', 'top_performing', '2025-05-01 16:50:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lab_resources`
--
ALTER TABLE `lab_resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_schedules`
--
ALTER TABLE `lab_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laboratory` (`laboratory`,`seat_number`,`date`,`time_slot`);

--
-- Indexes for table `seat_status`
--
ALTER TABLE `seat_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laboratory` (`laboratory`,`seat_number`);

--
-- Indexes for table `sit_in_history`
--
ALTER TABLE `sit_in_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sit_in_logs`
--
ALTER TABLE `sit_in_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idno` (`idno`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_awards`
--
ALTER TABLE `user_awards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `award_type` (`award_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lab_resources`
--
ALTER TABLE `lab_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lab_schedules`
--
ALTER TABLE `lab_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `seat_status`
--
ALTER TABLE `seat_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sit_in_history`
--
ALTER TABLE `sit_in_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `sit_in_logs`
--
ALTER TABLE `sit_in_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_awards`
--
ALTER TABLE `user_awards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
