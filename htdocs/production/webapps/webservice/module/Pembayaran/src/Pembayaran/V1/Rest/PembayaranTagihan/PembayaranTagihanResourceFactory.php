<?php
namespace Pembayaran\V1\Rest\PembayaranTagihan;

class PembayaranTagihanResourceFactory
{
    public function __invoke($services)
    {
        $obj = new PembayaranTagihanResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
