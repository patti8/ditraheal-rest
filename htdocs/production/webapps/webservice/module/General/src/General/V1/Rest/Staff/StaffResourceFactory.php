<?php
namespace General\V1\Rest\Staff;

class StaffResourceFactory
{
    public function __invoke($services)
    {
        $obj = new StaffResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
