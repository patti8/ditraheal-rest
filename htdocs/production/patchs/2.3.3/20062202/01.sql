USE aplikasi;

ALTER TABLE `modules`
	ADD COLUMN `INTERNAL_PACKAGE` TINYINT NOT NULL DEFAULT '1',
	ADD COLUMN `CRUD` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=Non Aktif; 1=Aktif',
	ADD COLUMN `C` TINYINT(4) NOT NULL DEFAULT '0',
	ADD COLUMN `R` TINYINT(4) NOT NULL DEFAULT '0',
	ADD COLUMN `U` TINYINT(4) NOT NULL DEFAULT '0',
	ADD COLUMN `D` TINYINT(4) NOT NULL DEFAULT '0';