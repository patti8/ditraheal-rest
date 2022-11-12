<?php
namespace General\V1\Rest\StaffRuangan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;
use General\V1\Rest\Staff\StaffService;

class StaffRuanganService extends Service
{
	private $staff;
	
	protected $references = array(
		'staff' => true
	);
    
	public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("staff_ruangan", "master"));
		$this->entity = new StaffRuanganEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['staff']) $this->staff = new StaffService(true, array(
				'StaffRuangan' => false
			));
			
		}
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');
		if($id > 0){
			$this->table->update($this->entity->getArrayCopy(), array('ID' => $id));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$staff = $this->staff->load(array('ID' => $entity['STAFF']));
			if(count($staff) > 0) $entity['REFERENSI']['STAFF'] = $staff[0];
		}
		
		return $data;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		

		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);			
			/* if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
				
			} else $select->offset(0)->limit($this->limit); */
			
			if(isset($params['IDX'])) {
				if(!System::isNull($params, 'IDX')) {
					$select->where("(ID = '".$params['IDX']."')");
					unset($params['IDX']);
				}
			}
			$select->where($params);
			
			$select->order($orders);
		})->toArray();
	}
}