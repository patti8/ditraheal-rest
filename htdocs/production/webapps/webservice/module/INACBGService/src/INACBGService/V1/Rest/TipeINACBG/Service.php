<?php
namespace INACBGService\V1\Rest\TipeINACBG;

use DBService\DatabaseService;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use Laminas\Db\Sql\TableIdentifier;

class Service extends DBService
{	
    public function __construct() {
        $this->table = DatabaseService::get('INACBG')->get("tipe_inacbg");
		$this->entity = new TipeINACBGEntity();
    }
	
	public function getAdapter() {
		return $this->table->getAdapter();
	}
}