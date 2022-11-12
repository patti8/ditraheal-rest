<?php
namespace Layanan\V1\Rest\HasilLab;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use DBService\generator\Generator;
use General\V1\Rest\ParameterTindakanLab\ParameterTindakanLabService;
use Layanan\V1\Rest\TindakanMedis\TindakanMedisService;

class HasilLabService extends Service
{
	private $parameterhasil;
	private $tindakanmedis;
	
	protected $references = [
		"ParameterHasil" => true,
		"TindakanMedis" =>  true
	];

    public function __construct($includeReferences = true, $references = []) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("hasil_lab", "layanan"));
		$this->entity = new HasilLabEntity();

		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {
            if($this->references['ParameterHasil']) $this->parameterhasil = new ParameterTindakanLabService();
			if($this->references['TindakanMedis']) $this->tindakanmedis = new TindakanMedisService();
        }
    }

	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		foreach($data as &$entity) {
            if($this->includeReferences){
                if($this->references['ParameterHasil']){
                    $parameterhasil = $this->parameterhasil->load(['ID' => $entity['PARAMETER_TINDAKAN']]);
                    if(count($parameterhasil) > 0) $entity['REFERENSI']['PARAMETER_TINDAKAN'] = $parameterhasil[0];
                }

				if($this->references['TindakanMedis']){
                    $tindakanmedis = $this->tindakanmedis->load(['ID' => $entity['TINDAKAN_MEDIS']]);
                    if(count($tindakanmedis) > 0) $entity['REFERENSI']['TINDAKAN_MEDIS'] = $tindakanmedis[0];
                }
            }
		}
        	
		return $data;
	}
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$id = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		if($id) {
			$this->table->update($this->entity->getArrayCopy(), array("ID" => $id));
		} else {
			$id = Generator::generateIdHasilLab();
			$this->entity->set('ID', $id);
			$this->table->insert($this->entity->getArrayCopy());
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('ID' => $id))
		);
	}
}