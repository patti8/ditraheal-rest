<?php
namespace DBService;

use Laminas\Mvc\Controller\AbstractRestfulController;

use SimpleXMLElement;
use Laminas\Json\Json;

use Laminas\Mvc\Exception;
use Laminas\Mvc\MvcEvent;

use Laminas\Authentication\AuthenticationService;
use DBService\DatabaseService;
use Aplikasi\Signature;
use Aplikasi\Token;

use Aplikasi\db\bridge_log\Service as BridgeLogService;
use DBService\generator\Generator;

use Laminas\ApiTools\ApiProblem\ApiProblem;

use Laminas\Mail;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;

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
	const AUTH_TYPE_TOKEN = 9;
	const AUTH_TYPE_LOGIN_OR_TOKEN = 10;
	const AUTH_TYPE_IP_OR_TOKEN = 11;
	
	protected $auth;
	protected $services;
	protected $privilages = [];
	protected $user;
	protected $dataAkses;
	protected $bridgeLog;
	protected $jenisBridge = 0;
	protected $isWriteBridgeLog = true;
	
	private $signature = false;
	private $token = false;
	
	protected $authType = self::AUTH_TYPE_IP;
	protected $authTypeAccess = -1;
	
	protected $config = [];
	protected $headers = [];
	
	protected $integrasi = [];
	protected $httpcode;

	protected function onBeforeSendRequest() {}
	protected function onAfterSendRequest($curl, $result) {}

	protected function onAfterAuthenticated($params = []) {}
	
	public function getSignature() {
		return $this->signature;
	}

	public function getConfig() {
		return $this->config;
	}
	
    public function sendRequest($action = "", $method = "GET", $data = "", $contenType = "application/json", $url = "") {
		$curl = curl_init();
				
		$url = ($url == '' ? $this->config["url"] : $url);
		$this->headers[count($this->headers)] = "Content-type: ".$contenType;
		$this->headers[count($this->headers)] = "Content-length: ".strlen($data);

		$id = $this->writeBridgeLog([
			"URL" => $url."/".$action,
			"HEADERS" => json_encode($this->headers),
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);

		curl_setopt($curl, CURLOPT_URL, $url."/".$action);
		curl_setopt($curl, CURLOPT_HEADER, false);		

		$this->onBeforeSendRequest();

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		$timeout = isset($this->config["timeout"]) ? $this->config["timeout"] : 5;
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		
		$result = curl_exec($curl);
		$this->httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->onAfterSendRequest($curl, $result);
		curl_close($curl);
		
		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);
		
		file_put_contents("logs/log.txt", $result);
		if($data !== "") file_put_contents("logs/post_data_log.txt", $data);
		file_put_contents("logs/url.txt", $url."/".$action);
		file_put_contents("logs/headers.txt", json_encode($this->headers));
		
		return $result;
	}
	
	/*
	 * array(
	 *		url => "Uniform Resource Locator",
	 *		action => "Web Service Name",
	 *		method => "{GET|POST|PUT|DELETE}",
	 *		data => "JSON String",
	 *      dataIsJson => true,
	 *		header => []
	 * )
	 */
	public function sendRequestData($options = []) {
		$url = isset($options["url"]) ? $options["url"] : $this->config["url"];		
	    $protocol = explode("://", $url);
	    $protocol = $protocol[0];
	    $action = isset($options["action"]) ? (trim($options["action"]) != "" ? "/".$options["action"] : "") : "";
	    $headers = [
	        "Content-type: application/json"
		];

		// default true
		$options["dataIsJson"] = !isset($options["dataIsJson"]) ? true : $options["dataIsJson"];
		$data = "";
		if(isset($options["data"])) {
			if($options["dataIsJson"]) {
				if(is_string($options["data"])) $data = $options["data"];
				else $data = json_encode($options["data"]);
			} else {
				$data = $options["data"];
			}
		}	    
	    
		$headers[] = "Content-length: ".strlen($data);
		
		$this->onBeforeSendRequest();
	    
	    if(isset($options["header"])) {
			foreach($options["header"] as $header) {
				if(!is_bool(strpos($header, "Content-type"))) unset($headers[0]);
			}
			$headers = array_merge($headers, $options["header"]);
		}
	    if(isset($this->headers)) $headers = array_merge($headers, $this->headers);
	    
	    $opts = [
	        "http" => [
	            "method" => isset($options["method"]) ? $options["method"] : "GET",
	            "header" => implode("\r\n", $headers),
	            "content" => $data //,
	            //"timeout" => 60
	        ]
		];
		
		if($protocol == "https") {
			$opts["ssl"] = [
				'verify_peer' => false,
    			'verify_peer_name' => false
			];
		}
	    
	    if($data !== "") file_put_contents("logs/post_data_log.txt", $data);
	    file_put_contents("logs/url.txt", $url.$action);
		file_put_contents("logs/headers.txt", json_encode($opts));
		$id = $this->writeBridgeLog([
			"URL" => $url.$action,
			"HEADERS" => $opts["http"]["header"],
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
	    try {
	        $context  = stream_context_create($opts);
			$result = @file_get_contents($url.$action, false, $context);
			$this->writeBridgeLog([
				"ID" => $id,
				"RESPONSE" => $result
			]);
	    } catch(\Exception $e) {
	        //var_dump($e->getMessage());
			$result = null;
			$this->writeBridgeLog([
				"ID" => $id,
				"RESPONSE" => $e->getMessage()
			]);
	    }
	    //$result = file_get_contents($url."/".$action, false, $context, -1, 40000);
	    file_put_contents("logs/log.txt", $result);
	    return $result;
	}

	/*
	 * array(
	 *		url => "Uniform Resource Locator",
	 *		action => "Web Service Name",
	 *		method => "{GET|POST|PUT|DELETE}",
	 *		data => "JSON String",
	 *      dataIsJson => true,
	 *		header => [],
	 *      json_encode_return => true
	 * )
	*/
	public function doSendRequest($options = []) {
		$curl = curl_init();
		
		$jsonEncodeReturn = isset($options["json_encode_return"]) ? $options["json_encode_return"] : true;
		$action = isset($options["action"]) ? (trim($options["action"]) != "" ? "/".$options["action"] : "") : "";				
		
		// default true
		$options["dataIsJson"] = !isset($options["dataIsJson"]) ? true : $options["dataIsJson"];
		$data = "";
		if(isset($options["data"])) {
			if($options["dataIsJson"]) {
				if(is_string($options["data"])) $data = $options["data"];
				else $data = json_encode($options["data"]);
			} else {
				$data = $options["data"];
			}
		}
		
		$this->onBeforeSendRequest();

		if(count($this->headers) == 0) {
			$headers = [
				"Content-type: application/json",
				"Content-length: ".strlen($data)
			];
		} else {
			$headers = $this->headers;
		}
		if(isset($options["header"])) {
			foreach($options["header"] as $header) {
				if(!is_bool(strpos($header, "Content-type"))) unset($headers[0]);
			}
			$headers = array_merge($headers, $options["header"]);
		}
		
		$id = $this->writeBridgeLog([
			"URL" => $options["url"].$action,
			"HEADERS" => json_encode($headers),
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
		
		curl_setopt($curl, CURLOPT_URL, $options["url"].$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, isset($options["method"]) ? $options["method"] : "GET");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);		
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$timeout = isset($options["timeout"]) ? $options["timeout"] : 5;
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);		
		
		$result = curl_exec($curl);	
		$this->httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->onAfterSendRequest($curl, $result);	
		curl_close($curl);

		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);
			
		return $jsonEncodeReturn ? json_decode($result) : $result;
	}
	
	protected function getPostData() {
		$request = $this->getRequest();
		if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
			$data = Json::decode($request->getContent(), $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }
		
		return $data;
	}

	protected function writeBridgeLog($data=[]) {
		if(!$this->isWriteBridgeLog) return false;		
		if(!isset($this->config["writeLog"])) return false;		
		if(!$this->config["writeLog"]) return false;
		if(!isset($this->bridgeLog)) $this->bridgeLog = new BridgeLogService();
		$this->bridgeLog->recreateTable();
		$isCreate = isset($data["ID"]) ? false : true;
		if($isCreate) $data["ID"] = Generator::generateIdBridgeLog();		
		$data["JENIS"] = $this->jenisBridge;
		$this->bridgeLog->simpanData($data, $isCreate);
		$this->bridgeLog->disconnect();
		return $data["ID"];
	}

	public function setIsWriteBridgeLog($val = true) {
		$this->isWriteBridgeLog = $val;
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
			$resultLogin = $this->doAuthLogin();
			if(is_array($resultLogin)) {
				if(($result instanceof ApiProblem)) {
					$this->response->setStatusCode(405);
					$resultLogin["message"] .= " / ".$result->toArray()["detail"];
					$e->setResult($resultLogin);
					return $resultLogin;
				}
			} else {
				$result = true;
			}	        
		} else if($this->authType == self::AUTH_TYPE_IP_AND_LOGIN) {
		    $result = $this->doAuthIP();
		    if(!($result instanceof ApiProblem)) {
		        $resultLogin = $this->doAuthLogin();
				if(is_array($resultLogin)) {
					$this->response->setStatusCode(405);
					$resultLogin["message"] .= " dan ".$result->toArray()["detail"];
					$e->setResult($resultLogin);
					return $resultLogin;
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
		} else if($this->authType == self::AUTH_TYPE_TOKEN) {
			$result = $this->doAuthToken();
		} else if($this->authType == self::AUTH_TYPE_LOGIN_OR_TOKEN) {
			$result = $this->doAuthLogin();
			if(is_array($result)) {
				$result = $this->doAuthToken();
			}
		} else if($this->authType == self::AUTH_TYPE_IP_OR_TOKEN) {
			$result = $this->doAuthIP();
		    if($result instanceof ApiProblem) {
		        $result = $this->doAuthToken();
		    }
		}
		
		if($result instanceof ApiProblem) {
			$errors = $result->toArray();	
			$this->response->setStatusCode($errors["status"]);
			$e->setResult($errors);
			return $errors;
		}

		$oau = $this->onAfterAuthenticated();
		if($oau instanceof ApiProblem) {
			$errors = $oau->toArray();	
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
				'message' => 'Not login',
				'data' => null
			);
		}

		$data = $this->auth->getIdentity();
		$this->privilages = (array) $data->XPRIV;
		if(isset($data->XITR)) $this->integrasi = $data->XITR;
		$this->user = $data->ID;
		$this->dataAkses = $data;
		$this->authTypeAccess = self::AUTH_TYPE_LOGIN;
		return true;
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
			$this->authTypeAccess = self::AUTH_TYPE_SIGNATURE;
		} catch(\Exception $e) {
			return new ApiProblem($e->getCode(), $e->getMessage());
		}
	}

	private function doAuthToken() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: Request is null');
		}

		$headers = $this->request->getHeaders();
		$this->token = new Token(
			$headers->get("X-Token")
		);
		
		try {
			$this->token->isValidToken();
			$this->authTypeAccess = self::AUTH_TYPE_TOKEN;
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
		if($result) $allow = $result["STATUS"] == 1;
		if(!$allow) {
			return new ApiProblem(401, "IP $ip is not allowed / registered");
		} else {
			$this->authTypeAccess = self::AUTH_TYPE_IP;
		}

		return true;
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
	
	public function toResponse($data = []) {
		if($this->getRequest()->getHeaders()->get("accept")->getFieldValue() == "application/json") return $this->toJsonResponse($data);
		return $this->toXMLResponse($data);
	}
	
	public function toJsonResponse($data = []) {
		$json = json_encode($data);
		//$json = str_replace(array("[", "]"), "", $json);
		$this->getResponse()->setContent($json);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/json')
			->addHeaderLine('Content-Length', strlen($json));
        
		return  $this->getResponse();
	}
	
	public function toXMLResponse($data = []) {	
		$xmlString = $this->toFormatXML($data);
		$this->getResponse()->setContent($xmlString);
		$headers = $this->getResponse()->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/xml')
			->addHeaderLine('Content-Length', strlen($xmlString));
        
		return  $this->getResponse();
	}
	
	public function toFormatXML($data = []) {
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

	/* Send Mail */
	/*
	 * [
	 *		"from" => "Email Asal",
	 *		"to" => "Email Tujuan",
	 *		"subject" => "Subject Email",
	 *		"body" => "content or mime message (text dan attachment)"	 
	 * ]
	 * example:
	 * [
	 * 		"from" => "admin@simpel.web.id",
	 * 		"to" => "somebody@email.com",
	 * 		"subject" => "Kirim Email",
	 * 		// "body" => "Dear Email" atau
	 * 		"body => [
	 * 			[ // gunakan parameter option ini bisa menggunakan class Part using use Laminas\Mime\Part;
	 * 				"content" => "text or content file (stream)",
	 * 				"type" => Mime::TYPE_HTML, // using use Laminas\Mime\Mime;
	 * 				"charset" => "utf-8", // optional
	 * 				"filename" => "Nama File",
	 * 				"disposition" => Mime::DISPOSITION_ATTACHMENT, // using Laminas\Mime\Mime
	 * 				"encoding" => Mime::ENCODING_BASE64 // using Laminas\Mime\Mime
	 * 			] // ini bisa lebih dari satu array
	 * 		]
	 * ]
	*/
	protected function sendMail($options = []) {
		$mail = new Mail\Message();
		$default = ini_get('max_execution_time');
		set_time_limit($this->config["connection_time_limit"]);
		unset($this->config["connection_time_limit"]);		
		$config = $this->config["services"]["MailServer"];
		$body = isset($options["body"]) ? $options["body"] : "";
		$headerType = "multipart/alternative";
		$contents = [];
		
		if(is_array($body)) {
			foreach($body as $b) {
				if($b instanceof Part) {
					$contents[] = $b;
					
					if($b->disposition == Mime::DISPOSITION_ATTACHMENT) $headerType = "multipart/related";
				} else {
					if(isset($b["content"])) {
						$part = new Part($b["content"]);
						if(isset($b["type"])) $part->type = $b["type"];
						if(isset($b["charset"])) $part->charset = $b["charset"];
						if(isset($b["filename"])) $part->filename = $b["filename"];
						if(isset($b["disposition"])) {
							$part->disposition = $b["disposition"];
							if($b["disposition"] == Mime::DISPOSITION_ATTACHMENT) {
								//file_put_contents("logs/payroll.pdf", $b["content"]);
								$headerType = "multipart/related";
							}
						}
						if(isset($b["encoding"])) $part->encoding = $b["encoding"];
						$contents[] = $part;				
					} 
				}
			}
		}

		if(is_string($body) || count($contents) == 0) {
			$part = new Part($body);
			$part->type = Mime::TYPE_HTML;
			$part->charset = 'utf-8';
			$part->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
			$contents[] = $part;
		}

		$bodyMessage = new MimeMessage();
		$bodyMessage->setParts($contents);

		$mail->setEncoding("UTF-8");
	    $mail->setBody($bodyMessage)
	    	->setFrom(isset($options["from"]) ? $options["from"] : $config["connection_config"]["username"])
			->addTo($options["to"])
			->setSubject($options["subject"]);
			
		$contentTypeHeader = $mail->getHeaders()->get('Content-Type');
		if(count($contents) > 1) $contentTypeHeader->setType($headerType);
	    
	    $smtpOption = new Mail\Transport\SmtpOptions($config);	    
	    $transport = new Mail\Transport\Smtp($smtpOption);
	    
	    try {
			$transport->send($mail);
			return true;
	    } catch(\Exception $e) {			
	        return new ApiProblem(500, 'Error: '.($e->getMessage()));
	    } finally {
			set_time_limit($default);
		}
	}

	/**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $result = [
			"status" => 422,
			"success" => false,
			"detail" => "Gagal menyimpan ".$this->title
        ];
		
		$notValidEntity = $this->service->getEntity()->getNotValidEntity($data, false);
        if(count($notValidEntity) > 0) {
			$result["status"] = 412;
            $result["detail"] = $notValidEntity["messages"];
			$this->response->setStatusCode($result["status"]);
			return $result;
        }

        $result["data"] = null;
		$data = is_object($data) ? (array) $data : $data;
        $data["OLEH"] = $this->user;
		
		$success = $this->service->simpanData($data, true);
		if($success) {
			$result["status"] = 200;
			$result["success"] = true;
			$result["data"] = $success[0];
			$result["detail"] = "Berhasil menyimpan ".$this->title;
		}
		
		$this->response->setStatusCode($result["status"]);		
		return $result;
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
        $result = [
			"status" => 422,
			"success" => false,
			"data" => null,
			"detail" => "Gagal merubah data ".$this->title
		];
		
		$notValidEntity = $this->service->getEntity()->getNotValidEntity($data, false, "PUT");
        if(count($notValidEntity) > 0) {
			$result["status"] = 412;
            $result["detail"] = $notValidEntity["messages"];
			$this->response->setStatusCode($result["status"]);
			return $result;
        }

		$entityId = $this->service->getEntityId();
		$data = is_object($data) ? (array) $data : $data;
		$data[$entityId] = $id;
        $data["OLEH"] = $this->user;		
		$params = [$entityId => $id];
		
		$records = $this->service->load($params);
		$canUpdate = count($records) > 0;
		
		if($canUpdate) {
			$success = $this->service->simpanData($data);
			if($success) {
				$result["status"] = 200;
				$result["success"] = true;
				$result["data"] = $success[0];
				$result["detail"] = "Berhasil merubah data ".$this->title;
			}
		}
		
		$this->response->setStatusCode($result["status"]);		
		return $result;
    }
}
