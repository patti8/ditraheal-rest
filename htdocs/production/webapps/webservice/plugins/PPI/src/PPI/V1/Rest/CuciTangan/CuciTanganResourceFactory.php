<?php
namespace PPI\V1\Rest\CuciTangan;

class CuciTanganResourceFactory
{
    public function __invoke($services)
    {
        return new CuciTanganResource();
    }
}
