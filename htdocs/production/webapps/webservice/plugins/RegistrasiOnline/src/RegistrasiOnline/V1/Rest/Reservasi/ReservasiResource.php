<?php
namespace RegistrasiOnline\V1\Rest\Reservasi;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Json\Json;
use DBService\Resource;
class ReservasiResource extends Resource
{
	private $refbpjs;
	
    public function __construct($service) {
		parent::__construct();
		$this->authType = self::AUTH_TYPE_LOGIN_OR_TOKEN;
		$this->service = new ReservasiService();
		
		$config = $service->get('Config');
		$this->config = $config['services']['SIMpelService'];
		$this->config = $this->config['plugins']['BPJS'];
		
	}
	/**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
	private function validateRequest($result) {
		try {
			$result = (array) Json::decode($result);			
			$data = isset($result['data']) ? $result['data'] : null;
			$api = null;
			if($data) {
				if($data->metadata) {					
					if($data->metadata->code == 200 && (trim($data->metadata->message) == 'OK' || trim($data->metadata->message) == 'OK' || trim($data->metadata->message) == 'Sukses')) {						
						$data = (array) $data->response;
					} else {
						$api = new ApiProblem(412, $data->metadata->message.'('.$data->metadata->code.')');
						$data = $api->toArray();
					}
				} else {
					$api = new ApiProblem(503, 'Metadata is null');
					$data = $api->toArray();
				}
			} else { // if data is null
				$api = new ApiProblem(503, 'Data is null');
				$data = $api->toArray();
			}
		} catch(\Exception $e) {
			$api = new ApiProblem(500, $result);
			$data = $api->toArray();
			$this->response->setStatusCode($api->__get('status'));
		}
		
		return $data;
	}
	
	public function cariPesertaAction($nomor,$tgl) 
	{
		$data = $this->validateRequest($this->cariIdentitasPesertaBpjs('peserta/'.$nomor."?tglSEP=".$tgl));
		if(isset($data['status'])) {
			return array(
				'success' => false,
				'data' => $data
			);
		} else {
			return array(
				'success' => true,
				'data' => $data['peserta']
			);
		}
	}
	protected function cariIdentitasPesertaBpjs($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
        $curl = curl_init();
        $url = ($url == '' ? $this->config["url"] : $url);
        curl_setopt($curl, CURLOPT_URL, $url."/".$action);
        curl_setopt($curl, CURLOPT_HEADER, false);
		$headers = array(
			"Content-type: application/json",
			"Content-length: ".strlen($data)
		);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($curl);
        curl_close($curl);
        file_put_contents("logs/log.txt", $result);
        if($data !== "") file_put_contents("logs/post_data_log.txt", $data);
        file_put_contents("logs/url.txt", $url."/".$action);
        file_put_contents("logs/headers.txt", json_encode($headers));
        
        return $result;
	}
    public function create($data)
    {
		if(isset($data->GET_REKAP)){
			return $this->service->getRekapAntrian($data);
		} else if(isset($data->GET_KODE_OPERASI)){
			return $this->service->getKodeBookingOperasi($data);
		} else if(isset($data->GET_LIST_JADWAL_OPERASI)){
			return $this->service->getListJadwalOperasi($data);
		} else if(isset($data->VIEW_KETERSEDIAAN_TEMPAT_TIDUR)){
			return $this->service->listKetersediaanTempatTidur($data);
		} else {
			$jenisAplikasi = isset($data->JENIS_APLIKASI) ? $data->JENIS_APLIKASI : 1;
			if($jenisAplikasi == 2){
				$tgl = date('Y-m-d');
				$cekPeserta = $this->cariPesertaAction($data->NO_KARTU_BPJS,$tgl);
				if($cekPeserta['success']){
					$record = $cekPeserta['data'];
					$data->NORM = 0;
					$data->NAMA = $record->nama;
					$data->TEMPAT_LAHIR = '-';
					$data->TANGGAL_LAHIR = $record->tglLahir;
					$data->JENIS = 2;
					return $this->service->simpanMobileJkn($data);
				} else {
					$data->NORM = 0;
					$data->NAMA = '-';
					$data->TEMPAT_LAHIR = '-';
					$data->TANGGAL_LAHIR = '0000-00-00';
					$data->JENIS = 2;
					return $this->service->simpanMobileJkn($data);
				}
				/*
				//Di Non Aktifkan Sementara Untuk Bridging Dukcapil
				$cekIdentitas = $this->cariPesertaAction($data->NIK);
				if($cekIdentitas){
					$data->NORM = $cekIdentitas['NORM'];
					$data->NAMA = $cekIdentitas['NAMA'];
					$data->TEMPAT_LAHIR = $cekIdentitas['TEMPAT_LAHIR'];
					$data->TANGGAL_LAHIR = $cekIdentitas['TANGGAL_LAHIR'];
					$data->JENIS = 1;
					return $this->service->simpanMobileJkn($data);
				} else {
					$tgl = date('Y-m-d');
					$cekPeserta = $this->cariPesertaAction($data->NO_KARTU_BPJS,$tgl);
					if($cekPeserta['success']){
						$record = $cekPeserta['data'];
						$data->NORM = 0;
						$data->NAMA = $record->nama;
						$data->TEMPAT_LAHIR = '-';
						$data->TANGGAL_LAHIR = $record->tglLahir;
						$data->JENIS = 2;
						return $this->service->simpanMobileJkn($data);
					} else {
						$data->NORM = 0;
						$data->NAMA = '-';
						$data->TEMPAT_LAHIR = '-';
						$data->TANGGAL_LAHIR = '0000-00-00';
						$data->JENIS = 2;
						return $this->service->simpanMobileJkn($data);
					}
				}
				*/
			} else if($jenisAplikasi == 5){ //Antrian Onsite
				return $this->service->simpanAntrianOnsite($data);
			} else {
				//return $this->service->simpan($data);
				return $this->service->simpanAppWeb($data);
			}
		}
		
		//return 1;
		//return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
		if(isset($params->REKAP_ANTRIAN_POLI)){
			return array(
				"success" => true,
				"response" => '',
				"metadata" => ''
			);
		} else if(isset($params->VIEW_DISPLAY_ANTRIAN)){
			$data = $this->service->displayAntrian($params);	
			return array(
				"success" => count($data) > 0 ? true : false,
				"total" => count($data),
				"data" => $data
			);
		} else if(isset($params->VIEW_KETERSEDIAAN_TEMPAT_TIDUR)){
			$data = $this->service->listKetersediaanTempatTidur($params);
			return array(
				"success" => count($data) > 0 ? true : false,
				"total" => count($data),
				"data" => $data
			);
		} else {
			$total = $this->service->getRowCount($params);
			$data = $this->service->load($params, array('*'), array('NO'=>'ASC'));	
			//$data = $this->service->load($params);	
			
			return array(
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data
			);
		}
		//return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return $this->service->respon($data);
		//return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
