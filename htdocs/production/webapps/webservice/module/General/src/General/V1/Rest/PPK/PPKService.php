<?php
namespace General\V1\Rest\PPK;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\Referensi\ReferensiService;

class PPKService extends Service
{
	private $referensi;

	protected $references = [
		'Referensi' => true		
	];
	
    public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "General\\V1\\Rest\\PPK\\PPKEntity";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ppk", "master"));
		$this->entity = new PPKEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
		}
    }
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Referensi']) {
					// get referensi jenis
					$referensi = $this->referensi->load(['JENIS' => 11,'ID' => $entity['JENIS']]);
					if(count($referensi) > 0) $entity['REFERENSI']['JENIS'] = $referensi[0];
					
					// get referensi kepemilikan
					$referensi = $this->referensi->load(['JENIS' => 28,'ID' => $entity['KEPEMILIKAN']]);
					if(count($referensi) > 0) $entity['REFERENSI']['KEPEMILIKAN'] = $referensi[0];
					
					// get referensi jenis pelayanan
					$referensi = $this->referensi->load(['JENIS' => 29,'ID' => $entity['JPK']]);
					if(count($referensi) > 0) $entity['REFERENSI']['JPK'] = $referensi[0];
				}
			}
		}
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(isset($params['NAMA'])) {
			if(!System::isNull($params, 'NAMA')) {
				$select->where->like('NAMA', '%'.$params['NAMA'].'%');
				unset($params['NAMA']);
			}
		}
		
		if(isset($params['QUERY'])) {
			if(!System::isNull($params, 'QUERY')) {
				$select->where("(KODE LIKE '".$params['QUERY']."%' OR BPJS = '".$params['QUERY']."' OR NAMA LIKE '%".$params['QUERY']."%')");
				unset($params['QUERY']);
			}
		}
	}
}
