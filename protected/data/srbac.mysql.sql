-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2012 at 11:49 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `demo3`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `assignments`:
--   `itemname`
--       `items` -> `name`
--

INSERT INTO `assignments` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('Authority', '1', '', 's:0:"";'),
('Installer', '1', '', 's:0:"";'),
('Installer', '2', '', 's:0:"";'),
('Operator', '1', '', 's:0:"";'),
('Operator', '2', '', 's:0:"";'),
('Operator', '3', '', 's:0:"";'),
('Provider', '1', '', 's:0:"";'),
('Provider', '4', '', 's:0:"";');

-- --------------------------------------------------------

--
-- Table structure for table `itemchildren`
--

DROP TABLE IF EXISTS `itemchildren`;
CREATE TABLE IF NOT EXISTS `itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `itemchildren`:
--   `parent`
--       `items` -> `name`
--   `child`
--       `items` -> `name`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `email`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.com'),
(2, 'installer', '2fc42d37fee2c81d767e09fb298b70c748940f86', 'installer@installer.com'),
(3, 'operator', 'fe96dd39756ac41b74283a9292652d366d73931f', 'operator@operator.com'),
(4, 'provider', '90c5d1358d128117989fc21f2897a25c99205e50', 'provider@provider.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `itemchildren`
--
ALTER TABLE `itemchildren`
  ADD CONSTRAINT `itemchildren_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
