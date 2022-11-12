<?php
namespace RegistrasiOnline\V1\Rest\Reservasi;

class ReservasiResourceFactory
{
    public function __invoke($services)
    {
		$obj = new ReservasiResource($services);
		$obj->setServiceManager($services);
		return $obj;
    }
}
