<?php
namespace Layanan\V1\Rest\ViewerRad;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("dcm_rad", "layanan"));		
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
	}
}