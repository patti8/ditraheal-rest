<?php
namespace MedicalRecord\V1\Rest\RiwayatPemberianObat;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\RiwayatPemberianObat\\RiwayatPemberianObatEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("riwayat_pemberian_obat", "medicalrecord"));
		$this->entity = new RiwayatPemberianObatEntity();		
	}		

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['riwayat_pemberian_obat.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = riwayat_pemberian_obat.KUNJUNGAN', array());
			$select->join(array('p'=>new TableIdentifier('pendaftaran', 'pendaftaran')), 'p.NOMOR = k.NOPEN', array());
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}		
	}	
}