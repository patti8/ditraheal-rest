<?php
namespace Kemkes\V2\Rest\DataTempatTidur;

class DataTempatTidurResourceFactory
{
    public function __invoke($services)
    {
        $obj = new DataTempatTidurResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
