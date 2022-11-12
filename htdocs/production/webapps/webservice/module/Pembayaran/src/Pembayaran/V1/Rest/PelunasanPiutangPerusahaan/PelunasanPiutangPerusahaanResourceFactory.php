<?php
namespace Pembayaran\V1\Rest\PelunasanPiutangPerusahaan;

class PelunasanPiutangPerusahaanResourceFactory
{
    public function __invoke($services)
    {
        return new PelunasanPiutangPerusahaanResource();
    }
}
