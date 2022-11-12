<?php
namespace Kemkes\db\referensi\propinsi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
		$this->limit = 5000;
        $this->config["entityName"] = "Kemkes\\db\\referensi\\propinsi\\Entity";
		$this->config["entityId"] = "prop_kode";	
		$this->config["autoIncrement"] = false;
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("propinsi", "kemkes"));
    }    
}