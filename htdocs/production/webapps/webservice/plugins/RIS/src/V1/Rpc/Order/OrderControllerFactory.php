<?php
namespace RIS\V1\Rpc\Order;

class OrderControllerFactory
{
    public function __invoke($controllers)
    {
        return new OrderController($controllers);
    }
}
