<?php
namespace Layanan\V1\Rest\HasilRad;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;

class HasilRadService extends Service
{
	private $dokter;
	
    public function __construct() {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\HasilRad\\HasilRadEntity";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("hasil_rad", "layanan"));
		$this->entity = new HasilRadEntity();
		
		$this->dokter = new DokterService();
    }
        
	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		$this->entity->set('TANGGAL', new \Laminas\Db\Sql\Expression('NOW()'));
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
			
			$dokter = $this->dokter->load(['ID' => $entity['DOKTER']]);
			if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
			
		}
		
		return $data;
	}

	public function getDeskripsi($tindakanMedis) {
        $adapter = $this->table->getAdapter();
		$conn = $adapter->getDriver()->getConnection();
	    $result = $conn->execute("SELECT layanan.getDeskripsiHasilRad('".$tindakanMedis."') DESKRIPSI");
        $data = $result->current();
        return $data["DESKRIPSI"];
    }
}