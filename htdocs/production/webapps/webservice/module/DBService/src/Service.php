<?php
namespace DBService;

use Laminas\Db\Sql\Select;
use DBService\generator\Generator;
use Laminas\ApiTools\ApiProblem\ApiProblem;

class Service
{
	protected $table;
	protected $entity;
	protected $columns;
	protected $limit = 100;
	
	protected $includeReferences = true;
	protected $references = [];
	protected $privilage = false;
	protected $user = '';
	protected $userAkses;
	protected $writeLog = true;
	
	/* data ini adalah data sebelum di lakukan perubahan pada fungsi create update delete / first load */
	protected $data;
	protected $firstLoad = true;
	
	protected $config = [
	    "entityName" => "Entity",
	    "entityId" => "ID",
	    "autoIncrement" => true,
		"generateUUID" => false // jika uuid true maka abaikan autoIncrement
	];

	public function recreateTable() {}
	protected function queryCallback(Select &$select, &$params, $columns, $orders) {}
	protected function onBeforeSaveCallback($key, &$entity, &$data, $isCreated = false) {}
	protected function onAfterSaveCallback($id, $data) {}

	protected $resource;
	
	public function getRowCount($params = []) {
		$params = (array) $params;
		if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {
			unset($params['start']);
			unset($params['limit']);
		}
		$result = $this->query(['*'], $params, true);
		if(count($result) > 0){
			return (int) $result[0]['rows'];
		}
		return 0;
	}
	
	public function setReferences($references = [], $replace = false) {
		if($replace) {
			foreach($this->references as $key => $val) {
				$this->references[$key] = false;
			}
		}
		
		foreach($references as $key => $val) {
			$this->references[$key] = $val;
		}
	}
	
	public function setColumns($columns = []) {
		$this->columns = $this->entity->getFilterFields($columns);
	}
	
	public function setPrivilage($privilage) {
		$this->privilage = $privilage;
	}
	
	public function getEntity() {
		$entity = isset($this->entity) ? $this->entity : null;
		$entity = ($entity instanceof SystemArrayObject) ? $entity : null;
		return $entity;
	}
	
	public function getTable() {
		return $this->table;
	}

	public function getTableName() {
		$table = $this->table->getTable();
	    $tableName = is_object($table) ? $table->getTable() : $table;
		return $tableName;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function isWriteLog() {
		return $this->writeLog;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}

	public function setUserAkses($akses) {
		$this->userAkses = $akses;
	}
	
	public function load($params = [], $columns = ['*'], $orders = []) {
		if(isset($this->columns)) $columns = $this->columns;
		$result = $this->query($columns, $params, false, $orders); 
		if($this->firstLoad) {
			$this->data = $result;
			$this->firstLoad = false;
		}
		return $result;
	}
	
	public function simpan($data) {}
	
	public function simpanData($data, $isCreated = false, $loaded = true) {
	    $data = is_array($data) ? $data : (array) $data;
	    $entity = $this->config["entityName"];
	    $key = $this->config["entityId"];
	    $this->entity = new $entity();
	    $this->entity->exchangeArray($data);
		
		$this->onBeforeSaveCallback($key, $this->entity, $data, $isCreated);
		
	    if($isCreated) {
			if($this->config["generateUUID"]) {
				$id = Generator::generateUUID();
				$this->entity->set($key, $id);
			}
	        $this->table->insert($this->entity->getArrayCopy());
	        if($this->config["autoIncrement"] && !$this->config["generateUUID"]) $id = $this->table->getLastInsertValue();
	        else $id = $id = $this->entity->get($key);
	    } else {
	        $id = $this->entity->get($key);
	        $this->table->update($this->entity->getArrayCopy(), [$key => $id]);
	    }
		
		$this->onAfterSaveCallback($id, $data);
		
	    if($loaded) {
			$tableName = $this->getTableName();
			return $this->load([$tableName.".".$key => $id]);
		}
	    return $id;
	}
	
	// data:application/vnd.ms-excel
	public function getFileContentFromPost($data, $validasiFormat = [], $msg = "Salah upload tipe file") {
	    $data = base64_decode($data);
	    $datas = explode(";", $data);
	    $tipes = explode(":", $datas[0]);
	    $isVf = true;
	    foreach($validasiFormat as $vf) {
	        if($tipes[1] != $vf) {
	            $isVf = false;
	            break;
	        }
	    }
	    if(!$isVf || count($datas) < 2) return new ApiProblem(422, $msg);

	    $content = str_replace("base64,", "", $datas[1]);
	    
	    return [
	        "tipe" => $tipes[1],
	        "content" => (base64_decode($content))
		];
	}
	
	public function getFileContentFromBlob($data, $type) {
		return "data:".$type.";base64,".base64_encode($data);
	}
	
	protected function query($columns, $params, $isCount = false, $orders = []) {		
		$params = is_array($params) ? $params : (array) $params;		
		return $this->table->select(function(Select $select) use ($isCount, $params, $columns, $orders) {
			if($isCount) $select->columns(['rows' => new \Laminas\Db\Sql\Expression('COUNT(1)')]);
			else if(!$isCount) $select->columns($columns);
			
			if(!System::isNull($params, 'start') && !System::isNull($params, 'limit')) {	
				if(!$isCount) $select->offset((int) $params['start'])->limit((int) $params['limit']);
				unset($params['start']);
				unset($params['limit']);
			} else $select->offset(0)->limit($this->limit);

			$this->queryCallback($select, $params, $columns, $orders);
			
			$select->where($params);
			$select->order($orders);
		})->toArray();
	}
	
	public function execute($sql, $params = []) {
	    $adapter = $this->table->getAdapter();
	    $stmt = $adapter->query($sql);
	    $data = [];
	    try {
	        $result = $stmt->execute($params);
	        foreach($result as $rst) {
	            $data[] = $rst;
			}
			$result->getResource()->closeCursor();
	    } catch(\Exception $e) {}
	    return $data;
	}
	
	public function parseReferensi(&$entity, $tableName = "") {
		if($tableName == "") {
			$table = $this->table->getTable();
			$tableName = is_object($table) ? $table->getTable() : $table;
		}
	    $data = [];
	    foreach($entity as $key => $val) {
			$_key = substr($key, 0, strlen($tableName));
			if($_key == $tableName) {
				if($_key != "") {
					$data[str_replace($tableName."_", "", $key)] = $val;
	            	unset($entity[$key]);
				}
			}
	    }
	    
	    return $data;
	}

	public function getEntityId() {
		return $this->config["entityId"];
	}

	public function setResource($resource) {
		$this->resource = $resource;
	}

	public function disconnect() {
		$adapter = $this->table->getAdapter();
		$driver = $adapter->getDriver();
		$conn = $driver->getConnection();
		$conn->disconnect();
	}

	public function reconnect() {
		$adapter = $this->table->getAdapter();
		$driver = $adapter->getDriver();
		$conn = $driver->getConnection();
		if(!$conn->isConnected()) $conn->connect();
	}

	public function isValidCustomValidationEntity($data) {
		return true;
	}
}