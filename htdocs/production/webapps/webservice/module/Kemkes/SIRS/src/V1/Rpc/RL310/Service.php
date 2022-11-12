<?php
namespace Kemkes\SIRS\V1\Rpc\RL310;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	private $kelas;
	private $ruangan;

	public function __construct() {
		$this->config["entityName"] = "Kemkes\\SIRS\\V1\\Rpc\\RL310\\Entity";
		$this->config["entityId"] = "object_id";
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("rl3-10", "kemkes-sirs"));
	}

	public function hapus($params = []) {
		$this->table->delete($params);
		return true;
	}

	public function ambilData($tahun = null) {
		if(empty($tahun)) $tahun = date("Y");
	    $adapter = $this->table->getAdapter();
	    $stmt = $adapter->query('CALL `kemkes-sirs`.ambilDataRL310(?)');
	    $results = $stmt->execute([$tahun]);
	    $results->getResource()->closeCursor();
	}
}