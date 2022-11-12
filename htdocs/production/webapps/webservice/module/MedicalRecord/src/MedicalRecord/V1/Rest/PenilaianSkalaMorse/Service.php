<?php
namespace MedicalRecord\V1\Rest\PenilaianSkalaMorse;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use DBService\generator\Generator;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	private $pengguna;
	private $referensi;

	public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PenilaianSkalaMorse\\PenilaianSkalaMorseEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_skala_morse", "medicalrecord"));
		$this->entity = new PenilaianSkalaMorseEntity();
		
		$this->referensi = new ReferensiService();
		$this->pengguna = new PenggunaService();
	}

	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		foreach($data as &$entity) {
			$result = $this->referensi->load(['JENIS' => 186, 'ID' => $entity['RIWAYAT_JATUH']]);
			if(count($result) > 0) $entity['REFERENSI']['RIWAYAT_JATUH'] = $result[0];

			$result = $this->referensi->load(['JENIS' => 187, 'ID' => $entity['DIAGNOSIS']]);
			if(count($result) > 0) $entity['REFERENSI']['DIAGNOSIS'] = $result[0];

			$result = $this->referensi->load(['JENIS' => 188, 'ID' => $entity['ALAT_BANTU']]);
			if(count($result) > 0) $entity['REFERENSI']['ALAT_BANTU'] = $result[0];

			$result = $this->referensi->load(['JENIS' => 189, 'ID' => $entity['HEPARIN']]);
			if(count($result) > 0) $entity['REFERENSI']['HEPARIN'] = $result[0];

			$result = $this->referensi->load(['JENIS' => 190, 'ID' => $entity['GAYA_BERJALAN']]);
			if(count($result) > 0) $entity['REFERENSI']['GAYA_BERJALAN'] = $result[0];

			$result = $this->referensi->load(['JENIS' => 191, 'ID' => $entity['KESADARAN']]);
			if(count($result) > 0) $entity['REFERENSI']['KESADARAN'] = $result[0];

			if(!empty($entity['OLEH'])) {
				$pengguna = $this->pengguna->getPegawai($entity['OLEH']);
				if($pengguna) $entity['REFERENSI']['PETUGAS'] = $pengguna;
			}
		}	

		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['penilaian_skala_morse.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = penilaian_skala_morse.KUNJUNGAN', []);
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