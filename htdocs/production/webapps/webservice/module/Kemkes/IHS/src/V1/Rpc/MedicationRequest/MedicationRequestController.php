<?php
namespace Kemkes\IHS\V1\Rpc\MedicationRequest;

use Kemkes\IHS\RPCResource;

class MedicationRequestController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "MedicationRequest";
        $this->title = 'medicationrequest';
    }

    public function sendAction()
    {
        $desstatus = [
            0 => 'false',
            1 => 'true'
        ];
        $params = [
            "send" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $refid = $record["refId"];
            $barang = $record["barang"];
            $group_racikan = $record["group_racikan"];
            $id = $record["id"];
            unset($record["refId"]);
            unset($record["nopen"]);
            unset($record["group_racikan"]);
            unset($record["barang"]);
            unset($record["status_racikan"]);
            unset($record["jenis"]);
            unset($record["sendDate"]);
            unset($record["send"]);

            $record["reportedBoolean"] = $desstatus[$record["reportedBoolean"]] ;

            $record = $this->stringToJson($record);
            $method = $id ? "PUT" : "POST";
            $action =  $id ? $this->resourceType."/".$id : $this->resourceType;

            $respon =  $this->sendToIhs($action, $method, $record);
            $record = $this->jsonToString($record);
            if($respon){
                if(isset($respon->id)){
                    $record["refId"] = $refid;
                    $record["barang"] = $barang;
                    $record["group_racikan"] = $group_racikan;
                    $record["id"] = $respon->id;
                    $record["send"] = 0;
                    $this->service->simpan($record);
                }
            }
        }
        return $data;
    }
}
