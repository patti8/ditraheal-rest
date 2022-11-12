<?php
namespace Mutu\V1\Rest\PDSA;

class PDSAResourceFactory
{
    public function __invoke($services)
    {
        return new PDSAResource();
    }
}
