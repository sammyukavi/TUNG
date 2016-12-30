-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2012 at 09:02 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tungmidlet`
--
CREATE DATABASE `tungmidlet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tungmidlet`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categoryId` int(6) NOT NULL AUTO_INCREMENT,
  `supermarketId` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryId`),
  UNIQUE KEY `categoryName` (`categoryName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `supermarketId`, `categoryName`) VALUES
(6, 1, 'Vehicles'),
(8, 3, 'Foods'),
(9, 4, 'Cutlery'),
(10, 1, 'Furniture'),
(12, 2, 'Clothes'),
(13, 3, 'Vegetables'),
(14, 4, 'Oil');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE IF NOT EXISTS `stocks` (
  `stockId` int(11) NOT NULL AUTO_INCREMENT,
  `supermarketId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `stockName` varchar(100) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`stockId`),
  UNIQUE KEY `stockName` (`stockName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stockId`, `supermarketId`, `categoryId`, `stockName`, `quantity`, `price`) VALUES
(16, 1, 6, 'Toyota Njue', '11', 3000000),
(17, 3, 13, 'Kasuku', '34', 3),
(19, 1, 6, 'Toyota N', '1', 3000000);

-- --------------------------------------------------------

--
-- Table structure for table `supermarkets`
--

CREATE TABLE IF NOT EXISTS `supermarkets` (
  `supermarketId` int(11) NOT NULL AUTO_INCREMENT,
  `supermarketName` varchar(20) NOT NULL,
  PRIMARY KEY (`supermarketId`),
  UNIQUE KEY `supermarketName` (`supermarketName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `supermarkets`
--

INSERT INTO `supermarkets` (`supermarketId`, `supermarketName`) VALUES
(4, 'game'),
(3, 'nakumatt'),
(1, 'tuskys'),
(2, 'uchumi');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
