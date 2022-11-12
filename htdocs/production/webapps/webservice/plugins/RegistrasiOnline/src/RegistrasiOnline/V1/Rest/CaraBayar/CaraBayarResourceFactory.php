<?php
namespace RegistrasiOnline\V1\Rest\CaraBayar;

class CaraBayarResourceFactory
{
    public function __invoke($services)
    {
        $obj = new CaraBayarResource();
		$obj->setServiceManager($services);
		return $obj;
    }
}
