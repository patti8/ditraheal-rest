<?php
namespace Layanan\V1\Rest\OrderDetilResep;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use General\V1\Rest\Referensi\ReferensiService;
use Inventory\V1\Rest\Barang\BarangService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;

class OrderDetilResepService extends Service
{
	private $tindakan;
	private $referensi;
	private $barang;
	private $hargabarang;
	
    public function __construct() {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_detil_resep", "layanan"));
		$this->entity = new OrderDetilResepEntity();
		$this->referensi = new ReferensiService();
		$this->barang = new BarangService();
		$this->hargabarang = new HargaBarangService();
    }
        
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$order_id = $this->entity->get('ORDER_ID');
		$farmasi = $this->entity->get('FARMASI');		
		$aturan = $this->entity->get('ATURAN_PAKAI');
		if(!is_numeric($aturan)) {
			$aturan = trim($aturan);
			if(str_replace(" ", "", $aturan) != '') { 				
				// cari deskripsi jika sudah ada
				$founds = $this->referensi->load([
					"JENIS" => 41,
					"DESKRIPSI" => $aturan
				]);
				$id = null;
				if(count($founds) == 0) {
					$aturanrow = $this->referensi->load([
						"JENIS" => 41
					]);	
					if(count($aturanrow) > 999 ) { //Batasi Hanya 1000 Row Referensi Aturan Pakai
						$this->entity->set('ATURAN_PAKAI', $aturan);
					} else {
						$ref = $this->referensi->simpan([
							"JENIS" => 41,
							"DESKRIPSI" => $aturan
						]);
						$id = $ref['data']['ID'];
						$this->entity->set('ATURAN_PAKAI', $id);
					}
				} else {
					$id = $founds[0]["ID"];
					$this->entity->set('ATURAN_PAKAI', $id);
				}
			}
		}
		$cek = $this->table->select(["ORDER_ID" => $order_id, "FARMASI" => $farmasi])->toArray();
		if(count($cek) > 0) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, ["ORDER_ID" => $order_id, "FARMASI" => $farmasi]);
		} else {
			$_data = $this->entity->getArrayCopy();
			$this->table->insert($_data);
		}
		
		return $this->load(['order_detil_resep.ORDER_ID' => $order_id]);
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$isId = is_numeric($entity['ATURAN_PAKAI']) ? $entity['ATURAN_PAKAI'] : false;
			if($isId) {
				$aturan = $this->referensi->load(['ID' => $entity['ATURAN_PAKAI'], 'JENIS'=>41]);
				if(count($aturan) > 0) $entity['REFERENSI']['ATURAN_PAKAI'] = $aturan[0];
			}
			
			$jenisresep = $this->referensi->load(['ID' => $entity['RACIKAN'], 'JENIS'=>47]);
			if(count($jenisresep) > 0) $entity['REFERENSI']['RACIKAN'] = $jenisresep[0];
			
			$farmasi = $this->barang->load(['ID' => $entity['FARMASI']]);
			if(count($farmasi) > 0) $entity['REFERENSI']['FARMASI'] = $farmasi[0];
			
			$hargabarang = $this->hargabarang->load(['BARANG' => $entity['FARMASI'], "STATUS" => 1]);
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
			
			$petunjuk = $this->referensi->load(array('ID' => $entity['PETUNJUK_RACIKAN'], 'JENIS'=>84));
			if(count($petunjuk) > 0) $entity['REFERENSI']['PETUNJUK_RACIKAN'] = $petunjuk[0];
		}
		
		return $data;
	}
}