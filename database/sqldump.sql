-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2026 at 09:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `h`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `header` text DEFAULT NULL,
  `body` text NOT NULL,
  `closing` text DEFAULT NULL,
  `issued_by` varchar(100) DEFAULT 'HR Department',
  `created_by` varchar(20) NOT NULL,
  `delivery_channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`delivery_channels`)),
  `target_type` enum('All','Department','Individual') NOT NULL,
  `status` enum('Draft','Queued','Sent','Failed') DEFAULT 'Queued',
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `subject`, `header`, `body`, `closing`, `issued_by`, `created_by`, `delivery_channels`, `target_type`, `status`, `sent_at`, `created_at`) VALUES
(3, 'No Subject', '', '', '', 'HR Department', 'EMP0001', '{}', 'All', 'Queued', NULL, '2026-01-26 07:17:30'),
(4, 'No Subject', '', '', '', 'HR Department', 'EMP0001', '{}', 'All', 'Queued', NULL, '2026-01-26 07:17:34'),
(5, 'No Subject', '', '', '', 'HR Department', 'EMP0001', '{}', 'All', 'Queued', NULL, '2026-01-26 07:17:35'),
(6, 'No Subject', '', '', '', 'HR Department', 'EMP0001', '{}', 'All', 'Queued', NULL, '2026-01-26 07:17:35'),
(7, 'Year-End Performance Bonus Announcement', 'Dear Valued Team Members,', 'We are pleased to inform everyone that performance bonuses for FY2025 will be credited on December 20, 2025.\n\nThe bonus amount is based on individual performance reviews and company targets achieved.\n\nThank you for your hard work and dedication this year!', 'Warm regards,', 'HR Department', 'EMP0001', '{\"email\":true}', 'All', 'Queued', NULL, '2026-01-26 07:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `status` enum('P','A','L','VL','SL','Emergency','Maternity','Paternity','-') NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `undertime_hours` decimal(4,2) DEFAULT 0.00,
  `overtime_hours` decimal(4,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `employee_number`, `date`, `status`, `time_in`, `time_out`, `undertime_hours`, `overtime_hours`, `notes`, `created_at`) VALUES
(57, 'EMP0012', '2026-01-26', 'L', '16:17:22', NULL, 0.00, 0.00, NULL, '2026-01-26 08:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'IT', 'Information Technology Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(2, 'Human Resources', 'Human Resources Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(3, 'Finance', 'Finance and Accounting Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(4, 'Sales', 'Sales Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(5, 'Marketing', 'Marketing Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(6, 'Engineering', 'Engineering Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(7, 'Operations', 'Operations Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45'),
(8, 'Customer Support', 'Customer Support Department', '2025-11-29 07:38:45', '2025-11-29 07:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `full_name` varchar(100) GENERATED ALWAYS AS (concat(`first_name`,' ',`last_name`)) STORED,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `manager_number` varchar(20) DEFAULT NULL,
  `employment_type` enum('Full-Time','Part-Time','Contract','Intern') DEFAULT 'Full-Time',
  `location` enum('Headquarters','Remote','Remote','Branch Office') DEFAULT 'Headquarters',
  `status` enum('Active','On Leave','Terminated','Suspended') DEFAULT 'Active',
  `date_hired` date NOT NULL,
  `date_terminated` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_number`, `first_name`, `last_name`, `email`, `phone`, `emergency_contact`, `address`, `department_id`, `position`, `manager_number`, `employment_type`, `location`, `status`, `date_hired`, `date_terminated`, `age`, `birthdate`, `base_salary`, `created_at`, `updated_at`) VALUES
