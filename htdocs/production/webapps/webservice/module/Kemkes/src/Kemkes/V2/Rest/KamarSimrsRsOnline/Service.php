<?php
namespace Kemkes\V2\Rest\KamarSimrsRsOnline;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;
use General\V1\Rest\RuangKamar\RuangKamarService as RuangKamar;
use Kemkes\db\referensi\tempat_tidur\Service as TempatTidur;

class Service extends DBService {
	private $ruangKamar;
	private $tempatTidur;
	protected $limit = 10000;

	protected $references = array(
		'RuangKamar' => true,
		'TempatTidur' => true,
	);

	public function __construct($includeReferences = true, $references = array()) {
        $this->config["entityName"] = "Kemkes\\V2\\Rest\\KamarSimrsRsOnline\\KamarSimrsRsOnlineEntity";
		$this->config["entityId"] = "id";
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("kamar_simrs_rs_online", "kemkes"));
		$this->entity = new KamarSimrsRsOnlineEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {
			if($this->references['RuangKamar']) $this->ruangKamar = new RuangKamar();
			if($this->references['TempatTidur']) $this->tempatTidur = new TempatTidur();
        }
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
            foreach($data as &$entity) {
				if($this->references['RuangKamar']) {
                    $referensi = $this->ruangKamar->load(array('ID' => $entity['ruang_kamar']));
                    if(count($referensi) > 0) $entity['REFERENSI']['RuangKamar'] = $referensi[0];
				}
                if($this->references['TempatTidur']) {
                    $referensi = $this->tempatTidur->load(array('kode_tt' => $entity['tempat_tidur']));
                    if(count($referensi) > 0) $entity['REFERENSI']['TempatTidur'] = $referensi[0];
				}	
            }
        }
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {		
		//$select->join(['ref'=>new TableIdentifier('tempat_tidur', 'kemkes')], 'ref.kode_tt = data_tempat_tidur.id_tt', []);
		//$select->where("ref.status = 1");
	}
}