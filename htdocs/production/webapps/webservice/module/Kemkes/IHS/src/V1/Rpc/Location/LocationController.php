<?php
namespace Kemkes\IHS\V1\Rpc\Location;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Location\Service;

class LocationController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Location";
        $this->title = 'location';
    }

    public function sendAction()
    {
        $desstatus = [
            "1" => "active",
            "2" => "suspended",
            "3" => "inactive"
        ];
        $params = [
            "send" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $refid = $record["refId"];
            $id = $record["id"];
            $codestatus = $record["status"];
            $record["status"] = $desstatus[$record["status"]] ;
            unset($record["refId"]);
            unset($record["sendDate"]);
            unset($record["send"]);
            unset($record["flag"]);
            $record = $this->stringToJson($record);
            
            $method = $id ? "PUT" : "POST";
            $action =  $id ? $this->resourceType."/".$id : $this->resourceType;

            $respon =  $this->sendToIhs($action, $method, $record);
            $record = $this->jsonToString($record);
            if($respon){
                if(isset($respon->id)){
                    $record["status"] = $codestatus;
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
