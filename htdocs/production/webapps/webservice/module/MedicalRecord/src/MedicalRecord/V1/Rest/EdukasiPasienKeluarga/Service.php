<?php
namespace MedicalRecord\V1\Rest\EdukasiPasienKeluarga;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "MedicalRecord\\V1\\Rest\\EdukasiPasienKeluarga\\EdukasiPasienKeluargaEntity";
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("edukasi_pasien_keluarga", "medicalrecord"));
		$this->entity = new EdukasiPasienKeluargaEntity();
	}		

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['edukasi_pasien_keluarga.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'HISTORY')) {
			unset($params['HISTORY']);
		
			$select->join(array('k'=>new TableIdentifier('kunjungan', 'pendaftaran')), 'k.NOMOR = edukasi_pasien_keluarga.KUNJUNGAN', array());
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
			if($entity['KESEDIAAN'] == 1)$entity['REFERENSI']['KESEDIAAN'] = 'Ya';
			else $entity['REFERENSI']['KESEDIAAN'] = 'Tidak';
			
			if($entity['HAMBATAN_PENDENGARAN'] == 1) $hambatan1 = 'Pendengaran|';
			else $hambatan1 = '';
			if($entity['HAMBATAN_PENGLIHATAN'] == 1) $hambatan2 = 'Penglihatan|';
			else $hambatan2 = '';
			if($entity['HAMBATAN_KOGNITIF'] == 1) $hambatan3 = 'Kognitif|';
			else $hambatan3 = '';
			if($entity['HAMBATAN_FISIK'] == 1) $hambatan4 = 'Fisik|';
			else $hambatan4 = '';
			if($entity['HAMBATAN_BUDAYA'] == 1) $hambatan5 = 'Budaya|';
			else $hambatan5 = '';
			if($entity['HAMBATAN_EMOSI'] == 1) $hambatan6 = 'Emosi|';
			else $hambatan6 = '';
			if($entity['HAMBATAN_BAHASA'] == 1) $hambatan7 = 'Bahasa|';
			else $hambatan7 = '';
			$hambatan8 = $entity['HAMBATAN_LAINNYA'];
			
			if($entity['HAMBATAN'] == 1) $entity['REFERENSI']['HAMBATAN'] = 'Ya ('.$hambatan1.$hambatan2.$hambatan3.$hambatan4.$hambatan5.$hambatan6.$hambatan7.$hambatan8.'|)';
			else $entity['REFERENSI']['HAMBATAN'] = 'Tidak';
			
			if($entity['PENERJEMAH'] == 1) $entity['REFERENSI']['PENERJEMAH'] = 'Ya ('.$entity['BAHASA'].')';
			else $entity['REFERENSI']['PENERJEMAH'] = 'Tidak';
			
			if($entity['EDUKASI_DIAGNOSA'] == 1) $edukasi1 = 'Diagnosa Penyakit|';
			else $edukasi1 = '';
			if($entity['EDUKASI_PENYAKIT'] == 1) $edukasi2 = '';
			else $edukasi2 = '';
			if($entity['EDUKASI_REHAB_MEDIK'] == 1) $edukasi3 = 'Rehabilitasi Medik|';
			else $edukasi3 = '';
			if($entity['EDUKASI_HKP'] == 1) $edukasi4 = 'Hak & Kewajiban Pasien|';
			else $edukasi4 = '';
			if($entity['EDUKASI_OBAT'] == 1) $edukasi5 = 'Obat-Obatan|';
			else $edukasi5 = '';
			if($entity['EDUKASI_NYERI'] == 1) $edukasi6 = 'Manajemen Nyeri|';
			else $edukasi6 = '';
			if($entity['EDUKASI_NUTRISI'] == 1) $edukasi7 = 'Diet & Nutrisi|';
			else $edukasi7 = '';
			if($entity['EDUKASI_PENGGUNAAN_ALAT'] == 1) $edukasi8 = 'Penggunaan Alat Medis|';
			else $edukasi8 = '';
			
			$entity['REFERENSI']['EDUKASI'] = $edukasi1.$edukasi2.$edukasi3.$edukasi4.$edukasi5.$edukasi6.$edukasi7.$edukasi8;
		}
		
		return $data;
	}
}