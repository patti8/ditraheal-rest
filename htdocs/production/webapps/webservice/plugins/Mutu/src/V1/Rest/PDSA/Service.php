<?php
namespace Mutu\V1\Rest\PDSA;
use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;


class Service extends DBService {

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("pdsa", "mutu"));
		$this->entity = new PDSAEntity();
	}

	public function simpan($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
	    $id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
	    
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