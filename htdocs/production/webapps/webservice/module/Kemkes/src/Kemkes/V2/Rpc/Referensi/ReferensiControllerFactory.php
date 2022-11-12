<?php
namespace Kemkes\V2\Rpc\Referensi;

class ReferensiControllerFactory
{
    public function __invoke($controllers)
    {
        return new ReferensiController($controllers);
    }
}
