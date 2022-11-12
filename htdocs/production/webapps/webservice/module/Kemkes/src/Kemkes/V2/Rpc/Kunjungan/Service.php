<?php
namespace Kemkes\V2\Rpc\Kunjungan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("statistik_kunjungan", "informasi"));
        $this->entity = new Entity();
    }
    
    public function simpan($data) {
        $data = is_array($data) ? $data : (array) $data;
        $this->entity = new Entity();
        $this->entity->exchangeArray($data);
        
        $params = [
            "TANGGAL" => $this->entity->get("TANGGAL")
        ];
        $this->table->update($this->entity->getArrayCopy(), $params);
        
        return true;
    }
	
	public function getRJ($params = array()) {		
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRJKemkes(".$tanggal.", ".$tanggal.")");
	}
	
	public function getRD($params = array()) {
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRDKemkes(".$tanggal.", ".$tanggal.")");
	}
	
	public function getRI($params = array()) {
		$tanggal = isset($params["tanggal"]) ? "STR_TO_DATE('".$params["tanggal"]."', '%d-%m-%Y')" : "DATE(NOW())";
		return $this->execute("CALL informasi.listKunjunganRIKemkes(".$tanggal.", ".$tanggal.")");
	}
}