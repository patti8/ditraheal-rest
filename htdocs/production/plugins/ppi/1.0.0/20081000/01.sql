USE ppi;
ALTER TABLE `xample_individu`
	CHANGE COLUMN `NIP` `NIP` VARCHAR(30) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `PERIODE`;
