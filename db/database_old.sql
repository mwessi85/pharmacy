-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2014 at 09:50 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pharmacy`
--
CREATE DATABASE `pharmacy` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pharmacy`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `comment`) VALUES
(1, 'Anti-biotics', ''),
(2, 'Pain-killers', ''),
(3, 'Anti-histamins', ''),
(4, 'Dewormers', ''),
(5, 'Anti-acids', ''),
(6, 'Diuretics', ''),
(7, 'Neuroleptics', ''),
(8, 'Benzodiazepines', '');

-- --------------------------------------------------------

--
-- Table structure for table `credit`
--

CREATE TABLE IF NOT EXISTS `credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(20) NOT NULL,
  `amount_paid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `staff` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_no` (`transaction_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `phone`, `address`) VALUES
(1, 'Not', 'Indicated', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form` varchar(20) NOT NULL,
  `sales_unit` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`id`, `form`, `sales_unit`) VALUES
(1, 'tablet', 'tablet'),
(2, 'syrup', 'syrup'),
(3, 'capsule', 'capsule');

-- --------------------------------------------------------

--
-- Table structure for table `frequency`
--

CREATE TABLE IF NOT EXISTS `frequency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frequency` varchar(30) NOT NULL,
  `times` int(11) NOT NULL,
  `details` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `frequency`
--

INSERT INTO `frequency` (`id`, `frequency`, `times`, `details`) VALUES
(1, 'OD', 1, 'Once a dat'),
(2, 'BD', 2, 'Twice a dat'),
(3, 'TD', 3, 'Three times a day'),
(4, 'START', 1, 'Immediately');

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE IF NOT EXISTS `lab_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test` varchar(50) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE IF NOT EXISTS `medicine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `generic_name` varchar(20) NOT NULL,
  `trade_name` varchar(20) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `form` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `status`, `generic_name`, `trade_name`, `weight`, `form`, `category`, `unit`) VALUES
(1, 'active', 'Paracetamol', 'Panadol', '1000', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pharm`
--

CREATE TABLE IF NOT EXISTS `pharm` (
  `id` int(11) NOT NULL,
  `pharm` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pharm`
--

INSERT INTO `pharm` (`id`, `pharm`) VALUES
(1, 1412028000);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(20) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(50) NOT NULL,
  `frequency` varchar(11) NOT NULL,
  `frequency_name` varchar(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `dispensed` int(11) NOT NULL,
  `buying` int(11) NOT NULL,
  `selling` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_sales`
--

CREATE TABLE IF NOT EXISTS `service_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(20) NOT NULL,
  `service` varchar(50) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(50) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `unit_cost` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service`, `status`, `unit_cost`, `description`) VALUES
(1, 'Dressing', 'active', 1000, ''),
(2, 'Plastering', 'active', 2000, '');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medicine` int(11) NOT NULL,
  `stock_amount` int(9) NOT NULL,
  `current_amount` int(9) NOT NULL,
  `percentage_balance` int(3) NOT NULL DEFAULT '100',
  `expiry_date` date NOT NULL,
  `buying` int(6) NOT NULL,
  `selling` int(6) NOT NULL,
  `stock_date` datetime NOT NULL,
  `staff` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `medicine`, `stock_amount`, `current_amount`, `percentage_balance`, `expiry_date`, `buying`, `selling`, `stock_date`, `staff`) VALUES
(1, 1, 100, 0, 0, '2014-09-28', 100, 130, '2014-08-06 19:29:15', 1),
(2, 1, 500, 336, 67, '2014-09-30', 300, 350, '2014-08-24 15:22:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `temp_med_sales`
--

CREATE TABLE IF NOT EXISTS `temp_med_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(50) NOT NULL,
  `frequency` int(11) NOT NULL,
  `frequency_name` varchar(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `dispensed` int(11) NOT NULL,
  `buying` int(11) NOT NULL,
  `selling` int(11) NOT NULL,
  `client` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_service_sales`
--

CREATE TABLE IF NOT EXISTS `temp_service_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(50) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_test_sales`
--

CREATE TABLE IF NOT EXISTS `temp_test_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_name` varchar(50) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test_sales`
--

CREATE TABLE IF NOT EXISTS `test_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(20) NOT NULL,
  `test` varchar(50) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `test_sales`
--

INSERT INTO `test_sales` (`id`, `transaction_no`, `test`, `unit_cost`, `quantity`, `datetime`, `client_id`, `client_name`, `staff_id`, `staff_name`) VALUES
(12, 'T-1408945953', 'Hiv', 1000, 2, '2014-08-25 08:55:31', 4, 'Carol Nassolo', 1, 'Mutebi Michael'),
(13, 'T-1408945953', 'Widow', 2000, 3, '2014-08-25 08:55:31', 4, 'Carol Nassolo', 1, 'Mutebi Michael');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test` varchar(50) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `unit_cost` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `test`, `status`, `unit_cost`, `description`) VALUES
(1, 'Hiv', 'active', 1000, ''),
(2, 'Widow', 'active', 2000, '');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `value`) VALUES
(1, 'mg'),
(3, 'mg/ml'),
(2, 'ml');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `username` varchar(15) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `level` enum('admin','staff','user') NOT NULL DEFAULT 'user',
  `comment` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `status`, `username`, `password`, `first_name`, `last_name`, `level`, `comment`) VALUES
(1, '1', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Michael', 'Mutebi', 'admin', ''),
(2, '1', 'rlutaaya', '6ccb4b7c39a6e77f76ecfa935a855c6c46ad5611', 'lutaaya', 'ronald', 'staff', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
