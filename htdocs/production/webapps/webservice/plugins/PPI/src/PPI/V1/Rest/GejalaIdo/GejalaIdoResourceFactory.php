<?php
namespace PPI\V1\Rest\GejalaIdo;

class GejalaIdoResourceFactory
{
    public function __invoke($services)
    {
        return new GejalaIdoResource();
    }
}
