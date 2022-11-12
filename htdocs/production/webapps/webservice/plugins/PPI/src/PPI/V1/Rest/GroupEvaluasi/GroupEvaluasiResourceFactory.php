<?php
namespace PPI\V1\Rest\GroupEvaluasi;

class GroupEvaluasiResourceFactory
{
    public function __invoke($services)
    {
        return new GroupEvaluasiResource();
    }
}
