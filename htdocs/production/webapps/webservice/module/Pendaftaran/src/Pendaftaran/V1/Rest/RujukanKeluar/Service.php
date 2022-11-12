<?php
namespace Pendaftaran\V1\Rest\RujukanKeluar;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use DBService\generator\Generator;
use General\V1\Rest\PPK\PPKService;
use General\V1\Rest\Diagnosa\DiagnosaService;
use General\V1\Rest\Dokter\DokterService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	
	private $tujuan;
	private $tujuanRuangan;
	private $diagnosa;
	private $dokter;
	
	protected $references = array(
		"Tujuan" => true,
		"TujuanRuangan" => true,
		"Diagnosa" => true,
		"Dokter" => true
	);
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "Pendaftaran\\V1\\Rest\\RujukanKeluar\\RujukanKeluarEntity";
		$this->config["entityId"] = "NOMOR";
		$this->config["autoIncrement"] = false;
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("rujukan_keluar", "pendaftaran"));
		$this->entity = new RujukanKeluarEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Tujuan']) $this->tujuan = new PPKService(false);
			if($this->references['TujuanRuangan']) $this->tujuanRuangan = new ReferensiService();
			if($this->references['Diagnosa']) $this->diagnosa = new DiagnosaService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
		}
	}

	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		$datas = $entity->getArrayCopy();
		file_put_contents("logs/update_rujukan.txt", json_encode($datas));
		if($isCreated) $entity->set("NOMOR", Generator::generateNoRujukan());
	}
	
	public function load($params = array(), $columns = array("*"), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references["Tujuan"]) {
					$tujuan = $this->tujuan->load(array("ID" => $entity["TUJUAN"]));
					if(count($tujuan) > 0) $entity["REFERENSI"]["TUJUAN"] = $tujuan[0];
				}
				if($this->references['TujuanRuangan']) {
					$tujuanRuangan = $this->tujuanRuangan->load(array('JENIS' => 70, 'ID' => $entity['TUJUAN_RUANGAN']));
					if(count($tujuanRuangan) > 0) $entity['REFERENSI']['TUJUAN_RUANGAN'] = $tujuanRuangan[0];
				}
				if($this->references["Diagnosa"]) {
					$diagnosa = $this->diagnosa->load(array("CODE" => $entity["DIAGNOSA"]));
					if(count($diagnosa) > 0) $entity["REFERENSI"]["DIAGNOSA"] = $diagnosa[0];
				}
				if($this->references['Dokter']) {
					$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
				}
			}
		}
		
		return $data;
	}
}