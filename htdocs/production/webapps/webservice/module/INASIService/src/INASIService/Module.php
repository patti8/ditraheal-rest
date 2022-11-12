<?php
namespace INASIService;

use Laminas\ApiTools\Provider\ApiToolsProviderInterface;

class Module implements ApiToolsProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\ApiTools\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
	
	public function getServiceConfig() {
		return array(
			'factories' => array(
				'INASIService\Service' => function($sm) {
					$config = $sm->get('Config');
					$config = $config['services']['INASIService'];
					return new \INASIService\Service($config);
				},
			)
		);
	}
}
