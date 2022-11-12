UPDATE `aplikasi`.`modules` SET `DESKRIPSI`='Mutu', `CLASS`='mutu.Workspace', `ICON_CLS`='x-fa fa-child', `HAVE_CHILD`='1', `MENU_HOME`='1', `PACKAGE_NAME`='mutu', `INTERNAL_PACKAGE`='0' WHERE  `ID`='32';

UPDATE aplikasi.modules 
   SET INTERNAL_PACKAGE = 0
 WHERE ID LIKE '32%';
