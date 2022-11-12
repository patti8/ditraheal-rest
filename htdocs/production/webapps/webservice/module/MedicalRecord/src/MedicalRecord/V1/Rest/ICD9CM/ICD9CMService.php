<?php
namespace MedicalRecord\V1\Rest\ICD9CM;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;
use DBService\System;

use General\V1\Rest\Diagnosa\DiagnosaService;

class ICD9CMService extends Service
{
	private $diagnosa;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("prosedur", "medicalrecord"));
		$this->entity = new ICD9CMEntity();
		
		$this->diagnosa = new DiagnosaService();
    }
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		
		if($id) {
			$data = $this->entity->getArrayCopy();
			$this->table->update($data, array("ID" => $id));
		} else {
			$data = $this->entity->getArrayCopy();
			$this->table->insert($data);
			$id = $this->table->getLastInsertValue();
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id)) 
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$diagnosa = $this->diagnosa->load(array('CODE' => $entity['KODE'], 'ICD9' => 1));
			if(count($diagnosa) > 0) $entity['REFERENSI']['DIAGNOSA'] = $diagnosa[0];
		}
		
		
		return $data;
	}

}