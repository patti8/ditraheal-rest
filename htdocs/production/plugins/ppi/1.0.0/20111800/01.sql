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
USE  ppi;
-- Dumping structure for procedure ppi.cetakAuditGrafikIndividu
DROP PROCEDURE IF EXISTS `cetakAuditGrafikIndividu`;
DELIMITER //
CREATE PROCEDURE `cetakAuditGrafikIndividu`(
	IN `PPROFESI` TEXT,
	IN `PGROUP` INT,
	IN `PPERIODE` VARCHAR(50)
)
BEGIN
	SET @awal = CONCAT(PPERIODE,'-01 00:00:00');
	SET @akhir = CONCAT(PPERIODE,'-31 23:59:59');
	SET @thn = SUBSTRING(PPERIODE,1,4);
	SET @bln = CONVERT(SUBSTRING(PPERIODE,6,2), UNSIGNED INTEGER);
	SET @sqlText = CONCAT('SELECT 
		ref.DESKRIPSI PROFESI_DESK,
		CONCAT(master.getBulanIndo("',@awal,'")," ",DATE_FORMAT("',@awal,'","%Y")) PERIODE,
		ref.ID PROFESI_ID,
		ai.`GROUP` ID_GROUP,
		ge.DESKRIPSI GROUP_DESK,
		IF(ni.TOTAL_HASIL IS NOT NULL, ni.TOTAL_HASIL, 0) TOTAL_HASIL
	FROM 
		master.referensi ref
	LEFT JOIN ppi.audit_individu ai ON ai.PROFESI = ref.ID  AND ai.STATUS = 2
	LEFT JOIN ppi.group_evaluasi ge ON ge.ID = ai.`GROUP`
	LEFT JOIN (
		SELECT
			ai.ID ID_AI,
			ai.PROFESI,
			ai.`GROUP`,
			TRUNCATE(SUM(tt.HASIL)/COUNT(*),2) TOTAL_HASIL
		FROM ppi.audit_individu ai
		LEFT JOIN (
			SELECT
				xi.PERIODE,
				xd.XAMPLE,
				SUM(IF(xd.`CHECK` = 1,1,0)) CHECK_YA,
				COUNT(*) TOTAL_ITEMS,
				TRUNCATE((SUM(IF(xd.`CHECK` = 1,1,0))/ COUNT(*))*100, 2) HASIL
			FROM ppi.xample_detail xd
			LEFT JOIN ppi.xample_individu xi ON xi.ID = xd.XAMPLE
			LEFT JOIN ppi.audit_individu ai ON ai.ID = xi.PERIODE
			WHERE ai.STATUS = 2 AND ai.PROFESI IN (',PPROFESI,') AND ai.`GROUP` = ',PGROUP,' AND ai.TANGGAL_BUAT BETWEEN "',@awal,'" AND "',@akhir,'"
			GROUP BY ai.GROUP, ai.PROFESI
		) tt ON tt.PERIODE = ai.ID
		WHERE ai.STATUS = 2 AND ai.PROFESI IN (',PPROFESI,') AND ai.`GROUP` = ',PGROUP,' AND ai.TANGGAL_BUAT BETWEEN "',@awal,'" AND "',@akhir,'"
		GROUP BY ai.ID
	) ni ON ni.ID_AI = ai.ID
	WHERE ref.JENIS = 36 AND ref.ID IN (',PPROFESI,') AND ai.`GROUP` = ',PGROUP,' GROUP BY ai.GROUP, ai.PROFESI');
	
	 -- select @sqlText;
	 PREPARE stmt FROM @sqlText;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
