<?php
namespace RegistrasiOnline\V1\Rpc\Plugins;

class PluginsControllerFactory
{
    public function __invoke($controllers)
    {
        return new PluginsController($controllers);
    }
}
