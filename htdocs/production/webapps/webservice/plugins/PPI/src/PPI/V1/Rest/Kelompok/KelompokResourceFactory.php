<?php
namespace PPI\V1\Rest\Kelompok;

class KelompokResourceFactory
{
    public function __invoke($services)
    {
        return new KelompokResource();
    }
}
