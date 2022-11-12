<?php
namespace Layanan\V1\Rest\NilaiKritisLab;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as dbService;
use Layanan\V1\Rest\HasilLab\HasilLabService;

class Service extends dbService{
    private $hasillab;

    protected $references = [
        "HasilLab" => true
    ];

    public function __construct($includeReferences = true, $references = []){
        $this->config["entityName"] = "\\Layanan\\V1\\Rest\\NilaiKritisLab\\NilaiKritisLabEntity";
        $this->config["entityId"] = "ID";
        $this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("nilai_kritis_lab","layanan"));
        $this->entity = new NilaiKritisLabEntity();

        $this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {
            if($this->references['HasilLab']) $this->hasillab = new HasilLabService();
        }
    }

    public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
            if($this->includeReferences){
                if($this->references['HasilLab']){
                    $hasillab = $this->hasillab->load(['ID' => $entity['HASIL_LAB']]);
                    if(count($hasillab) > 0) $entity['REFERENSI']['HASIL_LAB'] = $hasillab[0];
                }
            }
		}
        	
		return $data;
	}
}
