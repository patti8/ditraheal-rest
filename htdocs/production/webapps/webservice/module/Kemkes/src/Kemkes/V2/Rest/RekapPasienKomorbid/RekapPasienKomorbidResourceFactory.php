<?php
namespace Kemkes\V2\Rest\RekapPasienKomorbid;

class RekapPasienKomorbidResourceFactory
{
    public function __invoke($services)
    {
        $obj = new RekapPasienKomorbidResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
