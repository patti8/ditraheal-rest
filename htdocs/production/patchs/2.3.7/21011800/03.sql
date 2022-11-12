USE aplikasi;

REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('19190101', 'LAPORAN COVID - 19', 4, 'Laporan Covid-19', 1, 'plugins.kemkes.rsonline.laporancovid.Workspace', NULL, 'x-fa fa-shield', 1, 1, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010100', 'DASHBOARD', 5, 'Dashboard', 1, 'plugins.kemkes.rsonline.dashboard.Grid', NULL, 'x-fa fa-dashboard', 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010101', 'PASIEN', 5, 'Pasien', 1, 'plugins.kemkes.rsonline.pasien.Workspace', NULL, 'x-fa fa-user', 1, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010102', 'KETERSEDIAAN RUANGAN', 5, 'Ketersediaan Ruangan', 1, 'plugins.kemkes.rsonline.data.tempattidur.Grid', NULL, 'x-fa fa-building', 1, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010103', 'KETERSEDIAAN SDM', 5, 'Sumber Daya Manusia', 1, 'plugins.kemkes.rsonline.data.kebutuhan.sdm.Grid', NULL, 'x-fa fa-users', 1, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010104', 'KETERSEDIAAN APD, OBAT & ALKES', 5, 'APD, Obat dan Alkes', 1, 'plugins.kemkes.rsonline.data.kebutuhan.apd.Grid', NULL, 'x-fa fa-medkit', 1, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010105', 'REKAP PASIEN MASUK', 5, 'Rekap Pasien Masuk', 1, 'plugins.kemkes.rsonline.data.rekappasien.masuk.Grid', NULL, 'x-fa fa-file', 1, 0, NULL, 1, 0, 0, 0, 0, 0);

REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('1919010105', 'REKAP PASIEN MASUK', 5, 'Rekap Pasien Masuk', 1, 'plugins.kemkes.rsonline.data.rekappasien.masuk.Grid', NULL, 'x-fa fa-file', 1, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('191901010501', 'AMBIL DATA DARI RS ONLINE', 6, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('191901010502', 'TAMBAH DATA', 6, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('191901010503', 'EDIT DATA', 6, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('191901010504', 'KIRIM DATA KE RS ONLINE', 6, '', 1, NULL, NULL, NULL, 0, 0, NULL, 1, 0, 0, 0, 0, 0);
