<?php
namespace ReportService;

class ReportType
{	
	/* report vars */
	protected $export;
	protected $rptName;
	protected $rptExt;
	protected $jasperPrint;
	protected $rootPath;
	protected $print;
	protected $outputPath;
	protected $response;
	protected $rptFileName;

	protected $controller;
	protected $documentStorageId = null;
	protected $requestReportStorage = null;

	protected $responseEmpty = false;
	protected $config;
	protected $fileInfo;
    
    function __construct($config) {
		$this->rptName = $config["rptName"];
		$this->rptExt = $config["rptExt"];
		$this->rptFileName = $config["rptFileName"];
		$this->jasperPrint = $config["jasperPrint"];
		$this->rootPath = $config["rootPath"];
		$this->print = $config["print"];
		$this->response = $config["response"];
		$this->config = isset($config["config"]) ? $config["config"] : null;
		$this->responseEmpty = isset($config["responseEmpty"]) ? $config["responseEmpty"] : false;
		$this->controller = isset($config["controller"]) ? $config["controller"] : null;
		$this->requestReportStorage = isset($config["requestReportStorage"]) ? $config["requestReportStorage"] : null;
		$this->fileInfo = $config["fileInfo"];
		
		if(!$this->fileInfo["exists"]) {
			$this->reportInit();
			$this->reportSetting();
		}

		if(empty($this->rptFileName)) {
			$names = explode(".", $this->rptName);
			$nmRpt = $names[count($names) - 1];
			$names = explode("-", $nmRpt);
			$nmRpt = $names[count($names) - 1];
			$this->rptFileName = $nmRpt;
		}
    }

	protected function reportInit() {
	}
	
	protected function reportSetting() {
	}

	public function toReport() {
		if(!$this->fileInfo["exists"]) $this->export->exportReport();
		if(!$this->print) $this->download();
		else {
			if(!$this->fileInfo["exists"]) $this->requestForPrint();
		}
		return $this->documentStorageId;
    }
	
	protected function download() {
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'text/html');
        
		//header('Content-type: text/html');
		exec("sudo chmod 775 -Rf ".$this->outputPath);
        readfile($this->outputPath);

        unlink($this->outputPath);
	}
	
	protected function requestForPrint() {
		exec("sudo chmod 775 -Rf ".$this->outputPath);
		$data = file_get_contents($this->outputPath);
		$content = base64_encode($data);
					
        unlink($this->outputPath);
		
		echo json_encode(array(
			'content'=>$content
		));
	}	
}
