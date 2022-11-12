<?php
namespace Kemkes\IHS\V1\Rpc\MedicationDispanse;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService
{    
    public function __construct($includeReferences = true, $references = []) 
    {
        $this->config["entityName"] = "Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Entity";
        $this->config["autoIncrement"] = false;
        $this->config["entityId"] = "refId";
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("medication_dispanse", "kemkes-ihs"));
        $this->entity = new Entity();
    }

    public function simpan($data) {
	    $data = is_array($data) ? $data : (array) $data;
	    $this->entity->exchangeArray($data);
        
        $params = [
            'refId' => $this->entity->get('refId'), 
            'barang' => $this->entity->get('barang'),
            'group_racikan' => $this->entity->get('group_racikan')
        ];

        $this->table->update($this->entity->getArrayCopy(), $params);

        return $this->load($params);
	}
	
}