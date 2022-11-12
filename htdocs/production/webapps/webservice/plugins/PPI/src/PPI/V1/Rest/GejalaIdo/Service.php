<?php
namespace PPI\V1\Rest\GejalaIdo;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	
	private $ref;
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("gejala_ido", "ppi"));
		$this->entity = new GejalaIdoEntity();
		
		$this->ref = new ReferensiService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        $ref = $this->ref->load(array('ID' => $entity['GEJALA_IDO'], 'JENIS'=> 125));
	        if(count($ref) > 0) $entity['REFERENSI']['GEJALA_IDO'] = $ref[0];
		
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