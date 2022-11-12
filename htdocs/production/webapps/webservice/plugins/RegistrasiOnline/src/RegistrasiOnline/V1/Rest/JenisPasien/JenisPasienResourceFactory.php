<?php
namespace RegistrasiOnline\V1\Rest\JenisPasien;

class JenisPasienResourceFactory
{
    public function __invoke($services)
    {
        $obj = new JenisPasienResource();
		$obj->setServiceManager($services);
		return $obj;
		//return new JenisPasienResource();
    }
}
