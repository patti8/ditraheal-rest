USE aplikasi;

REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('26', 'MONITORING ANTRIAN', 1, 'Monitoring Antrian', 1, 'antrian.monitoring.Workspace', NULL, 'x-fa fa-user-cog', 1, 1, 'antrian', 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('2601', 'ANTRIAN KEMKES', 2, 'Antrian Kemkes', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('2602', 'ANTRIAN SIMRS', 2, 'Antrian SIMRS', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('34', 'DISPLAY ANTRIAN', 1, 'Display Antrian', 1, 'antrian.display.List', NULL, 'x-fa fa-tv', 0, 1, 'antrian', 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('35', 'ANTRIAN ONSITE', 1, 'Antrian Onsite', 1, 'antrian.onsite.List', NULL, 'x-fa fa-user-plus', 0, 1, 'antrian', 1, 0, 0, 0, 0, 0);

REPLACE INTO `signature` (`X_ID`, `X_PASS`, `X_REF`, `TOKEN`, `STATUS`) VALUES (8888, 'I23CWMCKMV', 'RegOnline', '88bae2fb2e516f1bbd362da07dd4869b', 1);
REPLACE INTO `signature` (`X_ID`, `X_PASS`, `X_REF`, `TOKEN`, `STATUS`) VALUES (9990, 'JQX34CVJN5', 'Mobile JKN Dev', '976c02ef1e63c153c91e6f22981c7aaa', 1);
REPLACE INTO `signature` (`X_ID`, `X_PASS`, `X_REF`, `TOKEN`, `STATUS`) VALUES (9991, '85S07OP53Q', 'Mobile JKN Prod', '0f429ee24ef5f87e94bbf5c5f1e34fc1', 1);

REPLACE INTO `properti_config` (`ID`, `NAMA`, `VALUE`) VALUES (30, 'TAMPILKAN_LIST_RUANGAN_ANTRIAN_ONSITE', 'FALSE');
REPLACE INTO `properti_config` (`ID`, `NAMA`, `VALUE`) VALUES (31, 'ANTRIAN_ONSITE_PER_CARA_BAYAR', 'TRUE');
REPLACE INTO `properti_config` (`ID`, `NAMA`, `VALUE`) VALUES (32, 'ANTRIAN_ONSITE_PER_JENIS_PASIEN', 'FALSE');
