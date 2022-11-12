<?php
namespace RegistrasiOnline\V1\Rest\CaraBayar;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Stdlib\Hydrator;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

class CaraBayarService extends Service
{   
	public function __construct() {
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("cara_bayar", "regonline"));
		$this->entity = new CaraBayarEntity();
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
			
			if(isset($params['STATUS'])) $select->where(array('STATUS' => $params['STATUS']));
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
}
