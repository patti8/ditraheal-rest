<?php
namespace PPI\V1\Rest\XampelDetail;

class XampelDetailResourceFactory
{
    public function __invoke($services)
    {
        return new XampelDetailResource();
    }
}
