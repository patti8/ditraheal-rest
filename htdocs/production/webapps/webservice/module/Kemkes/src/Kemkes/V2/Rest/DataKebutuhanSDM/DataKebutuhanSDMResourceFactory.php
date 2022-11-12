<?php
namespace Kemkes\V2\Rest\DataKebutuhanSDM;

class DataKebutuhanSDMResourceFactory
{
    public function __invoke($services)
    {
        $obj = new DataKebutuhanSDMResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
