-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2018 at 07:05 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql7249708`
--

-- --------------------------------------------------------

--
-- Table structure for table `specialoffers`
--

CREATE TABLE `specialoffers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `offerid` varchar(10) NOT NULL,
  `discount` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `createdon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specialoffers`
--

INSERT INTO `specialoffers` (`id`, `name`, `offerid`, `discount`, `active`, `createdon`) VALUES
(1, 'August Ending Promo', 'RBFOThVpPw', '60', 1, '29-07-2018 08:31:10am');

-- --------------------------------------------------------

--
-- Table structure for table `vouchercodes`
--

CREATE TABLE `vouchercodes` (
  `id` int(11) NOT NULL,
  `code` varchar(8) NOT NULL,
  `specialoffer` varchar(100) NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `expirationdate` varchar(20) NOT NULL,
  `usagedate` varchar(20) NOT NULL,
  `usagenumber` varchar(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `createdon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vouchercodes`
--

INSERT INTO `vouchercodes` (`id`, `code`, `specialoffer`, `recipient`, `expirationdate`, `usagedate`, `usagenumber`, `active`, `createdon`) VALUES
(1, 'Gs1we9Bs', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-09-2018', '', '0', 1, '29-07-2018'),
(2, '7iIAYr6H', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-07-2018', '29-07-2018', '1', 0, '29-07-2018'),
(3, 'ZiWJQfJL', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-09-2018', '29-07-2018', '1', 0, '29-07-2018'),
(4, 'j36OqsuM', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-09-2018', '', '0', 1, '29-07-2018'),
(5, 'HWOB3BJR', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-09-2018', '29-07-2018', '1', 0, '29-07-2018'),
(6, 'PPNH33W8', 'August Ending Promo', 'ttconfirmed@gmail.com', '26-09-2018', '29-07-2018', '1', 0, '29-07-2018'),
(7, 'SQZOTVDM', 'August Ending Promo', 'ttconfirmed@gmail.com', '30-07-2018', '29-07-2018', '1', 0, '29-07-2018'),
(8, '3Z5OKBGW', 'August Ending Promo', 'ttconfirmed@gmail.com', '30-07-2018', '29-07-2018', '1', 0, '29-07-2018'),
(9, 'DS5MTLGY', 'August Ending Promo', 'ttconfirmed@gmail.com', '30-07-2018', '', '0', 1, '29-07-2018'),
(10, 'Y4W11FAQ', 'August Ending Promo', 'ttconfirmed@gmail.com', '30-07-2018', '', '0', 1, '29-07-2018');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `specialoffers`
--
ALTER TABLE `specialoffers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchercodes`
--
ALTER TABLE `vouchercodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `specialoffers`
--
ALTER TABLE `specialoffers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vouchercodes`
--
ALTER TABLE `vouchercodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
