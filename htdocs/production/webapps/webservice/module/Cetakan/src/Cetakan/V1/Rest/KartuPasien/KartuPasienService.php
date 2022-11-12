<?php
namespace Cetakan\V1\Rest\KartuPasien;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\generator\Generator;
use Zend\json\Json;
use DBService\System;
use DBService\Service;

class KartuPasienService extends Service
{
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kartu_pasien", "cetakan"));
		$this->entity = new KartuPasienEntity();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		$this->entity->set('TANGGAL', new \Laminas\Db\Sql\Expression('NOW()'));
		
		if($id) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $id));
		} else {
			$id = Generator::generateIdKartu();
			$this->entity->set('ID', $id);
		
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		return $this->load(array('kartu_pasien.ID' => $id));
	}
}