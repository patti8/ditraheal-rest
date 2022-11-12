<?php
namespace PPI\V1\Rest\PenilaianKegiatan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use PPI\V1\Rest\PenilaianKegiatanDetail\Service as detailService;
use PPI\V1\Rest\GroupEvaluasi\Service as groupService;
use General\V1\Rest\Ruangan\RuanganService;

class Service extends DBService {
	
    private $group;
    private $ruangan;

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("penilaian_kegiatan", "ppi"));
		$this->entity = new PenilaianKegiatanEntity();
		
		$this->detail = new detailService();
		$this->group = new groupService();
		$this->ruangan = new RuanganService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        
	        $group = $this->group->load(array('ID' => $entity['GROUP']));
	        if(count($group) > 0) $entity['REFERENSI']['GROUP'] = $group[0];
	        
	        $ruangan = $this->ruangan->load(array('ID' => $entity['RUANGAN']));
	        if(count($ruangan) > 0) $entity['REFERENSI']['RUANGAN'] = $ruangan[0];
	        
	        
	    }
	    
	    return $data;
	}
	
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		$select = $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['TANGGAL_BUAT'])) {
				//$select->where("TANGGAL_BUAT = '".$params['TANGGAL_BUAT']."'");
				$select->where("TANGGAL_BUAT BETWEEN '".$params['TANGGAL_BUAT']."-01 00:00:00' AND '".$params['TANGGAL_BUAT']."-31 23:59:59'");
				
				unset($params['TANGGAL_BUAT']);
			}
			
			if(isset($params['STATUS'])) {
				$pot = explode(',',$params['STATUS']);
				$in = '';
				foreach($pot as &$a){
					if($in == '')$in = 'STATUS = '.$a.' ';
					else $in = $in.'OR STATUS = '.$a.' ';
				}
				$select->where($in);
				
				unset($params['STATUS']);
			}
			
			$select->where($params);
			$select->order($orders);
			
			//echo $select->getSqlString();
		});
		return $select->toArray();
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
	    
	    if($isCreated) {
	        $this->table->insert($this->entity->getArrayCopy());
	        $id = $this->table->getLastInsertValue();
	    } else {
	        $id = $this->entity->get("ID");
	        $this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
	    }
	    
	    $this->SimpanDetilPenerimaan($data, $id);
	    
	    if($loaded) return $this->load(array("ID" => $id));
	    return $id;
	}
	
	private function SimpanDetilPenerimaan($data, $id) {
	    if(isset($data['DETAIL'])) {
	        
	        foreach($data['DETAIL'] as $dtl) {
	            $dtl['PENILAIAN'] = $id;
	            $cek = ((int)$dtl['ID'] == 0) ? true : false;
	            $this->detail->simpan($dtl, $cek);
	        }
	    }
	}
}