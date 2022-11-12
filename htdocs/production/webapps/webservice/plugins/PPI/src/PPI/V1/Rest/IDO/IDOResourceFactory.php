<?php
namespace PPI\V1\Rest\IDO;

class IDOResourceFactory
{
    public function __invoke($services)
    {
        return new IDOResource();
    }
}
