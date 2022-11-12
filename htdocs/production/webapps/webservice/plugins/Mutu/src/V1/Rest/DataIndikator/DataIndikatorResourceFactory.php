<?php
namespace Mutu\V1\Rest\DataIndikator;

class DataIndikatorResourceFactory
{
    public function __invoke($services)
    {
        return new DataIndikatorResource();
    }
}
