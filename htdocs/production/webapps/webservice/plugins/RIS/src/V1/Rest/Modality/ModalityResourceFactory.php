<?php
namespace RIS\V1\Rest\Modality;

class ModalityResourceFactory
{
    public function __invoke($services)
    {
        $obj = new ModalityResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
