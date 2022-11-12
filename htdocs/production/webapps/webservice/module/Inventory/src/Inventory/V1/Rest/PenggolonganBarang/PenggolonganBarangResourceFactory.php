<?php
namespace Inventory\V1\Rest\PenggolonganBarang;

class PenggolonganBarangResourceFactory
{
    public function __invoke($services)
    {
        return new PenggolonganBarangResource();
    }
}
