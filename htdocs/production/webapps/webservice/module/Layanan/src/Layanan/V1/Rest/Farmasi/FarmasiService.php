<?php
namespace Layanan\V1\Rest\Farmasi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use Inventory\V1\Rest\Barang\BarangService;
use General\V1\Rest\Referensi\ReferensiService;
use Inventory\V1\Rest\HargaBarang\HargaBarangService;
use Layanan\V1\Rest\ReturFarmasi\ReturFarmasiService;

class FarmasiService extends Service
{
	private $barang;
	private $referensi;
	private $hargabarang;
	private $returfarmasi;
	
    public function __construct() {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\Farmasi\\FarmasiEntity";
		$this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("farmasi", "layanan"));
		$this->entity = new FarmasiEntity();
		
		$this->barang = new BarangService();
		$this->referensi = new ReferensiService();
		$this->hargabarang = new HargaBarangService(false);
		$this->returfarmasi = new ReturFarmasiService();
    }

	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if(!System::isNull($data, 'ATURAN_PAKAI')){
			$aturan = is_numeric($entity->get('ATURAN_PAKAI')) ? $entity->get('ATURAN_PAKAI') : false;
		
			if(!$aturan){
				$ref = $this->referensi->simpan(["JENIS" => 41, "DESKRIPSI" => $entity->get('ATURAN_PAKAI')]);
				$entity->set('ATURAN_PAKAI', $ref['data']['ID']);
			}
		}
		if($isCreated) $entity->set("ID", Generator::generateIdFarmasi());
	}
        
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		
		foreach($data as &$entity) {
			$barang = $this->barang->load(['ID' => $entity['FARMASI']]);
			if(count($barang) > 0) $entity['REFERENSI']['FARMASI'] = $barang[0];
			if(($entity['STATUS'] == 2) || ($entity['STATUS'] == 3)) {
				$idFarmasi = $entity['ID'];
				$adapter = $this->table->getAdapter();
				$stmt = $adapter->query("SELECT TARIF FROM pembayaran.rincian_tagihan WHERE REF_ID='$idFarmasi' AND JENIS = 4 LIMIT 1");
				$rst = $stmt->execute();
				$row = $rst->current();
				$hrgTagihan = !empty($row["TARIF"]) ? $row["TARIF"] : 0;
				$entity['REFERENSI']['FARMASI']['REFERENSI']['HARGA']['HARGA_JUAL'] = $hrgTagihan;
			}
			$status = $this->referensi->load(['ID' => $entity['STATUS'], 'JENIS'=>40]);
			if(count($status) > 0) $entity['REFERENSI']['STATUS'] = $status[0];
			
			$isId = is_numeric($entity['ATURAN_PAKAI']) ? $entity['ATURAN_PAKAI'] : false;
			if($isId) {
				$aturan = $this->referensi->load(['ID' => $entity['ATURAN_PAKAI'], 'JENIS'=>41]);
				if(count($aturan) > 0) $entity['REFERENSI']['ATURAN_PAKAI'] = $aturan[0];
			}
			
			$hargabarang = $this->hargabarang->load(['BARANG' => $entity['FARMASI'], "STATUS" => 1]);
			if(count($hargabarang) > 0) $entity['REFERENSI']['HARGA_BARANG'] = $hargabarang[0];
			
			$jenisresep = $this->referensi->load(['ID' => $entity['RACIKAN'], 'JENIS'=>47]);
			if(count($jenisresep) > 0) $entity['REFERENSI']['RACIKAN'] = $jenisresep[0];

			$petunjuk = $this->referensi->load(['ID' => $entity['PETUNJUK_RACIKAN'], 'JENIS'=>84]);
			if(count($petunjuk) > 0) $entity['REFERENSI']['PETUNJUK_RACIKAN'] = $petunjuk[0];

			$returfarmasi = $this->returfarmasi->load(['ID_FARMASI' => $entity['ID']]);
			if(count($returfarmasi) > 0) $entity['REFERENSI']['RETUR'] = $returfarmasi;
			if(isset($params['GET_STOK_RUANGAN'])){
				$getStok = $this->getStokRuangan($entity['KUNJUNGAN'],$entity['FARMASI']);
				if($getStok){
					$entity['REFERENSI']['STOK_RUANGAN'] = $getStok;
					$entity['REFERENSI']['STATUS_MAPPING'] = 1;
				} else {
					$entity['REFERENSI']['STOK_RUANGAN'] = 0;
					$entity['REFERENSI']['STATUS_MAPPING'] = 0;
				}
			}
			if(isset($params['GET_BATAS_LYN'])){
				$getBatas = $this->getTglBatasLayanan($params['GET_BATAS_LYN'],$entity['FARMASI'],$entity['TANGGAL']);
				if($getBatas){
					$entity['REFERENSI']['BATAS_LAYANAN'] = $getBatas;
					$entity['REFERENSI']['STATUS_VALID_LYN'] = 0;
				} else {
					$entity['REFERENSI']['BATAS_LAYANAN'] = 0;
					$entity['REFERENSI']['STATUS_VALID_LYN'] = 1;
				}
			}
			if($entity['STATUS'] == 1){
				$idFarmasi = $entity['ID'];
				$adapter = $this->table->getAdapter();
				$stmt = $adapter->query("
					SELECT IF(SUM(l.JUMLAH) IS NULL,0,SUM(l.JUMLAH)) JML_LYN
					  FROM layanan.bon_sisa_farmasi b, layanan.layanan_bon_sisa_farmasi l
					 WHERE l.REF = b.ID 
					   AND l.STATUS = 1 
					   AND b.REF = '$idFarmasi' 
					   AND b.STATUS = 1
				");
				$rst = $stmt->execute();
				$row = $rst->current();
				$entity['REFERENSI']['STATUS_LYN_BON_SISA'] = $row["JML_LYN"];;
			}
		}
		
		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		$norm = isset($params['NORM']) ? $params['NORM'] : '';
			
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['farmasi.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		if($norm != ''){
			$select->join(
				['k' => new TableIdentifier('kunjungan', 'pendaftaran')],
				'k.NOMOR = farmasi.KUNJUNGAN',
				['NOPEN']
			);
			
			$select->join(
				['p' => new TableIdentifier('pendaftaran', 'pendaftaran')],
				'p.NOMOR = k.NOPEN',
				['NORM']
			);
		}
		
		if(isset($params['QUERY'])) {
			if(!System::isNull($params, 'QUERY')) {
				$select->join(
					['d' => new TableIdentifier("barang", "inventory")], 
					"farmasi.FARMASI = d.ID", []
				);
				$select->where("d.NAMA LIKE '%".$params['QUERY']."%'");
				unset($params['QUERY']);
			}
		}
		if(!System::isNull($params, 'GET_STOK_RUANGAN')) {
			unset($params['GET_STOK_RUANGAN']);
		}
		if(!System::isNull($params, 'GET_BATAS_LYN')) {
			unset($params['GET_BATAS_LYN']);
		}
	}
	
	private function getStokRuangan($kunjungan,$barang) {
		$adapter = $this->table->getAdapter();
		$strLmt = $adapter->query("
			SELECT br.STOK
			  FROM pendaftaran.kunjungan k, inventory.barang_ruangan br
			 WHERE br.BARANG = '".$barang."' 
			   AND br.STATUS = 1 
			   AND br.RUANGAN = k.RUANGAN 
			   AND k.NOMOR = '".$kunjungan."'  LIMIT 1");
		$queryLmt = $strLmt->execute();
		if(count($queryLmt) > 0) {
			$rowLmt = $queryLmt->current();
			return $rowLmt['STOK'];
		} 
			
		return false;
	}
	private function getTglBatasLayanan($norm,$barang,$tanggal) {
		$tgllayanan = date_create($tanggal);
		$tgl = date_format($tgllayanan,"Y-m-d");
		$adapter = $this->table->getAdapter();
		$strLmt = $adapter->query("
			SELECT date_format(t.TANGGAL,'%d-%m-%Y') TANGGGAL 
			  FROM layanan.batas_layanan_obat t
			 WHERE t.NORM = '".$norm."' 
			   AND t.FARMASI = '".$barang."' 
			   AND t.TANGGAL > '".$tgl."' 
			   AND t.STATUS = 1 
			 ORDER BY t.TANGGAL DESC 
			 LIMIT 1");
		$queryLmt = $strLmt->execute();
		if(count($queryLmt) > 0){
			$rowLmt = $queryLmt->current();
			return $rowLmt['TANGGGAL'];
		}

		return false;
	}
}