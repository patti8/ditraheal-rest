<?php
namespace Kemkes\V2\Rest\Pasien;

class PasienResourceFactory
{
    public function __invoke($services)
    {
        $obj = new PasienResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
