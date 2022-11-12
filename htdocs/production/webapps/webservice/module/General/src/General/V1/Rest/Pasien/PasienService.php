<?php
namespace General\V1\Rest\Pasien;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Db\Sql\Select;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\System;
use DBService\Service;

use General\V1\Rest\KeluargaPasien\KeluargaPasienService;
use General\V1\Rest\KIP\KIPService;
use General\V1\Rest\KAP\KAPService;
use General\V1\Rest\KontakPasien\KontakPasienService;
use General\V1\Rest\TempatLahir\TempatLahirService;
use General\V1\Rest\Referensi\ReferensiService;
use General\V1\Rest\Wilayah\WilayahService;

class PasienService extends Service
{   
	private $keluarga;
	private $kip;
	private $kap;
	private $kontakpasien;
	private $tempatlahir;
	private $referensi;
	private $wilayah;
	
	protected $references = [
		'KeluargaPasien' => true,
		'KIP' => true,
		'KAP' => true,
		'KontakPasien' => true,
		'TempatLahir' => true,
		'Referensi' => true,
		'Wilayah' => true
	];
    
    public function __construct($includeReferences = true, $references = []) {
		$this->config["entityName"] = "General\\V1\\Rest\\Pasien\\PasienEntity";
		$this->config["entityId"] = 'NORM';
		$this->config["autoIncrement"] = true;

		$this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("pasien", "master"));
		$this->entity = new PasienEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['KeluargaPasien']) $this->keluarga = new KeluargaPasienService();
			if($this->references['KIP']) $this->kip = new KIPService();
			if($this->references['KAP']) $this->kap = new KAPService();
			if($this->references['KontakPasien']) $this->kontakpasien = new KontakPasienService();
			if($this->references['TempatLahir']) $this->tempatlahir = new TempatLahirService();
			if($this->references['Referensi']) $this->referensi = new ReferensiService();
			if($this->references['Wilayah']) $this->wilayah = new WilayahService();
		}
    }
    
	public function load($params = [], $columns = ['*'], $orders = []) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['KeluargaPasien']) {
					$kels = $this->keluarga->load(['NORM' => $entity['NORM']]);
					if(count($kels) > 0) $entity['KELUARGA'] = $kels;
				}
				
				if($this->references['KIP']) {
					$kips = $this->kip->load(['NORM' => $entity['NORM']]);
					if(count($kips) > 0) $entity['KARTUIDENTITAS'] = $kips;
				}
				
				if($this->references['KAP']) {
					$kaps = $this->kap->load(['NORM' => $entity['NORM']]);
					if(count($kaps) > 0) $entity['KARTUASURANSI'] = $kaps;
				}

				if($this->references['KontakPasien']) {
					$kontaks = $this->kontakpasien->load(['NORM' => $entity['NORM']]);
					if(count($kontaks) > 0) $entity['KONTAK'] = $kontaks;
				}

				if($this->references['TempatLahir']) {	
					if(is_numeric($entity['TEMPAT_LAHIR'])) {
						$tempatlahir = $this->tempatlahir->load(['ID' => $entity['TEMPAT_LAHIR']]);
						if($tempatlahir['total'] > 0) $entity['REFERENSI']['TEMPATLAHIR'] = $tempatlahir['data'][0];
					}
				}
				
				if($this->references['Referensi']) {
					$ref = $this->referensi->load(['JENIS' => 1, 'ID' => $entity['AGAMA']]);
					if(count($ref) > 0) $entity['REFERENSI']['AGAMA'] = $ref[0];
					$ref = $this->referensi->load(['JENIS' => 2, 'ID' => $entity['JENIS_KELAMIN']]);
					if(count($ref) > 0) $entity['REFERENSI']['JENISKELAMIN'] = $ref[0];
					$ref = $this->referensi->load(['JENIS' => 3, 'ID' => $entity['PENDIDIKAN']]);
					if(count($ref) > 0) $entity['REFERENSI']['PENDIDIKAN'] = $ref[0];
					$ref = $this->referensi->load(['JENIS' => 4, 'ID' => $entity['PEKERJAAN']]);
					if(count($ref) > 0) $entity['REFERENSI']['PEKERJAAN'] = $ref[0];
					$ref = $this->referensi->load(['JENIS' => 5, 'ID' => $entity['STATUS_PERKAWINAN']]);
					if(count($ref) > 0) $entity['REFERENSI']['STATUS_PERKAWINAN'] = $ref[0];
					$ref = $this->referensi->load(['JENIS' => 6, 'ID' => $entity['GOLONGAN_DARAH']]);
					if(count($ref) > 0) $entity['REFERENSI']['GOLONGAN_DARAH'] = $ref[0];
					if(isset($entity['STATUS'])) {
						$statusPasien = $this->referensi->load(['JENIS' => 13, 'ID' => $entity['STATUS']]);
						if(count($statusPasien) > 0) $entity['REFERENSI']['STATUS'] = $statusPasien[0];
					}
				}
				
				if($this->references['Wilayah']) {	
					if(isset($entity['WILAYAH'])) {
						$wilayah = $this->wilayah->load(['ID' => $entity['WILAYAH']]);
						if(count($wilayah) > 0) $entity['REFERENSI']['WILAYAH'] = $wilayah[0];
					}
				}
			}
		}
		
		return $data;
	}
	
	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {
		if($isCreated) {
			if(isset($data['NORM_MANUAL'])) {
				$norm = is_numeric($data['NORM_MANUAL']) ? $data['NORM_MANUAL'] : 0;
				if($norm > 0) $entity->set('NORM', $data['NORM_MANUAL']);
			}
			$this->entity->set('TANGGAL', new \Laminas\Db\Sql\Expression('NOW()'));
		}
	}

	protected function onAfterSaveCallback($id, $data) {
		$this->simpanKeluarga($data, $id);
		$this->simpanKIP($data, $id);
		$this->simpanKontakPasien($data, $id);
	}

	private function simpanKeluarga($data, $norm) {
		if(isset($data['KELUARGA'])) {
			foreach($data['KELUARGA'] as $kel) {
				$kel['NORM'] = $norm;
				$created = empty($kel['ID']) ? true : !is_numeric($kel['ID']);
				$this->keluarga->simpanData($kel, $created);
			}
		}
	}
	
	private function simpanKIP($data, $norm) {
		if(isset($data['KARTUIDENTITAS'])) {
			foreach($data['KARTUIDENTITAS'] as $kartu) {
				$kartu['NORM'] = $norm;				
				$this->kip->simpanData($kartu); 
			}
		}
	}
	
	private function simpanKontakPasien($data, $norm) {
		if(isset($data['KONTAK'])) {
			foreach($data['KONTAK'] as $kontak) {
				$kontak['NORM'] = $norm;
				$this->kontakpasien->simpanData($kontak); 
			}
		}
	}

	protected function queryCallback(Select &$select, &$params, $columns, $orders) {
		if(!System::isNull($params, 'NAMA')) {
			$select->where->like('NAMA', '%'.$params['NAMA'].'%');
			unset($params['NAMA']);
		}
		if(!System::isNull($params, 'ALAMAT')) {
			if(trim($params['ALAMAT']) != '') $select->where->like('ALAMAT', $params['ALAMAT'].'%');
			unset($params['ALAMAT']);
		}
		if(!System::isNull($params, 'TANGGAL_LAHIR')) {
			if(trim($params['TANGGAL_LAHIR']) != '') $select->where->equalTo('TANGGAL_LAHIR', substr($params['TANGGAL_LAHIR'],0,10));
			unset($params['TANGGAL_LAHIR']);
		}
	}

	public function getKIPService() {
		if($this->references['KIP']) return $this->kip;
		return null;
	}
	public function getKontakService() {
		if($this->references['KontakPasien']) return $this->kontakpasien;
		return null;
	}
	public function getKeluargaPasienService() {
		if($this->references['KeluargaPasien']) return $this->keluarga;
		return null;
	}

	public function isValidCustomValidationEntity($data) {
		$eIdentitas = $this->getEntity();
        $sKIP = $this->getKIPService();
        $eKIP = $sKIP ? $sKIP->getEntity() : null;
        $eKontaks = $this->getKontakService();
        $eKontaks = $eKontaks ? $eKontaks->getEntity() : null;
        $sKeluarga = $this->getKeluargaPasienService();
        $eKeluarga = $sKeluarga ? $sKeluarga->getEntity() : null;

        $notValidEntity = $eIdentitas->getNotValidEntity($data, false);
        if(count($notValidEntity) > 0) {
			$result["status"] = 412;
            $result["detail"] = $notValidEntity["messages"];
			return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]); 
        }

        $isBayi = false;
		if(isset($data["IS_BAYI"])) $isBayi = $data["IS_BAYI"] == 1;
        $tdkDikenal = $data["TIDAK_DIKENAL"] == 1;
        $identitas = [
            "TEMPAT_LAHIR", "TANGGAL_LAHIR", "AGAMA",  "JENIS_KELAMIN", "PENDIDIKAN", 
			"PEKERJAAN", "STATUS_PERKAWINAN", "KEWARGANEGARAAN"
        ];
        $kip = ["JENIS", "NOMOR"];
        $alamat = [ "ALAMAT", "RT", "RW", "KODEPOS", "WILAYAH" ];
        $identitas = array_merge($identitas, $alamat);
        $kip = array_merge($kip, $alamat);
        $eIdentitas->setRequiredFields(!$tdkDikenal, $identitas);
        if($tdkDikenal) $eIdentitas->setRequiredFields(true, ["TANGGAL_LAHIR", "ALAMAT"]);
        if($isBayi && !$tdkDikenal) $eIdentitas->setRequiredFields(false, $alamat);
        $notValidEntity = $eIdentitas->getNotValidEntity($data, false);
        if(count($notValidEntity) > 0) {
			$result["status"] = 412;
            $result["detail"] = $notValidEntity["messages"];
			return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
        }

		$validKontak = false;
        if($eKontaks && !empty($data["KONTAK"])) {
			if(is_array($data["KONTAK"])) {
				if(count($data["KONTAK"]) > 0) {
					$validKontak = true;
					if(!$tdkDikenal && !$isBayi) $eKontaks->setRequiredFields(true, ["JENIS", "NOMOR"]);
					$eKontaks->exchangeArray($data["KONTAK"][0]);
					$notValidEntity = $eKontaks->getNotValidEntity($data["KONTAK"][0], false);
					if(count($notValidEntity) > 0) {
						$result["status"] = 412;
						$result["detail"] = $notValidEntity["messages"];
						return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
					}
				}
			}
        }
		if(!$validKontak && !$tdkDikenal && !$isBayi) {
			$result["status"] = 412;
			$result["detail"] = "Kontak tidak boleh kosong";
			return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
		}

		$validKtp = false;
        if($eKIP && !empty($data["KARTUIDENTITAS"])) {
			if(is_array($data["KARTUIDENTITAS"])) {
				if(count($data["KARTUIDENTITAS"]) > 0) {
					$validKtp = true;
					if(!$tdkDikenal) $eKIP->setRequiredFields(true, $kip);
					$eKIP->exchangeArray($data["KARTUIDENTITAS"][0]);
					$notValidEntity = $eKIP->getNotValidEntity($data["KARTUIDENTITAS"][0], false);
					if(count($notValidEntity) > 0) {
						$result["status"] = 412;
						$result["detail"] = $notValidEntity["messages"];
						return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
					}
				}
			}
        }
		if(!$validKtp && !$tdkDikenal && !$isBayi) {
			$result["status"] = 412;
			$result["detail"] = "Kartu Identitas tidak boleh kosong";
			return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
		}

		$validIbu = false;
        if($eKeluarga && !empty($data["KELUARGA"])) {
			if(is_array($data["KELUARGA"])) {
				if(count($data["KELUARGA"]) > 0) {
					$ibu = null;
					foreach($data['KELUARGA'] as $kel) {
						if(isset($kel["SHDK"]) && isset($kel["JENIS_KELAMIN"]))
							if($kel["SHDK"] == 7 && $kel["JENIS_KELAMIN"] == 2) $ibu = $kel;
					}
					if($ibu) {
						$validIbu = true;
						$sKIPKel = $sKeluarga->getKIPKeluargaService();
						$eKIPKel = $sKIPKel ? $sKIPKel->getEntity() : null;
						if($isBayi || !$tdkDikenal) {
							$eKeluarga->setRequiredFields(true, ["SHDK", "NAMA"]);
							$eKeluarga->setTitle("Keluarga Pasien (Ibu)");
							$eKeluarga->exchangeArray($ibu);
							$notValidEntity = $eKeluarga->getNotValidEntity($ibu, false);
							if(count($notValidEntity) > 0) {
								$result["status"] = 412;
								$result["detail"] = $notValidEntity["messages"];
								return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
							}
							if($isBayi) {
								$eKIPKel->setTitle("Kartu Identitas Ibu");
								$eKIPKel->setRequiredFields(true, ["JENIS", "NOMOR"]);
								$validKtp = false;
								if($eKIPKel && !empty($ibu["KARTU_IDENTITAS"])) {
									if(is_array($ibu["KARTU_IDENTITAS"])) {
										if(count($ibu["KARTU_IDENTITAS"]) > 0) {
											$validKtp = true;
											$eKIPKel->exchangeArray($ibu["KARTU_IDENTITAS"][0]);
											$notValidEntity = $eKIPKel->getNotValidEntity($ibu["KARTU_IDENTITAS"][0], false);
											if(count($notValidEntity) > 0) {
												$result["status"] = 412;
												$result["detail"] = $notValidEntity["messages"];
												return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
											}
										}
									}
								}
								if(!$validKtp) {
									$result["status"] = 412;
									$result["detail"] = "Kartu Identitas Ibu (Nomor KTP) tidak boleh kosong";
									return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
								}
							}
						}
					}
				}
			}
        }
		if(!$validIbu && !$tdkDikenal) {
			$result["status"] = 412;
			$result["detail"] = "Data Ibu (Shdk, Jenis Kelamin dan Nama) tidak boleh kosong pada keluarga";
			return new ApiProblem($result["status"], $result["detail"], null, null, ["success" => false]);
		}
        
        if(!empty($data["KARTUIDENTITAS"])) {
            foreach($data["KARTUIDENTITAS"] as $ki) {
                $finds = $sKIP->load([
                    "JENIS" => $ki["JENIS"],
                    "NOMOR" => $ki["NOMOR"]
                ]);
                if(count($finds) > 0) {
                    return new ApiProblem(422, 'Pasien an. '.$data["NAMA"]. " telah terdaftar dengan No.RM: ".$finds[0]["NORM"]); 
                }
            }
        }
    
        if(!empty($data["NAMA"]) 
            && !empty($data["TANGGAL_LAHIR"]) 
            && !empty($data["TEMPAT_LAHIR"])
            && !empty($data["JENIS_KELAMIN"])
            && !empty($data["ALAMAT"])
        ) {
            $params = [
                "NAMA" => $data["NAMA"],
                "TANGGAL_LAHIR" => $data["TANGGAL_LAHIR"],
                "TEMPAT_LAHIR" => $data["TEMPAT_LAHIR"],
                "JENIS_KELAMIN" => $data["JENIS_KELAMIN"],
                "ALAMAT" => $data["ALAMAT"]
            ];
            $finds = $this->load($params);
            if(count($finds) > 0) {
                return new ApiProblem(422, 'Pasien an. '.$data["NAMA"]. " telah terdaftar dengan No.RM: ".$finds[0]["NORM"]); 
            }
        }

		return true;
	}
}
