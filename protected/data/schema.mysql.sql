SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `galileo` ;
CREATE SCHEMA IF NOT EXISTS `galileo` DEFAULT CHARACTER SET latin1 ;
USE `galileo` ;

-- DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assignments`
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
-- Table structure for table `itemchildren`

DROP TABLE IF EXISTS `itemchildren`;
CREATE TABLE IF NOT EXISTS `itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `itemchildren`
--

INSERT INTO `itemchildren` (`parent`, `child`) VALUES
('admin.UserAdministrating', 'admin.UserAdmin'),
('Authority', 'admin.UserAdministrating'),
('admin.UserAdministrating', 'admin.UserCreate'),
('admin.UserAdministrating', 'admin.UserDelete'),
('admin.UserAdministrating', 'admin.UserIndex'),
('admin.UserAdministrating', 'admin.UserUpdate'),
('admin.UserAdministrating', 'admin.UserView'),
('admin.UserViewing', 'admin.UserView'),
('Authority', 'admin.UserViewing'),
('MeasurementAdministrating', 'MeasurementAdmin'),
('Provider', 'MeasurementAdministrating'),
('MeasurementAdministrating', 'MeasurementCreate'),
('MeasurementAdministrating', 'MeasurementIndex'),
('MeasurementAdministrating', 'MeasurementView'),
('MeasurementViewing', 'MeasurementView'),
('Installer', 'MeasurementViewing'),
('Operator', 'MeasurementViewing'),
('PlanetAdministrating', 'PlanetAdmin'),
('Installer', 'PlanetAdministrating'),
('PlanetAdministrating', 'PlanetCreate'),
('PlanetAdministrating', 'PlanetDelete'),
('PlanetAdministrating', 'PlanetIndex'),
('PlanetAdministrating', 'PlanetUpdate'),
('PlanetAdministrating', 'PlanetView'),
('PlanetViewing', 'PlanetView'),
('Installer', 'PlanetViewing'),
('Operator', 'PlanetViewing'),
('SatteliteAdministrating', 'SatteliteAdmin'),
('Installer', 'SatteliteAdministrating'),
('SatteliteAdministrating', 'SatteliteCreate'),
('SatteliteAdministrating', 'SatteliteDelete'),
('SatteliteAdministrating', 'SatteliteIndex'),
('SatteliteAdministrating', 'SatteliteUpdate'),
('SatteliteAdministrating', 'SatteliteView'),
('SatteliteViewing', 'SatteliteView'),
('Installer', 'SatteliteViewing'),
('Operator', 'SatteliteViewing');

-- --------------------------------------------------------


DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin.UserAdmin', 0, NULL, NULL, 'N;'),
('admin.UserAdministrating', 1, NULL, NULL, 'N;'),
('admin.UserCreate', 0, NULL, NULL, 'N;'),
('admin.UserDelete', 0, NULL, NULL, 'N;'),
('admin.UserIndex', 0, NULL, NULL, 'N;'),
('admin.UserUpdate', 0, NULL, NULL, 'N;'),
('admin.UserView', 0, NULL, NULL, 'N;'),
('admin.UserViewing', 1, NULL, NULL, 'N;'),
('Authority', 2, NULL, NULL, NULL),
('Installer', 2, 'Install Planet/Sattelite Hardware, sets up DB for them', '', 's:0:"";'),
('MeasurementAdmin', 0, NULL, NULL, 'N;'),
('MeasurementAdministrating', 1, NULL, NULL, 'N;'),
('MeasurementCreate', 0, NULL, NULL, 'N;'),
('MeasurementDelete', 0, NULL, NULL, 'N;'),
('MeasurementIndex', 0, NULL, NULL, 'N;'),
('MeasurementUpdate', 0, NULL, NULL, 'N;'),
('MeasurementView', 0, NULL, NULL, 'N;'),
('MeasurementViewing', 1, NULL, NULL, 'N;'),
('Operator', 2, 'Read data', '', 's:0:"";'),
('PlanetAdmin', 0, NULL, NULL, 'N;'),
('PlanetAdministrating', 1, NULL, NULL, 'N;'),
('PlanetCreate', 0, NULL, NULL, 'N;'),
('PlanetDelete', 0, NULL, NULL, 'N;'),
('PlanetIndex', 0, NULL, NULL, 'N;'),
('PlanetUpdate', 0, NULL, NULL, 'N;'),
('PlanetView', 0, NULL, NULL, 'N;'),
('PlanetViewing', 1, NULL, NULL, 'N;'),
('Provider', 2, 'Planets push data into DB', '', 's:0:"";'),
('SatteliteAdmin', 0, NULL, NULL, 'N;'),
('SatteliteAdministrating', 1, NULL, NULL, 'N;'),
('SatteliteCreate', 0, NULL, NULL, 'N;'),
('SatteliteDelete', 0, NULL, NULL, 'N;'),
('SatteliteIndex', 0, NULL, NULL, 'N;'),
('SatteliteUpdate', 0, NULL, NULL, 'N;'),
('SatteliteView', 0, NULL, NULL, 'N;'),
('SatteliteViewing', 1, NULL, NULL, 'N;');


