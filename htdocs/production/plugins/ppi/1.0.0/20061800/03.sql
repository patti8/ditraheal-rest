-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.0.11 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Membuang struktur basisdata untuk ppi
CREATE DATABASE IF NOT EXISTS `ppi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ppi`;

-- membuang struktur untuk table ppi.alasan
CREATE TABLE IF NOT EXISTS `alasan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESKRIPSI` varchar(150) NOT NULL,
  `STATUS_ETC` int(1) NOT NULL DEFAULT '0',
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `STATUS_DLL` (`STATUS_ETC`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.audit_individu
CREATE TABLE IF NOT EXISTS `audit_individu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROFESI` int(11) NOT NULL,
  `GROUP` int(11) NOT NULL COMMENT 'GROUP EVALUASI',
  `TANGGAL_BUAT` date NOT NULL,
  `PEGAWAI` int(1) NOT NULL DEFAULT '1' COMMENT '0 : BUKAN PEGAWAI 1:PEGAWAI',
  `TANGGAL` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OLEH` int(11) NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `PROFESI` (`PROFESI`),
  KEY `GROUP_KEGIATAN` (`GROUP`),
  KEY `TANGGAL_BUAT` (`TANGGAL_BUAT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='DATA AUDIT OBJECK INDIVIDU';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.cuci_tangan
CREATE TABLE IF NOT EXISTS `cuci_tangan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROFESI` int(11) NOT NULL,
  `TANGGAL_BUAT` date NOT NULL,
  `TANGGAL` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `PROFESI` (`PROFESI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.cuci_tangan_detail
CREATE TABLE IF NOT EXISTS `cuci_tangan_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUCI_TANGAN` int(11) NOT NULL,
  `ID_PEG` varchar(20) NOT NULL,
  `MOMENT_1` int(1) NOT NULL DEFAULT '0',
  `MOMENT_2` int(1) NOT NULL DEFAULT '0',
  `MOMENT_3` int(1) NOT NULL DEFAULT '0',
  `MOMENT_4` int(1) NOT NULL DEFAULT '0',
  `MOMENT_5` int(1) NOT NULL DEFAULT '0',
  `KETERANGAN` text NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_PEG` (`ID_PEG`),
  KEY `CUCI_TANGAN` (`CUCI_TANGAN`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.flebitis
CREATE TABLE IF NOT EXISTS `flebitis` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOPEN` char(10) NOT NULL DEFAULT '',
  `KUNJUNGAN` char(19) NOT NULL DEFAULT '' COMMENT 'KUNJUNGAN',
  `NORM` int(11) NOT NULL DEFAULT '0',
  `USIA` varchar(50) NOT NULL,
  `RUANGAN` char(20) NOT NULL,
  `ANTIBIOTIK` int(1) NOT NULL,
  `TANGGAL_PAKAI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_BERHENTI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_PASANG` date NOT NULL,
  `TANGGAL_LEPAS` date NOT NULL,
  `LAMA_HARI` int(11) NOT NULL DEFAULT '0',
  `INFEKSI_LAIN` text NOT NULL,
  `JENIS_PEMERIKSAAN_KULTUR` int(11) NOT NULL COMMENT 'REFERENSI 152',
  `HASIL_KULTUR` text NOT NULL,
  `LABORATORIUM` text NOT NULL,
  `PEMAKAIAN_ALAT` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'PEMASANGAN INTRA VENA LINE ',
  `STATUS_HAIS` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'STATUS KEJADIAN HAIS ( FLEBITIS )',
  `TANGGAL_KEJADIAN` date DEFAULT NULL,
  `OLEH` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `NORM` (`NORM`),
  KEY `OPERASI` (`KUNJUNGAN`),
  KEY `STATUS` (`STATUS`),
  KEY `NOPEN` (`NOPEN`),
  KEY `RUANGAN` (`RUANGAN`),
  KEY `TANGGAL_PASANG` (`TANGGAL_PASANG`),
  KEY `TANGGAL_LEPAS` (`TANGGAL_LEPAS`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.gejala_ido
CREATE TABLE IF NOT EXISTS `gejala_ido` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDO` int(11) unsigned NOT NULL,
  `GEJALA_IDO` int(11) unsigned NOT NULL COMMENT 'MASTER - REFERENSI (JENIS  = 125)',
  `CHECK` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`CHECK`),
  KEY `IDO` (`IDO`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.group_evaluasi
CREATE TABLE IF NOT EXISTS `group_evaluasi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESKRIPSI` varchar(100) NOT NULL,
  `JENIS` int(1) NOT NULL DEFAULT '1' COMMENT 'JENIS : 1 UNTUK OBJECK RUANGAN, JENIS : 2 UNTUK OBJECK PERORANGAN',
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `JENIS` (`JENIS`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.ido
CREATE TABLE IF NOT EXISTS `ido` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KUNJUNGAN` char(19) NOT NULL COMMENT 'NOMOR KUNJUNGAN',
  `ID_OPERASI` int(11) NOT NULL DEFAULT '0' COMMENT 'ID OPERASI',
  `NORM` int(11) NOT NULL DEFAULT '0',
  `RUANGAN` char(20) NOT NULL,
  `TANGGAL_OPERASI` date NOT NULL,
  `USIA` varchar(50) NOT NULL,
  `SUHU` varchar(50) NOT NULL,
  `JENIS_OPERASI` int(1) NOT NULL COMMENT '1.B, 2.BT, 3.K',
  `ASA_SCORE` int(1) NOT NULL COMMENT '1 - 5',
  `KLP` int(1) NOT NULL COMMENT 'KLASIFIKASI LUKA OPERASI = 1.ILS, 2.ILD, ILO',
  `OPERASI` int(1) NOT NULL COMMENT '1.CITO, 2,EFEKTIF',
  `STATUS_IDO` int(1) NOT NULL COMMENT '1.IDO, 2.BKN IDO',
  `ANTIBIOTIK` int(1) NOT NULL COMMENT 'REF JENIS = 128',
  `HASIL_KULTUR` text NOT NULL,
  `OLEH` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `NORM` (`NORM`),
  KEY `OPERASI` (`ID_OPERASI`),
  KEY `STATUS` (`STATUS`),
  KEY `KUNJUNGAN` (`KUNJUNGAN`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.isk
CREATE TABLE IF NOT EXISTS `isk` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOPEN` char(10) NOT NULL DEFAULT '',
  `KUNJUNGAN` char(19) NOT NULL DEFAULT '' COMMENT 'KUNJUNGAN',
  `NORM` int(11) NOT NULL DEFAULT '0',
  `USIA` varchar(50) NOT NULL,
  `RUANGAN` char(20) NOT NULL,
  `ANTIBIOTIK` int(1) NOT NULL,
  `TANGGAL_PAKAI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_BERHENTI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_PASANG` date NOT NULL,
  `TANGGAL_LEPAS` date NOT NULL,
  `LAMA_HARI` int(11) NOT NULL DEFAULT '0',
  `INFEKSI_LAIN` text NOT NULL,
  `JENIS_PEMERIKSAAN_KULTUR` int(11) NOT NULL COMMENT 'REFERENSI 152',
  `HASIL_KULTUR` text NOT NULL,
  `LABORATORIUM` text NOT NULL,
  `PEMAKAIAN_ALAT` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'PEMASANGAN URINE KATETER (UC ) ',
  `STATUS_HAIS` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'STATUS KEJADIAN HAIS (ISK)',
  `TANGGAL_KEJADIAN` date DEFAULT NULL,
  `OLEH` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `NORM` (`NORM`),
  KEY `OPERASI` (`KUNJUNGAN`),
  KEY `STATUS` (`STATUS`),
  KEY `NOPEN` (`NOPEN`),
  KEY `RUANGAN` (`RUANGAN`),
  KEY `TANGGAL_PASANG` (`TANGGAL_PASANG`),
  KEY `TANGGAL_LEPAS` (`TANGGAL_LEPAS`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.kagiatan_alasan
CREATE TABLE IF NOT EXISTS `kagiatan_alasan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KEGIATAN` int(11) NOT NULL COMMENT 'ID TABLE KEGIATAN',
  `ALASAN` int(11) NOT NULL COMMENT 'ID ALASAN',
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `KEGIATAN` (`KEGIATAN`)
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.kegiatan
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESKRIPSI` text NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=latin1 COMMENT='KEGIATAN PENILAIAN';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.kegiatan_group
CREATE TABLE IF NOT EXISTS `kegiatan_group` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KEGIATAN` int(11) NOT NULL COMMENT 'ID KEGIATAN',
  `GROUP` int(11) NOT NULL COMMENT 'GROUP EVALUASI',
  `KELOMPOK` int(11) NOT NULL COMMENT 'KELOMPOK',
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `IDX_INDIKATOR_UNIT_M_UNIT` (`GROUP`),
  KEY `IDX_INDIKATOR_UNIT_INDIKATOR` (`KEGIATAN`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.kelompok
CREATE TABLE IF NOT EXISTS `kelompok` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KET` text NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.limbah_infeksius
CREATE TABLE IF NOT EXISTS `limbah_infeksius` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RUANGAN` char(50) NOT NULL,
  `NAMA_TERLAPOR` varchar(100) NOT NULL,
  `TANGGAL_KEJADIAN` datetime NOT NULL,
  `KATEGORI` int(1) NOT NULL COMMENT '1 : LIMBAH CAIR, 2: LIMBAH PADAT',
  `HASIL_LAB` text NOT NULL,
  `KETERANGAN` text NOT NULL,
  `OLEH` int(11) NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `RUANGAN` (`RUANGAN`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='menyimpan pelaporan tentang tertapar limbah infeksius';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.penilaian_kegiatan
CREATE TABLE IF NOT EXISTS `penilaian_kegiatan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TANGGAL_BUAT` datetime DEFAULT NULL,
  `RUANGAN` char(10) NOT NULL,
  `GROUP` int(11) NOT NULL,
  `USER` int(11) DEFAULT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS_PENILAIAN` (`STATUS`),
  KEY `IDX_PENILAIAN_INDIKATOR_MONITORING` (`GROUP`),
  KEY `IDX_PENILAIAN_INDIKATOR_UNIT` (`RUANGAN`),
  KEY `IDX_PENILAIAN_INDIKATOR_USER` (`USER`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='PENILAIAN KEGIATAN';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.penilaian_kegiatan_detail
CREATE TABLE IF NOT EXISTS `penilaian_kegiatan_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PENILAIAN` int(11) NOT NULL COMMENT 'PENILAIAN KEGIATAN',
  `KEGIATAN` int(11) NOT NULL COMMENT 'KEGIATAN GROUP',
  `CHECK` int(1) NOT NULL DEFAULT '0',
  `ALASAN` int(1) NOT NULL DEFAULT '0' COMMENT 'KEGIATA ALASAN',
  `KET` text NOT NULL,
  `RTL` text NOT NULL,
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `IDX_DETAIL_PENILAIAN_INDIKATOR_STATUS` (`STATUS`),
  KEY `IDX_DETAIL_PENILAIAN_INDIKATOR_P_INDIKATOR` (`KEGIATAN`),
  KEY `PENILAIAN` (`PENILAIAN`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.penilaian_kegiatan_harian
CREATE TABLE IF NOT EXISTS `penilaian_kegiatan_harian` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RUANGAN` char(10) NOT NULL,
  `GROUP` int(11) NOT NULL,
  `BULAN` int(2) NOT NULL,
  `TAHUN` year(4) NOT NULL,
  `USER` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `RUANGAN` (`RUANGAN`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.penilaian_kegiatan_harian_detail
CREATE TABLE IF NOT EXISTS `penilaian_kegiatan_harian_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PK_HARIAN` int(11) NOT NULL COMMENT 'PENILAIAN_KEGIATAN_HARIAN',
  `KEGIATAN` int(11) NOT NULL,
  `MINGGU` int(1) NOT NULL COMMENT '1, 2, 3, 4, 5',
  `HARIAN` int(1) NOT NULL COMMENT '1, 2, 3, 4, 5 ...',
  `CHECK` int(1) NOT NULL DEFAULT '0',
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `PK_HARIAN` (`PK_HARIAN`),
  KEY `KEGIATAN` (`KEGIATAN`),
  KEY `STATUS` (`STATUS`),
  KEY `MINGGU` (`MINGGU`),
  KEY `HARIAN` (`HARIAN`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.tertusuk_jarum
CREATE TABLE IF NOT EXISTS `tertusuk_jarum` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RUANGAN` char(50) NOT NULL,
  `NAMA_TERLAPOR` varchar(100) NOT NULL,
  `TANGGAL_KEJADIAN` datetime NOT NULL,
  `KATEGORI` int(1) NOT NULL COMMENT '1 : BEKAS, 2: BARU',
  `HASIL_LAB` text NOT NULL,
  `KETERANGAN` text NOT NULL,
  `OLEH` int(11) NOT NULL,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `RUANGAN` (`RUANGAN`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='menyimpan pelaporan tentang tertusuk jarum';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.tindak_lanjut
CREATE TABLE IF NOT EXISTS `tindak_lanjut` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GROUP` int(11) DEFAULT NULL,
  `BULAN` int(2) DEFAULT NULL,
  `TAHUN` year(4) DEFAULT NULL,
  `PLAN` text NOT NULL,
  `DO` text NOT NULL,
  `CHECK` text NOT NULL,
  `ACTION` text NOT NULL,
  `OLEH` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `GROUP` (`GROUP`),
  KEY `BULAN` (`BULAN`),
  KEY `TAHUN` (`TAHUN`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.vap
CREATE TABLE IF NOT EXISTS `vap` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOPEN` char(10) NOT NULL DEFAULT '',
  `KUNJUNGAN` char(19) NOT NULL DEFAULT '' COMMENT 'KUNJUNGAN',
  `NORM` int(11) NOT NULL DEFAULT '0',
  `USIA` varchar(50) NOT NULL,
  `RUANGAN` char(20) NOT NULL,
  `ANTIBIOTIK` int(1) NOT NULL,
  `TANGGAL_PAKAI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_BERHENTI_ANTIBIOTIK` date NOT NULL,
  `TANGGAL_PASANG` date NOT NULL,
  `TANGGAL_LEPAS` date NOT NULL,
  `LAMA_HARI` int(11) NOT NULL DEFAULT '0',
  `INFEKSI_LAIN` text NOT NULL,
  `JENIS_PEMERIKSAAN_KULTUR` int(11) NOT NULL COMMENT 'REFERENSI 152',
  `HASIL_KULTUR` text NOT NULL,
  `LABORATORIUM` text NOT NULL,
  `PEMAKAIAN_ALAT` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'PEMASANGAN VENTILATOR',
  `STATUS_HAIS` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'STATUS KEJADIAN HAIS (VAP)',
  `TANGGAL_KEJADIAN` date DEFAULT NULL,
  `OLEH` int(11) NOT NULL,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `NORM` (`NORM`),
  KEY `OPERASI` (`KUNJUNGAN`),
  KEY `STATUS` (`STATUS`),
  KEY `NOPEN` (`NOPEN`),
  KEY `RUANGAN` (`RUANGAN`),
  KEY `TANGGAL_PASANG` (`TANGGAL_PASANG`),
  KEY `TANGGAL_LEPAS` (`TANGGAL_LEPAS`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.xample_detail
CREATE TABLE IF NOT EXISTS `xample_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `XAMPLE` int(11) NOT NULL COMMENT 'table xample',
  `KEGIATAN` int(11) NOT NULL DEFAULT '0' COMMENT 'TABLE GROUP KEGIATAN',
  `CHECK` int(11) NOT NULL DEFAULT '0',
  `ALASAN` int(11) NOT NULL COMMENT 'table KEGIATA ALASAN',
  `STATUS` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `XAMPLE` (`XAMPLE`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=latin1 COMMENT='data nilai untuk xample audit pegawai ';

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table ppi.xample_individu
CREATE TABLE IF NOT EXISTS `xample_individu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PERIODE` int(11) NOT NULL COMMENT 'table periode penilaian',
  `NIP` varchar(20) NOT NULL,
  `NAMA` text NOT NULL,
  `OLEH` int(11) NOT NULL,
  `STATUS` int(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `NIP` (`NIP`),
  KEY `PERIODE` (`PERIODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk procedure ppi.cetakAuditGrafikIndividu
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetakAuditGrafikIndividu`(
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
		IF(ni.TOTAL_HASIL IS NOT NULL, ni.TOTAL_HASIL, 0) TOTAL_HASIL,
		tl.`GROUP`, 
		tl.PLAN, 
		tl.`DO`, 
		tl.`CHECK`, 
		tl.`ACTION`
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
			GROUP BY xd.XAMPLE
		) tt ON tt.PERIODE = ai.ID
		WHERE ai.STATUS = 2 AND ai.PROFESI IN (',PPROFESI,') AND ai.`GROUP` = ',PGROUP,' AND ai.TANGGAL_BUAT BETWEEN "',@awal,'" AND "',@akhir,'"
		GROUP BY ai.ID
	) ni ON ni.ID_AI = ai.ID
	,(
		SELECT tl.ID, tl.`GROUP`, tl.PLAN, tl.`DO`, tl.`CHECK`, tl.`ACTION`
		FROM ppi.tindak_lanjut tl 
		WHERE tl.`GROUP` = ',PGROUP,' AND tl.BULAN = ',@bln,' AND tl.TAHUN = ',@thn,'  AND tl.`STATUS` = 2
	) tl 
	WHERE ref.JENIS = 36 AND ref.ID IN (',PPROFESI,') ');
	
	-- select @sqlText;
	 PREPARE stmt FROM @sqlText;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.cetakAuditGrafikRuangan
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetakAuditGrafikRuangan`(
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
		IF(pkj.HASIL IS NULL, 0, pkj.HASIL) NILAI,
		tl.`GROUP`, 
		tl.PLAN, 
		tl.`DO`, 
		tl.`CHECK`, 
		tl.`ACTION`
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
		WHERE pk.STATUS = 2 AND pk.RUANGAN IN(',PRUANGAN,') AND pk.GROUP = ',PGROUP,' AND pk.TANGGAL_BUAT BETWEEN "',@awal,'" AND "',@akhir,'" GROUP BY pk.ID) pkj ON pkj.PENILAIAN_LEFT = pk.ID
	,(
		SELECT tl.ID, tl.`GROUP`, tl.PLAN, tl.`DO`, tl.`CHECK`, tl.`ACTION`
		FROM ppi.tindak_lanjut tl 
		WHERE tl.`GROUP` = ',PGROUP,' AND tl.BULAN = ',@bln,' AND tl.TAHUN = ',@thn,'  AND tl.`STATUS` = 2
	) tl 
	WHERE rgn.ID IN(',PRUANGAN,')');
	-- select @sqlText;
	 PREPARE stmt FROM @sqlText;
	 EXECUTE stmt;
	 DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.cetakAuditIndividu
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetakAuditIndividu`(IN `PID` INT
)
BEGIN
	SELECT
		inst.NAMA,
		ref.DESKRIPSI PROFESI_DESK,
		ge.DESKRIPSI GROUP_DESK,
		klp.KET KELOMPOK_DESK,
		k.DESKRIPSI KEGIATAN_DESK,
		xi.NIP,
		IF(ai.PEGAWAI = 1, CONCAT (xi.NIP,' - ', master.getNamaLengkapPegawai(xi.NIP)), xi.NAMA) NAMA_PEG,
		xd.`CHECK`,
		xi2.JUM_PEG,
		nil.HASIL,
		xi2.TOTAL_HASIL
	FROM
		ppi.xample_detail xd
	LEFT JOIN ppi.xample_individu xi ON xi.ID = xd.XAMPLE
	LEFT JOIN (
		SELECT 
			xi.PERIODE, 
			COUNT(*) JUM_PEG ,
			TRUNCATE(SUM(dt.HASIL) / COUNT(*),2) TOTAL_HASIL
		FROM ppi.xample_individu xi
		LEFT JOIN (
			SELECT
				xi.PERIODE,
				xi.ID,
				TRUNCATE((SUM(IF(xd.`CHECK` = 1,1,0))/ COUNT(*))*100, 2) HASIL
			FROM ppi.xample_detail xd
			LEFT JOIN ppi.xample_individu xi ON xi.ID = xd.XAMPLE
			LEFT JOIN ppi.audit_individu ai ON ai.ID = xi.PERIODE
			WHERE ai.ID = PID 
			GROUP BY xd.XAMPLE
		) dt ON dt.ID = xi.ID
		WHERE xi.PERIODE = PID
	) xi2 ON xi2.PERIODE = xi.PERIODE
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
			WHERE ai.ID = PID 
			GROUP BY xd.XAMPLE
	) nil ON nil.XAMPLE = xi.ID
	LEFT JOIN ppi.audit_individu ai ON ai.ID = xi.PERIODE
	LEFT JOIN ppi.kegiatan_group kg ON kg.ID = xd.KEGIATAN
	LEFT JOIN ppi.kegiatan k ON k.ID = kg.KEGIATAN
	LEFT JOIN ppi.group_evaluasi ge ON ge.ID = ai.`GROUP`
	LEFT JOIN ppi.kelompok klp ON klp.ID = kg.KELOMPOK
	lEFT JOIN master.referensi ref ON ref.ID = ai.PROFESI and ref.JENIS = 36
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE 
		ai.ID = PID;
		
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.cetakKegiatanHarian
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetakKegiatanHarian`(
	IN `PID` INT,
	IN `PMINGGU` INT
)
BEGIN
	SELECT
		inst.NAMA NAMAINSTANSI,
		rg.DESKRIPSI RUANGAN,
		DATE_FORMAT(NOW(), '%d-%M-%Y') TANGGAL_VIEW,
		SUM(IF(khd.MINGGU = 1 && khd.HARIAN = 1, khd.`CHECK`, 0)) `1_1`,
		SUM(IF(khd.MINGGU = 1 && khd.HARIAN = 2, khd.`CHECK`, 0)) `1_2`,
		SUM(IF(khd.MINGGU = 1 && khd.HARIAN = 3, khd.`CHECK`, 0)) `1_3`,
		SUM(IF(khd.MINGGU = 1 && khd.HARIAN = 4, khd.`CHECK`, 0)) `1_4`,
		SUM(IF(khd.MINGGU = 1 && khd.HARIAN = 5, khd.`CHECK`, 0)) `1_5`,
		SUM(IF(khd.MINGGU = 2 && khd.HARIAN = 1, khd.`CHECK`, 0)) `2_1`,
		SUM(IF(khd.MINGGU = 2 && khd.HARIAN = 2, khd.`CHECK`, 0)) `2_2`,
		SUM(IF(khd.MINGGU = 2 && khd.HARIAN = 3, khd.`CHECK`, 0)) `2_3`,
		SUM(IF(khd.MINGGU = 2 && khd.HARIAN = 4, khd.`CHECK`, 0)) `2_4`,
		SUM(IF(khd.MINGGU = 2 && khd.HARIAN = 5, khd.`CHECK`, 0)) `2_5`,
		SUM(IF(khd.MINGGU = 3 && khd.HARIAN = 1, khd.`CHECK`, 0)) `3_1`,
		SUM(IF(khd.MINGGU = 3 && khd.HARIAN = 2, khd.`CHECK`, 0)) `3_2`,
		SUM(IF(khd.MINGGU = 3 && khd.HARIAN = 3, khd.`CHECK`, 0)) `3_3`,
		SUM(IF(khd.MINGGU = 3 && khd.HARIAN = 4, khd.`CHECK`, 0)) `3_4`,
		SUM(IF(khd.MINGGU = 3 && khd.HARIAN = 5, khd.`CHECK`, 0)) `3_5`,
		SUM(IF(khd.MINGGU = 4 && khd.HARIAN = 1, khd.`CHECK`, 0)) `4_1`,
		SUM(IF(khd.MINGGU = 4 && khd.HARIAN = 2, khd.`CHECK`, 0)) `4_2`,
		SUM(IF(khd.MINGGU = 4 && khd.HARIAN = 3, khd.`CHECK`, 0)) `4_3`,
		SUM(IF(khd.MINGGU = 4 && khd.HARIAN = 4, khd.`CHECK`, 0)) `4_4`,
		SUM(IF(khd.MINGGU = 4 && khd.HARIAN = 5, khd.`CHECK`, 0)) `4_5`,
		SUM(IF(khd.MINGGU = 5 && khd.HARIAN = 1, khd.`CHECK`, 0)) `5_1`,
		SUM(IF(khd.MINGGU = 5 && khd.HARIAN = 2, khd.`CHECK`, 0)) `5_2`,
		SUM(IF(khd.MINGGU = 5 && khd.HARIAN = 3, khd.`CHECK`, 0)) `5_3`,
		SUM(IF(khd.MINGGU = 5 && khd.HARIAN = 4, khd.`CHECK`, 0)) `5_4`,
		SUM(IF(khd.MINGGU = 5 && khd.HARIAN = 5, khd.`CHECK`, 0)) `5_5`,
		TRUNCATE(((khd2.TOTAL / 25) * 100), 2) TOTAL,
		-- khd2.TOTAL,
		(SELECT COUNT(*) FROM ppi.kegiatan_group b  WHERE b.GROUP= kg.`GROUP` AND b.`STATUS` = 1) TOTAL_ALL,
		k.DESKRIPSI `KEGIATAN`, ge.DESKRIPSI `GROUP` FROM 
		ppi.penilaian_kegiatan_harian_detail khd
	LEFT JOIN (SELECT khd.KEGIATAN, SUM(khd.`CHECK`) TOTAL
		FROM 
			ppi.penilaian_kegiatan_harian_detail khd
			WHERE khd.PK_HARIAN = PID
		GROUP BY khd.KEGIATAN) khd2 ON khd2.KEGIATAN = khd.KEGIATAN
	LEFT JOIN ppi.penilaian_kegiatan_harian kh ON kh.ID = khd.PK_HARIAN
	LEFT JOIN ppi.kegiatan_group kg ON kg.ID = khd.KEGIATAN
	LEFT JOIN ppi.kegiatan k ON k.ID = kg.KEGIATAN
	LEFT JOIN ppi.group_evaluasi ge ON ge.ID = kg.GROUP
	LEFT JOIN master.ruangan rg ON rg.ID = kh.RUANGAN
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE khd.PK_HARIAN = PID
	GROUP BY khd.KEGIATAN;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.cetakMonitoringDanEvaluasi
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetakMonitoringDanEvaluasi`(
	IN `PID` INT,
	IN `PJENIS` INT
)
BEGIN
	DECLARE VJENIS int;
	
	SET VJENIS = PJENIS;
	IF VJENIS = 2 THEN
	SET @sqlText = CONCAT('SELECT
			inst.NAMA NAMAINSTANSI,
			ge.DESKRIPSI JUDUL,
			ruang.DESKRIPSI RUANGAN,
			klp.KET DESK_KELOMPOK,
			klp.ID ID_KELOMPOK,
			DATE_FORMAT(pk.TANGGAL_BUAT, "%H:%i:%s %d/%m/%Y") TANGGAL_BUAT,
			k.DESKRIPSI NAMA_KEGIATAN,
			IF(pkd.`CHECK`= 1, 1, 0) YA,
			IF(pkd.`CHECK`= 0, 1, 0) TIDAK,
			pkj.JUMLAH_YA,
			pkj.TOTAL,
			pkj.HASIL,
			pkd.KET,
			pkd.RTL
		FROM
			ppi.penilaian_kegiatan_detail pkd 
		LEFT JOIN ppi.penilaian_kegiatan pk ON pk.ID = pkd.PENILAIAN
		LEFT JOIN master.ruangan ruang ON ruang.ID = pk.RUANGAN
		LEFT JOIN ppi.group_evaluasi ge ON ge.ID = pk.GROUP
		LEFT JOIN ppi.kegiatan_group kg ON kg.ID = pkd.KEGIATAN
		LEFT JOIN ppi.kelompok klp ON klp.ID = kg.KELOMPOK
		LEFT JOIN ppi.kegiatan k ON k.ID = kg.KEGIATAN
		LEFT JOIN (
			SELECT
				pkd.PENILAIAN PENILAIAN_LEFT,
				SUM(IF(pkd.`CHECK` = 1,1,0)) JUMLAH_YA,
				COUNT(*) TOTAL,
				TRUNCATE((SUM(IF(pkd.`CHECK` = 1,1,0))/COUNT(*))*100, 2) HASIL
			FROM
				ppi.penilaian_kegiatan_detail pkd
			WHERE pkd.PENILAIAN = "',PID,'"
		) pkj ON pkj.PENILAIAN_LEFT = pk.ID
		,(SELECT mp.NAMA 
				FROM aplikasi.instansi ai
					, master.ppk mp
				WHERE ai.PPK=mp.ID) inst
		WHERE pkd.PENILAIAN = "',PID,'"');
	ELSE
		SET @sqlText = CONCAT('SELECT
			inst.NAMA NAMAINSTANSI,
			ge.DESKRIPSI JUDUL,
			ruang.DESKRIPSI RUANGAN,
			DATE_FORMAT(pk.TANGGAL_BUAT, "%d/%M/%Y") TANGGAL_BUAT,
			k.DESKRIPSI NAMA_KEGIATAN,
			IF(pkd.`CHECK`= 1, 1, 0) YA,
			IF(pkd.`CHECK`= 0, 1, 0) TIDAK,
			pkj.JUMLAH_YA,
			pkj.TOTAL,
			pkj.HASIL,
			pkd.KET,
			pkd.RTL
		FROM
			ppi.penilaian_kegiatan_detail pkd 
		LEFT JOIN ppi.penilaian_kegiatan pk ON pk.ID = pkd.PENILAIAN
		LEFT JOIN master.ruangan ruang ON ruang.ID = pk.RUANGAN
		LEFT JOIN ppi.group_evaluasi ge ON ge.ID = pk.GROUP
		LEFT JOIN ppi.kegiatan_group kg ON kg.ID = pkd.KEGIATAN
		LEFT JOIN ppi.kegiatan k ON k.ID = kg.KEGIATAN
		LEFT JOIN (
			SELECT
				pkd.PENILAIAN PENILAIAN_LEFT,
				SUM(IF(pkd.`CHECK` = 1,1,0)) JUMLAH_YA,
				COUNT(*) TOTAL,
				TRUNCATE((SUM(IF(pkd.`CHECK` = 1,1,0))/COUNT(*))*100, 2) HASIL
			FROM
				ppi.penilaian_kegiatan_detail pkd
			WHERE pkd.PENILAIAN = "',PID,'"
		) pkj ON pkj.PENILAIAN_LEFT = pk.ID
		,(SELECT mp.NAMA 
				FROM aplikasi.instansi ai
					, master.ppk mp
				WHERE ai.PPK=mp.ID) inst
		WHERE pkd.PENILAIAN = "',PID,'"');
	END IF;
	
	PREPARE stmt FROM @sqlText;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.lapAuditCuciTangan
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `lapAuditCuciTangan`(
	IN `PID` INT
)
BEGIN
	SELECT 
		inst.NAMA NAMAINSTANSI,
		ref.DESKRIPSI PROFESI,
		ctd.ID_PEG,
		master.getNamaLengkapPegawai(ctd.ID_PEG) NAMA_PEGAWAI,
		ctd.MOMENT_1,
		ctd.MOMENT_2,
		ctd.MOMENT_3,
		ctd.MOMENT_4,
		ctd.MOMENT_5,
		ctd.KETERANGAN,
		TRUNCATE((((ctd.MOMENT_1+ctd.MOMENT_2+ctd.MOMENT_3+ctd.MOMENT_4+ctd.MOMENT_5) / 5) * 100), 0) SKOR,
		TRUNCATE(((ctdt.SKOR1 / ctdt.TOTAL) * 100), 0) SKOR_1,
		TRUNCATE(((ctdt.SKOR2 / ctdt.TOTAL) * 100), 0) SKOR_2,
		TRUNCATE(((ctdt.SKOR3 / ctdt.TOTAL) * 100), 0) SKOR_3,
		TRUNCATE(((ctdt.SKOR4 / ctdt.TOTAL) * 100), 0) SKOR_4,
		TRUNCATE(((ctdt.SKOR5 / ctdt.TOTAL) * 100), 0) SKOR_5,
		ctdt.SKOR_TOTAL
	FROM ppi.cuci_tangan_detail ctd
	LEFT JOIN (
		SELECT
			ctd.CUCI_TANGAN,
			SUM(ctd.MOMENT_1) SKOR1,
			SUM(ctd.MOMENT_2) SKOR2,
			SUM(ctd.MOMENT_3) SKOR3,
			SUM(ctd.MOMENT_4) SKOR4,
			SUM(ctd.MOMENT_5) SKOR5,
			COUNT(*) TOTAL,
			TRUNCATE(SUM((((ctd.MOMENT_1+ctd.MOMENT_2+ctd.MOMENT_3+ctd.MOMENT_4+ctd.MOMENT_5) / 5) * 100))/COUNT(*), 2) SKOR_TOTAL
		FROM ppi.cuci_tangan_detail ctd
		WHERE ctd.CUCI_TANGAN = PID
	) ctdt ON ctdt.CUCI_TANGAN = ctd.CUCI_TANGAN
	LEFT JOIN ppi.cuci_tangan ct ON ct.ID = ctd.CUCI_TANGAN
	LEFT JOIN master.referensi ref ON ref.ID = ct.PROFESI AND ref.JENIS = 36
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE ctd.CUCI_TANGAN = PID;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.laporanLimbahInfeksius
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `laporanLimbahInfeksius`(
	IN `PAWAL` VARCHAR(50),
	IN `PAKHIR` VARCHAR(50)
)
BEGIN
	DECLARE V1, V2 DATETIME;
	
	SET V1 = CONCAT(PAWAL, ' 00:00:00');
	SET V2 = CONCAT(PAKHIR, ' 23:59:59');
	
	SELECT
		r.DESKRIPSI,
		li.NAMA_TERLAPOR,
		DATE_FORMAT(li.TANGGAL_KEJADIAN, '%h:%i:%s %d-%m-%Y') TGL_KEJADIAN,
		IF(li.KATEGORI = 1, 1,0) CAIR,
		IF(li.KATEGORI = 2, 1,0) PADAT,
		li.HASIL_LAB,
		li.KETERANGAN
	FROM
		ppi.limbah_infeksius li
	LEFT JOIN master.ruangan r ON r.ID = li.RUANGAN
	WHERE li.TANGGAL_KEJADIAN BETWEEN V1 AND V2;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.laporanSurveilansHarianIDO
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `laporanSurveilansHarianIDO`(
	IN `PPERIODE` VARCHAR(10)
)
BEGIN
	DECLARE V1, V2 DATE;
	
	SET V1 = CONCAT(PPERIODE, '-01 00:00:00');
	SET V2 = CONCAT(PPERIODE, '-31 23:59:59');
	
	SELECT 
		inst.NAMA NAMAINSTANSI,
		PPERIODE AS PERIODE,
		rg.DESKRIPSI RUANGAN,
		DATE_FORMAT(ido.TANGGAL_OPERASI,'%d-%m-%Y') TANGGAL_OPERASI,
		ido.NORM,
		master.getNamaLengkap(ido.NORM) NAMA_PASIEN,
		ido.USIA,
		ido.SUHU,
		ref.DESKRIPSI JENIS_KELAMIN,
		op.PRA_BEDAH,
		ido.JENIS_OPERASI,
		ido.ASA_SCORE,
		ido.KLP,
		op.DURASI,
		ido.OPERASI,
		IF(ido.STATUS_IDO = 1, 'âˆš','') IDO,
		ido.ANTIBIOTIK,
		ido.HASIL_KULTUR,
		atb.DESKRIPSI DESK_ANTIBIOTIK
	FROM ppi.ido  ido
	LEFT JOIN medicalrecord.operasi op ON op.ID = ido.ID_OPERASI
	LEFT JOIN master.ruangan rg ON rg.ID = ido.RUANGAN
	LEFT JOIN master.pasien psn ON psn.NORM = ido.NORM
	LEFT JOIN master.referensi ref ON ref.ID = psn.JENIS_KELAMIN AND ref.JENIS = '2'
	LEFT JOIN master.referensi atb ON atb.ID = ido.ANTIBIOTIK AND atb.JENIS = '128'
	,(SELECT mp.NAMA 
			FROM aplikasi.instansi ai
				, master.ppk mp
			WHERE ai.PPK=mp.ID) inst
	WHERE ido.TANGGAL_OPERASI BETWEEN V1 AND V2
	;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.laporanTertusukJarum
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `laporanTertusukJarum`(
	IN `PAWAL` VARCHAR(50),
	IN `PAKHIR` VARCHAR(50)
)
BEGIN
	DECLARE V1, V2 DATETIME;
	
	SET V1 = CONCAT(PAWAL, ' 00:00:00');
	SET V2 = CONCAT(PAKHIR, ' 23:59:59');
	
	SELECT
		r.DESKRIPSI,
		tj.NAMA_TERLAPOR,
		DATE_FORMAT(tj.TANGGAL_KEJADIAN, '%h:%i:%s %d-%m-%Y') TGL_KEJADIAN,
		IF(tj.KATEGORI = 1, 1,0) BEKAS,
		IF(tj.KATEGORI = 2, 1,0) BARU,
		tj.HASIL_LAB,
		tj.KETERANGAN
	FROM
		ppi.tertusuk_jarum tj
	LEFT JOIN master.ruangan r ON r.ID = tj.RUANGAN
	WHERE tj.TANGGAL_KEJADIAN BETWEEN V1 AND V2;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.rekapGrafikHaisJumlahPasien
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `rekapGrafikHaisJumlahPasien`(
	IN `TGLAWAL` DATE,
	IN `TGLAKHIR` DATE,
	IN `PJENIS` INT,
	IN `PJENISGRAFIK` INT
)
BEGIN	
	DECLARE VTITLE VARCHAR(150);
	DECLARE VALIAS CHAR(3);
	SET VTITLE = 'JUMLAH PASIEN DENGAN PEMASANGAN VAP (VENTILATOR AQUIRED PNEUMONIA)';
	SET VALIAS = 'vap';
	
	IF PJENISGRAFIK = 2 THEN 
		SET VTITLE = 'JUMLAH PASIEN DENGAN PEMASANGAN IVL (INTRA VENA LINE)';
		SET VALIAS = 'fle'; 
	END IF;
	IF PJENISGRAFIK = 3 THEN 
		SET VTITLE = 'JUMLAH PASIEN DENGAN PEMASANGAN UC ( URINE KATETER )'; 
		SET VALIAS = 'isk';
	END IF;
	
	SET @sqltext = CONCAT('SELECT 
		inst.NAMA NAMA_INSTANSI, "',VTITLE,'" TITLE, jk.DESKRIPSI DESK_JKUNJUNGA, "',TGLAWAL,'" P_AWAL, "',TGLAKHIR,'" P_AKHIR,	rug.ID, rug.DESKRIPSI DESK_RUANGAN,
		IF(',VALIAS,'.RUANGAN IS NULL, 0, ',VALIAS,'.T_ALAT) T_ALAT
	FROM `master`.ruangan rug
	LEFT JOIN (
		SELECT
			isk.RUANGAN,
			SUM(isk.PEMAKAIAN_ALAT) T_ALAT,
			SUM(isk.LAMA_HARI) T_LAMA,
			SUM(isk.STATUS_HAIS) T_STATUS
		FROM ppi.isk isk
		LEFT JOIN `master`.ruangan r ON r.ID = isk.RUANGAN
		WHERE isk.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY isk.RUANGAN
	) isk ON isk.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			vap.RUANGAN,
			SUM(vap.PEMAKAIAN_ALAT) T_ALAT,
			SUM(vap.LAMA_HARI) T_LAMA,
			SUM(vap.STATUS_HAIS) T_STATUS
		FROM ppi.vap vap
		LEFT JOIN `master`.ruangan r ON r.ID = vap.RUANGAN
		WHERE vap.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY vap.RUANGAN
	) vap ON vap.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			fle.RUANGAN,
			SUM(fle.PEMAKAIAN_ALAT) T_ALAT,
			SUM(fle.LAMA_HARI) T_LAMA,
			SUM(fle.STATUS_HAIS) T_STATUS
		FROM ppi.flebitis fle
		LEFT JOIN `master`.ruangan r ON r.ID = fle.RUANGAN
		WHERE fle.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY fle.RUANGAN
	) fle ON fle.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			ido.RUANGAN,
			COUNT(*) T_ALAT,
			0 T_LAMA,
			SUM(ido.STATUS_IDO) T_STATUS
		FROM ppi.ido ido
		LEFT JOIN `master`.ruangan r ON r.ID = ido.RUANGAN
		WHERE ido.TANGGAL_OPERASI BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY ido.RUANGAN
	) ido ON ido.RUANGAN = rug.ID
	LEFT JOIN `master`.referensi jk ON jk.ID = rug.JENIS_KUNJUNGAN AND jk.JENIS = 15
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE rug.JENIS_KUNJUNGAN = ',PJENIS,' 
	AND rug.`STATUS` = 1 
	AND rug.JENIS = 5 ORDER BY rug.ID ASC');
	-- SELECT @sqltext;
	PREPARE stmt FROM @sqltext;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.rekapGrafikHaisPersentase
DELIMITER //
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `rekapGrafikHaisPersentase`(
	IN `TGLAWAL` DATE,
	IN `TGLAKHIR` DATE,
	IN `PJENIS` INT,
	IN `PJENISGRAFIK` INT
)
BEGIN	
	DECLARE VTITLE VARCHAR(150);
	DECLARE VALIAS CHAR(3);
	SET VTITLE = 'KEJADIAN HAIs VAP (VENTILATOR AQUIRED PNEUMONIA)';
	SET VALIAS = 'vap';
	
	IF PJENISGRAFIK = 2 THEN 
		SET VTITLE = 'KEJADIAN HAIs FLEBITIS';
		SET VALIAS = 'fle'; 
	END IF;
	IF PJENISGRAFIK = 3 THEN 
		SET VTITLE = 'KEJADIAN HAIs ISK (INFEKSI SALURAN KEMIH)'; 
		SET VALIAS = 'isk';
	END IF;	
	IF PJENISGRAFIK = 4 THEN 
		SET VTITLE = ' KEJADIAN HAIs IDO (INFEKSI DAERAH OPERASI)'; 
		SET VALIAS = 'ido';
	END IF;
	
	SET @sqltext = CONCAT('SELECT 
		inst.NAMA NAMA_INSTANSI, "',VTITLE,'" TITLE, jk.DESKRIPSI DESK_JKUNJUNGA, "',TGLAWAL,'" P_AWAL, "',TGLAKHIR,'" P_AKHIR,	rug.ID, rug.DESKRIPSI DESK_RUANGAN,
		TRUNCATE(IF(',VALIAS,'.RUANGAN IS NULL, 0, (',VALIAS,'.T_STATUS / ',VALIAS,'.T_ALAT)* 100 ), 2) PERSEN
	FROM `master`.ruangan rug
	LEFT JOIN (
		SELECT
			isk.RUANGAN,
			SUM(isk.PEMAKAIAN_ALAT) T_ALAT,
			SUM(isk.LAMA_HARI) T_LAMA,
			SUM(isk.STATUS_HAIS) T_STATUS
		FROM ppi.isk isk
		LEFT JOIN `master`.ruangan r ON r.ID = isk.RUANGAN
		WHERE isk.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY isk.RUANGAN
	) isk ON isk.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			vap.RUANGAN,
			SUM(vap.PEMAKAIAN_ALAT) T_ALAT,
			SUM(vap.LAMA_HARI) T_LAMA,
			SUM(vap.STATUS_HAIS) T_STATUS
		FROM ppi.vap vap
		LEFT JOIN `master`.ruangan r ON r.ID = vap.RUANGAN
		WHERE vap.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY vap.RUANGAN
	) vap ON vap.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			fle.RUANGAN,
			SUM(fle.PEMAKAIAN_ALAT) T_ALAT,
			SUM(fle.LAMA_HARI) T_LAMA,
			SUM(fle.STATUS_HAIS) T_STATUS
		FROM ppi.flebitis fle
		LEFT JOIN `master`.ruangan r ON r.ID = fle.RUANGAN
		WHERE fle.TANGGAL_PASANG BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY fle.RUANGAN
	) fle ON fle.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			ido.RUANGAN,
			COUNT(*) T_ALAT,
			0 T_LAMA,
			SUM(ido.STATUS_IDO) T_STATUS
		FROM ppi.ido ido
		LEFT JOIN `master`.ruangan r ON r.ID = ido.RUANGAN
		WHERE ido.TANGGAL_OPERASI BETWEEN "',TGLAWAL,'" AND "',TGLAKHIR,'" 
		AND r.JENIS_KUNJUNGAN = "',PJENIS,'" GROUP BY ido.RUANGAN
	) ido ON ido.RUANGAN = rug.ID
	LEFT JOIN `master`.referensi jk ON jk.ID = rug.JENIS_KUNJUNGAN AND jk.JENIS = 15
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE rug.JENIS_KUNJUNGAN = ',PJENIS,' 
	AND rug.`STATUS` = 1 
	AND rug.JENIS = 5 ORDER BY rug.ID ASC');
	-- SELECT @sqltext;
	PREPARE stmt FROM @sqltext;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.rekapLaporanHaisRuangan
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `rekapLaporanHaisRuangan`(
	IN `PAWAL` DATE,
	IN `PAKHIR` DATE,
	IN `PRUANGAN` CHAR(10)
)
BEGIN
	SELECT	
		 ruang.DESKRIPSI DESK_RUANGAN, PAWAL P_AWAL, PAKHIR P_AKHIR,
		 rekap.KUNJUNGAN,
		 rekap.NORM,
		 `master`.getNamaLengkap(rekap.NORM) NAMA,
		 jk.DESKRIPSI JENIS_KELAMIN,
		 rekap.USIA,
		 `master`.getNamaLengkapPegawai(dkt.NIP) DPJP_PASIEN,
		 IF(ido.ID IS NULL,0,1) TOP,
		 CONCAT(
		 	IF(fle.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('IVL : ', fle.TANGGAL_PASANG, '<br>')),
		 	IF(vap.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('ETT : ', vap.TANGGAL_PASANG, '<br>')),
		 	IF(isk.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('UC : ', isk.TANGGAL_PASANG, '<br>'))
		 ) TANGGAL_PASANG,
		 CONCAT(
		 	IF(fle.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('IVL : ', fle.TANGGAL_LEPAS, '<br>')),
		 	IF(vap.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('ETT : ', vap.TANGGAL_LEPAS, '<br>')),
		 	IF(isk.ID IS NULL OR fle.PEMAKAIAN_ALAT = 0,'',CONCAT('UC : ', isk.TANGGAL_LEPAS, '<br>'))
		 ) TANGGAL_LEPAS,
		 IF(fle.ID IS NULL, 0, fle.LAMA_HARI) LAMA_FLEBITIS,
		 IF(vap.ID IS NULL, 0, vap.LAMA_HARI) LAMA_VAP,
		 IF(isk.ID IS NULL, 0, isk.LAMA_HARI) LAMA_ISK,
		 IF(fle.ID IS NULL, 0, fle.PEMAKAIAN_ALAT) ALAT_FLEBITIS,
		 IF(vap.ID IS NULL, 0, vap.PEMAKAIAN_ALAT) ALAT_VAP,
		 IF(isk.ID IS NULL, 0, isk.PEMAKAIAN_ALAT) ALAT_ISK,
		 IF(fle.ID IS NULL, 0, fle.STATUS_HAIS) HAIS_FLEBITIS,
		 IF(vap.ID IS NULL, 0, vap.STATUS_HAIS) HAIS_VAP,
		 IF(isk.ID IS NULL, 0, isk.STATUS_HAIS) HAIS_ISK,
		 IF(ido.ID IS NULL, 0, ido.STATUS_IDO) HAIS_IDO,
		 CONCAT(
		 	IF(fle.ID IS NULL,'',CONCAT('Flebitis : ', fle.INFEKSI_LAIN, '<br>')),
		 	IF(vap.ID IS NULL,'',CONCAT('VAP : ', vap.INFEKSI_LAIN, '<br>')),
		 	IF(isk.ID IS NULL,'',CONCAT('ISK : ', isk.INFEKSI_LAIN, '<br>'))
		 ) INFEKSI_LAIN,
		 CONCAT(
		 	IF(fle.ID IS NULL,'',CONCAT('Flebitis : ', fle.HASIL_KULTUR, '<br>')),
		 	IF(vap.ID IS NULL,'',CONCAT('VAP : ', vap.HASIL_KULTUR, '<br>')),
		 	IF(isk.ID IS NULL,'',CONCAT('ISK : ', isk.HASIL_KULTUR, '<br>')),
		 	IF(ido.ID IS NULL,'',CONCAT('ISK : ', ido.HASIL_KULTUR, '<br>'))
		 ) HASIL_KULTUR,
		 CONCAT(
		 	IF(fle.ID IS NULL,'',CONCAT('Flebitis : ', fle.LABORATORIUM, '<br>')),
		 	IF(vap.ID IS NULL,'',CONCAT('VAP : ', vap.LABORATORIUM, '<br>')),
		 	IF(isk.ID IS NULL,'',CONCAT('ISK : ', isk.LABORATORIUM, '<br>'))
		 ) LABORATORIUM,
		 CONCAT(
		 	IF(fle.ID IS NULL OR anfle.ID IS NULL,'',CONCAT('Flebitis : ', anfle.DESKRIPSI, '<br>')),
		 	IF(vap.ID IS NULL OR anvap.ID IS NULL,'',CONCAT('VAP : ', anvap.DESKRIPSI, '<br>')),
		 	IF(isk.ID IS NULL OR ansik.ID IS NULL,'',CONCAT('ISK : ', ansik.DESKRIPSI, '<br>')),
		 	IF(ido.ID IS NULL OR anido.ID IS NULL,'',CONCAT('IDO : ', anido.DESKRIPSI, '<br>'))
		 ) DESK_ANTIBIOTIK
	FROM(
			-- DATA FLEBITIS
			SELECT f.ID, f.KUNJUNGAN, f.NORM, f.USIA	FROM ppi.flebitis f
			WHERE f.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
			AND f.RUANGAN = PRUANGAN
			UNION ALL
			-- DATA VAP
			SELECT f.ID, f.KUNJUNGAN, f.NORM, f.USIA	FROM ppi.vap f 
			WHERE f.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
			AND f.RUANGAN = PRUANGAN
			UNION ALL
			-- DATA ISK
			SELECT f.ID, f.KUNJUNGAN, f.NORM, f.USIA  FROM ppi.isk f 
			WHERE f.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
			AND f.RUANGAN = PRUANGAN
			-- DATA IDO
			UNION ALL
			SELECT i.ID, i.KUNJUNGAN, i.NORM, i.USIA  FROM ppi.ido i
			WHERE i.TANGGAL_OPERASI BETWEEN PAWAL AND PAKHIR 
			AND i.RUANGAN = PRUANGAN
	) rekap
	LEFT JOIN ppi.flebitis fle ON fle.KUNJUNGAN = rekap.KUNJUNGAN
	LEFT JOIN ppi.vap vap ON vap.KUNJUNGAN = rekap.KUNJUNGAN
	LEFT JOIN ppi.isk isk ON isk.KUNJUNGAN = rekap.KUNJUNGAN
	LEFT JOIN ppi.ido ido ON ido.KUNJUNGAN = rekap.KUNJUNGAN
	LEFT JOIN `master`.pasien psn ON psn.NORM = rekap.NORM
	LEFT JOIN `master`.referensi jk ON jk.ID = psn.JENIS_KELAMIN AND jk.JENIS = 2
	LEFT JOIN `master`.referensi anfle ON anfle.ID = fle.ANTIBIOTIK AND anfle.JENIS = 128
	LEFT JOIN `master`.referensi anvap ON anvap.ID = vap.ANTIBIOTIK AND anvap.JENIS = 128
	LEFT JOIN `master`.referensi ansik ON ansik.ID = isk.ANTIBIOTIK AND ansik.JENIS = 128
	LEFT JOIN `master`.referensi anido ON anido.ID = ido.ANTIBIOTIK AND anido.JENIS = 128
	LEFT JOIN pendaftaran.kunjungan kjgn ON kjgn.NOMOR = rekap.KUNJUNGAN
	LEFT JOIN pendaftaran.tujuan_pasien tp ON tp.NOPEN = kjgn.NOPEN
	LEFT JOIN `master`.dokter dkt ON dkt.ID = tp.DOKTER
	,(SELECT 
		* 
	FROM `master`.ruangan r
	WHERE r.ID = PRUANGAN) ruang	 
	GROUP BY rekap.KUNJUNGAN;
END//
DELIMITER ;

-- membuang struktur untuk procedure ppi.rekapLaporanTotalPerJenisRuangan
DELIMITER //
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `rekapLaporanTotalPerJenisRuangan`(
	IN `PAWAL` DATE,
	IN `PAKHIR` DATE,
	IN `PJENIS` INT
)
BEGIN
	SELECT 
		inst.NAMA NAMA_INSTANSI, 
		jk.DESKRIPSI DESK_JKUNJUNGA,
		PAWAL P_AWAL, PAKHIR P_AKHIR,
		rug.ID,
		rug.DESKRIPSI DESK_RUANGAN,
		IF(isk.RUANGAN IS NULL, 0, isk.T_ALAT) T_ALAT_ISK,
		IF(isk.RUANGAN IS NULL, 0, isk.T_LAMA) T_LAMA_ISK,
		IF(isk.RUANGAN IS NULL, 0, isk.T_STATUS) T_STATUS_ISK,
		TRUNCATE(IF(isk.RUANGAN IS NULL, 0, (isk.T_STATUS / isk.T_ALAT)* 100 ), 2) PERSEN_ISK,
		IF(vap.RUANGAN IS NULL, 0, vap.T_ALAT) T_ALAT_VAP,
		IF(vap.RUANGAN IS NULL, 0, vap.T_LAMA) T_LAMA_VAP,
		IF(vap.RUANGAN IS NULL, 0, vap.T_STATUS) T_STATUS_VAP,
		TRUNCATE(IF(vap.RUANGAN IS NULL, 0, (vap.T_STATUS / vap.T_ALAT)* 100 ), 2) PERSEN_VAP,
		IF(fle.RUANGAN IS NULL, 0, fle.T_ALAT) T_ALAT_FLE,
		IF(fle.RUANGAN IS NULL, 0, fle.T_LAMA) T_LAMA_FLE,
		IF(fle.RUANGAN IS NULL, 0, fle.T_STATUS) T_STATUS_FLE,
		TRUNCATE(IF(fle.RUANGAN IS NULL, 0, (fle.T_STATUS / fle.T_ALAT)* 100 ), 2) PERSEN_FLE,
		IF(ido.RUANGAN IS NULL, 0, ido.T_IDO) T_IDO,
		IF(ido.RUANGAN IS NULL, 0, ido.T_STATUS_IDO) T_STATUS_IDO,
		TRUNCATE(IF(ido.RUANGAN IS NULL, 0, (ido.T_IDO / ido.T_STATUS_IDO)* 100 ), 2) PERSEN_IDO
	FROM `master`.ruangan rug
	LEFT JOIN (
		SELECT
			isk.RUANGAN,
			SUM(isk.PEMAKAIAN_ALAT) T_ALAT,
			SUM(isk.LAMA_HARI) T_LAMA,
			SUM(isk.STATUS_HAIS) T_STATUS
		FROM ppi.isk isk
		LEFT JOIN `master`.ruangan r ON r.ID = isk.RUANGAN
		WHERE isk.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
		AND r.JENIS_KUNJUNGAN = PJENIS GROUP BY isk.RUANGAN
	) isk ON isk.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			vap.RUANGAN,
			SUM(vap.PEMAKAIAN_ALAT) T_ALAT,
			SUM(vap.LAMA_HARI) T_LAMA,
			SUM(vap.STATUS_HAIS) T_STATUS
		FROM ppi.vap vap
		LEFT JOIN `master`.ruangan r ON r.ID = vap.RUANGAN
		WHERE vap.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
		AND r.JENIS_KUNJUNGAN = PJENIS GROUP BY vap.RUANGAN
	) vap ON vap.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			fle.RUANGAN,
			SUM(fle.PEMAKAIAN_ALAT) T_ALAT,
			SUM(fle.LAMA_HARI) T_LAMA,
			SUM(fle.STATUS_HAIS) T_STATUS
		FROM ppi.flebitis fle
		LEFT JOIN `master`.ruangan r ON r.ID = fle.RUANGAN
		WHERE fle.TANGGAL_PASANG BETWEEN PAWAL AND PAKHIR 
		AND r.JENIS_KUNJUNGAN = PJENIS GROUP BY fle.RUANGAN
	) fle ON fle.RUANGAN = rug.ID
	LEFT JOIN (
		SELECT
			ido.RUANGAN,
			COUNT(*) T_IDO,
			SUM(ido.STATUS_IDO) T_STATUS_IDO
		FROM ppi.ido ido
		LEFT JOIN `master`.ruangan r ON r.ID = ido.RUANGAN
		WHERE ido.TANGGAL_OPERASI BETWEEN PAWAL AND PAKHIR 
		AND r.JENIS_KUNJUNGAN = PJENIS GROUP BY ido.RUANGAN
	) ido ON ido.RUANGAN = rug.ID
	LEFT JOIN `master`.referensi jk ON jk.ID = rug.JENIS_KUNJUNGAN AND jk.JENIS = 15
	,(SELECT mp.NAMA 
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE rug.JENIS_KUNJUNGAN = PJENIS 
	AND rug.`STATUS` = 1 
	AND rug.JENIS = 5 ORDER BY rug.ID ASC;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
