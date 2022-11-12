<?php
namespace INACBGService\db\dokumen_pendukung;

use DBService\DatabaseService;
use Laminas\Db\Sql\TableIdentifier;
use DBService\Service as DBService;

class Service extends DBService {
	public function __construct($includeReferences = true, $references = array()) {
        $this->config["entityName"] = "INACBGService\\db\\dokumen_pendukung\Entity";
        $this->config["entityId"] = "id";

		$this->table = DatabaseService::get("SIMpel")->get(new TableIdentifier("dokumen_pendukung", "inacbg"));
		$this->entity = new Entity();
	}
}