-- --------------------------------------------------------

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
  ADD CONSTRAINT `itemchildren_ibfk_2` FOREIGN KEY (`child`) REFERENCES `items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- -----------------------------------------------------
-- Table `galileo`.`sattelite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`sattelite` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`sattelite` (
  `satteliteID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `satteliteActive` TINYINT(1) NOT NULL DEFAULT TRUE ,
  `parentPlanetID` SMALLINT UNSIGNED NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (parentPlanetID) REFERENCES planet(planetID)' ,
  `satteliteName` VARCHAR(25) NOT NULL ,
  `satteliteAdress` VARCHAR(45) NOT NULL ,
  `satteliteInstallDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`satteliteID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `galileo`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`user` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`user` (
  `userID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(128) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `email` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`userID`) )
ENGINE = InnoDB;


INSERT INTO `galileo`.user(userID,username, password, email) VALUES (NULL, 'admin',     SHA1('admin'),     'admin@admin.com') ;
INSERT INTO `galileo`.user(userID,username, password, email) VALUES (NULL, 'installer', SHA1('installer'), 'installer@installer.com') ;
INSERT INTO `galileo`.user(userID,username, password, email) VALUES (NULL, 'operator',  SHA1('operator'),  'operator@operator.com') ;
INSERT INTO `galileo`.user(userID,username, password, email) VALUES (NULL, 'provider',  SHA1('provider'),  'provider@provider.com') ;

-- -----------------------------------------------------
-- Table `galileo`.`group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`group` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`group` (
  `groupID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `groupName` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`groupID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `galileo`.`planet_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`planet_group` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`planet_group` (
  `planetID` SMALLINT UNSIGNED NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (planetID) REFERENCES planet(planetID)' ,
  `groupID` SMALLINT UNSIGNED NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (groupID) REFERENCES group(groupID)' ,
  PRIMARY KEY (`planetID`, `groupID`) )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `galileo`.`planet`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`planet` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`planet` (
  `planetID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `planetName` VARCHAR(25) NOT NULL ,
  `planetGSM` VARCHAR(20) NOT NULL ,
  `planetAdress` VARCHAR(45) NOT NULL ,
  `NrSatellites` SMALLINT UNSIGNED NULL ,
  `planetInstallDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`planetID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `galileo`.`record`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `galileo`.`record` ;

CREATE  TABLE IF NOT EXISTS `galileo`.`record` (
  `satteliteID` SMALLINT UNSIGNED NOT NULL COMMENT 'CONSTRAINT FOREIGN KEY (satteliteID) REFERENCES sattelite(satteliteID)' ,
  `recordDate` INT(11) UNSIGNED NOT NULL ,
  `recordData` SMALLINT UNSIGNED NOT NULL ,
  KEY (`satteliteID`, `recordDate`) )
---  INDEX (`satteliteID`, `recordDate`) 
ENGINE = InnoDB; 

-- -----------------------------------------------------
-- Partition the table for pruning/performance
-- -----------------------------------------------------

ALTER TABLE `galileo`.`record`  PARTITION BY RANGE( recordDate ) (
PARTITION p201301 VALUES LESS THAN (UNIX_TIMESTAMP("2013-02-01 00:00:00")),
PARTITION p201302 VALUES LESS THAN (UNIX_TIMESTAMP("2013-03-01 00:00:00")),
PARTITION p201303 VALUES LESS THAN (UNIX_TIMESTAMP("2013-04-01 00:00:00")),
PARTITION p201304 VALUES LESS THAN (UNIX_TIMESTAMP("2013-05-01 00:00:00")),
PARTITION p201305 VALUES LESS THAN (UNIX_TIMESTAMP("2013-06-01 00:00:00")),
PARTITION p201306 VALUES LESS THAN (UNIX_TIMESTAMP("2013-07-01 00:00:00")),
PARTITION p201307 VALUES LESS THAN (UNIX_TIMESTAMP("2013-08-01 00:00:00")),
PARTITION p201308 VALUES LESS THAN (UNIX_TIMESTAMP("2013-09-01 00:00:00")),
PARTITION p201309 VALUES LESS THAN (UNIX_TIMESTAMP("2013-10-01 00:00:00")),
PARTITION p201310 VALUES LESS THAN (UNIX_TIMESTAMP("2013-11-01 00:00:00")),
PARTITION p201311 VALUES LESS THAN (UNIX_TIMESTAMP("2013-12-01 00:00:00")),
PARTITION p201312 VALUES LESS THAN (UNIX_TIMESTAMP("2014-01-01 00:00:00")),
PARTITION pDefault VALUES LESS THAN MAXVALUE);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

