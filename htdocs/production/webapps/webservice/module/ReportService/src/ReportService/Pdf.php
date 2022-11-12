<?php
namespace ReportService;

class Pdf extends ReportType
{
	protected function reportInit() {
		$this->export =new \Java("net.sf.jasperreports.engine.export.JRPdfExporter");
	}
	
	protected function reportSetting() {
		$this->outputPath = $this->rootPath."/output/".$this->rptName.".".$this->rptExt;
		$this->file = new \Java("java.io.File", $this->outputPath);
		$this->sei = new \Java("net.sf.jasperreports.export.SimpleExporterInput", $this->jasperPrint);
		$this->soseo = new \Java("net.sf.jasperreports.export.SimpleOutputStreamExporterOutput", $this->file);
		$this->export->setExporterInput($this->sei);
		$this->export->setExporterOutput($this->soseo);

		$this->configuration = java("net.sf.jasperreports.export.PdfExporterConfiguration");
	}
	
	protected function download() {
		$id = null;
		$tgl = null;
		$success = false;
		$title = isset($this->config["TITLE"]) ? $this->config["TITLE"] : $this->rptName;
		if($this->fileInfo["exists"]) {
			$content = file_get_contents($this->fileInfo["path"]."/".$this->fileInfo["name"]);
			$content = base64_decode($content);
			$content = str_replace("data:application/pdf;base64,", "", $content);
			$content = base64_decode($content);
		}
		if(!$this->fileInfo["exists"]) {
			exec("sudo chmod 775 -Rf ".$this->outputPath);
			$content = file_get_contents($this->outputPath);
			$content = $this->setPdfTitle($content, $title);
		}
		
		if(!$this->responseEmpty) {
			$headers = $this->response->getHeaders();
			$headers->clearHeaders()
				->addHeaderLine('Content-Type', 'application/pdf')
				->addHeaderLine('Content-Disposition', 'inline; filename="' . $this->rptFileName.".".$this->rptExt.'"');

			header("Content-type: application/pdf");
			//readfile($this->outputPath);
			
			echo $content;
		}
		
		try {					
			if($this->requestReportStorage) {				
				$cfg = $this->controller->getConfig();
				$ds = null;
				$content = "data:application/pdf;base64,".base64_encode($content);
				$content = base64_encode($content);
				if(isset($cfg["DocumentStorage"])) $ds = $cfg["DocumentStorage"];
				if($ds) {
					if(!empty($this->requestReportStorage["TTD_OLEH"])) {
						$id = $this->requestReportStorage["ID"];
						$tgl = $this->requestReportStorage["DIBUAT_TANGGAL"];
						if($this->requestReportStorage["STATUS"] == 2) {
							$params = [
								"NAME" => $title,
								"EXT" => "pdf",
								"DOCUMENT_DIRECTORY_ID" => $this->requestReportStorage["DOCUMENT_DIRECTORY_ID"],
								"REFERENCE_ID" => $this->requestReportStorage["REF_ID"],
								"CREATED_BY" => $this->requestReportStorage["NAMA_USER"],
								"DATA" => $content
							];
							if(isset($this->requestReportStorage["REVISION_FROM"])) $params["REVISION_FROM"] = $this->requestReportStorage["REVISION_FROM"];
							$result = $this->controller->doSendRequest([
								"url" => $ds["url"],
								"action" => "document",
								"method" => "POST",
								"data" => $params
							]);
							
							if($result) {
								$result = (array) $result;
								if($result["success"]) {
									$success = true;
									$this->documentStorageId = $result["data"]->ID;									
								}
							}
						}
					}
					
				}
			}
		} catch(\Exception $e) {
		} finally {
			if(!$success) {
				if($this->requestReportStorage) {
					if($this->requestReportStorage["STATUS"] == 2) {
						$tgls = explode(" ", $tgl);
						$tgls = explode("-", $tgls[0]);
						$path = "reports/output/".$tgls[0]."/".$tgls[1]."/".$tgls[2];
						exec("sudo mkdir -p ".$path);
						file_put_contents($path."/".$id, $content);
					}
				}
			} else {
				if($this->fileInfo["exists"]) unlink($this->fileInfo["path"]."/".$this->fileInfo["name"]);
			}
			unlink($this->outputPath);	
		}
	}

	private function setPdfTitle($content, $title) {
		$pos = strpos($content, "/Title");
		if(!$pos) {
			$pos = strpos($content, "/CreationDate");
			if($pos > -1) {
				$contents = substr($content, strpos($content, "/CreationDate"));
				$poscr = strpos($contents, "/", 1);
				$str = substr($contents, 0, $poscr);
				if($poscr > -1) {
					$newstr = $str."/Title (".$title.")";
					$content = str_replace($str, $newstr, $content);
				}
			}
		}

		return $content;
	}
}
