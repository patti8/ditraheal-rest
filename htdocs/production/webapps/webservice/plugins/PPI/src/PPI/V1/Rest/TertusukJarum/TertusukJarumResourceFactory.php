<?php
namespace PPI\V1\Rest\TertusukJarum;

class TertusukJarumResourceFactory
{
    public function __invoke($services)
    {
        return new TertusukJarumResource();
    }
}
