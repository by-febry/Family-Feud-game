-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 12:23 PM
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
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `player_Names` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `game_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`player_Names`, `score`, `game_Date`) VALUES
('Steve Nash', 24, '2024-10-02'),
('Steve Nash', 24, '2024-10-02'),
('Steve Nash', 24, '2024-10-02'),
('Steve Nash', 24, '2024-10-02'),
('balls', 8, '2024-10-02'),
('balls', 10, '2024-10-02'),
('balls', 14, '2024-10-02'),
('balls', 14, '2024-10-02'),
('balls', 15, '2024-10-02'),
('balls', 15, '2024-10-02'),
('balls', 15, '2024-10-02'),
('balls', 8, '2024-10-02'),
('as', 1, '2024-10-02'),
('gg', 2, '2024-10-02'),
('asda', 3, '2024-10-02'),
('asda', 3, '2024-10-02'),
('gg', 2, '2024-10-02');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
