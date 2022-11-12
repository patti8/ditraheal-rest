<?php
namespace RegistrasiOnline\V1\Rest\Istansi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\PPK\PPKService;

class IstansiService extends DBService
{	
	private $ppkx;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("instansi", "aplikasi"));
		$this->entity = new IstansiEntity();
		$this->ppkx = new PPKService();		
	}
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$ppkx = $this->ppkx->load(array('ID' => $entity['PPK']));
			if(count($ppkx) > 0) $entity['REFERENSI']['PPK'] = $ppkx[0];
		}
		
		return $data;
	}
}
