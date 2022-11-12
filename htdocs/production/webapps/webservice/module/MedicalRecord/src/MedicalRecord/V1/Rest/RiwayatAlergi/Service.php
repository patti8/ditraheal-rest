<?php
namespace MedicalRecord\V1\Rest\RiwayatAlergi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	private $referensi;

	protected $references = [
		"Referensi" => true
	];
	
	public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\RiwayatAlergi\\RiwayatAlergiEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("riwayat_alergi", "medicalrecord"));
		$this->entity = new RiwayatAlergiEntity();
		
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
					if($entity['JENIS']) {
						$_params = [
							'JENIS' => 180,
							new \Laminas\Db\Sql\Predicate\In('ID', (array) json_decode("[".$entity['JENIS']."]"))
						];
						$result = $this->referensi->load($_params);
						if(count($result) > 0) $entity['REFERENSI']['JENIS'] = $result;
					}
				}
			}	
		}

		return $data;
	}
	
	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['riwayat_alergi.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = riwayat_alergi.KUNJUNGAN', []);
			$select->join(['p'=>new TableIdentifier('pendaftaran', 'pendaftaran')], 'p.NOMOR = k.NOPEN', []);
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}
}