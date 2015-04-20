-- phpMyAdmin SQL Dump
-- version 4.3.2
-- http://www.phpmyadmin.net
-- Host: localhost
-- Generation Time: Apr 21, 2015 at 12:33 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `serial` int(11) NOT NULL,
  `userId` varchar(10) NOT NULL,
  `userGroupId` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `loginId` varchar(150) NOT NULL,
  `password` varchar(50) NOT NULL,
  `verifyCode` varchar(10) NOT NULL,
  `expireDate` date NOT NULL,
  `isVerifed` varchar(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`serial`, `userId`, `userGroupId`, `name`, `loginId`, `password`, `verifyCode`, `expireDate`, `isVerifed`) VALUES
(1, 'U001', 'UG001', 'System Admin', 'admin', '513b098ff55b4f375d6210a5f45996dd', 'av799', '2015-08-01', 'Y'),
(10, 'U008', 'UG004', 'Md. Rasel', 'student', '513b098ff55b4f375d6210a5f45996dd', 'vhms2115', '2115-01-04', 'Y'),
(14, 'EMP003', 'UG003', 'Mr. Sabbir Alam', 'employee', '513b098ff55b4f375d6210a5f45996dd', 'vhms2115', '2115-01-04', 'Y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`serial`), ADD UNIQUE KEY `serial` (`serial`), ADD UNIQUE KEY `serial_2` (`serial`), ADD UNIQUE KEY `serial_3` (`serial`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
