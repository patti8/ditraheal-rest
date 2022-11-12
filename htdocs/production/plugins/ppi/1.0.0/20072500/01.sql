-- --------------------------------------------------------
-- Host:                         192.168.23.254
-- Versi server:                 5.7.30 - MySQL Community Server (GPL)
-- OS Server:                    Linux
-- HeidiSQL Versi:               10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Membuang struktur basisdata untuk master
USE `master`;

-- membuang struktur untuk function master.getBulanIndo
DROP FUNCTION IF EXISTS `getBulanIndo`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getBulanIndo`(`TANGGAL` DATETIME) RETURNS varchar(50) CHARSET latin1
    DETERMINISTIC
BEGIN

	DECLARE varhasil VARCHAR(50);
	
	SELECT 
		    CASE MONTH(TANGGAL) 
		      WHEN 1 THEN 'Januari' 
		      WHEN 2 THEN 'Februari' 
		      WHEN 3 THEN 'Maret' 
		      WHEN 4 THEN 'April' 
		      WHEN 5 THEN 'Mei' 
		      WHEN 6 THEN 'Juni' 
		      WHEN 7 THEN 'Juli' 
		      WHEN 8 THEN 'Agustus' 
		      WHEN 9 THEN 'September'
		      WHEN 10 THEN 'Oktober' 
		      WHEN 11 THEN 'November' 
		      WHEN 12 THEN 'Desember' 
		    END INTO varhasil;
	
	RETURN varhasil;
	
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
