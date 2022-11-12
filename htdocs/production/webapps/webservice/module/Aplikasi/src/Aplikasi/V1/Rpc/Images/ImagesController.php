<?php
namespace Aplikasi\V1\Rpc\Images;

use DBService\RPCResource;
use Aplikasi\V1\Rest\Instansi\InstansiService;

class ImagesController extends RPCResource
{    
    private $instansi;
    
    public function __construct($controller) {
        $this->authType = self::AUTH_TYPE_NOT_SECURE;
        $this->instansi = new InstansiService();
    }

    public function backgroundAction() {
        $instansi = $this->instansi->load([
            "start" => 0,
            "limit" => 1
        ]);
        $id = $instansi[0]["PPK"];        
        $tipe = "image/jpeg";
        $path = realpath('.').'/images/'.$id.'-background.jpg';
        if(file_exists($path)) {
            $image = file_get_contents($path);
        } else {
            $path = realpath('.').'/images/background.jpg';
            $image = file_get_contents($path);
        }
        
        return $this->downloadDocument($image, $tipe, "jpg", md5($id), false);
    }
}
