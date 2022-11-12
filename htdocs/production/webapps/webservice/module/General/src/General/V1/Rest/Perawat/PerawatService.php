<?php
namespace General\V1\Rest\Perawat;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use General\V1\Rest\PerawatRuangan\PerawatRuanganService;
use General\V1\Rest\Pegawai\PegawaiService;

class PerawatService extends Service
{
    private $perawatRuangan;
	private $pegawai;
	
	protected $references = [
		'Ruangan' => true,
		'Pegawai' => true
	];
    
    public function __construct($includeReferences = true, $references = []) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("perawat", "master"));
		$this->entity = new PerawatEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new PerawatRuanganService();
			if($this->references['Pegawai']) $this->pegawai = new PegawaiService();
		}
    }

	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		$perawats = []; 
		$i = 0;
		
		foreach($data as $entity) {
			$perawats[$i]['ID'] = $entity['ID'];
			$perawats[$i]['NAMA'] = $this->namaPegawai($entity['NIP']);
			$perawats[$i]['NIP'] = $entity['NIP'];
			$i++;
		}
		
		return $perawats;
	}
	
	public function cariPerawat($params = []) {
		$params = is_array($params) ? $params : (array) $params;					
		$perawats = [];
		$i = 0;
		 
		if(isset($params['RUANGAN']))
			$data = $this->ruangan->load([
				"RUANGAN" => $params["RUANGAN"],
				"STATUS" => 1
			]);
							
		foreach ($data as $d) {
			$search = ['ID' => $d['PERAWAT']];
			if(isset($params['QUERY'])) $search['QUERY'] = $params['QUERY'];
			$search["STATUS"] = 1;
			$pers = $this->load($search);		
			if(count($pers) > 0) {
				$perawats[$i] = $pers[0];
				$i++;
			}
		}
		
		return $perawats;
    }

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['perawat.STATUS'] = $status;
			unset($params['STATUS']);
		}

		if(!System::isNull($params, 'ID')) {				
			$params["perawat.ID"] = $params["ID"];
			unset($params["ID"]);
		}
		
		if(!System::isNull($params, 'NAMA')) { 
			$select->join(['p' => new TableIdentifier("pegawai", "master")], "perawat.NIP = p.NIP", ["NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"]);
			$select->where("p.NAMA LIKE '%".$params['NAMA']."%'");
			unset($params['NAMA']);
		}
		
		if(isset($params['QUERY'])) if(!System::isNull($params, 'QUERY')) {
			$select->join(['p' => new TableIdentifier("pegawai", "master")], "perawat.NIP = p.NIP", ["NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"]);
			$select->where("(p.NAMA LIKE '%".$params['QUERY']."%' OR p.NIP LIKE '".$params['QUERY']."%')");
			unset($params['QUERY']);
		}
	}
		
	private function namaPegawai($nip){
		$peg = $this->pegawai->load(['NIP'=>$nip], ["NIP", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG"]);
		if(str_replace(' ','', $peg[0]['GELAR_DEPAN']) != ''){
			$titik = '. ';
		}else{
			$titik = '';
		}
		if(str_replace(' ','', $peg[0]['GELAR_BELAKANG']) != ''){
			$koma = ', ';
		}else{
			$koma = '';
		}
		return $peg[0]['GELAR_DEPAN'].$titik.$peg[0]['NAMA'].$koma.$peg[0]['GELAR_BELAKANG'];
	}
}