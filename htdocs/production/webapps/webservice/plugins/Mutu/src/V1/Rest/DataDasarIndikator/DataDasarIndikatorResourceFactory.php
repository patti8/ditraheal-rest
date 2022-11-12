<?php
namespace Mutu\V1\Rest\DataDasarIndikator;

class DataDasarIndikatorResourceFactory
{
    public function __invoke($services)
    {
        return new DataDasarIndikatorResource();
    }
}
