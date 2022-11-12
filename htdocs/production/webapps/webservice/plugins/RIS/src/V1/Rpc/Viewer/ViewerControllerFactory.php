<?php
namespace RIS\V1\Rpc\Viewer;

class ViewerControllerFactory
{
    public function __invoke($controllers)
    {
        return new ViewerController($controllers);
    }
}
