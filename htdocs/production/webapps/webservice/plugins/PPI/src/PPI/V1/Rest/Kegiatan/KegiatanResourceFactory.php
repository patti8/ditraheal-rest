<?php
namespace PPI\V1\Rest\Kegiatan;

class KegiatanResourceFactory
{
    public function __invoke($services)
    {
        return new KegiatanResource();
    }
}
