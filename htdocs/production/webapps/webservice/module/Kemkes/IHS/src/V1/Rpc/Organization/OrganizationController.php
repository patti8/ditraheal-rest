<?php
namespace Kemkes\IHS\V1\Rpc\Organization;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Organization\Service as organizationService;

class OrganizationController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new organizationService();
        $this->resourceType = "Organization";
        $this->title = 'organization';
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
            $record["active"] = $record["active"] ? "true" : "false";
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
                    $record["active"] = $record["active"] ? 1 : 0;
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
