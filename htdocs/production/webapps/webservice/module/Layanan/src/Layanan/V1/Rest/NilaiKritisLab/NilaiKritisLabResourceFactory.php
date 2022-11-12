<?php
namespace Layanan\V1\Rest\NilaiKritisLab;

class NilaiKritisLabResourceFactory
{
    public function __invoke($services)
    {
        return new NilaiKritisLabResource();
    }
}
