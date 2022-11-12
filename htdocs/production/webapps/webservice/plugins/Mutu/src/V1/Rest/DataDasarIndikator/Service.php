<?php
namespace Mutu\V1\Rest\DataDasarIndikator;
use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Pasien\PasienService;

class Service extends DBService {
	private $pasien;

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("data_dasar_indikator", "mutu"));
		$this->entity = new DataDasarIndikatorEntity();

		$this->pasien = new PasienService(true, array(
			'KeluargaPasien' => false,
			'KIP' => false,
			'KAP' => false,
			'KontakPasien' => false,
			'TempatLahir' => false,
			'Referensi' => true
		)); 
	}

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		if($this->includeReferences) {
			foreach($data as &$entity) {
				$pasien = $this->pasien->load(array('NORM'=>$entity['NORM']));
				if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
			}
				
		}
		
		return $data;
	}

	public function simpan($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
	    
	    if($isCreated) {
	        $this->table->insert($this->entity->getArrayCopy());
	        $id = $this->table->getLastInsertValue();
	    } else {
	        $id = $this->entity->get("ID");
	        $this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
	    }
	    if($loaded) return $this->load(array("ID" => $id));
	    return $id;
	}
	
}	