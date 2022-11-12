<?php
namespace RegistrasiOnline\V1\Rest\Ruangan;

class RuanganResourceFactory
{
    public function __invoke($services)
    {
        //return new RuanganResource();
		$obj = new RuanganResource();
		$obj->setServiceManager($services);
		return $obj;
    }
}
