USE `aplikasi`;

INSERT INTO `modules` VALUES ('2601', 'Antrian Kemkes', 2, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
INSERT INTO `modules` VALUES ('2602', 'Antrian SIMRS', 2, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
UPDATE aplikasi.modules SET CLASS='antrian-onsite-list' WHERE ID = 35;