<?php
namespace MedicalRecord\V1\Rest\PemeriksaanPerut;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use DBService\generator\Generator;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	private $referensi;

	protected $references = [
		"Referensi" => true
	];

	public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PemeriksaanPerut\\PemeriksaanPerutEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("pemeriksaan_perut", "medicalrecord"));
		$this->entity = new PemeriksaanPerutEntity();

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
					$result = $this->referensi->load(['JENIS' => 178, 'ID' => $entity['ADA_KELAINAN']]);
					if(count($result) > 0) $entity['REFERENSI']['ADA_KELAINAN'] = $result[0];
				}
			}	
		}

		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['pemeriksaan_perut.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'TAHUN')) {
			$tahun = $params['TAHUN'];
			$select->where("(YEAR(pemeriksaan_perut.DIBUAT_TANGGAL) = '".$tahun."')");
			unset($params['TAHUN']);
		}

		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = pemeriksaan_perut.KUNJUNGAN', []);
			$select->join(['p'=>new TableIdentifier('pendaftaran', 'pendaftaran')], 'p.NOMOR = k.NOPEN', []);
			$select->where("k.FINAL_HASIL = 1");
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}
}