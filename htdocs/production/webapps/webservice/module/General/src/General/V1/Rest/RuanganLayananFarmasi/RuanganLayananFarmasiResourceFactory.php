<?php
namespace General\V1\Rest\RuanganLayananFarmasi;

class RuanganLayananFarmasiResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganLayananFarmasiResource();
    }
}
