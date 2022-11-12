<?php
namespace Layanan\V1\Rest\LayananBonSisa;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Inventory\V1\Rest\Barang\BarangService;
class LayananBonSisaService extends Service
{
	private $barang;

    public function __construct() {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\LayananBonSisa\\LayananBonSisaEntity";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("layanan_bon_sisa_farmasi", "layanan"));
		$this->entity = new LayananBonSisaEntity();
		
        $this->barang = new BarangService();
    }
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
            $barang = $this->barang->load(['ID' => $entity['FARMASI']]);
			if(count($barang) > 0) $entity['REFERENSI']['FARMASI'] = $barang[0];
        }
				
		return $data;
	}

    protected function queryCallback(Select &$select, &$params, $columns, $orders) {
        if(!System::isNull($params, 'BON')) { 
			$select->where("REF = '".$params['BON']."'");
			unset($params['BON']);
		}
        if(!System::isNull($params, 'STATUS')) { 
			$select->where("STATUS = '".$params['STATUS']."'");
			unset($params['STATUS']);
		}
	}
}
