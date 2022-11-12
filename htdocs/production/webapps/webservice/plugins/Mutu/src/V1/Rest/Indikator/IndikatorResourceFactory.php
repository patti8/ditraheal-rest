<?php
namespace Mutu\V1\Rest\Indikator;

class IndikatorResourceFactory
{
    public function __invoke($services)
    {
        return new IndikatorResource();
    }
}
