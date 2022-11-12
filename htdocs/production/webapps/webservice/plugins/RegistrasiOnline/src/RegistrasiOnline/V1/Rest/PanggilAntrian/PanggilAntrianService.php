<?php
namespace RegistrasiOnline\V1\Rest\PanggilAntrian;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class PanggilAntrianService extends DBService {
	
	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("panggil_antrian", "regonline"));
		$this->entity = new PanggilAntrianEntity();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				$entity['KOLOM'] = 100 / (isset($params['KOLOM']) ? $params['KOLOM'] : 1);
			}
		}
		return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new PanggilAntrianEntity();		
		$this->entity->exchangeArray($data);
        
		$cek = $this->load(array("LOKET" => $data["LOKET"],"POS" => $data["POS"],"TANGGAL" => $data["TANGGAL"]));
		if(count($cek)>0){
			$cek2 = $this->load(array("LOKET" => $data["LOKET"],"NOMOR" => $data["NOMOR"],"CARA_BAYAR" => $data["CARA_BAYAR"],"POS" => $data["POS"],"TANGGAL" => $data["TANGGAL"]));
			if(count($cek2)>0){
				$id = $cek2[0]['ID'];
			} else {
				$id = $cek[0]['ID'];
				$this->entity->set('ID', $id);
				$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
			}
        } else {
			$this->table->insert($this->entity->getArrayCopy());
			$id = $this->table->getLastInsertValue();
		}
		if($loaded) return $this->load(array("ID" => $id));
		return $id;
	}

	public function statusloket($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity = new PanggilAntrianEntity();		
		$this->entity->exchangeArray($data);
        
		$cek = $this->load(array("LOKET" => $data["LOKET"],"POS" => $data["POS"], "TANGGAL" => $data["TANGGAL"]));
		if(count($cek)>0){
			$id = $cek[0]['ID'];
			$nomor = $cek[0]['NOMOR'];
			$this->entity->set('ID', $id);
			$this->entity->set('NOMOR', $nomor);
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
			return array(
				'success' => true,
				'detail' => 'Sukses',
				'message' => 'Sukses'
			);
        } else {
			$this->table->insert($this->entity->getArrayCopy());
			return array(
				'success' => true,
				'detail' => 'Loket Berhasil Di Buka',
				'message' => 'Loket Berhasil Di Buka'
			);
		}
	}

	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			if(isset($params['KOLOM'])) {
				unset($params['KOLOM']);
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}