<?php
namespace Mutu\V1\Rest\RuanganIndikator;

class RuanganIndikatorResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganIndikatorResource();
    }
}
