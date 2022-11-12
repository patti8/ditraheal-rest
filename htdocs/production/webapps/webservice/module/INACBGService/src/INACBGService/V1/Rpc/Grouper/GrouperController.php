<?php
namespace INACBGService\V1\Rpc\Grouper;

use DBService\RPCResource;
use Laminas\View\Model\JsonModel;

use INACBGService\db\dokumen_pendukung\Service as DokumenPendukungService;
use Laminas\ApiTools\ApiProblem\ApiProblem;

class GrouperController extends RPCResource
{
	public function __construct($controllers) 
	{
		$this->authType = self::AUTH_TYPE_IP_OR_LOGIN;
		$this->service = $controllers->get('INACBGService\Service');		
	}

	public function getList()
    {
		$this->response->setStatusCode(405); 
		return new JsonModel([
			'data' => [
				'metaData' => [
					'message' => 'Method Not Allowed',
					'code' => 405,
					'requestId' => $this->service->getKodeRS()
				]
			]
		]);
    }
	
	public function create($data)
    {        
		$data = (array) $this->service->grouper($data);
		
		return new JsonModel([
            'data' => $data
		]);
    }
	
	public function klaimBaruAction()
    {        
		$data = $this->getPostData();	
		$data = (array) $this->service->klaimBaru($data);
		return new JsonModel([
            'data' => $data
		]);
	}
	
	public function kirimKlaimIndividualAction()
    {        
		$data = $this->getPostData();	
		$data = (array) $this->service->kirimKlaimIndividual($data);
		return new JsonModel([
            'data' => $data
		]);
    }
	
	public function kirimKlaimAction()
    {        
		$data = $this->getPostData();	
		$data = (array) $this->service->kirimKlaim($data);
		return new JsonModel([
            'data' => $data
		]);
    }
	
	public function batalKlaimAction()
    {
		$data = $this->getPostData();
		$data = (array) $this->service->batalKlaim($data);
		return new JsonModel([
            'data' => $data
		]);
	}
	
	public function generateNomorKlaimAction()
    {
		$data = $this->getPostData();
		$data = (array) $this->service->generateNomorKlaim($data);
		return new JsonModel([
            'data' => $data
		]);
	}

	public function uploadFilePendukungAction()
    {
		$data = $this->getPostData();
		$data = (array) $this->service->uploadFilePendukung($data);
		return new JsonModel([
            'data' => $data
		]);
	}

	public function hapusFilePendukungAction()
    {
		$data = $this->getPostData();
		$data = (array) $this->service->hapusFilePendukung($data);
		return new JsonModel([
            'data' => $data
		]);
	}

	public function daftarFilePendukungAction()
    {
		$data = $this->getPostData();
		$data = (array) $this->service->daftarFilePendukung($data);
		return new JsonModel([
            'data' => $data
		]);
	}

