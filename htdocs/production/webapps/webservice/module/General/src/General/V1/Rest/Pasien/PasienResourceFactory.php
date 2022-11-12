<?php
namespace General\V1\Rest\Pasien;

class PasienResourceFactory
{
    public function __invoke($services)
    {    
        $obj = new PasienResource();
        $obj->setServiceManager($services);
        return $obj;
    }
}
