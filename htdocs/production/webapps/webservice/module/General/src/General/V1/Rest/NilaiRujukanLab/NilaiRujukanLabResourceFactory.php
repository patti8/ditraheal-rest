<?php
namespace General\V1\Rest\NilaiRujukanLab;

class NilaiRujukanLabResourceFactory
{
    public function __invoke($services)
    {
        return new NilaiRujukanLabResource();
    }
}
