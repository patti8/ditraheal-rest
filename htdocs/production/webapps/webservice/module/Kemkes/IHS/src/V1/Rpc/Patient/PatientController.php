<?php
namespace Kemkes\IHS\V1\Rpc\Patient;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Patient\Service;

class PatientController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Patient";
        $this->title = 'patient';
        $this->paramsQuery = [
            'nik' => 'identifier=https://fhir.kemkes.go.id/id/nik|'
        ];
    }

    public function getIhsAction()
    {
        $params = [
            "httpRequest" => 'GET',
            "statusRequest" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $parameter = '';
            $parameter = $this->setParamsIhs(["nik" => $record['nik']]);
            $return = $this->sendToIhs($this->resourceType."?".$parameter);
            /* cek data yang tampil */
            if(isset($return->entry)){
                foreach($return->entry as $object){
                    if(isset($object->resource)){
                        $datains = $this->jsonToString($object->resource);
                        $datains['refId'] = $record['refId'];
                        $datains['statusRequest'] = 0;
                        $update = $this->update($record['refId'], $datains);
                        $record = $datains;
                    }
                }
            }
        }
        return $data;
    }

    public function nikAction() {
		$nik = $this->params()->fromRoute('id', 0);        
        $params = [
            "nik" => $nik
        ];

        $patients = $this->service->load($params);

        $parameter = $this->setParamsIhs($params);
        $return = $this->sendToIhs($this->resourceType."?".$parameter);
        if(isset($return->entry)) {
            foreach($return->entry as $object) {
                if(isset($object->resource)) {
                    $datains = $this->jsonToString($object->resource);                   
                    if(count($patients) > 0) {
                        $datains['refId'] = $patients[0]['refId'];
                        $datains['statusRequest'] = 0;
                        $this->update($patients[0]['refId'], $datains);
                    }
                }
            }
            return (array) $return;
        }

        if(empty($return)) {
            $return = [
                "resourceType" => "Bundle",
                "total" =>  0,
                "type" => "searchset"
            ];
        }

        $return = is_object($return) ? (array) $return : $return;

        if(count($patients) > 0) {
            $patients[0]["active"] = $patients[0]["active"] == 1;
            $patients[0]["deceasedBoolean"] = $patients[0]["deceasedBoolean"] == 1;
            $patients[0]["multipleBirthBoolean"] = $patients[0]["multipleBirthBoolean"] == 1;
            unset($patients[0]["getDate"]);
            unset($patients[0]["httpRequest"]);
            unset($patients[0]["statusRequest"]);

            $return["entry"] = [[
                "resource" => $this->stringToJson($patients[0], true)
            ]];
            $return["total"] = count($patients);
            $return["remote"] = false;
        }

        return (array) $return;
    }
}
