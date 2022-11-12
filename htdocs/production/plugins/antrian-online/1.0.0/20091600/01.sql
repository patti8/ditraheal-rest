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

-- Dumping database structure for regonline
CREATE DATABASE IF NOT EXISTS `regonline` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `regonline`;


-- Dumping structure for table regonline.cara_bayar
CREATE TABLE IF NOT EXISTS `cara_bayar` (
  `ID` int(11) NOT NULL,
  `DESKRIPSI` char(50) DEFAULT NULL,
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.cara_bayar: ~4 rows (approximately)
/*!40000 ALTER TABLE `cara_bayar` DISABLE KEYS */;
INSERT INTO `cara_bayar` (`ID`, `DESKRIPSI`, `STATUS`) VALUES
	(1, 'Tanpa Asuransi / Umum', 1),
	(2, 'BPJS / JKN', 1),
	(3, 'INHEALTH', 1),
	(4, 'BPJS KETENAGAKERJAAN', 1);
/*!40000 ALTER TABLE `cara_bayar` ENABLE KEYS */;


-- Dumping structure for table regonline.error_reservasi
CREATE TABLE IF NOT EXISTS `error_reservasi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TANGGAL` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DATA` text NOT NULL,
  `BATAS_TANGGAL` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.error_reservasi: ~0 rows (approximately)
/*!40000 ALTER TABLE `error_reservasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `error_reservasi` ENABLE KEYS */;


-- Dumping structure for table regonline.idreservasi
CREATE TABLE IF NOT EXISTS `idreservasi` (
  `TANGGAL` date NOT NULL,
  `NOMOR` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`TANGGAL`,`NOMOR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.idreservasi: 0 rows
/*!40000 ALTER TABLE `idreservasi` DISABLE KEYS */;
INSERT INTO `idreservasi` (`TANGGAL`, `NOMOR`) VALUES
	('2020-09-15', 1),
	('2020-09-16', 1);
/*!40000 ALTER TABLE `idreservasi` ENABLE KEYS */;


-- Dumping structure for table regonline.jenispendaftaran
CREATE TABLE IF NOT EXISTS `jenispendaftaran` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `DESKRIPSI` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.jenispendaftaran: ~2 rows (approximately)
/*!40000 ALTER TABLE `jenispendaftaran` DISABLE KEYS */;
INSERT INTO `jenispendaftaran` (`ID`, `DESKRIPSI`) VALUES
	(1, 'Pasien Lama'),
	(2, 'Pasien Baru');
/*!40000 ALTER TABLE `jenispendaftaran` ENABLE KEYS */;


-- Dumping structure for table regonline.libur_nasional
CREATE TABLE IF NOT EXISTS `libur_nasional` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TANGGAL_LIBUR` date DEFAULT NULL,
  `KETERANGAN` varchar(50) DEFAULT NULL,
  `TANGGAL` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OLEH` int(11) DEFAULT NULL,
  `STATUS` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TANGGAL_LIBUR` (`TANGGAL_LIBUR`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.libur_nasional: ~0 rows (approximately)
/*!40000 ALTER TABLE `libur_nasional` DISABLE KEYS */;
/*!40000 ALTER TABLE `libur_nasional` ENABLE KEYS */;


-- Dumping structure for table regonline.link_ruangan
CREATE TABLE IF NOT EXISTS `link_ruangan` (
  `ID` int(8) NOT NULL,
  `DESKRIPSI` varchar(45) NOT NULL,
  `ANTRIAN` char(10) NOT NULL DEFAULT 'A',
  `STATUS` int(1) NOT NULL DEFAULT '1',
  `DEFAULT` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `idunit` (`ANTRIAN`),
  KEY `nm_sub_unit` (`DESKRIPSI`),
  KEY `status` (`STATUS`),
  KEY `DEFAULT` (`DEFAULT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table regonline.loket
CREATE TABLE IF NOT EXISTS `loket` (
  `ID` int(11) NOT NULL,
  `DESKRIPSI` char(150) NOT NULL,
  `STATUS` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.loket: ~5 rows (approximately)
/*!40000 ALTER TABLE `loket` DISABLE KEYS */;
INSERT INTO `loket` (`ID`, `DESKRIPSI`, `STATUS`) VALUES
	(1, 'Loket 1', 1),
	(2, 'Loket 2', 1),
	(3, 'Loket 3', 1),
	(4, 'Loket 4', 1),
	(5, 'Loket 5', 1),
	(6, 'Loket 6', 1);
/*!40000 ALTER TABLE `loket` ENABLE KEYS */;


-- Dumping structure for table regonline.panggil_antrian
CREATE TABLE IF NOT EXISTS `panggil_antrian` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOKET` smallint(6) DEFAULT NULL,
  `NOMOR` int(11) DEFAULT NULL,
  `POS` char(10) DEFAULT 'A',
  `TANGGAL` date DEFAULT NULL,
  `CARA_BAYAR` mediumint(9) DEFAULT '0',
  `STATUS` smallint(6) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `LOKET` (`LOKET`),
  KEY `NOMOR` (`NOMOR`),
  KEY `POS` (`POS`),
  KEY `TANGGAL` (`TANGGAL`),
  KEY `STATUS` (`STATUS`),
  KEY `CARA_BAYAR` (`CARA_BAYAR`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.panggil_antrian: ~0 rows (approximately)
/*!40000 ALTER TABLE `panggil_antrian` DISABLE KEYS */;
/*!40000 ALTER TABLE `panggil_antrian` ENABLE KEYS */;


-- Dumping structure for table regonline.pengaturan
CREATE TABLE IF NOT EXISTS `pengaturan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LIMIT_DAFTAR` int(11) NOT NULL COMMENT 'Jml Max Pendaftar',
  `DURASI` int(11) NOT NULL DEFAULT '10' COMMENT 'Durasi Waktu Proses Per Pasien (Menit)',
  `MULAI` time NOT NULL DEFAULT '07:30:00',
  `BATAS_JAM_AMBIL` time NOT NULL DEFAULT '12:00:00',
  `POS_ANTRIAN` char(10) NOT NULL DEFAULT '1',
  `STATUS` int(11) NOT NULL DEFAULT '1',
  `UPDATE_TIME` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `POS_ANTRIAN` (`POS_ANTRIAN`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.pengaturan: ~0 rows (approximately)
/*!40000 ALTER TABLE `pengaturan` DISABLE KEYS */;
INSERT INTO `pengaturan` (`ID`, `LIMIT_DAFTAR`, `DURASI`, `MULAI`, `BATAS_JAM_AMBIL`, `POS_ANTRIAN`, `STATUS`, `UPDATE_TIME`) VALUES
	(7, 50, 30, '08:00:00', '12:00:00', 'A', 1, '2020-05-08 11:55:40');
/*!40000 ALTER TABLE `pengaturan` ENABLE KEYS */;


-- Dumping structure for table regonline.poli_bpjs
CREATE TABLE IF NOT EXISTS `poli_bpjs` (
  `KDPOLI` char(10) NOT NULL,
  `NMPOLI` char(100) DEFAULT NULL,
  `ANTRIAN` char(10) DEFAULT '1',
  `STATUS` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`KDPOLI`),
  KEY `ANTRIAN` (`ANTRIAN`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.poli_bpjs: ~158 rows (approximately)
/*!40000 ALTER TABLE `poli_bpjs` DISABLE KEYS */;
INSERT INTO `poli_bpjs` (`KDPOLI`, `NMPOLI`, `ANTRIAN`, `STATUS`) VALUES
	('004', 'ALERGI-IMMUNOLOGI KLINIK', 'A', 1),
	('005', 'GASTROENTEROLOGI-HEPATOLOGI', 'A', 1),
	('006', 'GERIATRI', 'A', 1),
	('007', 'GINJAL-HIPERTENSI', 'A', 1),
	('008', 'HEMATOLOGI - ONKOLOGI MEDIK', 'A', 1),
	('009', 'HEPATOLOGI', 'A', 1),
	('010', 'ENDOKRIN-METABOLIK-DIABETES', 'A', 1),
	('011', 'PSIKOSOMATIK', 'A', 1),
	('012', 'PULMONOLOGI', 'A', 1),
	('013', 'REUMATOLOGI', 'A', 1),
	('014', 'PENYAKIT TROPIK-INFEKSI', 'A', 1),
	('015', 'KARDIOVASKULAR', 'A', 1),
	('017', 'BEDAH ONKOLOGI', 'A', 1),
	('018', 'BEDAH DIGESTIF', 'A', 1),
	('020', 'FETOMATERNAL', 'A', 1),
	('021', 'ONKOLOGI GINEKOLOGI', 'A', 1),
	('022', 'UROGINEKOLOGI REKONTRUSKI', 'A', 1),
	('023', 'OBSTETRI GINEKOLOGI SOSIAL', 'A', 1),
	('024', 'ENDOKRINOLOGI', 'A', 1),
	('025', 'FERTILITAS', 'A', 1),
	('027', 'ANAK ALERGI IMUNOLOGI', 'A', 1),
	('028', 'ANAK ENDOKRINOLOGI', 'A', 1),
	('029', 'ANAK GASTRO-HEPATOLOGI', 'A', 1),
	('030', 'ANAK HEMATOLOGI ONKOLOGI', 'A', 1),
	('031', 'ANAK INFEKSI & PEDIATRI TROPIS', 'A', 1),
	('032', 'ANAK KARDIOLOGI', 'A', 1),
	('033', 'ANAK NEFROLOGI', 'A', 1),
	('034', 'ANAK NEUROLOGI', 'A', 1),
	('036', 'PEDIATRI GAWAT DARURAT', 'A', 1),
	('037', 'PENCITRAAN ANAK', 'A', 1),
	('038', 'PERINATOLOGI', 'A', 1),
	('039', 'RESPIROLOGI ANAK', 'A', 1),
	('040', 'TUMBUH KEMBANG PED. SOSIAL', 'A', 1),
	('041', 'KESEHATAN REMAJA', 'A', 1),
	('043', 'INTENSIVE CARE/ICU', 'A', 1),
	('044', 'ANESTESI KARDIOVASKULER', 'A', 1),
	('045', 'MANAJEMEN NYERI', 'A', 1),
	('047', 'NEUROANESTESI', 'A', 1),
	('048', 'ANESTESI PEDIATRI', 'A', 1),
	('049', 'ANESTESI OBSTETRI', 'A', 1),
	('051', 'Radiologi Thoraks', 'A', 1),
	('052', 'Radiologi Muskuloskeletal', 'A', 1),
	('053', 'Radiologi Tr Urinariusgenitalia', 'A', 1),
	('054', 'Radiologi Tr Digestivus', 'A', 1),
	('055', 'Neuroradiologi', 'A', 1),
	('056', 'Pencitraan Payudara/womans imaging', 'A', 1),
	('057', 'Radiologi intervensional kardiovaskular', 'A', 1),
	('058', 'Pencitraan kepala leher', 'A', 1),
	('059', 'Radiologi pediatrik', 'A', 1),
	('060', 'Kedokteran nuklir', 'A', 1),
	('067', 'OTOLOGI', 'A', 1),
	('068', 'NEUROTOLOGI', 'A', 1),
	('069', 'RINOLOGI', 'A', 1),
	('070', 'LARINGO-FARINGOLOGI', 'A', 1),
	('071', 'ONKOLOGI KEPALA LEHER', 'A', 1),
	('072', 'PLASTIK REKONSTRUKSI', 'A', 1),
	('073', 'BRONKOESOFAGOLOGI', 'A', 1),
	('074', 'ALERGI IMUNOLOGI', 'A', 1),
	('075', 'THT KOMUNITAS', 'A', 1),
	('078', 'NEUROTRAUMA', 'A', 1),
	('079', 'NEUROINFEKSI', 'A', 1),
	('080', 'NEUROINFEKSI DAN IMUNOLOGI', 'A', 1),
	('081', 'EPILEPSI', 'A', 1),
	('082', 'NEUROFISIOLOGI KLINIS', 'A', 1),
	('083', 'NEUROMUSKULAR, SARAF PERIFER', 'A', 1),
	('086', 'NEURO-INTENSIF', 'A', 1),
	('095', 'INFEKSI', 'A', 1),
	('096', 'ONKOLOGI TORAKS', 'A', 1),
	('097', 'ASMA DAN PPOK', 'A', 1),
	('099', 'FAAL PARU KLINIK', 'A', 1),
	('100', 'PARU KERJA DAN LINGKUNGAN', 'A', 1),
	('101', 'IMUNOLOGIK KLINIK', 'A', 1),
	('104', 'BURN (LUKA BAKAR)', 'A', 1),
	('105', 'MICRO SURGERY', 'A', 1),
	('106', 'KRANIOFASIAL (KKF)', 'A', 1),
	('107', 'HAND (BEDAH TANGAN)', 'A', 1),
	('108', 'GENITALIA EKSTERNA', 'A', 1),
	('109', 'REKONTRUKSI DAN ESTETIK', 'A', 1),
	('132', 'Bedah Vaskuler', 'A', 1),
	('133', 'Kornea dan Bedah Refraktif', 'A', 1),
	('134', 'Infeksi dan Immunologi', 'A', 1),
	('135', 'Vitreo - Retina', 'A', 1),
	('136', 'Strabismus', 'A', 1),
	('137', 'Neuro Oftalmologi', 'A', 1),
	('138', 'Glaukoma', 'A', 1),
	('139', 'Pediatrik Oftalmologi', 'A', 1),
	('140', 'Refraksi', 'A', 1),
	('141', 'Rekonstruksi', 'A', 1),
	('142', 'Onkologi Mata', 'A', 1),
	('143', 'Dermatologi Infeksi Tropik', 'A', 1),
	('144', 'Dermatologi Pediatrik', 'A', 1),
	('146', 'Infeksi Menular Seksual', 'A', 1),
	('147', 'Dermato - Alergo - Imunologi', 'A', 1),
	('148', 'Dermatologi Geriatrik', 'A', 1),
	('149', 'Tumor dan Bedah Kulit', 'A', 1),
	('150', 'Dermatopatologi', 'A', 1),
	('151', 'Trauma dan Rekonstruksi', 'A', 1),
	('152', 'Tulang Belakang', 'A', 1),
	('153', 'Tumor Tulang', 'A', 1),
	('154', 'Pediatrik', 'A', 1),
	('156', 'Hand and Microsurgery', 'A', 1),
	('157', 'Rekonstruksi Dewasa/Hip and Knee', 'A', 1),
	('158', 'Bio Orthopedic', 'A', 1),
	('160', 'Neuropsikiatri dan Psikometri', 'A', 1),
	('162', 'Psikiatri Anak dan Remaja', 'A', 1),
	('163', 'Psikiatri Geriatri', 'A', 1),
	('165', 'Consultation-Liaison Psychiatri', 'A', 1),
	('168', 'Radioterapi', 'A', 1),
	('169', 'Radiologi Onkologi', 'A', 1),
	('170', 'Bedah Kepala Leher', 'A', 1),
	('ANA', 'ANAK', 'A', 1),
	('AND', 'ANDROLOGI', 'A', 1),
	('ANT', 'ANASTESI', 'A', 1),
	('BDA', 'BEDAH ANAK', 'A', 1),
	('BDM', 'GIGI BEDAH MULUT', 'A', 1),
	('BDP', 'BEDAH PLASTIK', 'A', 1),
	('BED', 'BEDAH', 'A', 1),
	('BSY', 'BEDAH SARAF', 'A', 1),
	('BTK', 'BTKV (BEDAH THORAX KARDIOVASKU', 'A', 1),
	('FMK', 'FARMAKOLOGI KLINIK', 'A', 1),
	('GIG', 'GIGI', 'A', 1),
	('GIZ', 'GIZI KLINIK', 'A', 1),
	('GND', 'GIGI ENDODONSI', 'A', 1),
	('GOR', 'GIGI ORTHODONTI', 'A', 1),
	('GPR', 'GIGI PERIODONTI', 'A', 1),
	('GRD', 'GIGI RADIOLOGI', 'A', 1),
	('HDL', 'HEMODIALISA', 'A', 1),
	('HIV', 'B20', 'A', 1),
	('IGD', 'INSTALASI GAWAT DARURAT', 'A', 1),
	('INT', 'PENYAKIT DALAM', 'A', 1),
	('IRM', 'REHABILITASI MEDIK', 'A', 1),
	('JAN', 'JANTUNG DAN PEMBULUH DARAH', 'A', 1),
	('JIW', 'JIWA', 'A', 1),
	('KDK', 'KEDOKTERAN KELAUTAN', 'A', 1),
	('KDN', 'KEDOKTERAN NUKLIR', 'A', 1),
	('KDO', 'KEDOKTERAN OKUPASI', 'A', 1),
	('KDP', 'KEDOKTERAN PENERBANGAN', 'A', 1),
	('KEM', 'SARANA KEMOTERAPI', 'A', 1),
	('KLT', 'KULIT KELAMIN', 'A', 1),
	('KON', 'GIGI PEDODONTIS', 'A', 1),
	('KOR', 'KEDOKTERAAN OLAHRAGA', 'A', 1),
	('MAT', 'MATA', 'A', 1),
	('MKB', 'MIKROBIOLOGI KLINIK', 'A', 1),
	('OBG', 'OBGYN', 'A', 1),
	('ORT', 'ORTHOPEDI', 'A', 1),
	('PAA', 'PATOLOGI ANATOMI', 'A', 1),
	('PAK', 'PATOLOGI KLINIK', 'A', 1),
	('PAR', 'PARU', 'A', 1),
	('PNM', 'GIGI PENYAKIT MULUT', 'A', 1),
	('PRM', 'PARASITOLOGI UMUM', 'A', 1),
	('PTD', 'GIGI PROSTHODONTI', 'A', 1),
	('RAT', 'SARANA RADIOTERAPI', 'A', 1),
	('RDN', 'RADIOLOGI ONKOLOGI', 'A', 1),
	('RDO', 'RADIOLOGI', 'A', 1),
	('RDT', 'RADIOTERAPI', 'A', 1),
	('SAR', 'SARAF', 'A', 1),
	('THT', 'THT-KL', 'A', 1),
	('URO', 'UROLOGI', 'A', 1);
/*!40000 ALTER TABLE `poli_bpjs` ENABLE KEYS */;


-- Dumping structure for table regonline.pos_antrian
CREATE TABLE IF NOT EXISTS `pos_antrian` (
  `NOMOR` char(10) NOT NULL,
  `DESKRIPSI` char(50) DEFAULT NULL,
  `STATUS` tinyint(4) DEFAULT '1',
  `PANGGIL` int(3) DEFAULT NULL,
  `INPUT_TIME` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`NOMOR`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.pos_antrian: ~3 rows (approximately)
/*!40000 ALTER TABLE `pos_antrian` DISABLE KEYS */;
INSERT INTO `pos_antrian` (`NOMOR`, `DESKRIPSI`, `STATUS`, `PANGGIL`, `INPUT_TIME`) VALUES
	('A', 'Loket Antrian A', 1, 1, NULL),
	('B', 'Loket Antrian B', 1, 1, NULL),
	('C', 'Loket Antrian C', 1, 1, NULL);
/*!40000 ALTER TABLE `pos_antrian` ENABLE KEYS */;


-- Dumping structure for table regonline.reservasi
CREATE TABLE IF NOT EXISTS `reservasi` (
  `ID` char(10) NOT NULL,
  `TANGGALKUNJUNGAN` date DEFAULT NULL COMMENT 'field -> tanggalperiksa BPJS',
  `TANGGAL_REF` datetime DEFAULT NULL,
  `NORM` int(8) DEFAULT '0' COMMENT 'field -> nomorrm BPJS',
  `NIK` char(16) DEFAULT '0' COMMENT 'field -> nik BPJS',
  `NAMA` varchar(50) DEFAULT NULL,
  `TEMPAT_LAHIR` char(100) DEFAULT NULL,
  `TANGGAL_LAHIR` date DEFAULT NULL,
  `POLI` int(11) DEFAULT NULL,
  `POLI_BPJS` char(10) DEFAULT NULL,
  `DOKTER` char(50) DEFAULT NULL,
  `CARABAYAR` char(50) DEFAULT '1',
  `NO_KARTU_BPJS` char(15) DEFAULT NULL COMMENT 'fiel -> nomorkartu BPJS',
  `CONTACT` char(50) DEFAULT NULL COMMENT 'field -> notelp BPJS',
  `TGL_DAFTAR` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `NO` int(3) DEFAULT NULL,
  `JAM` char(50) DEFAULT NULL,
  `POS_ANTRIAN` char(10) DEFAULT NULL,
  `JENIS` int(1) DEFAULT '1' COMMENT '1 : PasienLama 2: PasienBaru',
  `NO_REF_BPJS` char(75) DEFAULT '0' COMMENT 'field -> nomorreferensi BPJS',
  `JENIS_REF_BPJS` int(1) DEFAULT '0' COMMENT 'field -> jenisreferensi BPJS',
  `JENIS_REQUEST_BPJS` int(1) DEFAULT '0' COMMENT 'field -> jenisrequest BPJS',
  `POLI_EKSEKUTIF_BPJS` int(1) DEFAULT '0' COMMENT 'field -> polieksekutif BPJS',
  `JENIS_APLIKASI` int(1) DEFAULT '1' COMMENT '0=Web, 1=MobileApp, 2=Mobile JKN,5=Onsite',
  `STATUS` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `STATUS` (`STATUS`),
  KEY `JENIS_APLIKASI` (`JENIS_APLIKASI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table regonline.reservasi: ~0 rows (approximately)
/*!40000 ALTER TABLE `reservasi` DISABLE KEYS */;
INSERT INTO `reservasi` (`ID`, `TANGGALKUNJUNGAN`, `TANGGAL_REF`, `NORM`, `NIK`, `NAMA`, `TEMPAT_LAHIR`, `TANGGAL_LAHIR`, `POLI`, `POLI_BPJS`, `DOKTER`, `CARABAYAR`, `NO_KARTU_BPJS`, `CONTACT`, `TGL_DAFTAR`, `NO`, `JAM`, `POS_ANTRIAN`, `JENIS`, `NO_REF_BPJS`, `JENIS_REF_BPJS`, `JENIS_REQUEST_BPJS`, `POLI_EKSEKUTIF_BPJS`, `JENIS_APLIKASI`, `STATUS`) VALUES
	('2009160001', '2020-09-16', '2020-09-15 09:49:19', 0, '3506141308950002', 'ZAKY KHAIRAN AZWAR', '-', '2005-04-05', NULL, '005', NULL, '2', '0000000000123', '081123456778', '2020-09-15 15:49:19', 1, '08:30', 'A', 2, '0001R0040116A000001', 1, 2, 0, 2, 1);
/*!40000 ALTER TABLE `reservasi` ENABLE KEYS */;


-- Dumping structure for procedure regonline.CetakKarcisAntrian
DROP PROCEDURE IF EXISTS `CetakKarcisAntrian`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `CetakKarcisAntrian`(IN `PNOMOR` CHAR(20))
BEGIN
	SELECT 
		DATE_FORMAT(r.TANGGALKUNJUNGAN,'%d-%m-%Y') TANGGAL, CONCAT(r.POS_ANTRIAN, IF(r.CARABAYAR = 2,2,1),'-',LPAD(r.NO,3,'0')) NOMOR, p.DESKRIPSI, inst.NAMA NAMAISTANSI, inst.ALAMAT
	FROM reservasi r
	LEFT JOIN link_ruangan p ON p.ID = r.POLI
	, (SELECT mp.NAMA, ai.PPK, mp.ALAMAT
		FROM aplikasi.instansi ai
			, master.ppk mp
		WHERE ai.PPK=mp.ID) inst
	WHERE r.ID = PNOMOR;
END//
DELIMITER ;


-- Dumping structure for procedure regonline.createAntrianLoket
DROP PROCEDURE IF EXISTS `createAntrianLoket`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `createAntrianLoket`()
BEGIN
	INSERT INTO panggil_antrian (LOKET,NOMOR,POS,TANGGAL,STATUS)
	SELECT l.ID LOKET, 0 NOMOR, p.NOMOR POS, DATE(NOW()), 0 STATUS FROM pos_antrian p
	LEFT JOIN loket l ON l.`STATUS` = 1;
END//
DELIMITER ;


-- Dumping structure for procedure regonline.sinkronisasiCaraBayar
DROP PROCEDURE IF EXISTS `sinkronisasiCaraBayar`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sinkronisasiCaraBayar`()
BEGIN
	DECLARE VNAMA VARCHAR(2000);
	DECLARE VID,VIDREF INT;
	DECLARE VSTATUS TINYINT;
	
	DECLARE DATA_NOT_FOUND TINYINT DEFAULT FALSE;
	DECLARE CR_CARA_BAYAR CURSOR FOR
		SELECT
			ID,DESKRIPSI,STATUS FROM master.referensi WHERE JENIS = 10;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET DATA_NOT_FOUND = TRUE;
			
	OPEN CR_CARA_BAYAR;
	EOF: LOOP
		FETCH CR_CARA_BAYAR INTO VID, VNAMA, VSTATUS;
		
		IF DATA_NOT_FOUND THEN
			UPDATE temp.temp SET ID = 0 WHERE ID = 0;
			LEAVE EOF;
		END IF;
		
		SELECT VIDREF FROM regonline.cara_bayar WHERE ID = VID;
		
		IF FOUND_ROWS() > 0 THEN
			UPDATE regonline.cara_bayar SET DESKRIPSI = VNAMA, STATUS = VSTATUS WHERE ID = VID;
		ELSE
			INSERT INTO regonline.cara_bayar (ID,DESKRIPSI,STATUS) VALUES (VID,VNAMA,VSTATUS);
		END IF;
	END LOOP;
	CLOSE CR_CARA_BAYAR;
END//
DELIMITER ;


-- Dumping structure for procedure regonline.sinkronisasiLinkRuangan
DROP PROCEDURE IF EXISTS `sinkronisasiLinkRuangan`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sinkronisasiLinkRuangan`()
BEGIN
	DECLARE VNAMA VARCHAR(2000);
	DECLARE VID,VIDREF INT;
	DECLARE VSTATUS TINYINT;
	
	DECLARE DATA_NOT_FOUND TINYINT DEFAULT FALSE;
	DECLARE CR_LINK_RUANGAN CURSOR FOR
		SELECT
			ID,DESKRIPSI,STATUS FROM master.ruangan WHERE JENIS = 5 AND ID LIKE '1010101%';
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET DATA_NOT_FOUND = TRUE;
			
	OPEN CR_LINK_RUANGAN;
	EOF: LOOP
		FETCH CR_LINK_RUANGAN INTO VID, VNAMA, VSTATUS;
		
		IF DATA_NOT_FOUND THEN
			UPDATE temp.temp SET ID = 0 WHERE ID = 0;
			LEAVE EOF;
		END IF;
		
		SELECT VIDREF FROM regonline.link_ruangan WHERE ID = VID;
		
		IF FOUND_ROWS() > 0 THEN
			UPDATE regonline.link_ruangan SET DESKRIPSI = VNAMA, STATUS = VSTATUS WHERE ID = VID;
		ELSE
			INSERT INTO regonline.link_ruangan (ID,DESKRIPSI,ANTRIAN,STATUS) VALUES (VID,VNAMA,1,VSTATUS);
		END IF;
	END LOOP;
	CLOSE CR_LINK_RUANGAN;
	
	UPDATE link_ruangan a SET a.DEFAULT = 1 ORDER BY a.ID ASC LIMIT 1;
END//
DELIMITER ;


-- Dumping structure for function regonline.generatorReservasi
DROP FUNCTION IF EXISTS `generatorReservasi`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `generatorReservasi`(`PTANGGAL` DATE

) RETURNS char(30) CHARSET latin1
    DETERMINISTIC
BEGIN
	INSERT INTO regonline.idreservasi(TANGGAL)VALUES(PTANGGAL);
	RETURN CONCAT(DATE_FORMAT(PTANGGAL, '%y%m%d'), LPAD(LAST_INSERT_ID(), 4, '0'));
END//
DELIMITER ;


-- Dumping structure for function regonline.getNomorAntrian
DROP FUNCTION IF EXISTS `getNomorAntrian`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getNomorAntrian`(`PPOS` CHAR(10), `PTANGGAL` DATE, `PCARABAYAR` INT) RETURNS int(20)
    DETERMINISTIC
BEGIN
	DECLARE VNOMOR INT(20);
	
	SELECT NO INTO VNOMOR
	FROM regonline.reservasi 
	WHERE POS_ANTRIAN = PPOS AND TANGGALKUNJUNGAN = PTANGGAL AND CARABAYAR = PCARABAYAR AND STATUS IN (1,2) ORDER BY NO DESC LIMIT 1;
	
	IF FOUND_ROWS() > 0 THEN
		RETURN (VNOMOR + 1);
	ELSE
		RETURN 1;
	END IF;
END//
DELIMITER ;


-- Dumping structure for function regonline.getPosAntrianBpjs
DROP FUNCTION IF EXISTS `getPosAntrianBpjs`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getPosAntrianBpjs`(`PPOLI` CHAR(20)) RETURNS char(20) CHARSET latin1
    DETERMINISTIC
BEGIN
	DECLARE VPOS CHAR(20);
	
	SELECT ANTRIAN INTO VPOS
	  FROM poli_bpjs
	WHERE KDPOLI = PPOLI ORDER BY KDPOLI ASC limit 1;
	
	IF FOUND_ROWS() > 0 THEN
		RETURN VPOS;
	ELSE
		RETURN "A";
	END IF;
END//
DELIMITER ;


-- Dumping structure for function regonline.getPosAntrianWeb
DROP FUNCTION IF EXISTS `getPosAntrianWeb`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getPosAntrianWeb`(`PPOLI` CHAR(20)) RETURNS char(20) CHARSET latin1
    DETERMINISTIC
BEGIN
	DECLARE VPOS CHAR(20);
	
	SELECT ANTRIAN INTO VPOS
	  FROM link_ruangan
	WHERE ID = PPOLI AND STATUS = 1 ORDER BY ID ASC limit 1;
	
	IF FOUND_ROWS() > 0 THEN
		RETURN VPOS;
	ELSE
		RETURN "A";
	END IF;
END//
DELIMITER ;


-- Dumping structure for event regonline.executeLoketAntrian
DROP EVENT IF EXISTS `executeLoketAntrian`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `executeLoketAntrian` ON SCHEDULE EVERY 1 DAY STARTS '2020-07-23 00:23:52' ON COMPLETION PRESERVE ENABLE DO BEGIN
	CALL createAntrianLoket();
END//
DELIMITER ;


-- Dumping structure for event regonline.executeSinkronisasiCaraBayar
DROP EVENT IF EXISTS `executeSinkronisasiCaraBayar`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `executeSinkronisasiCaraBayar` ON SCHEDULE EVERY 10 SECOND STARTS '2018-05-02 01:30:22' ON COMPLETION PRESERVE ENABLE DO BEGIN
	call regonline.sinkronisasiCaraBayar();
END//
DELIMITER ;


-- Dumping structure for event regonline.executeSinkronisasiLinkRuangan
DROP EVENT IF EXISTS `executeSinkronisasiLinkRuangan`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `executeSinkronisasiLinkRuangan` ON SCHEDULE EVERY 10 SECOND STARTS '2018-05-02 01:34:11' ON COMPLETION PRESERVE ENABLE DO BEGIN
	call regonline.sinkronisasiLinkRuangan();
END//
DELIMITER ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

USE `pegawai`;
CREATE TABLE IF NOT EXISTS `libur_nasional` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TANGGAL_LIBUR` date DEFAULT NULL,
  `KETERANGAN` varchar(50) DEFAULT NULL,
  `TANGGAL` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OLEH` int(11) DEFAULT NULL,
  `STATUS` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `TANGGAL_LIBUR` (`TANGGAL_LIBUR`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
