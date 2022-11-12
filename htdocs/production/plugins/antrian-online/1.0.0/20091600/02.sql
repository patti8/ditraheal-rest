USE `aplikasi`;
INSERT INTO `modules` VALUES ('35', 'ANTRIAN ONSITE', 1, 'Module Ambil Antrian Onsite', 1, 'antrian.onsite.Workspace', NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
INSERT INTO `modules` VALUES ('34', 'DISPLAY ANTRIAN', 1, 'Display Pemanggilan No.Antrian', 1, 'antrian.display.List', NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
INSERT INTO `modules` VALUES ('26', 'MONITORING ANTRIAN', 1, 'Monitoring Antrian Registrasi Online', 1, 'antrian.monitoring.Workspace', NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);

INSERT INTO `signature` VALUES (8888, '949O55DY5R', 'RegOnline', '30d32ade2db68897e17f03ef79235f86', 1);
INSERT INTO `signature` VALUES (9990, 'JGDBUEEJWT', 'Mobile JKN Dev', '2359e699f3c3219d0c547e66f7b2f47f', 1);
INSERT INTO `signature` VALUES (9991, '03G7JO8XV9', 'Mobile JKN Prod', 'fd7f941e75a256ecd0a47995286d42e8', 1);

INSERT INTO `properti_config` VALUES (30, 'TAMPILKAN_LIST_RUANGAN_ANTRIAN_ONSITE', 'FALSE');
INSERT INTO `properti_config` VALUES (31, 'ANTRIAN_ONSITE_PER_CARA_BAYAR', 'TRUE');
INSERT INTO `properti_config` VALUES (32, 'ANTRIAN_ONSITE_PER_JENIS_PASIEN', 'FALSE');
