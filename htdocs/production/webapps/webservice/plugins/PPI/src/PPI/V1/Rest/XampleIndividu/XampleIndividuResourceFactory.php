<?php
namespace PPI\V1\Rest\XampleIndividu;

class XampleIndividuResourceFactory
{
    public function __invoke($services)
    {
        return new XampleIndividuResource();
    }
}
