<?php
namespace RegistrasiOnline\V1\Rest\Pengaturan;

class PengaturanResourceFactory
{
    public function __invoke($services)
    {
        return new PengaturanResource();
    }
}
