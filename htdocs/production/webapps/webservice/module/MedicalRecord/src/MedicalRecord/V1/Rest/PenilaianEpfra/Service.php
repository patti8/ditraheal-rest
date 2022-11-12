<?php
namespace MedicalRecord\V1\Rest\PenilaianEpfra;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;

class Service extends DBService {
	private $referensi;
	private $pengguna;

	public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PenilaianEpfra\\PenilaianEpfraEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_epfra", "medicalrecord"));
		$this->entity = new PenilaianEpfraEntity();

		$this->referensi = new ReferensiService();
		$this->pengguna = new PenggunaService();
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		foreach($data as &$entity) {
			$referensi = $this->referensi->load(['JENIS' => 199, 'ID' => $entity['USIA']]);
			if(count($referensi) > 0) $entity['REFERENSI']['USIA'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 200, 'ID' => $entity['STATUS_MENTAL']]);
			if(count($referensi) > 0) $entity['REFERENSI']['STATUS_MENTAL'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 201, 'ID' => $entity['ELIMINASI']]);
			if(count($referensi) > 0) $entity['REFERENSI']['ELIMINASI'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 202, 'ID' => $entity['MEDIKASI']]);
			if(count($referensi) > 0) $entity['REFERENSI']['MEDIKASI'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 203, 'ID' => $entity['DIAGNOSIS']]);
			if(count($referensi) > 0) $entity['REFERENSI']['DIAGNOSIS'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 204, 'ID' => $entity['AMBULASI']]);
			if(count($referensi) > 0) $entity['REFERENSI']['AMBULASI'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 205, 'ID' => $entity['NUTRISI']]);
			if(count($referensi) > 0) $entity['REFERENSI']['NUTRISI'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 206, 'ID' => $entity['GANGGUAN_TIDUR']]);
			if(count($referensi) > 0) $entity['REFERENSI']['GANGGUAN_TIDUR'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 207, 'ID' => $entity['RIWAYAT_JATUH']]);
			if(count($referensi) > 0) $entity['REFERENSI']['RIWAYAT_JATUH'] = $referensi[0];

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
			$params['penilaian_dekubitus.STATUS'] = $status;
			unset($params['STATUS']);
		}

		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = penilaian_epfra.KUNJUNGAN', []);
			$select->join(['p'=>new TableIdentifier('pendaftaran', 'pendaftaran')], 'p.NOMOR = k.NOPEN', []);
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}
}