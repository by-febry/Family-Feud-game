-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 04:51 AM
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
-- Database: `familyfeudgame`
--

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `team_name` varchar(250) NOT NULL,
  `score` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`team_name`, `score`, `date`) VALUES
('Team Alpha', 50, '2024-10-09 01:28:03'),
('Team Bravo', 30, '2024-10-09 01:28:03'),
('Team Charlie', 70, '2024-10-09 01:28:03'),
('Team Delta', 40, '2024-10-09 01:28:03'),
('a', 0, '2024-10-09 01:31:36'),
('v', 0, '2024-10-09 01:31:36'),
('a', 0, '2024-10-09 01:34:23'),
('v', 0, '2024-10-09 01:34:23'),
('nikko', 0, '2024-10-09 02:07:59'),
('aaa', 0, '2024-10-09 02:07:59'),
('nikko', 0, '2024-10-09 02:09:21'),
('aaa', 0, '2024-10-09 02:09:21'),
('nikko', 0, '2024-10-09 02:09:45'),
('aaa', 0, '2024-10-09 02:09:45'),
('nikko', 0, '2024-10-09 02:23:39'),
('aaa', 0, '2024-10-09 02:23:39'),
('nikko', 0, '2024-10-09 02:25:51'),
('aaa', 0, '2024-10-09 02:25:51'),
('team itik', 0, '2024-10-09 02:49:52'),
('team bbbb', 0, '2024-10-09 02:49:52');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