('EMP0001', 'Reinher', 'Lopez', 'lopezreinher7@gmail.com', '+63 917 111 1111', 'Laura Hayes (Wife) - 0917-111-1112', '101 Executive St, Manila', 7, 'Director of Operations', NULL, 'Full-Time', 'Headquarters', 'Active', '2019-01-01', NULL, 52, '1973-06-15', 150000.00, '2025-11-30 02:25:18', '2026-01-02 13:37:38'),
('EMP0002', 'Sofia', 'Garcia', 'sofia.garcia@company.com', '+63 917 222 2222', 'Ramon Garcia (Brother)', '202 HR Ave, Quezon City', 2, 'HR Manager', 'EMP0001', 'Full-Time', 'Headquarters', 'Active', '2020-05-15', NULL, 38, '1987-03-22', 110000.00, '2025-11-30 02:25:18', '2025-12-27 03:45:49'),
('EMP0003', 'Ethan', 'Chiu', 'ethan.chiu@company.com', '+63 917 333 3333', 'Mia Chiu (Mother)', '303 Tech Blvd, Makati', 6, 'Chief Engineer', 'EMP0001', 'Full-Time', 'Headquarters', 'Active', '2021-03-20', NULL, 44, '1981-09-10', 140000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0004', 'Lina', 'Dela Cruz', 'lina.delacruz@company.com', '+63 917 444 4444', 'Victor Dela Cruz (Husband)', '404 IT Tower, Pasig', 1, 'Network Administrator', 'EMP0003', 'Full-Time', 'Headquarters', 'Active', '2022-01-10', NULL, 31, '1994-02-28', 75000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0005', 'Robert', 'Montano', 'robert.montano@company.com', '+63 917 555 5555', 'Carla Montano (Daughter)', '505 Sales Drive, Cebu', 4, 'Sales Team Lead', 'EMP0001', 'Full-Time', 'Branch Office', 'Active', '2021-11-01', NULL, 40, '1985-11-05', 90000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0006', 'Jocelyn', 'Lim', 'jocelyn.lim@company.com', '+63 917 666 6666', 'Ken Lim (Father)', '606 Creative Lane, Pasay', 5, 'Marketing Specialist', 'EMP0005', 'Full-Time', 'Headquarters', 'Active', '2023-04-01', NULL, 28, '1997-07-18', 60000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0007', 'Daniel', 'Ramos', 'daniel.ramos@company.com', '+63 917 777 7777', 'Ana Ramos (Sister)', '707 Support Road, Remote', 1, 'IT Support Specialist', 'EMP0004', 'Full-Time', 'Remote', 'Active', '2023-06-20', NULL, 25, '2000-04-12', 55000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0008', 'Camille', 'Cruz', 'camille.cruz@company.com', '+63 917 888 8888', 'Peter Cruz (Brother)', '808 People Street, Mandaluyong', 2, 'Recruiter', 'EMP0002', 'Full-Time', 'Headquarters', 'Active', '2022-09-01', NULL, 33, '1992-08-30', 52000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0009', 'Henry', 'Wang', 'henry.wang@company.com', '+63 917 999 9999', 'Alice Wang (Wife)', '909 Finance Tower, Taguig', 3, 'Senior Accountant', 'EMP0001', 'Full-Time', 'Headquarters', 'Active', '2020-10-10', NULL, 46, '1979-01-25', 88000.00, '2025-11-30 02:25:18', '2025-12-22 11:53:29'),
('EMP0010', 'Elena', 'Lopez', 'elena.lopez@company.com', '+63 917 101 0101', 'Marco Lopez (Father)', '1010 Code Place, Remote', 6, 'Software Developer', 'EMP0003', 'Full-Time', 'Remote', 'Active', '2023-01-25', NULL, 29, '1996-05-14', 80000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0011', 'Kevin', 'Santos', 'kevin.santos@company.com', '+63 917 111 1212', 'Grace Santos (Mother)', '1111 Sales Road, Cebu', 4, 'Sales Associate', 'EMP0005', 'Part-Time', 'Branch Office', 'Active', '2024-02-14', NULL, 22, '2003-10-08', 35000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0012', 'Nicole', 'Aquino', 'nicole.aquino@company.com', '+63 917 121 2121', 'James Aquino (Husband)', '1212 Content Corner, Remote', 5, 'Content Writer', 'EMP0006', 'Full-Time', 'Remote', 'Active', '2023-08-01', NULL, 27, '1998-12-03', 58000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0013', 'Victor', 'Perez', 'victor.perez@company.com', '+63 917 131 3131', 'Tessa Perez (Sister)', '1313 Server Room, Manila', 1, 'Database Admin', 'EMP0004', 'Full-Time', 'Headquarters', 'Active', '2022-05-01', NULL, 35, '1990-07-20', 82000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0014', 'Zara', 'Kho', 'zara.kho@company.com', '+63 917 141 4141', 'Leo Kho (Father)', '1414 Training Center, Manila', 2, 'Training Coordinator', 'EMP0002', 'Full-Time', 'Headquarters', 'Active', '2024-01-01', NULL, 24, '2001-11-17', 50000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0015', 'Mark', 'Tiu', 'mark.tiu@company.com', '+63 917 151 5151', 'Rita Tiu (Wife)', '1515 Logistics Bay, Manila', 7, 'Logistics Specialist', 'EMP0001', 'Full-Time', 'Headquarters', 'Active', '2022-07-01', NULL, 39, '1986-04-09', 65000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0016', 'Joyce', 'Valdez', 'joyce.valdez@company.com', '+63 917 161 6161', 'Sam Valdez (Mother)', '1616 Support Hub, Branch', 8, 'Support Agent', 'EMP0005', 'Full-Time', 'Branch Office', 'Active', '2023-03-10', NULL, 26, '1999-09-21', 45000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0017', 'Paolo', 'Uy', 'paolo.uy@company.com', '+63 917 171 7171', 'Manny Uy (Father)', '1717 Quality St, Remote', 6, 'QA Tester', 'EMP0003', 'Full-Time', 'Remote', 'Active', '2023-05-15', NULL, 30, '1995-02-28', 68000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0018', 'Isabel', 'Lim', 'isabel.lim@company.com', '+63 917 181 8181', 'Greg Lim (Husband)', '1818 Accounting Lane, Manila', 3, 'Financial Analyst', 'EMP0009', 'Full-Time', 'Headquarters', 'Active', '2022-10-10', NULL, 34, '1991-06-16', 72000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0019', 'Chris', 'Gomez', 'chris.gomez@company.com', '+63 917 191 9191', 'Danny Gomez (Brother)', '1919 Territory Road, Cebu', 4, 'Territory Manager', 'EMP0005', 'Full-Time', 'Branch Office', 'Active', '2022-04-01', NULL, 42, '1983-08-14', 95000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0020', 'Sarah', 'Diaz', 'sarah.diaz@company.com', '+63 917 202 0202', 'Tom Diaz (Spouse)', '2020 Digital Loft, Remote', 5, 'SEO Specialist', 'EMP0006', 'Full-Time', 'Remote', 'Active', '2023-11-01', NULL, 29, '1996-10-31', 61000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:42'),
('EMP0021', 'Miguel', 'Ramos', 'miguel.ramos@company.com', '+63 917 212 1212', 'Lia Ramos (Mother)', '2121 Systems Center, Manila', 1, 'Systems Analyst', 'EMP0004', 'Full-Time', 'Headquarters', 'Active', '2024-02-19', NULL, 37, '1988-03-19', 78000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0022', 'Hannah', 'Yu', 'hannah.yu@company.com', '+63 917 222 2222', 'Kyle Yu (Father)', '2222 HR Annex, Manila', 2, 'HR Assistant', 'EMP0002', 'Full-Time', 'Headquarters', 'Active', '2024-05-01', NULL, 23, '2002-01-11', 40000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0023', 'Leo', 'Reyes', 'leo.reyes@company.com', '+63 917 232 3232', 'Nina Reyes (Wife)', '2323 Warehouse Ave, Manila', 7, 'Warehouse Supervisor', 'EMP0015', 'Full-Time', 'Headquarters', 'Active', '2022-09-01', NULL, 45, '1980-12-05', 55000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0024', 'Cindy', 'Tan', 'cindy.tan@company.com', '+63 917 242 4242', 'Eric Tan (Brother)', '2424 Customer Hub, Branch', 8, 'Senior Support Agent', 'EMP0016', 'Full-Time', 'Branch Office', 'Active', '2021-12-01', NULL, 32, '1993-05-27', 50000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0025', 'Jason', 'Lim', 'jason.lim@company.com', '+63 917 252 5252', 'Grace Lim (Mother)', '2525 Developer Apt, Remote', 6, 'Junior Developer', 'EMP0017', 'Full-Time', 'Remote', 'Active', '2024-06-01', NULL, 25, '2000-09-02', 55000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0026', 'Patricia', 'Diaz', 'patricia.diaz@company.com', '+63 917 262 6262', 'Sam Diaz (Father)', '2626 Bookkeeping Lane, Manila', 3, 'Bookkeeper', 'EMP0018', 'Full-Time', 'Headquarters', 'Active', '2023-02-01', NULL, 29, '1996-11-15', 53000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0027', 'Mike', 'Bautista', 'mike.bautista@company.com', '+63 917 272 7272', 'Tess Bautista (Sister)', '2727 Intern Quarters, Branch', 4, 'Sales Intern', 'EMP0019', 'Intern', 'Branch Office', 'Active', '2024-07-01', NULL, 20, '2005-04-22', 25000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0028', 'Ben', 'Cheng', 'ben.cheng@company.com', '+63 917 282 8282', 'Chris Cheng (Brother)', '2828 Marketing Suite, Remote', 5, 'PPC Manager', 'EMP0020', 'Full-Time', 'Remote', 'Active', '2023-09-15', NULL, 36, '1989-07-08', 70000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0029', 'Erica', 'Ong', 'erica.ong@company.com', '+63 917 292 9292', 'Ken Ong (Father)', '2929 IT Hub, Manila', 1, 'IT Intern', 'EMP0021', 'Intern', 'Headquarters', 'Active', '2024-08-01', NULL, 21, '2004-02-18', 20000.00, '2025-11-30 02:25:18', '2025-12-20 02:22:43'),
('EMP0030', 'REINHER', 'LOPEZ', 'reinherlopez@gmail.com', '+63 917 111 1111', 'Laura Hayes (Wife) - 0917-111-1112', 'San Roque Bauan, Batangas, Relocation', 2, '0', 'EMP0016', 'Contract', 'Remote', 'Active', '2014-10-21', NULL, 30, '1995-02-01', 99999999.99, '2026-01-26 05:51:33', '2026-01-26 05:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(20) NOT NULL,
  `vacation_leave` int(11) DEFAULT 15,
  `sick_leave` int(11) DEFAULT 10,
  `emergency_leave` int(11) DEFAULT 5,
  `maternity_leave` int(11) DEFAULT 105,
  `paternity_leave` int(11) DEFAULT 15,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_number`, `vacation_leave`, `sick_leave`, `emergency_leave`, `maternity_leave`, `paternity_leave`, `updated_at`) VALUES
(10, 'EMP0001', 15, 10, 5, 105, 15, '2025-11-30 12:03:13'),
(11, 'EMP0002', 15, 10, 5, 105, 15, '2025-12-22 11:40:10'),
(12, 'EMP0003', 15, 10, 5, 105, 15, '2025-12-27 03:44:55'),
(13, 'EMP0004', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(14, 'EMP0005', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(15, 'EMP0006', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(16, 'EMP0007', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(17, 'EMP0008', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(18, 'EMP0009', 15, 10, 3, 105, 15, '2025-12-20 02:40:17'),
(19, 'EMP0010', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(20, 'EMP0011', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(21, 'EMP0012', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(22, 'EMP0013', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(23, 'EMP0014', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(24, 'EMP0015', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(25, 'EMP0016', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(26, 'EMP0017', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(27, 'EMP0018', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(28, 'EMP0019', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(29, 'EMP0020', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(30, 'EMP0021', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(31, 'EMP0022', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(32, 'EMP0023', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(33, 'EMP0024', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(34, 'EMP0025', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(35, 'EMP0026', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(36, 'EMP0027', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(37, 'EMP0028', 15, 10, 5, 105, 15, '2025-11-30 02:29:15'),
(38, 'EMP0029', 15, 10, 5, 105, 15, '2025-11-30 02:29:15');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(20) NOT NULL,
  `leave_type` enum('VL','SL','Emergency','Maternity','Paternity') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_requested` int(11) NOT NULL,
  `reason` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` datetime DEFAULT current_timestamp(),
  `approved_by` varchar(20) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','HR','Employee') DEFAULT 'Employee',
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_number`, `username`, `password_hash`, `role`, `last_login`, `is_active`, `created_at`) VALUES
(1, 'EMP0001', 'Reinher', '$2y$10$uF6N/n6tLOXLUJoebapH8uWmOFam3S37SzqPWKKIZPmI3LMUtBO4u', 'HR', '2026-01-26 16:17:10', 1, '2025-11-30 12:22:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_emp_date` (`employee_number`,`date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `manager_number` (`manager_number`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_department` (`department_id`),
  ADD KEY `idx_birthdate` (`birthdate`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_number` (`employee_number`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `employees` (`employee_number`) ON DELETE CASCADE;

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employees` (`employee_number`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`manager_number`) REFERENCES `employees` (`employee_number`) ON DELETE SET NULL;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employees` (`employee_number`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employees` (`employee_number`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`employee_number`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employees` (`employee_number`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE announcement_recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    announcement_id INT NOT NULL,
    employee_number VARCHAR(20) NULL,
    department_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at DATETIME NULL,
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

