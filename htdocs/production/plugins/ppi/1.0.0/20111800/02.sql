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
-- Dumping structure for procedure ppi.cetakAuditGrafikRuangan
DROP PROCEDURE IF EXISTS `cetakAuditGrafikRuangan`;
DELIMITER //
CREATE PROCEDURE `cetakAuditGrafikRuangan`(
	IN `PRUANGAN` TEXT,
	IN `PGROUP` INT,
	IN `PPERIODE` VARCHAR(50)
)
BEGIN
	SET @awal = CONCAT(PPERIODE,'-01 00:00:01');
	SET @akhir = CONCAT(PPERIODE,'-31 23:59:59');
	SET @thn = SUBSTRING(PPERIODE,1,4);
	SET @bln = CONVERT(SUBSTRING(PPERIODE,6,2), UNSIGNED INTEGER);
	SET @sqlText = CONCAT('SELECT 
		rgn.ID,
		CONCAT(master.getBulanIndo(@awal)," ",DATE_FORMAT(@awal,"%Y")) PERIODE,
		rgn.DESKRIPSI RUANGAN,
		pk.`GROUP`,
		ge.DESKRIPSI RUANGAN_DESK,
		IF(pkj.HASIL IS NULL, 0, pkj.HASIL) NILAI
	FROM
		master.ruangan rgn
	LEFT JOIN ppi.penilaian_kegiatan pk ON pk.RUANGAN = rgn.ID AND pk.STATUS = 2
	LEFT JOIN ppi.group_evaluasi ge ON ge.ID = pk.`GROUP` AND ge.ID = ',PGROUP,'
	LEFT JOIN (SELECT
			ge.DESKRIPSI GROUP_DESK,
			pkd.PENILAIAN PENILAIAN_LEFT,
			SUM(IF(pkd.`CHECK` = 1,1,0)) JUMLAH_YA,
			COUNT(*) TOTAL,
			TRUNCATE((SUM(IF(pkd.`CHECK` = 1,1,0))/COUNT(*))*100, 2) HASIL
		FROM ppi.penilaian_kegiatan pk
		LEFT JOIN ppi.group_evaluasi ge ON ge.DESKRIPSI = pk.`GROUP`
		LEFT JOIN ppi.penilaian_kegiatan_detail pkd ON pk.ID = pkd.PENILAIAN
		WHERE pk.STATUS = 2 AND pk.RUANGAN IN(',PRUANGAN,') AND pk.GROUP = ',PGROUP,' AND pk.TANGGAL_BUAT BETWEEN "',@awal,'" AND "',@akhir,'" 
		GROUP BY pk.`GROUP`, pk.RUANGAN) pkj ON pkj.PENILAIAN_LEFT = pk.ID
	WHERE rgn.ID IN(',PRUANGAN,') AND pk.GROUP = ',PGROUP,' GROUP BY pk.`GROUP`, pk.RUANGAN');
	-- select @sqlText;
	 PREPARE stmt FROM @sqlText;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
