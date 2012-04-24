-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2012 at 11:20 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo3`
--

-- --------------------------------------------------------

--
-- Table structure for table `group`
--
-- IMPORTANT: columns id,name root,lft,right,level are required,leave them as they are.
-- name column must create a required rule in Model so leave NOT NULL as is.

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `planet`
-- IMPORTANT: columns id and name are required.Keep these column names.
-- name column must create a required rule in Model so leave NOT NULL as is.

DROP TABLE IF EXISTS `planet`;
CREATE TABLE IF NOT EXISTS `planet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `planetGSM` varchar(20) NOT NULL,
  `planetAdress` varchar(45) NOT NULL,
  `NrSatellites` smallint(5) unsigned DEFAULT NULL,
  `planetInstallDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `planet_group`
--

DROP TABLE IF EXISTS `planet_group`;
CREATE TABLE IF NOT EXISTS `planet_group` (
  `planet_id` int(10) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (planet_id) REFERENCES planet(id)',
  `group_id` int(10) unsigned NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (group_id) REFERENCES group(id)',
  PRIMARY KEY (`planet_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for table `planet_group`
--
ALTER TABLE `planet_group`
  ADD CONSTRAINT  FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT  FOREIGN KEY (`planet_id`) REFERENCES `planet` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
