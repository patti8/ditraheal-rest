<?php
namespace PPI\V1\Rest\CuciTangan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

use PPI\V1\Rest\CuciTanganDetail\Service as detailService;
use General\V1\Rest\Referensi\ReferensiService;

class Service extends DBService {
	
    private $ref;

	public function __construct($includeReferences = true, $references = array()) {
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("cuci_tangan", "ppi"));
		$this->entity = new CuciTanganEntity();
		$this->detail = new detailService();
		$this->ref = new ReferensiService();
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
	    $data = parent::load($params, $columns, $orders);
	    foreach($data as &$entity) {
	        $ref = $this->ref->load(array('ID' => $entity['PROFESI'], 'JENIS'=> 36));
	        if(count($ref) > 0) $entity['REFERENSI']['PROFESI'] = $ref[0];
	    }
	    
	    return $data;
	}
	
	public function simpan($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
	    $id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
	    
	    if($isCreated) {
	        $this->table->insert($this->entity->getArrayCopy());
	        $id = $this->table->getLastInsertValue();
	    } else {
	        $id = $this->entity->get("ID");
	        $this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
	    }
	    
	    $this->SimpanDetil($data, $id);
	    
	    if($loaded) return $this->load(array("ID" => $id));
	    return $id;
	}
	
	private function SimpanDetil($data, $id) {
	    if(isset($data['DETAIL'])) {
	        
	        foreach($data['DETAIL'] as $dtl) {
	            $sat = is_numeric($dtl['ID']) ? $dtl['ID'] : 0;
	            $dtl['CUCI_TANGAN'] = $id;
	            $this->detail->simpan($dtl, ($sat == 0) ? true : false );
	        }
	    }
	}
	
}