<?php
namespace MedicalRecord\V1\Rest\CPPT;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\TenagaMedis\TenagaMedisService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class Service extends DBService {
	private $tenagaMedis;
	private $kunjungan;
	
	protected $references = array(
		"TenagaMedis" => true,
		"Kunjungan" => true,
	);
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\CPPT\\CPPTEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("cppt", "medicalrecord"));
		$this->entity = new CPPTEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['TenagaMedis']) $this->tenagaMedis = new TenagaMedisService();
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService(true, [
				'Ruangan' => true,
				'Referensi' => false,
				'Pendaftaran' => false,
				'RuangKamarTidur' => false,
				'PasienPulang' => false,
				'Pembatalan' => false,
				'Perujuk' => false,
				'Mutasi' => false
			]);
		}
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['TenagaMedis']) {
					$tenagaMedis = $this->tenagaMedis->load(['ID' => $entity['TENAGA_MEDIS'], "JENIS" => $entity["JENIS"]]);
					if(count($tenagaMedis) > 0) $entity['REFERENSI']['TENAGA_MEDIS'] = $tenagaMedis[0];
				}
				if($this->references['Kunjungan']) {
					$this->kunjungan->setReferences(json_decode(json_encode([						
						"Ruangan" => [
							"COLUMNS" => ['DESKRIPSI']
						]		
					])), true);
					$kunjungan = $this->kunjungan->load(['NOMOR' => $entity['KUNJUNGAN']]);
					if(count($kunjungan) > 0) $entity['REFERENSI']['KUNJUNGAN'] = $kunjungan[0];
				}
			}
		}
		
		return $data;
	}
	
	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['cppt.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = cppt.KUNJUNGAN', []);
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