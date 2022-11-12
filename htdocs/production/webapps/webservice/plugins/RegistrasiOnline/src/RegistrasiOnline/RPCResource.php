<?php
namespace RegistrasiOnline;

use Laminas\Mvc\Controller\AbstractRestfulController;

use SimpleXMLElement;
use Laminas\Json\Json;

use Laminas\Mvc\Exception;
use Laminas\Mvc\MvcEvent;

use Laminas\Authentication\AuthenticationService;
use DBService\DatabaseService;
use Aplikasi\Signature;

use DBService\generator\Generator;

use Laminas\ApiTools\ApiProblem\ApiProblem;

class RPCResource extends AbstractRestfulController
{
	const AUTH_TYPE_LOGIN = 0;
	const AUTH_TYPE_SIGNATURE = 1;
	const AUTH_TYPE_NOT_SECURE = 2;
	const AUTH_TYPE_SIGNATURE_OR_LOGIN = 3;
	const AUTH_TYPE_IP = 4;
	const AUTH_TYPE_IP_OR_LOGIN = 5;
	const AUTH_TYPE_IP_AND_LOGIN = 6;
	const AUTH_TYPE_SIGNATURE_OR_IP = 7;
	const AUTH_TYPE_SIGNATURE_AND_IP = 8;
	const AUTH_TYPE_NOT_LOGIN_REG_ONLINE = 9;
	
	protected $auth;
	protected $services;
	protected $privilages = array();
	protected $user;
	protected $dataAkses;
	protected $bridgeLog;
	protected $jenisBridge = 0;
	protected $writeBridgeLog = true;
	
	private $signature = false;
	
	protected $authType = self::AUTH_TYPE_IP;
	
	protected $config = array();
	protected $headers = array();
	
	protected $integrasi = array();
	
	protected function onBeforeSendRequest() {}
	protected function onAfterAuthenticated($params = []) {}

	public function getSignature() {
		return $this->signature;
	}
	
    protected function sendRequestRegOnline($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
		$url = ($url == '' ? $this->config["url"] : $url);
		
		$headers = $this->request->getHeaders();
		$xToken = $headers->get("x-token")->getFieldValue();
		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$this->headers[count($this->headers)] = "Content-type: ".$contenType;
		$this->headers[count($this->headers)] = "Content-length: ".strlen($data);
		$this->headers[count($this->headers)] = "X-Token: ".$xToken;
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		
		$result = curl_exec($curl);
		curl_close($curl);
		file_put_contents("logs/log_antrian.txt", $result);
		if($data !== "") file_put_contents("logs/post_data_log_antrian.txt", $data);
		file_put_contents("logs/url_antrian.txt", $url."/".$action);
		file_put_contents("logs/headers_antrian.txt", json_encode($this->headers));
		
		return $result;
	}
	
	/*
	 * array(
	 *		url => "Uniform Resource Locator",
	 *		action => "Web Service Name",
	 *		method => "{GET|POST|PUT|DELETE}",
	 *		data => "JSON String",
	 *		header => []
	 * )
	 */
	
	
	protected function getPostData() {
		$request = $this->getRequest();
		if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
			$data = Json::decode($request->getContent(), $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }
		
		return $data;
	}
	
