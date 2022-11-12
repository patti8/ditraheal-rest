<?php
namespace MedicalRecord\V1\Rest\PenilaianNyeri;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PenilaianNyeri\\PenilaianNyeriEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_nyeri", "medicalrecord"));
		$this->entity = new PenilaianNyeriEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		$this->referensi = new ReferensiService();
	}		

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['penilaian_nyeri.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = penilaian_nyeri.KUNJUNGAN', array());
			$select->join(array('p'=>new TableIdentifier('pendaftaran', 'pendaftaran')), 'p.NOMOR = k.NOPEN', array());
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}

	public function load($params = array(), $columns = array('*'), $edukasipasienkeluargas = array()) {
		$data = parent::load($params, $columns, $edukasipasienkeluargas);
		
		foreach($data as &$entity) {
			if($entity['NYERI'] == 1)$entity['REFERENSI']['NYERI'] = 'Ya';
			else $entity['REFERENSI']['NYERI'] = 'Tidak';
			
			if($entity['ONSET'] == 1)$entity['REFERENSI']['ONSET'] = 'Akut';
			else $entity['REFERENSI']['ONSET'] = 'Krenis';
			
			$metode = $this->referensi->load(array('ID' => $entity['METODE'], 'JENIS'=>71));
			if(count($metode) > 0) $entity['REFERENSI']['METODE'] = $metode[0];
		}
		
		return $data;
	}
}