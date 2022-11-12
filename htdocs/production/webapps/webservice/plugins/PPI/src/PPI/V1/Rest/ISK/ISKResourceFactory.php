<?php
namespace PPI\V1\Rest\ISK;

class ISKResourceFactory
{
    public function __invoke($services)
    {
        return new ISKResource();
    }
}
