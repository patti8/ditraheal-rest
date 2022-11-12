<?php
namespace RegistrasiOnline\V1\Rest\Istansi;

class IstansiResourceFactory
{
    public function __invoke($services)
    {
        return new IstansiResource();
    }
}
