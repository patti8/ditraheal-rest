<?php
namespace Mutu\V1\Rest\Laporan;

class LaporanResourceFactory
{
    public function __invoke($services)
    {
        return new LaporanResource();
    }
}
