<?php
namespace Kemkes\V2\Rest\DiagnosaPasien;

class DiagnosaPasienResourceFactory
{
    public function __invoke($services)
    {
        $obj = new DiagnosaPasienResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
