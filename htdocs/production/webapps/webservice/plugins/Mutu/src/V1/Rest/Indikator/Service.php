<?php
namespace Mutu\V1\Rest\Indikator;
use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;


class Service extends DBService {
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("indikator", "mutu"));
		$this->entity = new IndikatorEntity();
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
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Zend\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['KODE'])) {
				if(!System::isNull($params, 'KODE')) {
					$select->where->like('KODE', '%'.$params['KODE'].'%');
					unset($params['KODE']);
				}
			}

			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}	