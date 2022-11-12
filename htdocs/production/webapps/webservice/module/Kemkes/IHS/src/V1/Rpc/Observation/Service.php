<?php
namespace Kemkes\IHS\V1\Rpc\Observation;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService
{    
    public function __construct($includeReferences = true, $references = []) 
    {
        $this->config["entityName"] = "Kemkes\\IHS\\V1\\Rpc\\Observation\\Entity";
        $this->config["autoIncrement"] = false;
        $this->config["entityId"] = "refId";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("observation", "kemkes-ihs"));
        $this->entity = new Entity();
    }

    public function simpan($data) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);

        $params = [
            'refId' => $this->entity->get('refId'), 
            'jenis' => $this->entity->get('jenis')
        ];

        $this->table->update($this->entity->getArrayCopy(), $params);

        return $this->load($params);
	}
	
}