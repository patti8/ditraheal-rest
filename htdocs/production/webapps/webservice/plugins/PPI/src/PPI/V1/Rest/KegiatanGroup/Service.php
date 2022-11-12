<?php
namespace PPI\V1\Rest\KegiatanGroup;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use PPI\V1\Rest\Kegiatan\Service as ServiceKegiatan;
use PPI\V1\Rest\Kelompok\Service as klpservice;
class Service extends DBService {
	
    private $kegiatan;
    private $klp;
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("kegiatan_group", "ppi"));
		$this->entity = new KegiatanGroupEntity();
		
		$this->kegiatan = new ServiceKegiatan();
		$this->klp = new klpservice();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        $kegiatan = $this->kegiatan->load(array('ID' => $entity['KEGIATAN']));
	        if(count($kegiatan) > 0) $entity['REFERENSI']['KEGIATAN'] = $kegiatan[0];
	        
	        $klp = $this->klp->load(array('ID' => $entity['KELOMPOK']));
	        if(count($klp) > 0) $entity['REFERENSI']['KELOMPOK'] = $klp[0];
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