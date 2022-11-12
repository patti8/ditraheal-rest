<?php
namespace Kemkes\IHS\V1\Rpc\Composition;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService
{    
    public function __construct($includeReferences = true, $references = []) 
    {
        $this->config["entityName"] = "Kemkes\\IHS\\V1\\Rpc\\Composition\\Entity";
        $this->config["autoIncrement"] = false;
        $this->config["entityId"] = "refId";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("composition", "kemkes-ihs"));
        $this->entity = new Entity();
    }
}