<?php
namespace Layanan\V1\Rest\PemakaianBhpDetil;

class PemakaianBhpDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PemakaianBhpDetilResource();
    }
}