	public function dokumenPendukungAction()
    {		
		$request = $this->getRequest();
		$params = (array) $request->getQuery();
		$data = $this->getPostData();
		$method = $request->getMethod();		        
        $id = "";
        if($method == "PUT" || $method == "GET") {
            $paths = explode("/", $this->getRequest()->getUri()->getPath());
            $id = $paths[count($paths) - 1];
		}
		$dps = new DokumenPendukungService();

		if($method == "GET") {			
			if($id != "") {
				$data = $dps->load([
					"id" => $id
				], ["file_name", "file_type", "file_content"]);
				if(count($data) > 0) {					
					$data = $data[0];
					$data["file_content"] = base64_encode($data["file_content"]);
					return $data;
				}
			}
			$data = $dps->load([
				"no_klaim" => isset($params["no_klaim"]) ? $params["no_klaim"] : "",
				"file_class" => isset($params["file_class"]) ? $params["file_class"] : "",
				"status" => 1
			], ["id", "no_klaim", "file_id", "file_class", "file_name", "file_size", "file_type", "kirim_bpjs"]);
			$total = count($data);
			
			return [
				"status" => $total > 0 ? 200 : 404,
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data,
				"detail" => "Data ".($total > 0 ? " ditemukan" : " tidak ditemukan")
			];
		}

		if($method == "PUT") {
			$tipe = $data["tipe"];	
			$message = "Gagal";		
			$rows = $dps->load([
				"id" => $id
			]);
			if(isset($data["status"])) {
				if($data["status"] == 0) {
					$data = $rows[0];
					$result = $this->service->hapusFilePendukung([
						"nomor_sep" => $data["no_klaim"],
						"file_id" => $data["file_id"],
						"tipe" => $tipe
					]);
					if($result) {
						if($result->metadata->code == 200) {
							$dps->simpanData([
								"id" => $id,
								"status" => 0
							], false, false);
						}
					}
					return new JsonModel([
						'data' => $result
					]);
				}

				$message = "Gagal hapus berkas";
			}

			if(isset($data["kirim_bpjs"])) {
				if($data["kirim_bpjs"] == 1) {
					$data = $rows[0];
					$content = base64_encode($data["file_content"]);
					$result = $this->service->hapusFilePendukung([
						"nomor_sep" => $data["no_klaim"],
						"file_id" => $data["file_id"],
						"tipe" => $tipe
					]);
					if($result) {
						if($result->metadata->code == 200) {
							$result = $this->service->uploadFilePendukung([
								"nomor_sep" => $data["no_klaim"],
								"file_class" => $data["file_class"],
								"file_name" => $data["file_name"],						
								"tipe" => $tipe,
								"content" => $content
							]);
							if($result) {
								$metadata = $result->metadata;
								$response = isset($metadata->response) ? $metadata->response : $result->response;
								$bpjs = isset($metadata->upload_dc_bpjs_response) ? $metadata->upload_dc_bpjs_response : (isset($result->upload_dc_bpjs_response) ? $result->upload_dc_bpjs_response : null);
								
								$data["file_id"] = $response->file_id;
								if($bpjs) $data["kirim_bpjs"] = 0;
								else $data["kirim_bpjs"] = 1;

								$dps->simpanData([
									"id" => $id,
									"kirim_bpjs" => $data["kirim_bpjs"],
									"file_id" => $response->file_id
								], false, false);
							}
						}
					}
										
					return new JsonModel([
						'data' => $result
					]);
				}

				$message = "Gagal kirim berkas";
			}	
			
			return new JsonModel([
				'data' => [
					"status" => 404,
					"detail" => $message
				]
			]);
		}

		if($method == "POST") {			
			$dataContent = $dps->getFileContentFromPost($data["file_content"], ["application/pdf"]);
			$content = base64_encode($dataContent["content"]);
			$result = $this->service->uploadFilePendukung([
				"nomor_sep" => $data["no_klaim"],
				"file_class" => $data["file_class"],
				"file_name" => $data["file_name"],
				"tipe" => $data["tipe"],
				"content" => $content
			]);
			if($result) {
				$metadata = $result->metadata;
				$response = isset($metadata->response) ? $metadata->response : $result->response;
				$bpjs = isset($metadata->upload_dc_bpjs_response) ? $metadata->upload_dc_bpjs_response : (isset($result->upload_dc_bpjs_response) ? $result->upload_dc_bpjs_response : null);
				file_put_contents("logs/hasil_upload.txt", json_encode($result));
				$data["file_id"] = $response->file_id;
				if($bpjs) $data["kirim_bpjs"] = 0;
				else $data["kirim_bpjs"] = 1;
			}
									
			if($dataContent instanceof ApiProblem) {
				$error = $dataContent->toArray();
				$this->response->setStatusCode($errors["status"]);
				return new JsonModel([
					'data' => $error
				]);
			}
			$data["file_content"] = $dataContent["content"];
			$dps->simpanData($data, true, false);
			
			return new JsonModel([
				'data' => [
					"status" => 200,
					"detail" => "Upload file berhasil",
					"eklaim" => $result,
					"bpjs" => $bpjs					
				]
			]);
		}
	}
}