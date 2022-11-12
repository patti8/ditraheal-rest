<?php
namespace PPI\V1\Rest\Vap;

class VapResourceFactory
{
    public function __invoke($services)
    {
        return new VapResource();
    }
}
