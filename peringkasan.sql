/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.33-MariaDB : Database - peringkasan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`peringkasan` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `peringkasan`;

/*Table structure for table `bobot` */

DROP TABLE IF EXISTS `bobot`;

CREATE TABLE `bobot` (
  `id_term` bigint(20) NOT NULL AUTO_INCREMENT,
  `term` varchar(50) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `id_dokumen` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_term`),
  KEY `id_dokumen` (`id_dokumen`),
  CONSTRAINT `bobot_ibfk_1` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bobot` */

/*Table structure for table `dokbot` */

DROP TABLE IF EXISTS `dokbot`;

CREATE TABLE `dokbot` (
  `id_dokbot` bigint(20) NOT NULL AUTO_INCREMENT,
  `bobot` float DEFAULT NULL,
  `id_dokumen` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_dokbot`),
  KEY `id_dokumen` (`id_dokumen`),
  CONSTRAINT `dokbot_ibfk_2` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dokbot` */

/*Table structure for table `dokumen` */

DROP TABLE IF EXISTS `dokumen`;

CREATE TABLE `dokumen` (
  `id_dokumen` bigint(20) NOT NULL AUTO_INCREMENT,
  `dokumen` text,
  `index` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `dokumen` */

/*Table structure for table `stemming` */

DROP TABLE IF EXISTS `stemming`;

CREATE TABLE `stemming` (
  `id_stemming` bigint(20) NOT NULL AUTO_INCREMENT,
  `term` varchar(50) DEFAULT NULL,
  `term_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_stemming`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stemming` */

/* Procedure structure for procedure `turncate` */

/*!50003 DROP PROCEDURE IF EXISTS  `turncate` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `turncate`()
BEGIN
	SET FOREIGN_KEY_CHECKS = 0;
	TRUNCATE TABLE dokumen	;
	TRUNCATE TABLE dokbot	;
	TRUNCATE TABLE stemming	;
	SET FOREIGN_KEY_CHECKS = 1;
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
