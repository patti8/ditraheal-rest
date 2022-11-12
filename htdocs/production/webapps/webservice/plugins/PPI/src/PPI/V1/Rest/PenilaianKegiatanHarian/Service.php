<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarian;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use PPI\V1\Rest\GroupEvaluasi\Service as groupService;
use General\V1\Rest\Ruangan\RuanganService;

class Service extends DBService {
	
    private $group;
    private $ruangan;
    
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_kegiatan_harian", "ppi"));
		$this->entity = new PenilaianKegiatanHarianEntity();
		
		$this->ruangan = new RuanganService();
		$this->group = new groupService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        
	        $group = $this->group->load(array('ID' => $entity['GROUP']));
	        if(count($group) > 0) $entity['REFERENSI']['GROUP'] = $group[0];
	        
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
	    
	    if($loaded) return $this->load(array("ID" => $id));
	    return $id;
	}
	
}