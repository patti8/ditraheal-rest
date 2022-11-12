<?php
namespace Kemkes\V2\Rest\RekapPasienNonKomorbid;

class RekapPasienNonKomorbidResourceFactory
{
    public function __invoke($services)
    {
        $obj = new RekapPasienNonKomorbidResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
