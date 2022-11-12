<?php
namespace RIS\V1\Rest\ModalityTindakan;

class ModalityTindakanResourceFactory
{
    public function __invoke($services)
    {
        $obj = new ModalityTindakanResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
