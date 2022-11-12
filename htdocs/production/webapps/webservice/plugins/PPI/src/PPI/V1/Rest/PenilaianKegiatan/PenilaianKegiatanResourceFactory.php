<?php
namespace PPI\V1\Rest\PenilaianKegiatan;

class PenilaianKegiatanResourceFactory
{
    public function __invoke($services)
    {
        return new PenilaianKegiatanResource();
    }
}
