<?php
namespace Kemkes\IHS\V1\Rpc\Practitioner;

use Kemkes\IHS\RPCResource;
use Kemkes\IHS\V1\Rpc\Practitioner\Service;

class PractitionerController extends RPCResource
{
    public function __construct($controller)
    {
        parent::__construct($controller);
        $this->service = new Service();
        $this->resourceType = "Practitioner";
        $this->title = 'Practitioner';
        $this->paramsQuery = [
            'nik' => 'identifier=https://fhir.kemkes.go.id/id/nik|'
        ];
    }

    public function getIhsAction()
    {
        $params = [
            "get" => 1
        ];
        $data = $this->service->load($params);
        foreach($data as &$record){
            $paremeter = '';
            $paremeter = $this->setParamsIhs(["nik" => $record['refId']]);
            $return = $this->sendToIhs($this->resourceType."?".$paremeter);
            /* cek data yang tampil */
            if(isset($return->entry)){
                foreach($return->entry as $object){
                    if(isset($object->resource)){
                        $datains = $this->jsonToString($object->resource);
                        $datains['refId'] = $record['refId'];
                        $datains['get'] = 0;
                        $update = $this->update($record['refId'], $datains);
                        $record = $datains;
                    }
                }
            }
        }        
        return $data;
    }
}
