<?php
namespace General\V1\Rest\Rekening;

class RekeningResourceFactory
{
    public function __invoke($services)
    {
        return new RekeningResource();
    }
}
