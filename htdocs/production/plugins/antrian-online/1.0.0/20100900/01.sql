USE informasi;
-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.0.11 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for procedure informasi.infoKetersediaanTempatTidur
DROP PROCEDURE IF EXISTS `infoKetersediaanTempatTidur`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `infoKetersediaanTempatTidur`()
BEGIN
	SET @sqlText = CONCAT('
	SELECT rkls.DESKRIPSI KAMAR
	, SUM(IF(rkt.STATUS_ID=1,1,0)) KOSONG, SUM(IF(rkt.STATUS_ID=1,0,1)) TERISI
	, (SUM(IF(rkt.STATUS_ID=1,1,0)) + SUM(IF(rkt.STATUS_ID=1,0,1))) TOTAL
		  FROM (
		SELECT rkt.ID, rkt.RUANG_KAMAR, rkt.TEMPAT_TIDUR, rkt.STATUS STATUS_ID
		  FROM master.ruang_kamar_tidur rkt
		 WHERE NOT rkt.STATUS IN (0)
		   AND rkt.STATUS = 1	 
		UNION	
		SELECT rkt.ID, rkt.RUANG_KAMAR, rkt.TEMPAT_TIDUR, rkt.STATUS STATUS_ID
		  FROM master.ruang_kamar_tidur rkt
		  		 LEFT JOIN pendaftaran.reservasi r ON r.RUANG_KAMAR_TIDUR = rkt.ID AND r.STATUS = 1
		 WHERE NOT rkt.STATUS IN (0)
		   AND rkt.STATUS = 2
		UNION		 
		SELECT rkt.ID, rkt.RUANG_KAMAR, rkt.TEMPAT_TIDUR, rkt.STATUS STATUS_ID
		  FROM master.ruang_kamar_tidur rkt
		  		 LEFT JOIN pendaftaran.kunjungan k ON k.RUANG_KAMAR_TIDUR = rkt.ID AND k.STATUS = 1
		  		 LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
		  		 LEFT JOIN master.pasien ps ON ps.NORM = p.NORM
		 WHERE NOT rkt.STATUS IN (0)
		   AND rkt.STATUS = 3
	) rkt
	LEFT JOIN master.ruang_kamar rk ON rk.ID = rkt.RUANG_KAMAR 
	LEFT JOIN master.referensi rkls ON rk.KELAS = rkls.ID AND rkls.JENIS = 19
	LEFT JOIN master.ruangan rgn On rgn.ID = rk.RUANGAN
	WHERE NOT rkt.STATUS_ID IN (0)
	  AND rgn.STATUS = 1 AND rkls.ID IS NOT NULL
	GROUP BY rk.KELAS
	ORDER BY rkls.DESKRIPSI ASC');
	
	-- select @sqlText;
	PREPARE stmt FROM @sqlText;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END//
DELIMITER ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
