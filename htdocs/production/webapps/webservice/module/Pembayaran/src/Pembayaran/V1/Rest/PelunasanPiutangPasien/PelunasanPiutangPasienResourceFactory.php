<?php
namespace Pembayaran\V1\Rest\PelunasanPiutangPasien;

class PelunasanPiutangPasienResourceFactory
{
    public function __invoke($services)
    {
        return new PelunasanPiutangPasienResource();
    }
}
