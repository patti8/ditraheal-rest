-- --------------------------------------------------------
-- Host:                         192.168.137.8
-- Versi server:                 8.0.22 - MySQL Community Server - GPL
-- OS Server:                    Linux
-- HeidiSQL Versi:               11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Membuang struktur basisdata untuk ris
USE `ris`;

-- membuang struktur untuk table ris.tindakan_modality
CREATE TABLE IF NOT EXISTS `tindakan_modality` (
  `ID` smallint NOT NULL AUTO_INCREMENT,
  `TINDAKAN` smallint NOT NULL,
  `MODALITY` smallint NOT NULL,
  `STATUS` smallint NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TINDAKAN_MODALITY` (`TINDAKAN`,`MODALITY`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Pengeluaran data tidak dipilih.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
