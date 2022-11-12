<?php
namespace PPI\V1\Rest\TindakLanjut;

class TindakLanjutResourceFactory
{
    public function __invoke($services)
    {
        return new TindakLanjutResource();
    }
}