	/**
     * Handle the request
     *
     * @todo   try-catch in "patch" for patchList should be removed in the future
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
	public function onDispatch(MvcEvent $e) {
		if(!isset($this->auth)) $this->auth = new AuthenticationService();	
		$result = null;
		if($this->authType == self::AUTH_TYPE_LOGIN) {
			$result = $this->doAuthLogin();			
			if(is_array($result)) {
				$this->response->setStatusCode(405);
				$e->setResult($result);
				return $result;
			}
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE) {
			$result = $this->doAuthSignature();
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_LOGIN) {
			$result = $this->doAuthLogin();
			if(is_array($result)) {
				$result = $this->doAuthSignature();
			}
		} else if($this->authType == self::AUTH_TYPE_IP) {
			$result = $this->doAuthIP();
		} else if($this->authType == self::AUTH_TYPE_IP_OR_LOGIN) {
		    $result = $this->doAuthIP();
		    if($result instanceof ApiProblem) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		} else if($this->authType == self::AUTH_TYPE_IP_AND_LOGIN) {
		    $result = $this->doAuthIP();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_IP) {
		    $headers = $this->request->getHeaders();
		    if($headers->get("X-Signature")) {
		        $result = $this->doAuthSignature();
		    } else {
		        $result = $this->doAuthIP();
		    }
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_AND_IP) {
		    $result = $this->doAuthSignature();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthIP();
		    }
		} else if($this->authType == self::AUTH_TYPE_NOT_LOGIN_REG_ONLINE) {
			$result = $this->doAllowGetToken();
		}
		
		if($result instanceof ApiProblem) {
			$errors = $result->toArray();	
			$this->response->setStatusCode($errors["status"]);
			$e->setResult($errors);
			return $errors;
		}
		
		return parent::onDispatch($e);
	}
	
	private function doAuthLogin() {
		if(!$this->auth->hasIdentity()) {
			return array(
				'success' => false,
				'message' => 'not login',
				'data' => null
			);
		} else {
			$data = $this->auth->getIdentity();
			$this->privilages = (array) $data->XPRIV;
			if(isset($data->XITR)) $this->integrasi = $data->XITR;
			$this->user = $data->ID;
			$this->dataAkses = $data;
		}
	}
	
	private function doAuthSignature() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$headers = $this->request->getHeaders();					
		$this->signature = new Signature(
			$headers->get("X-Id"), 
			$headers->get("X-Timestamp"),
			$headers->get("X-Signature")
		);
		
		try {
			$this->signature->isValidSignature();
		} catch(\Exception $e) {
			return new ApiProblem($e->getCode(), $e->getMessage());
		}
	}
	
	private function doAuthIP() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$ip = $this->request->getServer('REMOTE_ADDR');
		
		$db = DatabaseService::get("SIMpel");
		$adapter = $db->getAdapter();
		
		$stmt = $adapter->query('
			SELECT *
			  FROM aplikasi.allow_ip_authentication
			 WHERE NOMOR = ?');
		$results = $stmt->execute(array($ip));
		$result = $results->current();
		$allow = false;
		if($result) {
			$allow = $result["STATUS"] == 1;
		}
		
		if(!$allow) {
			return new ApiProblem(401, "IP $ip is not allowed / registered");
		}
	}
	
	private function doAllowGetToken() {
		
		$allow = true;
		
		if(!$allow) {
			return new ApiProblem(401, "Not Akses");
		}
	}
	
	/*
		Examples:
		array(
			'123' => '123'
		);
	*/
	public function isAllowPrivilage($id) {
		$allow = false;
		foreach($this->privilages as $key=>$val) {
			if($key == $id) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	/*
		Examples:
		array(
			'0' => array(
				'ID' => '1'
			)
		);
	*/
	public function isIntegrasi($field, $val) {
		$allow = false;
		foreach($this->integrasi as $data) {
			$data = (array) $data;
			if($data[$field] == $val) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	public function toResponse($data = array()) {
		if($this->getRequest()->getHeaders()->get("accept")->getFieldValue() == "application/json") return $this->toJsonResponse($data);
		return $this->toXMLResponse($data);
	}
	
	public function toJsonResponse($data = array()) {
		$json = json_encode($data);
		//$json = str_replace(array("[", "]"), "", $json);
		$this->getResponse()->setContent($json);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/json')
			->addHeaderLine('Content-Length', strlen($json));
        
		return  $this->getResponse();
	}
	
	public function toXMLResponse($data = array()) {	
		$xmlString = $this->toFormatXML($data);
		$this->getResponse()->setContent($xmlString);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/xml')
			->addHeaderLine('Content-Length', strlen($xmlString));
        
		return  $this->getResponse();
	}
	
	public function toFormatXML($data = array()) {
		$doc = new SimpleXMLElement("<xml version='1.0'></xml>");
		foreach($data as $entity) {
			$dt = $doc->addChild("data");
			foreach($entity as $key => $val) {
				$dt->addChild($key, htmlspecialchars($val));
			}
		}
		$xmlString = $doc->asXML();
		$xmlString = str_replace(array('<?xml version="1.0"?>', "\n", "\r"), "", $xmlString);
		
		return $xmlString;
	}
	
	protected function getXMLPostData() {					
        return new SimpleXMLElement($this->getRequest()->getContent());
	}
	
	protected function getResultRequest($val, $field = "status") {
	    try {
	        $result = Json::decode($val, Json::TYPE_ARRAY);
	        $status = isset($result[$field]) ? (is_numeric($result[$field]) ? $result[$field] : 200) : 200;
	    } catch(\Exception $e) {
	        $status = 404;
	        $result = [
	            "status" => 404,
	            "detail" => "Page not found",
	            "data" => null
	        ];
	    }
	    
	    if($this->response) $this->response->setStatusCode($status);
	    
	    return $result;
	}
	
	public function downloadDocument($content, $tipe, $ext, $id, $attachment = true) {
	    $this->getResponse()->setContent($content);
	    $headers = $this->getResponse()->getHeaders();
	    $filename = $id.".".$ext;
	    $headers->clearHeaders()
	    ->addHeaderLine('Content-Type', $tipe)
	    ->addHeaderLine('Content-Length', strlen($content));
	    
	    if($attachment) $headers->addHeaderLine('Content-Disposition', 'attachment; filename="'.$filename.'"');
	    
	    return $this->response;
	}
}
