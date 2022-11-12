<?php
namespace Kemkes\V2\Rest\RekapPasienMasuk;

class RekapPasienMasukResourceFactory
{
    public function __invoke($services)
    {
        $obj = new RekapPasienMasukResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
