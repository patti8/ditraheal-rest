<?php
namespace PPI\V1\Rest\KegiatanAlasan;

class KegiatanAlasanResourceFactory
{
    public function __invoke($services)
    {
        return new KegiatanAlasanResource();
    }
}
