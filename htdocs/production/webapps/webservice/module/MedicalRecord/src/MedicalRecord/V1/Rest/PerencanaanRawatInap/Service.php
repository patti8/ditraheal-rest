<?php
namespace MedicalRecord\V1\Rest\PerencanaanRawatInap;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use DBService\generator\Generator;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class Service extends DBService {
	private $kunjungan;
	
	protected $references = array(
		"Kunjungan" => true,
	);

	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PerencanaanRawatInap\\PerencanaanRawatInapEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("perencanaan_rawat_inap", "medicalrecord"));
		$this->entity = new PerencanaanRawatInapEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService(true, [
				'Ruangan' => true,
				'Referensi' => false,
				'RuangKamarTidur' => false,
				'PasienPulang' => false,
				'Pembatalan' => false,
				'Perujuk' => false,
				'Mutasi' => false
			]);
		}
	}

	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if($isCreated) $entity->set("NOMOR", Generator::generateNoSPRI());
	}

	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Kunjungan']) {
					$this->kunjungan->setReferences(json_decode(json_encode([						
						"Ruangan" => [
							"COLUMNS" => ['DESKRIPSI']
						],
						"Pendaftaran" => [
							"REFERENSI" => [
								"Penjamin" => true
							]
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
			$params['perencanaan_rawat_inap.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'TAHUN')) {
			$tahun = $params['TAHUN'];
			$select->where("(YEAR(perencanaan_rawat_inap.TANGGAL) = '".$tahun."')");
			unset($params['TAHUN']);
		}

		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = perencanaan_rawat_inap.KUNJUNGAN', []);
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