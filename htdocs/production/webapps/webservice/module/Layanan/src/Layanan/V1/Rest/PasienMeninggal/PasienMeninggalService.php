<?php
namespace Layanan\V1\Rest\PasienMeninggal;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use DBService\System;

use General\V1\Rest\Dokter\DokterService;

class PasienMeninggalService extends Service
{
	private $dokter;
	
    public function __construct() {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\PasienMeninggal\\PasienMeninggalEntity";	
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien_meninggal", "layanan"));
		$this->entity = new PasienMeninggalEntity();
		
		$this->dokter = new DokterService();
    }
        
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
	
		foreach($data as &$entity) {
			$dokter = $this->dokter->load(array('ID' => $entity['DOKTER']));
			if(count($dokter) > 0) $entity['REFERENSI']['DOKTER'] = $dokter[0];
		}
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {		
		$select->join(
			array('kjgn' => new TableIdentifier('kunjungan', 'pendaftaran')),
			'kjgn.NOMOR = KUNJUNGAN',
			array()
		);
	}
}