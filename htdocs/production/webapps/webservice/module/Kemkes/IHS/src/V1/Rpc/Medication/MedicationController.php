<?php
namespace Kemkes\IHS\V1\Rpc\Medication;

use Kemkes\IHS\RPCResource;

class MedicationController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Medication";
        $this->title = 'medication';
    }

    public function sendAction()
    {
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
            unset($record["group_racikan"]);
            unset($record["nopen"]);
            unset($record["barang"]);
            unset($record["status_racikan"]);
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
