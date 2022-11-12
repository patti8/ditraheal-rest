<?php
namespace Kemkes\IHS\V1\Rpc\Composition;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Composition\Service; 
class CompositionController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Composition";
        $this->title = 'composition';
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
