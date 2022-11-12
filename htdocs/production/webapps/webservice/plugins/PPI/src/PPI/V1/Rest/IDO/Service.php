<?php
namespace PPI\V1\Rest\IDO;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use General\V1\Rest\Pasien\PasienService;
use General\V1\Rest\Ruangan\RuanganService;
use PPI\V1\Rest\GejalaIdo\Service as GejalaIdoService;

class Service extends DBService {
    private $pasien;
    private $ruangan;
    
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("ido", "ppi"));
		$this->entity = new IDOEntity();
		$this->pasien = new PasienService(true, array(
		    'KeluargaPasien' => false,
		    'KIP' => false,
		    'KAP' => true,
		    'KontakPasien' => true,
		    'TempatLahir' => true,
		    'Referensi' => true
		)); 
		
		$this->gejalaIdo = new GejalaIdoService();
		$this->ruangan = new RuanganService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        
	        $pasien = $this->pasien->load(array('NORM'=>$entity['NORM']));
	        if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
	        
	        $ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
	        if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
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
	    
		$this->SimpanGejalaIdo($data, $id);
		
	    if($loaded) return $this->load(array("ID" => $id));
	    return $id;
	}

	private function SimpanGejalaIdo($data, $id) {
	    if(isset($data['GEJALA_IDO'])) {
	        
	        foreach($data['GEJALA_IDO'] as $dtl) {
	            $dtl['IDO'] = $id;
	            $cek = ((int)$dtl['ID'] == 0) ? true : false;
	            $this->gejalaIdo->simpan($dtl, $cek);
	        }
	    }
	}
}