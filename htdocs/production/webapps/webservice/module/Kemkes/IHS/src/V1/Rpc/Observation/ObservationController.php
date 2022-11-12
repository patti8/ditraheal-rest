<?php
namespace Kemkes\IHS\V1\Rpc\Observation;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Observation\Service;

class ObservationController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Observation";
        $this->title = 'observation';
    }

    public function sendAction()
    {
        $params = [
            "send" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $refid = $record["refId"];
            $jenis = $record["jenis"];
            $id = $record["id"];
            unset($record["refId"]);
            unset($record["nopen"]);
            unset($record["jenis"]);
            unset($record["sendDate"]);
            unset($record["send"]);
            $record = $this->stringToJson($record);
            
            $method = $id ? "PUT" : "POST";
            $action =  $id ? $this->resourceType."/".$id : $this->resourceType;

            $respon =  $this->sendToIhs($action, $method, $record);
            $record = $this->jsonToString($record);
            if($respon){
                if(isset($respon->id)){
                    $record["refId"] = $refid;
                    $record["jenis"] = $jenis;
                    $record["id"] = $respon->id;
                    $record["send"] = 0;
                    $this->service->simpan($record);
                }
            }
        }
        return $data;
    }
}