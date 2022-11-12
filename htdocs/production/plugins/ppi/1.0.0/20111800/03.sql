-- --------------------------------------------------------
-- Host:                         192.168.56.2
-- Server version:               8.0.21 - MySQL Community Server - GPL
-- Server OS:                    Linux
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
USE ppi;
-- Dumping structure for procedure ppi.pdsaDetail
DROP PROCEDURE IF EXISTS `pdsaDetail`;
DELIMITER //
CREATE PROCEDURE `pdsaDetail`(
	IN `PPERIODE` VARCHAR(50),
	IN `PGROUP` INT
)
BEGIN
	SET @thn = SUBSTRING(PPERIODE,1,4);
	SET @bln = CONVERT(SUBSTRING(PPERIODE,6,2), UNSIGNED INTEGER);
	
	SELECT tl.ID, tl.`GROUP`, tl.PLAN, tl.`DO`, tl.`CHECK`, tl.`ACTION`
	FROM ppi.tindak_lanjut tl 
	WHERE tl.`GROUP` = PGROUP AND tl.BULAN = @bln AND tl.TAHUN = @thn  AND tl.`STATUS` = 2;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
