<?php
namespace MedicalRecord\V1\Rest\PemeriksaanUmum;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PemeriksaanUmum\\PemeriksaanUmumEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("nutrisi", "medicalrecord"));
		$this->entity = new PemeriksaanUmumEntity();
	}		

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['nutrisi.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = nutrisi.KUNJUNGAN', array());
			$select->join(array('p'=>new TableIdentifier('pendaftaran', 'pendaftaran')), 'p.NOMOR = k.NOPEN', array());
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}
}