<?php
namespace PPI\V1\Rest\LimbahInfeksius;

class LimbahInfeksiusResourceFactory
{
    public function __invoke($services)
    {
        return new LimbahInfeksiusResource();
    }
}
