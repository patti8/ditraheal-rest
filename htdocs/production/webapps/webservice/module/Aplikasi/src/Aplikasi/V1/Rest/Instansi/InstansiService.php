<?php
namespace Aplikasi\V1\Rest\Instansi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class InstansiService extends Service
{	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("instansi", "aplikasi"));
		$this->entity = new InstansiEntity();		
	}	
}
