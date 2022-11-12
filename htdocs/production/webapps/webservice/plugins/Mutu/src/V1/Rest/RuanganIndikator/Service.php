<?php
namespace Mutu\V1\Rest\RuanganIndikator;
use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use Mutu\V1\Rest\Indikator\Service as IndikatorService;

class Service extends DBService {

	private $indikator;

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("ruangan_indikator", "mutu"));
		$this->entity = new RuanganIndikatorEntity();

		$this->indikator = new IndikatorService();
	}

	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        $indikator = $this->indikator->load(array('ID' => $entity['INDIKATOR']));
	        if(count($indikator) > 0) $entity['REFERENSI']['INDIKATOR'] = $indikator[0];
	    }
	    
	    return $data;
	}

	public function simpan($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
	    
	    if($isCreated) {
	        $this->table->insert($this->entity->getArrayCopy());
	        $ruangan = $this->entity->get("RUANGAN");
	        $indikator = $this->entity->get("INDIKATOR");
	        $params = array("RUANGAN" => $ruangan, "INDIKATOR" => $indikator);
	    } else {
	        $ruangan = $this->entity->get("RUANGAN");
	        $indikator = $this->entity->get("INDIKATOR");
	        $params = array("RUANGAN" => $ruangan, "INDIKATOR" => $indikator);
	        $this->table->update($this->entity->getArrayCopy(), $params);
	    }
	    if($loaded) return $this->load($params);
	    return $param;
	}
	
}	