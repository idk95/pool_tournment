-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2020 at 02:47 PM
-- Server version: 5.6.49-cll-lve
-- PHP Version: 7.3.6

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pool-db`
--
CREATE DATABASE IF NOT EXISTS `pool-db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pool-db`;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solids_id` int(11) NOT NULL,
  `stripes_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `winner` int(11) DEFAULT NULL,
  `absencent` int(11) DEFAULT NULL,
  `solids_left` int(11) DEFAULT '7',
  `stripes_left` int(11) DEFAULT '7',
  PRIMARY KEY (`id`),
  KEY `matches_ibfk_1` (`solids_id`),
  KEY `matches_ibfk_2` (`stripes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `solids_id`, `stripes_id`, `date`, `winner`, `absencent`, `solids_left`, `stripes_left`) VALUES
(1, 5, 6, '2020-10-05', 5, NULL, 0, 3),
(2, 1, 4, '2020-10-05', NULL, NULL, 7, 7),
(3, 2, 7, '2020-10-05', NULL, NULL, 7, 7),
(4, 8, 3, '2020-10-05', NULL, NULL, 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `points` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `name`, `points`) VALUES
(1, 'Benji Price', 0),
(2, 'Mark Landers', 0),
(3, 'Oliver Tsubasa', 0),
(4, 'Tobey Misaki', 0),
(5, 'Roberto Maravilha', 3),
(6, 'Carlos Santana', 1),
(7, 'Cristiano Ronaldo', 0),
(8, 'Rui Barbosa', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE IF NOT EXISTS `scores` (
  `matches_id` int(11) NOT NULL,
  `people_id` int(11) NOT NULL,
  `ball` int(11) NOT NULL,
  KEY `matches_id` (`matches_id`),
  KEY `people_id` (`people_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`matches_id`, `people_id`, `ball`) VALUES
(1, 6, 13),
(1, 6, 9),
(1, 6, 10),
(1, 5, 6),
(1, 5, 12),
(1, 5, 5),
(1, 6, 2),
(1, 6, 11),
(1, 6, 14),
(1, 6, 8),
(1, 6, 15);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`solids_id`) REFERENCES `people` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`stripes_id`) REFERENCES `people` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`matches_id`) REFERENCES `matches` (`id`),
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
