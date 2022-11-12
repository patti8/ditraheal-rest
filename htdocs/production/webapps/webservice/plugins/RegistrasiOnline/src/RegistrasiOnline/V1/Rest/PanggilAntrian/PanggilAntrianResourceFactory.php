<?php
namespace RegistrasiOnline\V1\Rest\PanggilAntrian;

class PanggilAntrianResourceFactory
{
    public function __invoke($services)
    {
        return new PanggilAntrianResource();
    }
}
