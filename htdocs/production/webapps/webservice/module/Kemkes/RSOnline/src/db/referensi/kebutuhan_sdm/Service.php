<?php
namespace Kemkes\RSOnline\db\referensi\kebutuhan_sdm;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct() {
		$this->limit = 5000;
        $this->config["entityName"] = "Kemkes\\RSOnline\\db\\referensi\\kebutuhan_sdm\\Entity";
		$this->config["entityId"] = "id_kebutuhan";
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kebutuhan_sdm", "kemkes-rsonline"));
    }    
}