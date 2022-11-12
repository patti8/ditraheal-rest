<?php
namespace Aplikasi\V1\Rest\Pengguna;

class PenggunaResourceFactory
{
    public function __invoke($services)
    {
        $obj = new PenggunaResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
