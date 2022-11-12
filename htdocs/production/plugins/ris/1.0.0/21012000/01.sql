USE aplikasi;

UPDATE aplikasi.modules m
   SET m.ID = CONCAT('90', SUBSTR(ID, 3))
 WHERE ID LIKE '9904%';

UPDATE aplikasi.group_pengguna_akses_module g
   SET g.MODUL = CONCAT('90', SUBSTR(MODUL, 3))
 WHERE g.MODUL LIKE '9904%'; 

REPLACE INTO `modules` (`ID`, `NAMA`, `LEVEL`, `DESKRIPSI`, `STATUS`, `CLASS`, `CONFIG`, `ICON_CLS`, `HAVE_CHILD`, `MENU_HOME`, `PACKAGE_NAME`, `INTERNAL_PACKAGE`, `CRUD`, `C`, `R`, `U`, `D`) VALUES ('90', 'PLUGINS', 1, 'Plugins', 1, NULL, NULL, NULL, 1, 0, NULL, 0, 0, 0, 0, 0, 0);