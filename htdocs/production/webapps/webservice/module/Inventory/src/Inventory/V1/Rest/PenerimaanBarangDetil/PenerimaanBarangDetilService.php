<?php
namespace Inventory\V1\Rest\PenerimaanBarangDetil;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;

use Inventory\V1\Rest\Barang\BarangService;

class PenerimaanBarangDetilService extends Service
{
	private $barang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("penerimaan_barang_detil", "inventory"));
		$this->entity = new PenerimaanBarangDetilEntity();
		
		$this->barang = new BarangService();		
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : 0;
		if($id == 0) {
			$this->table->insert($this->entity->getArrayCopy());
			
		} else {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $penjualans = array()) {
		$data = parent::load($params, $columns, $penjualans);
		
		foreach($data as &$entity) {			
			$barang = $this->barang->load(array('ID' => $entity['BARANG']));
			if(count($barang) > 0) $entity['REFERENSI']['BARANG'] = $barang[0];			
		}
		
		return $data;
	}
}
