<?php
namespace General\V1\Rest\TarifTindakan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class TarifTindakanService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tarif_tindakan", "master"));
		$this->entity = new TarifTindakanEntity();
    }    
		
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = $this->entity->get('ID');        
		
		$rows = $this->table->select(array("ID" => $id))->toArray();
		if(count($rows) > 0){
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
            $this->entity->set('TANGGAL', new \Laminas\Db\Sql\Expression('NOW()'));
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
}