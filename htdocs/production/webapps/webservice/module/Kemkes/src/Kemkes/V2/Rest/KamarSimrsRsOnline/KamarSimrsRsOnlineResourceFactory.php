<?php
namespace Kemkes\V2\Rest\KamarSimrsRsOnline;

class KamarSimrsRsOnlineResourceFactory
{
    public function __invoke($services)
    {
        $obj = new KamarSimrsRsOnlineResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
