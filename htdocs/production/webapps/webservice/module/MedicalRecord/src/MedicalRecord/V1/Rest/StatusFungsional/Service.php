<?php
namespace MedicalRecord\V1\Rest\StatusFungsional;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\StatusFungsional\\StatusFungsionalEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("status_fungsional", "medicalrecord"));
		$this->entity = new StatusFungsionalEntity();		
	}		

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['status_fungsional.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = status_fungsional.KUNJUNGAN', array());
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
			if($entity['CACAT_TUBUH_TIDAK'] == 1) $cacattubuh = 'Tidak';
			else $cacattubuh = 'Ya |'.$entity['KET_CACAT_TUBUH'];
			$entity['REFERENSI']['CACATTUBUH'] = $cacattubuh;
			
			if($entity['TONGKAT'] == 1) $alat1 = 'Tongkat|';
			else $alat1 = '';
			if($entity['KURSI_RODA'] == 1) $alat2 = 'Kursi Roda|';
			else $alat2 = '';
			if($entity['BRANKARD'] == 1) $alat3 = 'Brankard|';
			else $alat3 = '';
			if($entity['WALKER'] == 1) $alat4 = 'Walker|';
			else $alat4 = '';
			$alat5 = $entity['ALAT_BANTU'];
			
			if($entity['TANPA_ALAT_BANTU'] == 1) $entity['REFERENSI']['ALATBANTU'] = 'Tidak Ada';
			else $entity['REFERENSI']['ALATBANTU'] = 'Ya ('.$alat1.$alat2.$alat3.$alat4.$alat5.')';			
		}
		
		return $data;
	}
}