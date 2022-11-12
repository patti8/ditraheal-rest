<?php
namespace Layanan\V1\Rest\TindakanMedis;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Expression;
use DBService\System;
use DBService\generator\Generator;
use Layanan\V1\Rest\PetugasTindakanMedis\PetugasTindakanMedisService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;
use Aplikasi\V1\Rest\Pengguna\PenggunaService;
use General\V1\Rest\Dokter\DokterService;
use General\V1\Rest\Perawat\PerawatService;

class TindakanMedisService extends Service
{
	private $petugas;
	private $kunjungan;
	private $isCreate = false;
	private $pengguna;
	private $dokter;
	private $perawat;
	
	protected $references = [
		'Kunjungan' => true
	];
	
    public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\TindakanMedis\\TindakanMedisEntity";
		$this->config["autoIncrement"] = false;
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tindakan_medis", "layanan"));
		$this->entity = new TindakanMedisEntity();
		$this->petugas = new PetugasTindakanMedisService();
		$this->pengguna = new PenggunaService();
		$this->dokter = new DokterService();
		$this->perawat = new PerawatService();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;

		if($includeReferences) {			
			if($this->references['Kunjungan']) $this->kunjungan = new KunjunganService();
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
				$results = $this->parseReferensi($entity, "JENIS_TINDAKAN");
				if(count($results) > 0) $entity["REFERENSI"]["JENIS_TINDAKAN"] = $results;
			}
		}
		
		return $data;
	}
        
	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if($isCreated) $entity->set("ID", Generator::generateIdTindakanMedis());
		$this->isCreate = $isCreated;
	}

	protected function onAfterSaveCallback($id, $data) {
		$this->simpanPetugas($data, $id);
		if(!$this->isCreate) return;
		if(!isset($data['PETUGAS_MEDIS'])) {
			if(!empty($data["OLEH"])) {
				$jnskjgn = "";
				if($this->references['Kunjungan']) {
					$kjgns = $this->kunjungan->load([
						"NOMOR" => $data["KUNJUNGAN"]
					]);
					if(count($kjgns) > 0) $jnskjgn = $kjgns[0]["JENIS_KUNJUNGAN"];
				}
				if($jnskjgn == 4) return; // not lab
				$pegawai = $this->pengguna->getPegawai($data["OLEH"]);
				if($pegawai) {
					$tenagamedis = null;
					if($pegawai["PROFESI"] == 4) { // dokter
						$dokter = $this->dokter->load([
							"NIP" => $pegawai["NIP"],
							"STATUS" => 1
						]);
						if(count($dokter) > 0) {
							$tenagamedis = [
								"TINDAKAN_MEDIS" => $id,
								"JENIS" => 1,
								"MEDIS" => $dokter[0]["ID"]			
							];
						}
					}
					if($pegawai["PROFESI"] == 6 || $pegawai["PROFESI"] == 3) { // perawat dan bidan
						$perawat = $this->perawat->load([
							"NIP" => $pegawai["NIP"],
							"STATUS" => 1
						]);
						if(count($perawat) > 0) {
							$jenis = 3;
							if($pegawai["PROFESI"] == 3) $jenis = 5;
							$tenagamedis = [
								"TINDAKAN_MEDIS" => $id,
								"JENIS" => $jenis,
								"MEDIS" => $perawat[0]["ID"]
							];
						}
					}

					if($tenagamedis) $this->petugas->simpanData($tenagamedis, true, false);
				}
			}
		}
	}
	
	private function simpanPetugas($data, $id) {
		if(isset($data['PETUGAS_MEDIS'])) {
			foreach($data['PETUGAS_MEDIS'] as $tgs) {
				$tgs['TINDAKAN_MEDIS'] = $id;
				$this->petugas->simpanData($tgs, !is_numeric($tgs['ID']), false);
			}
		}
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(isset($params["ID"])) {
			$params["tindakan_medis.ID"] = $params["ID"];
			unset($params["ID"]);
		}
		
		if(!System::isNull($params, 'STATUS')) {
			$status = $params['STATUS'];
			$params['tindakan_medis.STATUS'] = $status;
			unset($params['STATUS']);
		}
		
		$select->join(
			['t' => new TableIdentifier('tindakan', 'master')],
			't.ID = TINDAKAN',
			['TINDAKAN_DESKRIPSI' => 'NAMA']
		);

		$select->join(
			['ref' => new TableIdentifier('referensi', 'master')],
			new \Laminas\Db\Sql\Predicate\Expression("ref.JENIS = 74 AND ref.ID = t.JENIS"),			
			['JENIS_TINDAKAN_DESKRIPSI' => 'DESKRIPSI']
		);

		if(!System::isNull($params, 'JENIS_TINDAKAN')) {
			$jenis = $params['JENIS_TINDAKAN'];
			$params['t.JENIS'] = $jenis;
			unset($params['JENIS_TINDAKAN']);
		}
		
		$select->join(
			['u' => new TableIdentifier('pengguna', 'aplikasi')],
			'u.ID = OLEH',
			[]
		);
		
		$select->join(
			['p' => new TableIdentifier('pegawai', 'master')],
			'p.NIP = u.NIP',
			['NAMA_PENGGUNA' => 'NAMA'],
			Select::JOIN_LEFT
		);

		if(!System::isNull($params, 'NORM')) {
			$norm = $params['NORM'];

			$select->join(
				['k' => new TableIdentifier('kunjungan', 'pendaftaran')],
				'k.NOMOR = tindakan_medis.KUNJUNGAN',
				[]
			);
			$select->join(
				['pdftrn' => new TableIdentifier('pendaftaran', 'pendaftaran')],
				'pdftrn.NOMOR = k.NOPEN',
				[]
			);
			
			$select->where('pdftrn.NORM = '.$norm);
			unset($params['NORM']);
		}
	}
}