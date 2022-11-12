<?php
namespace RIS\V1\Rpc\Viewer;

use DBService\RPCResource;

class ViewerController extends RPCResource
{
    private $driver;

    public function __construct($controller) {
        $this->authType = self::AUTH_TYPE_LOGIN;
	    $this->config = $controller->get('Config');
        $this->config = $this->config['services']['RIService']["viewer"];

        $driver = "\\RIS\\driver\\viewer\\".$this->config["name"];
        $this->driver = new $driver($this->config, $this);        
    }
    
    public function get($id)
    {
        $this->driver->getViewer($id);
        exit;
    }

    public function supportViewerWithIframeAction()
    {
        $support = $this->driver->supportViewerWithIframe();
        return [
            "support" => $support
        ];
    }    
}
