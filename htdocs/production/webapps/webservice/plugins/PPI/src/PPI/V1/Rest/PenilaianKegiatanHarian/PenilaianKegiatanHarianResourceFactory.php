<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarian;

class PenilaianKegiatanHarianResourceFactory
{
    public function __invoke($services)
    {
        return new PenilaianKegiatanHarianResource();
    }
}
