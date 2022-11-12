<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarianDetail;

class PenilaianKegiatanHarianDetailResourceFactory
{
    public function __invoke($services)
    {
        return new PenilaianKegiatanHarianDetailResource();
    }
}
