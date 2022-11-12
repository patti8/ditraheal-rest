<?php
namespace MedicalRecord\V1\Rest\PenilaianSkalaHumptyDumpty;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\Referensi\ReferensiService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;

class Service extends DBService {
	private $pengguna;
	private $referensi;

	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\PenilaianSkalaHumptyDumpty\\PenilaianSkalaHumptyDumptyEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_skala_humpty_dumpty", "medicalrecord"));
		$this->entity = new PenilaianSkalaHumptyDumptyEntity();

		$this->referensi = new ReferensiService();
		$this->pengguna = new PenggunaService();
	}

	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		foreach($data as &$entity) {
			$result = $this->referensi->load(['JENIS' => 192, 'ID' => $entity['UMUR']]);
			if(count($result) > 0) $entity['REFERENSI']['UMUR'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 193, 'ID' => $entity['JENIS_KELAMIN']]);
			if(count($result) > 0) $entity['REFERENSI']['JENIS_KELAMIN'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 194, 'ID' => $entity['DIAGNOSA']]);
			if(count($result) > 0) $entity['REFERENSI']['DIAGNOSA'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 195, 'ID' => $entity['GANGGUAN_KONGNITIF']]);
			if(count($result) > 0) $entity['REFERENSI']['GANGGUAN_KONGNITIF'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 196, 'ID' => $entity['FAKTOR_LINGKUNGAN']]);
			if(count($result) > 0) $entity['REFERENSI']['FAKTOR_LINGKUNGAN'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 197, 'ID' => $entity['RESPON']]);
			if(count($result) > 0) $entity['REFERENSI']['RESPON'] = $result[0];
			
			$result = $this->referensi->load(['JENIS' => 198, 'ID' => $entity['PENGGUNAAN_OBAT']]);
			if(count($result) > 0) $entity['REFERENSI']['PENGGUNAAN_OBAT'] = $result[0];
			
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
			$params['penilaian_skala_humpty_dumpty.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(['k'=>new TableIdentifier('kunjungan', 'pendaftaran')], 'k.NOMOR = penilaian_skala_humpty_dumpty.KUNJUNGAN', []);
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