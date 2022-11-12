<?php
namespace Mutu\V1\Rest\Analisa;
use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;


use Mutu\V1\Rest\Indikator\Service as IndikatorService;
use General\V1\Rest\Ruangan\RuanganService; 

class Service extends DBService {
private $indikator;
	private $ruangan;

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("analisa", "mutu"));
		$this->entity = new AnalisaEntity();

		$this->indikator = new IndikatorService();
		$this->ruangan = new RuanganService();
	}

	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        $indikator = $this->indikator->load(array('ID' => $entity['INDIKATOR']));
	        if(count($indikator) > 0) $entity['REFERENSI']['INDIKATOR'] = $indikator[0];

	        $ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
	        if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
	    }
	    
	    return $data;
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