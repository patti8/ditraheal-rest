<?php
namespace PPI\V1\Rest\AuditIndividu;

class AuditIndividuResourceFactory
{
    public function __invoke($services)
    {
        return new AuditIndividuResource();
    }
}
