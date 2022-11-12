<?php
namespace Kemkes\V2\Rest\DataKebutuhanSDM;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

use Kemkes\db\referensi\kebutuhan_sdm\Service as KebutuhanSDMService;

class Service extends DBService {
	private $kebutuhanSDM;

	protected $references = array(
		'KebutuhanSDM' => true,
	);

	public function __construct($includeReferences = true, $references = array()) {
        $this->config["entityName"] = "Kemkes\\V2\\Rest\\DataKebutuhanSDM\\DataKebutuhanSDMEntity";
		$this->config["entityId"] = "id_kebutuhan";
		$this->config["autoIncrement"] = false;
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("data_kebutuhan_sdm", "kemkes"));
		$this->entity = new DataKebutuhanSDMEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {
			if($this->references['KebutuhanSDM']) $this->kebutuhanSDM = new KebutuhanSDMService();
        }
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
            foreach($data as &$entity) {
                if($this->references['KebutuhanSDM']) {
                    $referensi = $this->kebutuhanSDM->load(array('id_kebutuhan' => $entity['id_kebutuhan']));
                    if(count($referensi) > 0) $entity['REFERENSI']['KebutuhanSDM'] = $referensi[0];
				}	
            }
        }
		
		return $data;
	}
}