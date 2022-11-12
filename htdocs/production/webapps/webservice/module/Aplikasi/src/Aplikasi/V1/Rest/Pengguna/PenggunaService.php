<?php
namespace Aplikasi\V1\Rest\Pengguna;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Aplikasi\Password;

use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Pegawai\PegawaiService;

class PenggunaService extends Service
{
	private $referensi;
	private $pegawai;
	
    public function __construct() {
		$this->config["entityName"] = "Aplikasi\\V1\\Rest\\Pengguna\\PenggunaEntity";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pengguna", "aplikasi"));
		$this->entity = new PenggunaEntity();
		
		$this->referensi = new ReferensiService();
		$this->pegawai = new PegawaiService();
    }
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$referensi = $this->referensi->load(['JENIS' => 69,'ID' => $entity['JENIS']]);
			if(count($referensi) > 0) $entity['REFERENSI']['JENIS_PENGGUNA'] = $referensi[0];
		}
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(isset($params['NAMA'])) {
			if(!System::isNull($params, 'NAMA')) {
				$select->where->like('NAMA', '%'.$params['NAMA'].'%');
				unset($params['NAMA']);
			}
		}
		
		/*if(isset($params['PASSWORD'])) {
			if(!System::isNull($params, 'PASSWORD')) {
				//$select->where->like('PASSWORD', new \Laminas\Db\Sql\Expression("PASSWORD('".$params['PASSWORD']."')"));
				$select->where(array('PASSWORD', Password::encrypt($params['PASSWORD'])));
				unset($params['PASSWORD']);
			}
		}*/
	}

	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if(isset($data["PASSWORD"])) {
    		$pass = Password::encrypt($data['PASSWORD']);
    		$this->entity->set('PASSWORD', $pass);
		}
	}

	public function getPegawai($penggunaId) {
		$pengguna = $this->load([
			"ID" => $penggunaId
		]);

		if(count($pengguna) > 0) {
			$pegawai = $this->pegawai->load([
				"NIP" => $pengguna[0]["NIP"]
			]);
			if(count($pegawai) > 0) return $pegawai[0];
		}

		return false;
	}
}
