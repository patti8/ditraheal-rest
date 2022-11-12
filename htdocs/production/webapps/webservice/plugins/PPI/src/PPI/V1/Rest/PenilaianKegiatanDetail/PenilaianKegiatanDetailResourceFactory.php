<?php
namespace PPI\V1\Rest\PenilaianKegiatanDetail;

class PenilaianKegiatanDetailResourceFactory
{
    public function __invoke($services)
    {
        return new PenilaianKegiatanDetailResource();
    }
}
