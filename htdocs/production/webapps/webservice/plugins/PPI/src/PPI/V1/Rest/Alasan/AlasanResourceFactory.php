<?php
namespace PPI\V1\Rest\Alasan;

class AlasanResourceFactory
{
    public function __invoke($services)
    {
        return new AlasanResource();
    }
}
