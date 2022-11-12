<?php
namespace Aplikasi\db\bridge_log;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
	    $this->config["entityName"] = "\\Aplikasi\\db\\bridge_log\\Entity";
	    $this->config["entityId"] = "ID";
	    $this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("bridge_log", "logs"));
    }

	public function recreateTable() {
		$this->table = DatabaseService::get("SIMpel", true)->get(new TableIdentifier("bridge_log", "logs"));
	}
}