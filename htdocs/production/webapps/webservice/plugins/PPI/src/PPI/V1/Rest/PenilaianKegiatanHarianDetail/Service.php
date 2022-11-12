<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarianDetail;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use PPI\V1\Rest\KegiatanGroup\Service as KGService;

class Service extends DBService {
	
    private $kg;
    
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_kegiatan_harian_detail", "ppi"));
		$this->entity = new PenilaianKegiatanHarianDetailEntity();
		
		$this->kg = new KGService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        
	        $kg = $this->kg->load(array('ID' => $entity['KEGIATAN']));
	        if(count($kg) > 0) $entity['REFERENSI']['GROUP_DETAIL'] = $kg[0];
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