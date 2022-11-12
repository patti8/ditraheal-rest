USE aplikasi;
UPDATE aplikasi.modules SET 
	DESKRIPSI = 'Mutu'
	, CLASS = 'mutu.Workspace'
	, HAVE_CHILD = '1'
	, MENU_HOME = '1'
	, ICON_CLS = 'fa fa-child'
	, PACKAGE_NAME = 'mutu' 
WHERE ID = '9902';