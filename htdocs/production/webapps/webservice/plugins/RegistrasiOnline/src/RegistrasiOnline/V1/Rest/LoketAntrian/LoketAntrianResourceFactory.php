<?php
namespace RegistrasiOnline\V1\Rest\LoketAntrian;

class LoketAntrianResourceFactory
{
    public function __invoke($services)
    {
        $obj = new LoketAntrianResource();
		$obj->setServiceManager($services);
		return $obj;
    }
}
