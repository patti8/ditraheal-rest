<?php
namespace Layanan\V1\Rest\StatusHasilPemeriksaan;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\System;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "Layanan\\V1\\Rest\\StatusHasilPemeriksaan\\StatusHasilPemeriksaanEntity";		
		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("status_hasil_pemeriksaan", "layanan"));
		$this->entity = new StatusHasilPemeriksaanEntity();
	}

	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				$results = $this->parseReferensi($entity, "tindakan");
				if(count($results) > 0) $entity["REFERENSI"]["TINDAKAN"] = $results;
				$results = $this->parseReferensi($entity, "kunjungan");
				if(count($results) > 0) $entity["REFERENSI"]["KUNJUNGAN"] = $results;
				$results = $this->parseReferensi($entity, "pendaftaran");
				if(count($results) > 0) $entity["REFERENSI"]["PENDAFTARAN"] = $results;
				$results = $this->parseReferensi($entity, "pasien");
				if(count($results) > 0) $entity["REFERENSI"]["PASIEN"] = $results;
				$results = $this->parseReferensi($entity, "ruangan");
				if(count($results) > 0) $entity["REFERENSI"]["RUANGAN"] = $results;
				$results = $this->parseReferensi($entity, "pengirim");
				if(count($results) > 0) $entity["REFERENSI"]["RUANGAN_PENGIRIM"] = $results;
				$results = $this->parseReferensi($entity, "status_hasil");
				if(count($results) > 0) $entity["REFERENSI"]["STATUS_HASIL"] = $results;
				$results = $this->parseReferensi($entity, "status_pengiriman");
				if(count($results) > 0) $entity["REFERENSI"]["STATUS_PENGIRIMAN_HASIL"] = $results;
			}	
		}

		return $data;
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		$jenis = 0;
		if(!System::isNull($params, 'JENIS')) {
			$jenis = $params['JENIS'];
			$params['status_hasil_pemeriksaan.JENIS'] = $jenis;
			unset($params['JENIS']);
		}
		
		$awal = $akhir = "";
		if(!System::isNull($params, 'PERIODE_AWAL')) {
			$awal = $params['PERIODE_AWAL'];
			unset($params['PERIODE_AWAL']);
		}
		if(!System::isNull($params, 'PERIODE_AKHIR')) {
			$akhir = $params['PERIODE_AKHIR'];
			unset($params['PERIODE_AKHIR']);
		}
		if(!System::isNull($params, 'GROUP_PEMERIKSAAN')) {
			$group = $params['GROUP_PEMERIKSAAN'];
			unset($params['GROUP_PEMERIKSAAN']);
		}

		if(!System::isNull($params, 'ID')) {
			$id = $params['ID'];
			$select->where("status_hasil_pemeriksaan.ID = ".$id);
			unset($params['ID']);
		}

		$select->join(
			["tm" => new TableIdentifier('tindakan_medis', 'layanan')],
			new \Laminas\Db\Sql\Predicate\Expression("tm.ID = status_hasil_pemeriksaan.TINDAKAN_MEDIS_ID AND tm.STATUS = 1"),
			[]
		);
		$select->join(
			["ref1" => new TableIdentifier('referensi', 'master')],
			new \Laminas\Db\Sql\Predicate\Expression("ref1.ID = status_hasil_pemeriksaan.STATUS_HASIL AND ref1.JENIS = 208"),
			['status_hasil_ID' => 'ID', 'status_hasil_DESKRIPSI' => 'DESKRIPSI', 'status_hasil_CONFIG' => 'CONFIG']
		);
		$select->join(
			["ref2" => new TableIdentifier('referensi', 'master')],
			new \Laminas\Db\Sql\Predicate\Expression("ref2.ID = status_hasil_pemeriksaan.STATUS_PENGIRIMAN_HASIL AND ref2.JENIS = 209"),
			['status_pengiriman_ID' => 'ID', 'status_pengiriman_DESKRIPSI' => 'DESKRIPSI', 'status_pengiriman_CONFIG' => 'CONFIG']
		);
		$select->join(
			["t" => new TableIdentifier('tindakan', 'master')],
			"t.ID = tm.TINDAKAN",
			['tindakan_NAMA' => 'NAMA']
		);
		$select->join(
			["k" => new TableIdentifier('kunjungan', 'pendaftaran')],
			new \Laminas\Db\Sql\Predicate\Expression("k.NOMOR = tm.KUNJUNGAN AND k.STATUS IN (1, 2)"),
			['kunjungan_NOMOR' => 'NOMOR', 'kunjungan_MASUK' => 'MASUK']
		);
		$select->join(
			["p" => new TableIdentifier('pendaftaran', 'pendaftaran')],
			"p.NOMOR = k.NOPEN",
			['pendaftaran_NOMOR' => 'NOMOR', 'pendaftaran_TANGGAL' => 'TANGGAL']
		);
		$select->join(
			["ps" => new TableIdentifier('pasien', 'master')],
			"ps.NORM = p.NORM",
			[
				'pasien_NORM' => 'NORM',
				'pasien_GELAR_DEPAN' => 'GELAR_DEPAN',
				'pasien_NAMA' => 'NAMA',
				'pasien_GELAR_BELAKANG' => 'GELAR_BELAKANG',
				'pasien_TANGGAL_LAHIR' => 'TANGGAL_LAHIR'
			]
		);
		$select->join(
			["r" => new TableIdentifier('ruangan', 'master')],
			"r.ID = k.RUANGAN",
			['ruangan_DESKRIPSI' => 'DESKRIPSI']
		);
		$ordername = "";
		if($jenis == 7) $ordername = "order_rad";
		if($jenis == 8) $ordername = "order_lab";
		if($ordername != "") {
			$select->join(
				["o" => new TableIdentifier($ordername, 'layanan')],
				new \Laminas\Db\Sql\Predicate\Expression("o.NOMOR = k.REF"),
				[],
				Select::JOIN_LEFT
			);
			$select->join(
				["ka" => new TableIdentifier('kunjungan', 'pendaftaran')],
				new \Laminas\Db\Sql\Predicate\Expression("ka.NOMOR = o.KUNJUNGAN AND ka.STATUS IN (1, 2)"),
				[]
			);
			$select->join(
				["ra" => new TableIdentifier('ruangan', 'master')],
				"ra.ID = ka.RUANGAN",
				['pengirim_DESKRIPSI' => 'DESKRIPSI']
			);
		}

		if($jenis == 7) {
			$select->join(
				["hr" => new TableIdentifier("hasil_rad", 'layanan')],
				new \Laminas\Db\Sql\Predicate\Expression("hr.TINDAKAN_MEDIS = tm.ID"),
				['KRITIS'],
				Select::JOIN_LEFT
			);
		}

		if($jenis == 8) {
			$select->join(
				["hr" => new TableIdentifier("hasil_lab", 'layanan')],
				new \Laminas\Db\Sql\Predicate\Expression("hr.TINDAKAN_MEDIS = tm.ID"),
				[],
				Select::JOIN_LEFT
			);
			$select->join(
				["nk" => new TableIdentifier("nilai_kritis_lab", 'layanan')],
				new \Laminas\Db\Sql\Predicate\Expression("nk.HASIL_LAB = hr.ID"),
				["KRITIS" => new \Laminas\Db\Sql\Predicate\Expression("IF(nk.ID IS NULL, 0, 1)")],
				Select::JOIN_LEFT
			);
		}

		if(!System::isNull($params, 'QUERY')) {
			$query = $params['QUERY'];
			$sql = new \Laminas\Db\Sql\Predicate\Like("ps.NAMA", $query."%");
			if(is_numeric($query)) $sql = "ps.NORM = ".$query;
			$select->where($sql);
			unset($params['QUERY']);
		}

		if($awal != "" && $akhir != "") {
			$select->where(new \Laminas\Db\Sql\Predicate\Between("p.TANGGAL", $awal." 00:00:00", $akhir." 23:59:59"));
		}
	}
}