<?php
namespace Pembayaran\V1\Rest\PiutangPasien;

use DBService\DatabaseService;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service;
use Laminas\Db\Sql\TableIdentifier;

use General\V1\Rest\Referensi\ReferensiService;

class PiutangPasienService extends Service
{
	private $referensi; 
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("piutang_pasien", "pembayaran"));
		$this->entity = new PiutangPasienEntity();
		
		$this->referensi = new ReferensiService();
    }
	
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$tagihan = $this->entity->get('TAGIHAN');
		$cek = $this->load(array('TAGIHAN' => $tagihan));
		
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array('TAGIHAN' => $tagihan));
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
			
		}
		
		return array(
			'success' => true,
			'data' => $this->load(array('TAGIHAN' => $tagihan))
		);
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
			foreach($data as &$entity) {
				$shdk = $this->referensi->load(array('ID' => $entity['SHDK'], 'JENIS' => 7));
				if(count($shdk) > 0) $entity['REFERENSI']['SHDK'] = $shdk[0];
				
				$jeniskartu = $this->referensi->load(array('ID' => $entity['JENIS_KARTU'], 'JENIS' => 9));
				if(count($jeniskartu) > 0) $entity['REFERENSI']['JENIS_KARTU'] = $jeniskartu[0];

			}

		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'NORM')) {
			$select->join(array('t'=>new TableIdentifier('tagihan', 'pembayaran')), 't.ID = TAGIHAN', array());
			$select->where('t.REF = '.$params['NORM'].' AND t.JENIS = 1');
			
			unset($params['NORM']);
			
			if(!System::isNull($params, 'STATUS')){
				$select->where('piutang_pasien.STATUS = '.$params['STATUS']);
				unset($params['STATUS']);
			}
		}
	}	
	
}
