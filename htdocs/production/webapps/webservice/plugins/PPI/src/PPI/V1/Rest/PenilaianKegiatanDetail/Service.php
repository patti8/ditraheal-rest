<?php
namespace PPI\V1\Rest\PenilaianKegiatanDetail;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use PPI\V1\Rest\KegiatanGroup\Service as KGService;
use PPI\V1\Rest\KegiatanAlasan\Service as KAService;

class Service extends DBService {
	
    private $kg;
    private $ka;
    
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_kegiatan_detail", "ppi"));
		$this->entity = new PenilaianKegiatanDetailEntity();
		
		$this->kg = new KGService();
		$this->ka = new KAService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        
	        $kg = $this->kg->load(array('ID' => $entity['KEGIATAN']));
	        if(count($kg) > 0) $entity['REFERENSI']['GROUP_DETAIL'] = $kg[0];
			
			$ka = $this->ka->load(array('ID' => $entity['ALASAN']));
	        if(count($ka) > 0) $entity['REFERENSI']['KEGIATANALASAN'] = $ka[0];
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