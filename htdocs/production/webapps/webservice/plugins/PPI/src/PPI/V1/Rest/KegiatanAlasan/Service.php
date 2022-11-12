<?php
namespace PPI\V1\Rest\KegiatanAlasan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use PPI\V1\Rest\Alasan\Service as ServiceAlasan;

class Service extends DBService {
	
	private $alasan;
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("kagiatan_alasan", "ppi"));
		$this->entity = new KegiatanAlasanEntity();
		
		$this->alasan = new ServiceAlasan();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
			$alasan = $this->alasan->load(array('ID' => $entity['ALASAN']));
	        if(count($alasan) > 0) $entity['REFERENSI']['ALASAN'] = $alasan[0];
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