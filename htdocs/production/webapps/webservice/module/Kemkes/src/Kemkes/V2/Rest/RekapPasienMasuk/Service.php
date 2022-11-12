<?php
namespace Kemkes\V2\Rest\RekapPasienMasuk;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct($includeReferences = true, $references = array()) {
        $this->config["entityName"] = "Kemkes\\V2\\Rest\\RekapPasienMasuk\\RekapPasienMasukEntity";
		$this->config["entityId"] = "id";
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rekap_pasien_masuk", "kemkes"));
		$this->entity = new RekapPasienMasukEntity();
	}	
}