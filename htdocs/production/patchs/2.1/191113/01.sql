USE layanan;

ALTER TABLE `petugas_tindakan_medis`
	ADD COLUMN `ID` INT NOT NULL AUTO_INCREMENT FIRST,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`ID`),
	ADD UNIQUE INDEX `TINDAKAN_MEDIS_JENIS_MEDIS_KE` (`TINDAKAN_MEDIS`, `JENIS`, `MEDIS`, `KE`);