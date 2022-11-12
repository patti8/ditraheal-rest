-- --------------------------------------------------------
-- Host:                         192.168.23.254
-- Versi server:                 5.7.31 - MySQL Community Server (GPL)
-- OS Server:                    Linux
-- HeidiSQL Versi:               11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Membuang struktur basisdata untuk ris
CREATE DATABASE IF NOT EXISTS `ris` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ris`;

-- membuang struktur untuk table ris.hl7_message
DROP TABLE IF EXISTS `hl7_message`;
CREATE TABLE IF NOT EXISTS `hl7_message` (
  `ID` char(20) NOT NULL,
  `REQUEST` text NOT NULL,
  `RESPONSE` text NOT NULL,
  `STATUS` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Belum Terkirim; 1=Terkirim',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ris.segment
DROP TABLE IF EXISTS `segment`;
CREATE TABLE IF NOT EXISTS `segment` (
  `CODE` char(10) NOT NULL,
  `SEQ` int(11) NOT NULL,
  `TABLE_ID` char(5) NOT NULL,
  `GROUP` char(10) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(250) NOT NULL,
  PRIMARY KEY (`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ris.segment_field_reference
DROP TABLE IF EXISTS `segment_field_reference`;
CREATE TABLE IF NOT EXISTS `segment_field_reference` (
  `CODE` char(10) NOT NULL,
  `VALUE` char(25) NOT NULL,
  `GROUP` char(25) NOT NULL,
  `DESCRIPTION` varchar(250) NOT NULL,
  PRIMARY KEY (`CODE`,`VALUE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
