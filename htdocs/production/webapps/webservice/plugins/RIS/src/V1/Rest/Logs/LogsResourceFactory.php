<?php
namespace RIS\V1\Rest\Logs;

class LogsResourceFactory
{
    public function __invoke($services)
    {
        $obj = new LogsResource();
    	$obj->setServiceManager($services);
        return $obj;
    }
}
