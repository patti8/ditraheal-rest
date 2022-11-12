<?php
namespace RegistrasiOnline\V1\Rest\Reservasi;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use DBService\Service as DBService;
use DBService\System;

use RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarService;
use RegistrasiOnline\V1\Rest\Ruangan\RuanganService;
use RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsService;
use RegistrasiOnline\V1\Rest\Pasien\PasienService;
use RegistrasiOnline\V1\Rest\Istansi\IstansiService;
use RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianService;
use RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianService;
use General\V1\Rest\PPK\PPKService;
//Use Pegawai\V1\Rest\LiburNasional\Service as LiburNasionalService;
use \DateTime;
use \DateTimeZone;

class ReservasiService extends DBService
{   
	private $carabayar;
	private $poli;
	private $poli_bpjs;
	private $pasien;
	private $instansi;
	private $ppk;
	private $kip;
	private $libur;
	private $posantrian;
	private $panggil;
	public function __construct() {
		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("reservasi", "regonline"));
		$this->system1 = System::getSysDate($this->table->getAdapter());
		$this->entity = new ReservasiEntity();
		$this->carabayar = new CaraBayarService();
		$this->poli = new RuanganService();
		$this->poli_bpjs = new RefPoliBpjsService();
		$this->pasien = new PasienService();
		$this->instansi = new IstansiService();
		$this->ppk = new PPKService();
		//$this->libur = new LiburNasionalService();
		$this->posantrian = new PosAntrianService();
		$this->panggil = new PanggilAntrianService();
    }
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);
		
		if($this->includeReferences) {
			foreach($data as &$entity) {
				$carabayar = $this->carabayar->load(array('ID' => $entity['CARABAYAR']));
				if(count($carabayar) > 0){
					$entity['REFERENSI']['CARABAYAR'] = $carabayar[0];
				} else {
					$entity['REFERENSI']['CARABAYAR']['DESKRIPSI'] = '-';
				}
				$poli = $this->poli->load(array('ID' => $entity['POLI']));
				if(count($poli) > 0) $entity['REFERENSI']['POLI'] = $poli[0];
				if(count($poli) > 0){
					$entity['REFERENSI']['POLI'] = $poli[0];
				} else {
					$entity['REFERENSI']['POLI']['DESKRIPSI'] = '-';
				}
				
				$poli_bpjs = $this->poli_bpjs->load(array('KDPOLI' => $entity['POLI_BPJS']));
				if(count($poli_bpjs) > 0){
					$entity['REFERENSI']['POLI_BPJS'] = $poli_bpjs[0];
				} else {
					$entity['REFERENSI']['POLI_BPJS']['NMPOLI'] = '-';
				}
				$posantrian = $this->posantrian->load(array('NOMOR' => $entity['POS_ANTRIAN']));
				if(count($posantrian) > 0){
					$entity['REFERENSI']['POS_ANTRIAN'] = $posantrian[0];
				} else {
					$entity['REFERENSI']['POS_ANTRIAN']['DESKRIPSI'] = '-';
				}
				$pasien = $this->pasien->load(array('NORM' => $entity['NORM']));
				if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
				
				$instansi = $this->instansi->load();
				if(count($instansi) > 0) $entity['REFERENSI']['PPK'] = $instansi[0];

				if($entity['STATUS'] == 2){
					$entity['REFERENSI']['STATUS_PANGGIL'] = 2;
				} else {
					if($entity['CARABAYAR'] == 2){
						$panggil = $this->panggil->load(array('NOMOR' => $entity['NO'],'POS' => $entity['POS_ANTRIAN'],'TANGGAL' => $entity['TANGGALKUNJUNGAN'], 'CARA_BAYAR' => 2));
					} else {
						$panggil = $this->panggil->load(array('NOMOR' => $entity['NO'],'POS' => $entity['POS_ANTRIAN'],'TANGGAL' => $entity['TANGGALKUNJUNGAN'], 'CARA_BAYAR' => 1));
					}
					if(count($panggil) > 0){
						$entity['REFERENSI']['STATUS_PANGGIL'] = 1;
					} else {
						$entity['REFERENSI']['STATUS_PANGGIL'] = 0;
					}

				}
			}
		}
		
		return $data;
	}
	public function onCekIdentitas($nik){
		$adapter = $this->table->getAdapter();
		$strLmt = $adapter->query("SELECT p.NAMA, p.NORM, p.TEMPAT_LAHIR, p.TANGGAL_LAHIR FROM master.kartu_identitas_pasien k, master.pasien p
								WHERE p.NORM = k.NORM AND k.JENIS= 1 AND k.NOMOR = ".$nik." LIMIT 1");
		$queryLmt = $strLmt->execute();
		return $queryLmt->current();
	}
	public function getRekapAntrian($data){
		$adapter = $this->table->getAdapter();
		$strLmt = $adapter->query("SELECT 
										p.NMPOLI, COUNT(*) JML_ANTRIAN, SUM(IF(r.STATUS = 2, 1,0)) JML_TERLAYANI, LOCALTIME() TGL_UPDATE
									FROM regonline.reservasi r, regonline.poli_bpjs p
									WHERE p.KDPOLI = r.POLI_BPJS AND r.TANGGALKUNJUNGAN = '".$data->TANGGALKUNJUNGAN."' AND r.POLI_BPJS = '".$data->POLI_BPJS."' AND r.POLI_EKSEKUTIF_BPJS = ".$data->POLI_EKSEKUTIF_BPJS."");
		$queryLmt = $strLmt->execute();
		if(count($queryLmt) > 0){
			$recordData = $queryLmt->current();
			if($recordData['JML_ANTRIAN'] > 0){
				$tglUpdate = date_create($recordData['TGL_UPDATE']);
				$resultData["namapoli"] = $recordData['NMPOLI'];
				$resultData["totalantrean"] = $recordData['JML_ANTRIAN'];
				$resultData["jumlahterlayani"] = $recordData['JML_TERLAYANI'];
				$resultData["lastupdate"] = date_timestamp_get($tglUpdate) * 1000;
				$metadata = array("message" => "Ok","code" => 200);
				$status = true;
			} else {
				$resultData = '';
				$status = false;
				$metadata = array("message" => "List Antrian Masih Kosong","code" => 422);
			}
		} else {
			$resultData = '';
			$status = false;
			$metadata = array("message" => "List Antrian Masih Kosong","code" => 422);
		}
		return array(
			'success' => $status,
			'response' => $resultData,
			'metadata' => $metadata
		);
	}
	public function displayAntrian($data){
		$adapter = $this->table->getAdapter();
		$tglx = date_create($data->TANGGALKUNJUNGAN);
		$tgl = date_format($tglx,"Y-m-d");
		$strLmt = $adapter->query("SELECT 
							MAX(IF(r.STATUS = 2,r.NO,0)) ANTRIAN, SUM(IF(r.STATUS = 1,1,0)) SISA_ANTRIAN, SUM(IF(r.STATUS = 2,1,0)) LEWAT_ANTRIAN, COUNT(*) JML_ANTRIAN, r.POS_ANTRIAN
						FROM regonline.reservasi r
						WHERE  r.TANGGALKUNJUNGAN = '".$tgl."' AND r.POS_ANTRIAN IN (".$data->VIEW_DISPLAY_ANTRIAN.") GROUP BY r.POS_ANTRIAN");
		$results = $strLmt->execute();
		$data = array();
		foreach($results as $row) {
			$data[] = $row;
		}
		return $data;
	}
	public function getKodeBookingOperasi($data){
		$adapter = $this->table->getAdapter();
		$resultData = '';
		$metadata = array("message" => "Module Penjadwalan Operasi Masih Tahap Pengembangan","code" => 422);
		return array(
			'success' => false,
			'response' => $resultData,
			'metadata' => $metadata
		);
	}
	public function getListJadwalOperasi($data){
		$adapter = $this->table->getAdapter();
		$resultData = '';
		$metadata = array("message" => "Module Penjadwalan Operasi Masih Tahap Pengembangan","code" => 422);
		return array(
			'success' => false,
			'response' => $resultData,
			'metadata' => $metadata
		);
	}
	public function listKetersediaanTempatTidur($data){
		$adapter = $this->table->getAdapter();
		$strLmt = $adapter->query("CALL informasi.infoKetersediaanTempatTidur()");
		$results = $strLmt->execute();
		$data = array();
		if(count($results) > 0){
			foreach($results as $row) {
				$data[] = $row;
			}
			$metadata = array("message" => "Data Di Temukan","code" => 200);
			return array(
				'success' => true,
				'response' => $data,
				'metadata' => $metadata
			);
		} else {
			$metadata = array("message" => "Data Tidak Di Temukan","code" => 422);
			return array(
				'success' => false,
				'response' => $data,
				'metadata' => $metadata
			);
		}
	}
	public function simpanAppWeb($data){
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$adapter = $this->table->getAdapter();
		if($data["JENIS"] == 1) {
			$cek = $this->load(array("TANGGALKUNJUNGAN" => $data["TANGGALKUNJUNGAN"],"NORM" => $data["NORM"]));
		} else {
			$cek = $this->load(array("TANGGALKUNJUNGAN" => $data["TANGGALKUNJUNGAN"],"NAMA" => $data["NAMA"],"TANGGAL_LAHIR" => $data["TANGGAL_LAHIR"]));
		}
		
		if(count($cek)>0){
			if($cek[0]["CARABAYAR"] == 2){
				$cb = 2;
			} else {
				$cb = 1;
			}
			$resultData["nomorantrean"] = $cek[0]["POS_ANTRIAN"].''.$cb.'-'.$cek[0]["NO"];
			$resultData["kodebooking"] = $cek[0]["ID"];
			$resultData["jenisantrean"] = 1;
			$tglKnjngn = date_create($cek[0]["TANGGALKUNJUNGAN"].' '.$cek[0]["JAM"]);
			$resultData["estimasidilayani"] = date_timestamp_get($tglKnjngn) * 1000;
			$resultData["tanggalkunjungan"] = $cek[0]["TANGGALKUNJUNGAN"];
			$resultData["jamkunjungan"] = $cek[0]["JAM"];
			$resultData["namapoli"] = $cek[0]["REFERENSI"]["POLI"]["DESKRIPSI"];
			$resultData["namadokter"] = '';
			$resultData["namapasien"] = $cek[0]["NAMA"];
			$metadata = array("message" => "Anda telah mendaftar sebelumnya pada tanggal yang sama","code" => 201);
			$statusProses = false;
			return array(
				'success' => false,
				'response' => $resultData,
				'metadata' => $metadata
			);
		} else {
			if($data['CARABAYAR'] == '2'){
				$cb = 2;
			} else {
				$cb = 1;
			}
			$tglTmp = date_create();
			$tglSkrngx = date_format($tglTmp,'Y-m-d 00:00:00');
			$tglSkrng = date_create($tglSkrngx);
			$tanggalKnjungan = date_create($data['TANGGALKUNJUNGAN'].' 00:00:00');
			$selisih = date_diff($tanggalKnjungan, $tglSkrng);
			$selisihTgl = $selisih->days;
			if($tanggalKnjungan < $tglSkrng){
				$metadata = array("message" => "Tanggal Kunjungan Tidak Boleh Di Bawah Dari Tanggal Sekarang","code" => 422);
				return array(
					'success' => false,
					'response' => '',
					'metadata' => $metadata
				);
			}
			
			if($selisihTgl > 15){
				$metadata = array("message" => "Tanggal Kunjungan Maksimal 15 Hari Dari Tanggal Sekarang","code" => 422);
				return array(
					'success' => false,
					'response' => '',
					'metadata' => $metadata
				);
			}
			$jamBatas = date_create('08:00:00');
			if($data['TANGGALKUNJUNGAN'] == date('Y-m-d')){
				if(date('H:i:s') < $jamBatas){
					$metadata = array("message" => "Maaf, Batas pengambilan antrian hari ini maksimal Pukul ".date_format($jamBatas, 'H:i')."","code" => 422);
					return array(
						'success' => false,
						'response' => '',
						'metadata' => $metadata
					);
				}
			}
			
			$posAntrian = $this->getPosAntrianWeb($data['POLI']);
			$noReservasi = $this->getNomorReservasiBpjs($data['TANGGALKUNJUNGAN']);
			$nextNoAntrian = $this->getNextNoAntrianBpjs($posAntrian,$data['TANGGALKUNJUNGAN'],$cb);
			
			$noAntrian = $nextNoAntrian;
			$this->entity->set('ID', $noReservasi);
			$this->entity->set('POS_ANTRIAN', $posAntrian);
			$this->entity->set('TANGGAL_REF', date('Y-m-d H:i:s'));
			
			$strLmt = $adapter->query("SELECT LIMIT_DAFTAR,DURASI,MULAI FROM regonline.pengaturan WHERE POS_ANTRIAN = '".$posAntrian."' AND STATUS=1 ORDER BY ID DESC LIMIT 1");
			$queryLmt = $strLmt->execute();
			$rowConf = $queryLmt->current();
			
			if($rowConf){
				$limit = $rowConf["LIMIT_DAFTAR"];
				$durasi = $rowConf["DURASI"];
				$jamMulai = date_create($rowConf["MULAI"]);
			} else {
				$limit = 100;
				$durasi = 30;
				$jamMulai = date_create('07:30:00');
			}
			if($nextNoAntrian > $limit){
				$metadata = array("message" => "Kuota Pendaftaran full","code" => 201);
				$statusProses = false;
			} else {
				$tmenit = $nextNoAntrian * $durasi;
				$jamBerikutnya = date_add($jamMulai, date_interval_create_from_date_string(" ".$tmenit."minutes"));
				$this->entity->set('NO', $nextNoAntrian);
				$this->entity->set('JAM', date_format($jamBerikutnya, 'H:i'));
				$insertAntrian = $this->table->insert($this->entity->getArrayCopy());
				if($insertAntrian){
					$loadData = $this->load(array('ID' => $noReservasi));
					$resultData["nomorantrean"] = $loadData[0]['POS_ANTRIAN'].''.$cb.'-'.$loadData[0]['NO'];
					$resultData["kodebooking"] = $loadData[0]['ID'];
					$resultData["jenisantrean"] = 1;
					$tglKnjngn = date_create($loadData[0]["TANGGALKUNJUNGAN"].' '.$loadData[0]["JAM"]);
					$resultData["estimasidilayani"] = date_timestamp_get($tglKnjngn) * 1000;
					$resultData["tanggalkunjungan"] = $loadData[0]["TANGGALKUNJUNGAN"];
					$resultData["jamkunjungan"] = $loadData[0]["JAM"];
					$resultData["namapoli"] = $loadData[0]["REFERENSI"]["POLI"]["DESKRIPSI"];
					$resultData["namadokter"] = '';
					$resultData["namapasien"] = $loadData[0]["NAMA"];
					$resultData["selisih"] = $selisihTgl;
					$resultData["tglk"] = $tanggalKnjungan;
					$resultData["tglS"] = $tglSkrng;
					$metadata = array("message" => "Pendaftaran Berhasil","code" => 200);
					$statusProses = true;
				} else {
					$metadata = array("message" => "Pendaftaran Antrian Gagal","code" => 501);
					$statusProses = false;
				}
				
			}
		}
		return array(
			'success' => $statusProses,
			'response' => $resultData,
			'metadata' => $metadata
		);
	}
	public function simpanAntrianOnsite($data){
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$adapter = $this->table->getAdapter();
		
		//$posAntrian = Generator::getPosAntrianWeb($data['POLI']);
		$posAntrian = $data['POS_ANTRIAN'];
		$noReservasi = $this->getNomorReservasiBpjs($data['TANGGALKUNJUNGAN']);
		$nextNoAntrian = $this->getNextNoAntrianBpjs($posAntrian,$data['TANGGALKUNJUNGAN'],$data['CARABAYAR']);
		
		$noAntrian = $nextNoAntrian;
		$this->entity->set('ID', $noReservasi);
		$this->entity->set('POS_ANTRIAN', $posAntrian);
		$this->entity->set('TANGGAL_REF', date('Y-m-d H:i:s'));
		$this->entity->set('NO', $nextNoAntrian);
		
		$insertAntrian = $this->table->insert($this->entity->getArrayCopy());
		if($insertAntrian){
			$loadData = $this->load(array('ID' => $noReservasi));
			$resultData["nomorantrean"] = $loadData[0]['NO'];
			$resultData["kodebooking"] = $loadData[0]['ID'];
			$resultData["jenisantrean"] = 1;
			$resultData["estimasidilayani"] = '';
			$resultData["tanggalkunjungan"] = $loadData[0]["TANGGALKUNJUNGAN"];
			$resultData["jamkunjungan"] = '';
			$resultData["namapoli"] = '';
			$resultData["namadokter"] = '';
			$metadata = array("message" => "Ambil Antrian Berhasil","code" => 200);
			$statusProses = true;
		} else {
			$metadata = array("message" => "Ambil Antrian Gagal","code" => 501);
			$statusProses = false;
		}

		return array(
			'success' => $statusProses,
			'response' => $resultData,
			'metadata' => $metadata
		);
	}
	public function getTimestamp() {
		$dt = new DateTime(null, new DateTimeZone("UTC"));
		return $dt->getTimestamp();
	}
	public function simpanMobileJkn($data){
		$data = is_array($data) ? $data : (array) $data;		
		$this->entity->exchangeArray($data);
		$adapter = $this->table->getAdapter();
		$tglTmp = date_create();
		$tglSkrngx = date_format($tglTmp,'Y-m-d 00:00:00');
		$tglSkrng = date_create($tglSkrngx);
		$tanggalKnjungan = date_create($data['TANGGALKUNJUNGAN'].' 00:00:00');
		
		
		if(strlen($data['NO_KARTU_BPJS']) != 13){
			$metadata = array("message" => "Nomor Kartu BPJS 13 Karakter","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		if(($data['NO_KARTU_BPJS'] == null) OR ($data['NO_KARTU_BPJS'] == '')){
			$metadata = array("message" => "Nomor Kartu BPJS Tidak Boleh Kosong","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		if(strlen($data['NIK']) != 16){
			$metadata = array("message" => "NIK 16 Karakter","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		if(($data['NIK'] == null) OR ($data['NIK'] == '')){
			$metadata = array("message" => "NIK Tidak Boleh Kosong","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$validTanggal = $this->isValidTanggal($data['TANGGALKUNJUNGAN']);
		
		if(!$validTanggal['success']){
			$metadata = array("message" => $validTanggal['message'],"code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		
		$selisih = date_diff($tanggalKnjungan, $tglSkrng);;
		$selisihTgl = $selisih->days;
		
		if($tanggalKnjungan < $tglSkrng){
			$metadata = array("message" => "Tanggal Kunjungan Tidak Boleh Di Bawah Dari Tanggal Sekarang","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		
		if($selisihTgl > 90){
			$metadata = array("message" => "Tanggal Kunjungan Maksimal 90 Hari Dari Tanggal Sekarang","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		
		if(($data['POLI_BPJS'] == null) OR ($data['POLI_BPJS'] == '')){
			$metadata = array("message" => "Kode Poli Tidak Boleh Kosong","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$poli_bpjs = $this->poli_bpjs->load(array('KDPOLI' => $data['POLI_BPJS']));
		if(count($poli_bpjs) == 0){
			$metadata = array("message" => "Kode Poli Tidak Sesuai Ref BPJS","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$jnsRqst = array(1,2);
		if(!in_array($data['JENIS_REQUEST_BPJS'],$jnsRqst)){
			$metadata = array("message" => "Jenis Request Tidak Sesuai","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		if(!in_array($data['JENIS_REF_BPJS'],$jnsRqst)){
			$metadata = array("message" => "Jenis Referensi Tidak Sesuai","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$jnsPoli = array('0','1');
		if(!in_array($data['POLI_EKSEKUTIF_BPJS'],$jnsPoli)){
			$metadata = array("message" => "Poli Eksekutif Tidak Sesuai","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$strLn = $adapter->query("SELECT * FROM pegawai.libur_nasional WHERE TANGGAL_LIBUR = '".$data["TANGGALKUNJUNGAN"]."' AND STATUS=1 ORDER BY ID DESC LIMIT 1");
		$queryLn = $strLn->execute();
		if(count($queryLn)>0){
			$metadata = array("message" => "Tanggal Tersebut Adalah Hari Libur Poliklinik ","code" => 422);
			return array(
				'success' => false,
				'response' => '',
				'metadata' => $metadata
			);
		}
		$cek = $this->load(array("NO_REF_BPJS" => $data["NO_REF_BPJS"]));
		if(count($cek)>0){
			$resultData["nomorantrean"] = $cek[0]["POS_ANTRIAN"].'-2'.str_pad($cek[0]["NO"],3,'0',STR_PAD_LEFT);
			$resultData["kodebooking"] = $cek[0]["ID"];
			$resultData["jenisantrean"] = $cek[0]["JENIS_REQUEST_BPJS"];
			$tglKnjngn = date_create($cek[0]["TANGGALKUNJUNGAN"].' '.$cek[0]["JAM"]);
			$resultData["estimasidilayani"] = date_timestamp_get($tglKnjngn) * 1000;
			$resultData["namapoli"] = $cek[0]["REFERENSI"]["POLI_BPJS"]["NMPOLI"];
			$resultData["namadokter"] = '';
			$metadata = array("message" => "Maaf No.Rujukan / No.Referensi ini telah terbit No.Antriannya pada Tanggal ".$cek[0]["TANGGALKUNJUNGAN"]." No.Antrian ".$cek[0]["POS_ANTRIAN"].'-'.str_pad($cek[0]["NO"],3,'0',STR_PAD_LEFT),"code" => 422);
			$statusProses = false;
			return array(
				'success' => false,
				'response' => $resultData,
				'metadata' => $metadata
			);
		} else {
			$cb = 2; //Default BPJS
			$posAntrian = $this->getPosAntrianBpjs($data['POLI_BPJS']);
			$noReservasi = $this->getNomorReservasiBpjs($data['TANGGALKUNJUNGAN']);
			$nextNoAntrian = $this->getNextNoAntrianBpjs($posAntrian,$data['TANGGALKUNJUNGAN'],$cb);
			$noAntrian = $nextNoAntrian;
			$this->entity->set('ID', $noReservasi);
			$this->entity->set('POS_ANTRIAN', $posAntrian);
			$this->entity->set('TANGGAL_REF', date('Y-m-d H:i:s'));
			
			$strLmt = $adapter->query("SELECT LIMIT_DAFTAR,DURASI,MULAI,BATAS_JAM_AMBIL FROM regonline.pengaturan WHERE POS_ANTRIAN = '".$posAntrian."' AND STATUS=1 ORDER BY ID DESC LIMIT 1");
			$queryLmt = $strLmt->execute();
			$rowConf = $queryLmt->current();
			
			if($rowConf){
				$limit = $rowConf["LIMIT_DAFTAR"];
				$durasi = $rowConf["DURASI"];
				$jamMulai = date_create($rowConf["MULAI"]);
				$jamBatas = date_create($rowConf["BATAS_JAM_AMBIL"]);
			} else {
				$limit = 100;
				$durasi = 30;
				$jamMulai = date_create('07:30:00');
				$jamBatas = date_create('12:00:00');
			}
			if($nextNoAntrian > $limit){
				$metadata = array("message" => "Kuota Pendaftaran full","code" => 422);
				$resultData = [];
				$statusProses = false;
			} else {
				if($data['TANGGALKUNJUNGAN'] == date('Y-m-d')){
					if(date('H:i:s') < $jamBatas){
						$metadata = array("message" => "Maaf, Batas pengambilan antrian hari ini maksimal Pukul ".date_format($jamBatas, 'H:i')."","code" => 422);
						return array(
							'success' => false,
							'response' => '',
							'metadata' => $metadata
						);
					}
				}
				$tmenit = $nextNoAntrian * $durasi;
				$jamBerikutnya = date_add($jamMulai, date_interval_create_from_date_string(" ".$tmenit."minutes"));
				$this->entity->set('NO', $nextNoAntrian);
				$this->entity->set('JAM', date_format($jamBerikutnya, 'H:i'));
				$insertAntrian = $this->table->insert($this->entity->getArrayCopy());
				if($insertAntrian){
					$loadData = $this->load(array('ID' => $noReservasi));
					$resultData["nomorantrean"] = $loadData[0]['POS_ANTRIAN'].'2-'.str_pad($loadData[0]["NO"],3,'0',STR_PAD_LEFT);
					$resultData["kodebooking"] = $loadData[0]['ID'];
					$resultData["jenisantrean"] = 1;
					$tglKnjngn = date_create($loadData[0]["TANGGALKUNJUNGAN"].' '.$loadData[0]["JAM"]);
					$resultData["estimasidilayani"] = date_timestamp_get($tglKnjngn) * 1000;
					$resultData["namapoli"] = $loadData[0]["REFERENSI"]["POLI_BPJS"]["NMPOLI"];
					$resultData["namadokter"] = '';
					$metadata = array("message" => "Pendaftaran Berhasil","code" => 200);
					$statusProses = true;
				} else {
					$metadata = array("message" => "Pendaftaran Antrian Gagal","code" => 422);
					$statusProses = false;
				}
				
			}
		}
		
		return array(
			'success' => $statusProses,
			'response' => $resultData,
			'metadata' => $metadata
		);
		
	}
	public function isValidTanggal($tanggal) {
		$tglKnjng  = explode('-', $tanggal);
		if (count($tglKnjng) == 3) {
			if (checkdate($tglKnjng[1], $tglKnjng[2], $tglKnjng[0])) {
				return array(
					'success' => true,
					'message' => 'valid'
				);
			} else {
				return array(
					'success' => false,
					'message' => 'Tanggal Tidak Sesuai (Format YYYY-MM-DD)'
				);
			}
		} else {
			return array(
				'success' => false,
				'message' => 'Tanggal Tidak Sesuai (Format YYYY-MM-DD)'
			);
		}
	}
	public function respon($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		
		$nomor = is_numeric($this->entity->get('ID')) ? $this->entity->get('ID') : false;
		
		if($nomor) {
			$_data = $this->entity->getArrayCopy();
			$this->table->update($_data, array("ID" => $nomor));
			$result["NOMOR"] = $nomor;
			$result["PESAN"] = 'Sukses respon Pasien';
			$result["status"] = true;
		} else {
			$result["NOMOR"] = $nomor;
			$result["PESAN"] = 'Gagal respon Pasien';
			$result["status"] = false;
		}
		return $result;
	}
	
	public function simpanErrorReservasi($data, $tgl) {		
		$table = DatabaseService::get('PINISI')->get(new TableIdentifier("error_reservasi", "regonline"));
		$table->insert(array(
			"DATA" => json_encode($data),
			"BATAS_TANGGAL" => $tgl
		));
	}
	
	protected function query($columns, $params, $isCount = false, $orders = array()) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(array('rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')));
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				$select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);
			
			if(isset($params['NOMOR'])) {
				if(!System::isNull($params, 'NOMOR')) {
					$select->where(array("ID" => $params["NOMOR"]));
					unset($params['NOMOR']);
				}
			}
			
			if(isset($params['JENIS'])) {
				if(!System::isNull($params, 'JENIS')) {
					$select->where(array("JENIS" => $params["JENIS"]));
					unset($params['JENIS']);
				}
			}
			
			if(isset($params['POS_ANTRIAN'])) {
				if(!System::isNull($params, 'POS_ANTRIAN')) {
					$select->where(array("POS_ANTRIAN" => $params["POS_ANTRIAN"]));
					unset($params['POS_ANTRIAN']);
				}
			}
			
			if(isset($params['TANGGALKUNJUNGAN'])) {
				if(!System::isNull($params, 'TANGGALKUNJUNGAN')) {
					$select->where(array("TANGGALKUNJUNGAN" => $params["TANGGALKUNJUNGAN"]));
					unset($params['TANGGALKUNJUNGAN']);
				}
			}
			if(isset($params['POLI_EKSEKUTIF_BPJS'])) {
				if(!System::isNull($params, 'POLI_EKSEKUTIF_BPJS')) {
					$select->where(array("POLI_EKSEKUTIF_BPJS" => $params["POLI_EKSEKUTIF_BPJS"]));
					unset($params['POLI_EKSEKUTIF_BPJS']);
				}
			}
			if(isset($params['POLI_BPJS'])) {
				if(!System::isNull($params, 'POLI_BPJS')) {
					$select->where(array("POLI_BPJS" => $params["POLI_BPJS"]));
					unset($params['POLI_BPJS']);
				}
			}
			
			if(!System::isNull($params, 'QUERY')) { 
				$select->where("NAMA LIKE '%".$params['QUERY']."%'");
				unset($params['QUERY']);
			}

			if(!System::isNull($params, 'FILTER_CB')) {
				if($params['FILTER_CB'] == '2'){
					$select->where("CARABAYAR = '2'");
				}
				if($params['FILTER_CB'] == '1'){
					$select->where("CARABAYAR != '2'");
				} 
				
				unset($params['FILTER_CB']);
			}
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function getNomorReservasiBpjs($tglKunjungan) {
		$adapter = $this->table->getAdapter();
		$stmt = $adapter->query('SELECT regonline.generatorReservasi("'.$tglKunjungan.'") ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
	public function getPosAntrianWeb($poli) {
		$adapter = $this->table->getAdapter();
		$stmt = $adapter->query('SELECT regonline.getPosAntrianWeb("'.$poli.'") ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	public function getPosAntrianBpjs($poli) {
		$adapter = $this->table->getAdapter();
		$stmt = $adapter->query('SELECT regonline.getPosAntrianBpjs("'.$poli.'") ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	public function getNextNoAntrianBpjs($posAntrian,$tglKunjungan,$carabayar) {
		$adapter = $this->table->getAdapter();
		$stmt = $adapter->query('SELECT regonline.getNomorAntrian("'.$posAntrian.'","'.$tglKunjungan.'",'.$carabayar.') ID');
		$rst = $stmt->execute();
		$row = $rst->current();
		
		return $row['ID'];
	}
	
}
