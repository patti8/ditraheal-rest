<?php
namespace PPI\V1\Rest\Flebitis;

class FlebitisResourceFactory
{
    public function __invoke($services)
    {
        return new FlebitisResource();
    }
}
