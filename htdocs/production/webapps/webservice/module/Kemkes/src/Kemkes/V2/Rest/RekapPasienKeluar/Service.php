<?php
namespace Kemkes\V2\Rest\RekapPasienKeluar;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	protected $writeLog = true;
	
	public function __construct($includeReferences = true, $references = array()) {
        $this->config["entityName"] = "Kemkes\\V2\\Rest\\RekapPasienKeluar\\RekapPasienKeluarEntity";
		$this->config["entityId"] = "id";
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rekap_pasien_keluar", "kemkes"));
		$this->entity = new RekapPasienKeluarEntity();
	}	
}