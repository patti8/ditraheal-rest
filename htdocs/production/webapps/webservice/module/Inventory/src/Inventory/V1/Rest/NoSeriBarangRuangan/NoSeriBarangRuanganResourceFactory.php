<?php
namespace Inventory\V1\Rest\NoSeriBarangRuangan;

class NoSeriBarangRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new NoSeriBarangRuanganResource();
    }
}
