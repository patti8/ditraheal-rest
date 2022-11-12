<?php
namespace MedicalRecord\V1\Rest\PenilaianDekubitus;

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
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PenilaianDekubitus\\PenilaianDekubitusEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_dekubitus", "medicalrecord"));
		$this->entity = new PenilaianDekubitusEntity();

		$this->referensi = new ReferensiService();
		$this->pengguna = new PenggunaService();
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		foreach($data as &$entity) {
			$referensi = $this->referensi->load(['JENIS' => 181, 'ID' => $entity['KONDISI_FISIK']]);
			if(count($referensi) > 0) $entity['REFERENSI']['KONDISI_FISIK'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 182, 'ID' => $entity['KESADARAN']]);
			if(count($referensi) > 0) $entity['REFERENSI']['KESADARAN'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 183, 'ID' => $entity['AKTIVITAS']]);
			if(count($referensi) > 0) $entity['REFERENSI']['AKTIVITAS'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 184, 'ID' => $entity['MOBILITAS']]);
			if(count($referensi) > 0) $entity['REFERENSI']['MOBILITAS'] = $referensi[0];

			$referensi = $this->referensi->load(['JENIS' => 185, 'ID' => $entity['INKONTINENSIA']]);
			if(count($referensi) > 0) $entity['REFERENSI']['INKONTINENSIA'] = $referensi[0];

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
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = penilaian_dekubitus.KUNJUNGAN', []);
			$select->join(['p'=>new TableIdentifier('pendaftaran', 'pendaftaran')], 'p.NOMOR = k.NOPEN', []);
			
			if(!System::isNull($params, 'NORM')) {
				$norm = $params['NORM'];
				$params['p.NORM'] = $norm;
				unset($params['NORM']);
			}
		}
	}
}