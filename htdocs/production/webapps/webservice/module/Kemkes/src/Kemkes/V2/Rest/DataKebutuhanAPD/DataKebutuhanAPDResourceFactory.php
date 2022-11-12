<?php
namespace Kemkes\V2\Rest\DataKebutuhanAPD;

class DataKebutuhanAPDResourceFactory
{
    public function __invoke($services)
    {
        $obj = new DataKebutuhanAPDResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
