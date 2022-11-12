<?php
namespace PPI\V1\Rest\KegiatanGroup;

class KegiatanGroupResourceFactory
{
    public function __invoke($services)
    {
        return new KegiatanGroupResource();
    }
}
