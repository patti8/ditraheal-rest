<?php
namespace RegistrasiOnline\V1\Rest\Pasien;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use General\V1\Rest\KontakPasien\KontakPasienService;
use General\V1\Rest\TempatLahir\TempatLahirService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Wilayah\WilayahService;

class PasienService extends Service
{   
	private $kontakpasien;
	private $tempatlahir;
	private $referensi;
	private $wilayah;
	
	protected $references = array(
		'KontakPasien' => true,
		'TempatLahir' => true,
		'Referensi' => true,
		'Wilayah' => true
	);
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien", "master"));
		//$this->entity = new PasienEntity();
    }
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['NORM'])) {
				if(!System::isNull($params, 'NORM')) {
					$select->where(array("NORM" => $params["NORM"]));
					unset($params['NORM']);
				}
			}
			if(isset($params['TANGGAL_LAHIR'])) {
				if(!System::isNull($params, 'TANGGAL_LAHIR')) {
					$select->where("TANGGAL_LAHIR LIKE '".$params['TANGGAL_LAHIR']."%'");
					unset($params['TANGGAL_LAHIR']);
				}
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
}
