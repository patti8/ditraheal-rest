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

USE ris;

-- Membuang data untuk tabel ris.segment: ~5 rows (lebih kurang)
/*!40000 ALTER TABLE `segment` DISABLE KEYS */;
REPLACE INTO `segment` (`CODE`, `SEQ`, `TABLE_ID`, `GROUP`, `NAME`, `DESCRIPTION`) VALUES
	('MSA-1', 1, '0008', 'MSA', 'AcknowledgmentCode', 'Acknowledgment Code'),
	('MSH-11', 11, '0103', 'MSH', 'ProcessingID', 'Processing ID'),
	('MSH-9', 9, '0076', 'MSH', 'MessageType', 'Identifies message type'),
	('OBR-24', 24, '0074', 'OBR', 'DiagnosticServSectID', 'Diagnostic service section ID'),
	('OBR-25', 25, '0123', 'OBR', 'ResultStatus', 'Result status'),
	('ORC-5', 5, '0038', 'ORC', 'OrderStatus', 'Order Status'),
	('PID-8', 8, '0001', 'PID', 'Sex', 'Administrative Sex'),
	('PV1-2', 2, '0004', 'PV1', 'PatientClass', 'Patient Class');
/*!40000 ALTER TABLE `segment` ENABLE KEYS */;

-- Membuang data untuk tabel ris.segment_field_reference: ~55 rows (lebih kurang)
/*!40000 ALTER TABLE `segment_field_reference` DISABLE KEYS */;
REPLACE INTO `segment_field_reference` (`CODE`, `VALUE`, `GROUP`, `DESCRIPTION`) VALUES
	('MSA-1', 'AA', '', 'Application Accept'),
	('MSA-1', 'AE', '', 'Application Error'),
	('MSA-1', 'AR', '', 'Application Reject'),
	('MSA-1', 'CA', '', 'Commit Accept'),
	('MSA-1', 'CE', '', 'Commit Error'),
	('MSA-1', 'CR', '', 'Commit Reject'),
	('MSH-11', 'D', '', 'Debugging'),
	('MSH-11', 'P', '', 'Production'),
	('MSH-11', 'T', '', 'Training'),
	('MSH-9', 'ACK', '', 'Acknowledgement'),
	('MSH-9', 'ORM^O01', 'ORM', 'Order Message'),
	('MSH-9', 'ORU^R01', 'ORU', 'Result Message (Observation Result)'),
	('OBR-24', 'AU', '', 'Audiology'),
	('OBR-24', 'BG', '', 'Blood Gases'),
	('OBR-24', 'BLB', '', 'Blood Bank'),
	('OBR-24', 'CH', '', 'Chemistry'),
	('OBR-24', 'CP', '', 'Cytopathology'),
	('OBR-24', 'CT', '', 'CAT Scan'),
	('OBR-24', 'CTH', '', 'Cardiac Catheterization'),
	('OBR-24', 'CUS', '', 'Cardiac Ultrasound'),
	('OBR-24', 'EC', '', 'Electrocardiac (e.g., EKG,  EEC, Holter)'),
	('OBR-24', 'EN', '', 'Electroneuro (EEG, EMG,EP,PSG)'),
	('OBR-24', 'HM', '', 'Hematology'),
	('OBR-24', 'ICU', '', 'Bedside ICU Monitoring'),
	('OBR-24', 'IMM	', '', 'Immunology'),
	('OBR-24', 'LAB', '', 'Laboratory'),
	('OBR-24', 'MB', '', 'Microbiology'),
	('OBR-24', 'MCB', '', 'Mycobacteriology'),
	('OBR-24', 'MYC', '', 'Mycology'),
	('OBR-24', 'NMS', '', 'Nuclear Medicine Scan'),
	('OBR-25', 'A', '', 'Some, but not all, results available'),
	('OBR-25', 'C', '', 'Correction to results'),
	('OBR-25', 'F', '', 'Final results'),
	('OBR-25', 'I', '', 'No results available; specimen received, procedure incomplete'),
	('OBR-25', 'M', '', 'Corrected, not final'),
	('OBR-25', 'N', '', 'Procedure completed, results pending'),
	('OBR-25', 'O', '', 'Order received; specimen not yet received'),
	('OBR-25', 'P', '', 'Preliminary: A verified early result is available, final results not yet obtained'),
	('OBR-25', 'R', '', 'Results stored; not yet verified'),
	('OBR-25', 'S', '', 'No results available; procedure scheduled, but not done'),
	('OBR-25', 'X', '', 'No results available; Order canceled'),
	('OBR-25', 'Y', '', 'No order on record for this test'),
	('OBR-25', 'Z', '', 'No record of this patient'),
	('ORC-5', 'A', '', 'Some, but not all, results available'),
	('ORC-5', 'CA', '', 'Order was canceled'),
	('ORC-5', 'CM', '', 'Order is completed'),
	('ORC-5', 'DC', '', 'Order was discontinued'),
	('ORC-5', 'ER', '', 'Error, order not found'),
	('ORC-5', 'HD', '', 'Order is on hold	'),
	('ORC-5', 'IP', '', 'In process, unspecified'),
	('ORC-5', 'RP', '', 'Order has been replaced'),
	('ORC-5', 'SC', '', 'In process, scheduled'),
	('PID-8', 'A', '', 'Ambiguous'),
	('PID-8', 'F', '', 'Female'),
	('PID-8', 'M', '', 'Male'),
	('PID-8', 'N', '', 'Not applicable'),
	('PID-8', 'O', '', 'Other'),
	('PID-8', 'U', '', 'Unknown'),
	('PV1-2', 'B', '', 'Obstetrics'),
	('PV1-2', 'C', '', 'Commercial Account'),
	('PV1-2', 'E', '', 'Emergency'),
	('PV1-2', 'I', '', 'Inpatient'),
	('PV1-2', 'N', '', 'Not Applicable'),
	('PV1-2', 'O', '', 'Outpatient'),
	('PV1-2', 'P', '', 'Preadmit'),
	('PV1-2', 'R', '', 'Recurring patient'),
	('PV1-2', 'U', '', 'Unknown');
/*!40000 ALTER TABLE `segment_field_reference` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
