<?php
namespace General\V1\Rest\RuanganRadiologi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;

use General\V1\Rest\Ruangan\RuanganService;

class RuanganRadiologiService extends Service
{   
	private $ruanganasal;
	private $ruangantujuan;

	public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("ruangan_radiologi", "master"));
		$this->entity = new RuanganRadiologiEntity();
		
		$this->ruanganasal = new RuanganService();
		$this->ruangantujuan = new RuanganService();
    }

	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$ruangan = $this->entity->get('RUANGAN');
		$radiologi = $this->entity->get('RADIOLOGI');
		$cek = $this->table->select(array("RUANGAN" => $ruangan, "RADIOLOGI" => $radiologi))->toArray();
		if(count($cek) > 0) {
			$this->table->update($this->entity->getArrayCopy(), array('RUANGAN' => $ruangan, "RADIOLOGI" => $radiologi));
		} else {
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$ruanganasal = $this->ruanganasal->load(array('ID' => $entity['RUANGAN']));
			if(count($ruanganasal) > 0) $entity['REFERENSI']['RUANGAN'] = $ruanganasal[0];
			$ruangantujuan = $this->ruangantujuan->load(array('ID' => $entity['RADIOLOGI']));
			if(count($ruangantujuan) > 0) $entity['REFERENSI']['RADIOLOGI'] = $ruangantujuan[0];
		}
		
		return $data;
	}
}