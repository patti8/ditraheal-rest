<?php
namespace RegistrasiOnline\V1\Rest\PosAntrian;

class PosAntrianResourceFactory
{
    public function __invoke($services)
    {
        return new PosAntrianResource();
    }
}
