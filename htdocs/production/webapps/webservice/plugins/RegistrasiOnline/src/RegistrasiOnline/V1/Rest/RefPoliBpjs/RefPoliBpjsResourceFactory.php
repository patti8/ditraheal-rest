<?php
namespace RegistrasiOnline\V1\Rest\RefPoliBpjs;

class RefPoliBpjsResourceFactory
{
    public function __invoke($services)
    {
        return new RefPoliBpjsResource();
    }
}
