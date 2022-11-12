<?php
namespace Mutu\V1\Rest\Analisa;

class AnalisaResourceFactory
{
    public function __invoke($services)
    {
        return new AnalisaResource();
    }
}
