<?php
namespace Kemkes\IHS\V1\Rpc\Condition;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Condition\Service;

class ConditionController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Condition";
        $this->title = 'condition';
    }

    public function sendAction()
    {
        $params = [
            "send" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $refid = $record["refId"];
            $id = $record["id"];
            unset($record["refId"]);
            unset($record["nopen"]);
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
                    $record["id"] = $respon->id;
                    $record["send"] = 0;
                    $this->update($refid, $record);
                }
            }
        }
        return $data;
    }
}
