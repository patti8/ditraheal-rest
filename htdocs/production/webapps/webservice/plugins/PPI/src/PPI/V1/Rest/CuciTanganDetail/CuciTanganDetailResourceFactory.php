<?php
namespace PPI\V1\Rest\CuciTanganDetail;

class CuciTanganDetailResourceFactory
{
    public function __invoke($services)
    {
        return new CuciTanganDetailResource();
    }
}
