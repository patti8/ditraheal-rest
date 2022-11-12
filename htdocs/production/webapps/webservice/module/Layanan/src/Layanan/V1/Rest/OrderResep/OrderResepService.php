<?php
namespace Layanan\V1\Rest\OrderResep;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use General\V1\Rest\Ruangan\RuanganService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Dokter\DokterService;
use Layanan\V1\Rest\OrderDetilResep\OrderDetilResepService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;

class OrderResepService extends Service
{
	private $ruangan;
	private $referensi;
	private $detilrad;
	private $dokter;
	private $kunjungan;

	private $pengguna;
	
	protected $references = [
		'Ruangan' => true,
		'Referensi' => true,
		'Dokter' => true,
		'OrderDetil' => true,
		'Kunjungan' => true
	];
	
    public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\OrderResep\\OrderResepEntity";
		$this->config["entityId"] = "NOMOR";
		$this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("order_resep", "layanan"));
		$this->entity = new OrderResepEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {			
			if($this->references['Ruangan']) $this->ruangan = new RuanganService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Dokter']) $this->dokter = new DokterService();
			if($this->references['OrderDetil']) $this->detilresep = new OrderDetilResepService();
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
		}

		$this->pengguna = new PenggunaService();
    }

	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if($isCreated) {
			$kunjungan = $this->kunjungan->load(['kunjungan.NOMOR' => $entity->get('KUNJUNGAN')]);
			if(count($kunjungan) > 0) {
				Generator::reinitialize();
				$nomor = Generator::generateNoOrderResep($kunjungan[0]['RUANGAN']);
				$this->entity->set('NOMOR', $nomor);
				if($this->entity->get('REF') == '0') $this->entity->set('REF', $nomor);
			}
		}
	}

	protected function onAfterSaveCallback($id, $data) {
		$this->SimpanDetilResep($data, $id);
	}
        
	private function SimpanDetilResep($data, $id) {
		if(isset($data['ORDER_DETIL'])) {
			foreach($data['ORDER_DETIL'] as $tgs) {
				$tgs['ORDER_ID'] = $id;
				$this->detilresep->simpan($tgs);
			}
		}
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Kunjungan']) {
					if(is_object($this->references['Kunjungan'])) {
						$references = isset($this->references['Kunjungan']->REFERENSI) ? (array) $this->references['Kunjungan']->REFERENSI : [];
						$this->kunjungan->setReferences($references, true);
						if(isset($this->references['Kunjungan']->COLUMNS)) $this->kunjungan->setColumns((array) $this->references['Kunjungan']->COLUMNS);
					}
					$kunjungan = $this->kunjungan->load(['kunjungan.NOMOR' => $entity['KUNJUNGAN']]);
					if(count($kunjungan) > 0) $entity['REFERENSI']['KUNJUNGAN'] = $kunjungan[0];
				}
				if($this->references['Ruangan']) {
					if(is_object($this->references['Ruangan'])) {
						$references = isset($this->references['Ruangan']->REFERENSI) ? (array) $this->references['Ruangan']->REFERENSI : [];
						$this->ruangan->setReferences($references, true);
						if(isset($this->references['Ruangan']->COLUMNS)) $this->ruangan->setColumns((array) $this->references['Ruangan']->COLUMNS);
					}
					$ruangan = $this->ruangan->load(['ID' => $entity['TUJUAN']]);
					if(count($ruangan) > 0) $entity['REFERENSI']['TUJUAN'] = $ruangan[0];
				}
				if($this->references['Dokter']) {
					$dokter = $this->dokter->load(['ID' => $entity['DOKTER_DPJP']]);
					if(count($dokter) > 0) $entity['REFERENSI']['DOKTER_DPJP'] = $dokter[0];
				}
				if($this->references['Referensi']) {
					$referensi = $this->referensi->load(['JENIS' => 31,'ID' => $entity['STATUS']]);
					if(count($referensi) > 0) $entity['REFERENSI']['STATUS'] = $referensi[0];
				}
				if(!empty($entity['OLEH'])) {
					$pengguna = $this->pengguna->getPegawai($entity['OLEH']);
					if($pengguna) $entity['REFERENSI']['PETUGAS'] = $pengguna;
				}
			}
		}
		
		return $data;
	}
	
	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['order_resep.STATUS'] = (int) $status;
			unset($params['STATUS']);
		}
		
		if(!System::isNull($params, 'NOMOR')) {
			$params['order_resep.NOMOR'] = $params['NOMOR'];
			unset($params['NOMOR']);
		}
		
		$select->join(
			['kjgn' => new TableIdentifier('kunjungan', 'pendaftaran')],
			'kjgn.NOMOR = KUNJUNGAN',
			[]
		);
		
		$select->join(
			['p' => new TableIdentifier('pendaftaran', 'pendaftaran')],
			'p.NOMOR = kjgn.NOPEN',
			[]
		);
		
		if(!System::isNull($params, 'TANGGAL')) {
			if(!is_array($params["TANGGAL"])) {
				$tanggal = substr($params['TANGGAL'], 0, 10);
				$select->where("order_resep.TANGGAL BETWEEN '".$tanggal." 00:00:00' AND '".$tanggal." 23:59:59'");					
			} else {
				$tanggal = $params["TANGGAL"]["VALUE"];
				$params["order_resep.TANGGAL"] = $tanggal;
			}
			unset($params['TANGGAL']);
		}
		
		if($this->user && $this->privilage) {
			$usr = $this->user;
			$histori = false;
			$join = "par.RUANGAN = order_resep.TUJUAN";
			if(!System::isNull($params, 'HISTORY')) {
				$join = "(par.RUANGAN = order_resep.TUJUAN OR par.RUANGAN = kjgn.RUANGAN)";
				$histori = true;
				unset($params['HISTORY']);
			}
			$select->join(["par" => new Expression(
				"(SELECT DISTINCT par.RUANGAN 
				FROM aplikasi.pengguna_akses_ruangan par
					 INNER JOIN `master`.ruangan r 
			   WHERE par.STATUS = 1 
				 AND par.PENGGUNA = $usr
				 AND r.ID = par.RUANGAN ".
				 (!$histori ? "AND r.JENIS_KUNJUNGAN = 11 " : "")."
				 AND r.JENIS = 5)")],
				 $join,
				[]
			);
		}
	}

	public function kunjunganSudahDiOrderResepkan($kunjungan) {
	    $found = $this->load([
	        "KUNJUNGAN" => $kunjungan,
	        "STATUS" => [1,2]
	    ]);
	    return count($found) > 0;
	}

	public function getKunjungan() {
		return $this->kunjungan;
	}
}