<?php
namespace RIS\V1\Rest\Logs;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "RIS\\V1\\Rest\\Logs\\LogsEntity";		
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("hl7_logs", "ris"));
		$this->entity = new LogsEntity();
	}
		
	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		/*if(!System::isNull($params, 'NAMA')) {
			$like = $params['NAMA']."%";
			$select->where->like("NAMA", $like);
			unset($params['NAMA']);
		}*/			
	}
}