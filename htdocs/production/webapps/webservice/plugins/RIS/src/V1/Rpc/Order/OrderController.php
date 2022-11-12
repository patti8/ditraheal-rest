<?php
namespace RIS\V1\Rpc\Order;

use DBService\RPCResource;
use Laminas\ApiTools\ApiProblem\ApiProblem;

class OrderController extends RPCResource
{
    private $driver;

    public function __construct($controller) {
        $this->authType = self::AUTH_TYPE_IP_OR_LOGIN;
	    $this->config = $controller->get('Config');
        $this->config = $this->config['services']['RIService']["HL7"];
        
        $driver = "\\RIS\\driver\\worklist\\".$this->config["provider"]["name"];
        $this->driver = new $driver($this->config);
    }

    public function kirimAction() {
        $request = $this->getRequest();  
        $data = (array) $this->getPostData($request);
        
        $result = $this->driver->kirim($data);

        if($result instanceof ApiProblem) {
            $errors = $result->toArray();	
			$this->response->setStatusCode($errors["status"]);
			return $errors;
        }

        return [
            "status" => 200,
            "data" => $result
        ];
    }

    public function batalAction() {
        $request = $this->getRequest();  
        $data = (array) $this->getPostData($request);
        
        $result = $this->driver->kirim($data, "CA");

        if($result instanceof ApiProblem) {
            $errors = $result->toArray();
			$this->response->setStatusCode($errors["status"]);
			return $errors;
        }

        return [
            "status" => 200,
            "success" => true,
            "data" => $result
        ];
    }

    public function runAction() {
        return [];
    }
}
