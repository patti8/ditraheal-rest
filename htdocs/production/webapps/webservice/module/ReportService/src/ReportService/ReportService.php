<?php
namespace ReportService;

use DBService\JavaConnection;
use DBService\Crypto;
use Laminas\Authentication\Storage\Session as StorageSession;
use Aplikasi\db\request_report\Service as RequestServiceStorage;

class ReportService extends JavaConnection
{
	/**
     * @var ReportServiceOption
     */
	private $conns = array();
		
	private $driver;
	private $driverManager;
	private $locale;
	private $localeCode;
	
	private $crypto;
	private $report;
	private $key;
	private $rss;

	private $controller;
    
    public function __construct($config) {
        parent::__construct($config);
		
		$this->key = $this->config['key'];
		$this->crypto = new Crypto();

		$this->storageSession = new StorageSession("RR", "RPT");
		$this->rss = new RequestServiceStorage();
		
		$this->connectionInit();
		$this->locale = new \Java("java.util.Locale", $this->config['db']['locale'][0], $this->config['db']['locale'][1]);
		$this->localeCode = $this->config['db']['locale'][0].'_'.$this->config['db']['locale'][1];
		$this->report = new Report($this->locale, $this->localeCode);
    }

	public function setController($controller) {
		$this->controller = $controller;
		$this->report->setController($controller);
	}
	
	private function connectionInit() {
		$conns = $this->config['db']['connectionStrings'];
		foreach($conns as $conn) {		
			$this->conns[] = $this->createConnection($conn);
		}		
	}
	
	private function createConnection($connString) {
		try {
			$this->driver = new \Java($this->config['db']['driver']);	
			$this->driverManager = \Java($this->config['db']['driverManager']);			
			$this->driverManager->registerDriver($this->driver);			
			$conn = $this->driverManager->getConnection($connString);			
			return $conn;
		} catch(\JavaException $e) {
			echo "</br>Error: can't connect db";
			return array(
				'error' => $e->getMessage()
			);
		}
	}
	
	private function closeConnections() {
		foreach($this->conns as $conn) {
			if($conn) $conn->close();
		}
		$this->conns = array();
	}
	
	public function getReport() {
		return $this->report;
	}
	
    public function generate($response, $requestReport) {
		$data = null;
		$exists = false;
		if(file_exists("reports/output/".$requestReport)) { // REQUEST_FOR_PRINT
			$exists = true;
			$data = file_get_contents("reports/output/".$requestReport);
			unlink("reports/output/".$requestReport);
		} else {
			$data = $this->storageSession->read();
			$this->storageSession->write([]);
			if($data) $exists = true;
		}

		$dss = null;
		$dssStatus = 1;
		if(!$exists) {
			$dss = $this->rss->load([
				"KEY" => $requestReport
			]);
			if(count($dss) > 0) {
				$data = $dss[0]["REQUEST_DATA"];
				$dss = $dss[0];
				$dssStatus = $dss["STATUS"];
				if($dssStatus == 1) {
					$revs = $this->rss->load([
						"DOCUMENT_DIRECTORY_ID" => $dss["DOCUMENT_DIRECTORY_ID"],
						"REF_ID" => $dss["REF_ID"],
						"start" => 0,
						"limit" => 1,
						"STATUS" => 2
					], ['*'], ['DIBUAT_TANGGAL DESC']);
					if(count($revs) > 0) $dss["REVISION_FROM"] = $revs[0]["ID"];
				}
			}
		}

		if($data) {
			$this->crypto->setKey($requestReport);			
            $data = $this->crypto->decrypt($data);
            $data = base64_decode($data);
			$data = (array) json_decode($data);

			if($dss) {
				if($dssStatus == 1) {
					if(empty($dss["TTD_OLEH"])) {
						if(isset($data["DOCUMENT_STORAGE"])) {
							if(isset($data["DOCUMENT_STORAGE"]->SIGN)) {
								if($data["DOCUMENT_STORAGE"]->SIGN == 1) {
									$this->rss->simpanData([
										"ID" => $dss["ID"],
										"TTD_OLEH" => $data["USER_ID"],
										"TTD_TANGGAL" => new \Laminas\Db\Sql\Expression("NOW()")
									], false, false);
									$dss["TTD_OLEH"] = $data["USER_ID"];
								}
							}
						}
					} else {
						if(isset($data["DOCUMENT_STORAGE"])) {
							if(isset($data["DOCUMENT_STORAGE"]->FINAL)) {
								if($data["DOCUMENT_STORAGE"]->FINAL == 1) {
									$this->rss->simpanData([
										"ID" => $dss["ID"],
										"STATUS" => 2,
									], false, false);
									$dss["STATUS"] = 2;
								}
							}
						}
					}
				} else {
					$dss["REQUEST_BY_ID"] = true;
				}
				$this->report->setRequestReportStorage($dss);
			}

			$id = $this->report->create(
				$response,
				$this->conns[$data["CONNECTION_NUMBER"]], 
				$data
			);

			if($id) {
				if($dss) {
					$this->rss->simpanData([
						"ID" => $dss["ID"],
						"DOCUMENT_STORAGE_ID" => $id
					], false, false);
				}
			}
		}

		return $response;
	}	
}
