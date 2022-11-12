<?php
namespace RegistrasiOnline\V1\Rpc\Plugins;

//use Laminas\Mvc\Controller\AbstractActionController;
use RegistrasiOnline\RPCResource;
use DBService\Service;
use Laminas\Json\Json;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use RegistrasiOnline\Token;
use \DateTime;
use \DateTimeZone;

class PluginsController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_NOT_SECURE;
    public function __construct($controller) {
		$this->config = $controller->get('Config');
		$this->config = $this->config['services']['SIMpelService'];
		$this->config = $this->config['plugins']['RegOnline'];
		$this->jenisBridge = 1;
		$this->servicex = new Service();
    }
    public function getTokenAction()
    {
		
		$method = $this->request->getmethod();
		if($method == 'POST'){
			$data = $this->getPostData($this->getRequest());
			$datax = array(
				"X_ID"=>$data['username'],
				"X_PASS"=>$data['password'],
			);
			$tkn = new Token(null);
			$timestamp = $tkn->getTimestamp();
			$getToken = $tkn->generateTokenX($datax, $timestamp);
			if($getToken['success']){
				$record['token'] = $tkn->generateToken($datax, $timestamp);
				$metadata = array("message" => "Ok","code" => 200);
				$result = array(
					"response" => $record,
					"metadata" => $metadata
				);
			} else {
				$record['token'] = '0';
				$metadata = array("message" => "User / Password Tidak Valid","code" => 500);
				$result = array(
					"response" => $record,
					"metadata" => $metadata
				);
			}
		} else {
			$metadata = array("message" => "The GET method has not been defined","code" => 405);
			$result = array(
				"response" => '',
				"metadata" => $metadata
			);
		}
		return $result;
    }
	public function getInstansiAction()
    {
		$method = $this->request->getmethod();
		if($method == 'GET'){
			$result = $this->sendRequestRegOnline('istansi',$method);
			$result = (array) Json::decode($result);
		} else {
			$metadata = array("message" => "The POST method has not been defined","code" => 405);
			$result = array(
				"response" => '',
				"metadata" => $metadata
			);
		}
		return $result;
    }
	public function getCaraBayarAction()
    {
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline('carabayar',$method);
		$result = (array) Json::decode($result);
		return $result;
    }
	public function getRuanganAction()
    {
		$method = $this->request->getmethod();
		if($method == 'GET'){
			$result = $this->sendRequestRegOnline('ruangan',$method);
			$result = (array) Json::decode($result);
		} else {
			$metadata = array("message" => "The POST method has not been defined","code" => 405);
			$result = array(
				"response" => '',
				"metadata" => $metadata
			);
		}
		return $result;
    }
	public function getJenisPasienAction()
    {
		$method = $this->request->getmethod();
		if($method == 'GET'){
			$result = $this->sendRequestRegOnline('jenispasien',$method);
			$result = (array) Json::decode($result);
		} else {
			$metadata = array("message" => "The POST method has not been defined","code" => 405);
			$result = array(
				"response" => '',
				"metadata" => $metadata
			);
		}
		return $result;
    }
	public function getPasienAction()
    {
		$params = (array) $this->getRequest()->getQuery();
		if(count($params) > 0){
			$query = count($params) > 0 ? "?".http_build_query($params) : "";
		} else {
			$data = (array) $this->getPostData($this->getRequest());
			$query = count($data) > 0 ? "?".http_build_query($data) : "";
			
		}
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("pasien".$query,$method);
		$result = (array) Json::decode($result);
		return $result;
    }
	public function getIdentitasPasienAction()
    {
		$data = (array) $this->getPostData($this->getRequest());
		$query = count($data) > 0 ? "?".http_build_query($data) : "";
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("pasien".$query,'GET');
		$result = (array) Json::decode($result);
		return $result;
    }
	public function createAntrianAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		$record = array(
			"NO_KARTU_BPJS" => isset($getRecord['nomorkartu']) ? $getRecord['nomorkartu'] : '0',
			"NIK" => isset($getRecord['nik']) ? $getRecord['nik'] : '0',
			"NORM" => isset($getRecord['nomorrm']) ? $getRecord['nomorrm'] : '0',
			"CONTACT" => isset($getRecord['notelp']) ? $getRecord['notelp'] : '0',
			"POLI_BPJS" => isset($getRecord['kodepoli']) ? $getRecord['kodepoli'] : '0',
			"POLI_EKSEKUTIF_BPJS" => isset($getRecord['polieksekutif']) ? $getRecord['polieksekutif'] : '0',
			"CARABAYAR" => isset($getRecord['caraBayar']) ? $getRecord['caraBayar'] : '2',
			"NO_REF_BPJS" => isset($getRecord['nomorreferensi']) ? $getRecord['nomorreferensi'] : '0',
			"JENIS_REF_BPJS" => isset($getRecord['jenisreferensi']) ? $getRecord['jenisreferensi'] : '0',
			"JENIS_REQUEST_BPJS" => isset($getRecord['jenisrequest']) ? $getRecord['jenisrequest'] : '1',
			"TANGGALKUNJUNGAN" => isset($getRecord['tanggalperiksa']) ? $getRecord['tanggalperiksa'] : '0000-00-00',
			"STATUS" => 1,
			"JENIS_APLIKASI" =>2
		);
		$records = Json::encode($record);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",$method,$records);
		
		$result = (array) Json::decode($result);
		if(isset($result['success'])){
			if($result['success']){
				return array(
					'response' => $result['response'],
					'metadata' => $result['metadata']
				);
			} else {
				return array(
					'metadata' => $result['metadata']
				);
			}
		} else {
			return $result;
		}
    }
	public function createAntrianWebAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		
		$records = Json::encode($getRecord);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",$method,$records);
		
		$result = (array) Json::decode($result);
		return $result;
    }
	public function getAntrianAction()
    {
		$params = (array) $this->getRequest()->getQuery();
		if(count($params) > 0){
			$query = count($params) > 0 ? "?".http_build_query($params) : "";
		} else {
			$data = (array) $this->getPostData($this->getRequest());
			$query = count($data) > 0 ? "?".http_build_query($data) : "";
		}
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi".$query,$method);
		$result = (array) Json::decode($result);
		return $result;
    }
	public function getRekapAntrianAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		$record = array(
			"GET_REKAP" => 1,
			"TANGGALKUNJUGAN" => isset($getRecord['tanggalperiksa']) ? $getRecord['tanggalperiksa'] : '0000-00-00',
			"POLI_BPJS" => isset($getRecord['kodepoli']) ? $getRecord['kodepoli'] : '0',
			"POLI_EKSEKUTIF_BPJS" => isset($getRecord['polieksekutif']) ? $getRecord['polieksekutif'] : '0'
		);
		$records = Json::encode($record);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",$method,$records);
		$result = (array) Json::decode($result);
		if(isset($result['success'])){
			if($result['success']){
				return array(
					'response' => $result['response'],
					'metadata' => $result['metadata']
				);
			} else {
				return array(
					'metadata' => $result['metadata']
				);
			}
		} else {
			return $result;
		}
    }
	public function getKodeBookingOperasiAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		$record = array(
			"GET_KODE_OPERASI" => 1,
			"TANGGALKUNJUGAN" => isset($getRecord['nopeserta']) ? $getRecord['nopeserta'] : '0'
		);
		$records = Json::encode($record);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",$method,$records);
		$result = (array) Json::decode($result);
		if(isset($result['success'])){
			if($result['success']){
				return array(
					'response' => $result['response'],
					'metadata' => $result['metadata']
				);
			} else {
				return array(
					'metadata' => $result['metadata']
				);
			}
		} else {
			return $result;
		}
    }
	public function getListJadwalOperasiAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		$record = array(
			"GET_LIST_JADWAL_OPERASI" => 1,
			"PAWAL" => isset($getRecord['tanggalawal']) ? $getRecord['tanggalawal'] : '0000-00-00',
			"PAKHIR" => isset($getRecord['tanggalakhir']) ? $getRecord['tanggalakhir'] : '0000-00-00'
		);
		$records = Json::encode($record);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",$method,$records);
		$result = (array) Json::decode($result);
		if(isset($result['success'])){
			if($result['success']){
				return array(
					'response' => $result['response'],
					'metadata' => $result['metadata']
				);
			} else {
				return array(
					'metadata' => $result['metadata']
				);
			}
		} else {
			return $result;
		}
	}
	public function getKetersediaanTempatTidurAction()
    {
		$getRecord = $this->getPostData($this->getRequest());
		$record = array(
			"VIEW_KETERSEDIAAN_TEMPAT_TIDUR" => 1
		);
		$records = Json::encode($record);
		$method = $this->request->getmethod();
		$result = $this->sendRequestRegOnline("reservasi",'POST',$records);
		$result = (array) Json::decode($result);
		return $result;
	}
}
