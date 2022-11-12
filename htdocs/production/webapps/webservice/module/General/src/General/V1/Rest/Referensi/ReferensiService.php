<?php
namespace General\V1\Rest\Referensi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\PenjaminSubSpesialistik\Service as PenjaminSubSpesialistikService;

class ReferensiService extends Service
{
    private $penjaminSubSpesialistik;
    
    protected $limit = 3000;
    protected $references = [
        'PenjaminSubSpesialistik' => true
	];

    public function __construct($includeReferences = true, $references = []) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("referensi", "master"));
		$this->entity = new ReferensiEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
		    if($this->references['PenjaminSubSpesialistik']) $this->penjaminSubSpesialistik = new PenjaminSubSpesialistikService(false);
		}
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
        $jenis = $this->entity->get('JENIS');
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, ["JENIS" => $jenis, "ID" => $id]);
		} else {
			if(($jenis == 41) && ($id != 0)){				
				$this->entity->set('DESKRIPSI', $this->entity->get('DESKRIPSI'));
				$this->entity->set('ID', '');
			}
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			$id = $this->table->getLastInsertValue();
		}
		$results = $this->load(['ID'=>$id, 'JENIS'=>$jenis]);
		if(count($results) > 0) $data = $results[0];
		return [
			'success' => true,
			'data' => $data
		];
	}

	public function load($params = [], $columns = ['*'], $orders = []) {
	    $data = parent::load($params, $columns, $orders);
	    
	    if($this->includeReferences) {
	        foreach($data as &$entity) {
				$entity["DESKRIPSI"] = utf8_encode($entity["DESKRIPSI"]);
	            if(isset($entity["JENIS"])){
	                // SMF
	                if($entity["JENIS"] == 26) {
	                    if($this->references['PenjaminSubSpesialistik']) {
	                        $penjaminSubSpesialistik = $this->penjaminSubSpesialistik->load(['SUB_SPESIALIS_RS' => $entity['ID'], 'STATUS' => 1]);
	                        if(count($penjaminSubSpesialistik) > 0) $entity['REFERENSI']['PENJAMIN_SUB_SPESIALISTIK'] = $penjaminSubSpesialistik;
	                    }
	                }
	            }
	        }
	    }
	    
	    return $data;
	}
	
	protected function query($columns, $params, $isCount = false, $orders = []) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(['rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')]);
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("DESKRIPSI LIKE '".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
