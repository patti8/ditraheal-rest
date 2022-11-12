<?php
namespace Pembayaran\V1\Rest\Transfer;

class TransferResourceFactory
{
    public function __invoke($services)
    {
        return new TransferResource();
    }
}
