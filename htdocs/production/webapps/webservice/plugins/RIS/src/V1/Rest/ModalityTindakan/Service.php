<?php
namespace RIS\V1\Rest\ModalityTindakan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use RIS\V1\Rest\Modality\Service as Modality;
use General\V1\Rest\Tindakan\TindakanService as Tindakan;

class Service extends DBService {
	private $modality;
	private $tindakan;

	protected $references = array(
		"Tindakan" => true,
		"Modality" => true,
	);

	public function __construct($includeReferences = true, $references = array()) {
		$this->config["entityName"] = "RIS\\V1\\Rest\\ModalityTindakan\\ModalityTindakanEntity";		
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("tindakan_modality", "ris"));
		$this->entity = new ModalityTindakanEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Modality']) $this->modality = new Modality();
			if($this->references['Tindakan']) $this->tindakan = new Tindakan();
		}
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Modality']) {
					$modality = $this->modality->load(['ID' => $entity['MODALITY']]);
					if(count($modality) > 0) $entity['REFERENSI']['MODALITY'] = $modality[0];
				}
				if($this->references['Tindakan']) {
					$tindakan = $this->tindakan->load(['ID' => $entity['TINDAKAN']]);
					if(count($tindakan) > 0) $entity['REFERENSI']['TINDAKAN'] = $tindakan[0];
				}
			}
		}
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'QUERY')) {
			$query = "%".$params['QUERY']."%";
			$jenis = isset($params["JENIS"]) ? $params["JENIS"] : 1;

			if($jenis == 1) {
				$select->join(
					['t' => new TableIdentifier('tindakan', 'master')],
					't.ID = tindakan_modality.TINDAKAN',
					[],
					$select::JOIN_INNER
				);
				$select->where('t.STATUS = 1 AND t.JENIS = 7');
				$select->where->like('t.NAMA', $query);
			}
			if($jenis == 2) {
				$select->join(
					['m' => new TableIdentifier('modality', 'ris')],
					'm.ID = tindakan_modality.MODALITY',
					[],
					$select::JOIN_INNER
				);
				$select->where->like('m.NAMA', $query);
			}
			if(isset($params["JENIS"])) unset($params['JENIS']);
			unset($params['QUERY']);
		}				
	}
}