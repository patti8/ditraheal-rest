<?php
namespace Kemkes\SIRS\V1\Rpc\RL4bSebab;

use Kemkes\SIRS\RPCResource;

class RL4bSebabController extends RPCResource
{
	protected $title = "RL 4b Sebab";

	public function __construct($controller) {
        parent::__construct($controller);
		$this->service = new Service();
    }

	/**
     * Return list of resources
     *
     * @return mixed
     */
	public function getList() {
	    $queries = (array) $this->request->getQuery();
	    $params = [
			"page" => 1,
			"limit" => 100
		];
		$data = [];
		$total = 0;
		if(!empty($queries["page"])) $params["page"] = $queries["page"];
		if(!empty($queries["limit"])) $params["limit"] = $queries["limit"];

		if(!empty($queries["remote"])) {
			$result = $this->doSendRequest([
				"url" => $this->config['url'],
				"action" => "rlempatbsebab?".http_build_query($params),
			]);

			if($this->httpcode == 200) {
				if($result->status) {
					$total = ($result->pagination->total_number_of_pages * $params["limit"]);
					$data =  (array) $result->data;
					if(count($data) < $params["limit"]) $total -= ($params["limit"] - count($data));
				}
			}
		} else {
			if(!empty($queries["tahun"])) $params["tahun"] = $queries["tahun"];
			$params["start"] = ($params["page"] * $params["limit"]) - $params["limit"];
			unset($params["page"]);

			try {
				$total = $this->service->getRowCount($params);
				if($total > 0) $data = $this->service->load($params, ['*'], ['tahun DESC', 'no_dtd', 'no_urut']);
			} catch(\Exception $e) {
				return [
					"status" => 412,
					"success" => false,
					"detail" => $e->getMessage()
				];
			}
		}

	    return [
	        "status" => $total > 0 ? 200 : 404,
	        "success" => $total > 0 ? true : false,
	        "total" => $total,
	        "data" => $data,
	        "detail" => $this->title." ".($total > 0 ? "ditemukan" : "tidak ditemukan")
	    ];
	}

	/**
     * kirim data ke kemkes sirs
     *
     * @param  mixed $id
     * @return mixed
     */
    public function kirimAction() {
		$queries = (array) $this->request->getQuery();
	    /* ambil data */
	    $params = [];
	    $params["kirim"] = 1;
		
		if(!empty($queries["id"])) $params["id"] = $queries["id"];
		$return = [
			"status" => 422,
			"success" => false,
			"detail" => "Data tidak terkirim"
		];
		
		$froms = [];
		try {
		    $froms = $this->service->load($params, ['*']);
		} catch(\Exception $e) {
			return [
				"status" => 412,
				"success" => false,
				"detail" => $e->getMessage()
			];
		}

		$result = null;
		$terkirim = $gagal = 0;
	    foreach($froms as &$row) {
	        $params = $row;
	        
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_0-<=6_hari_l"] = $params["0-<=6_hari_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_0-<=6_hari_p"] = $params["0-<=6_hari_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>6-<=28_hari_l"] = $params[">6-<=28_hari_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>6-<=28_hari_p"] = $params[">6-<=28_hari_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>28_hari-<=1_tahun_l"] = $params[">28_hari-<=1_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>28_hari-<=1_tahun_p"] = $params[">28_hari-<=1_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>1-<=4_tahun_l"] = $params[">1-<=4_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>1-<=4_tahun_p"] = $params[">1-<=4_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>4-<=14_tahun_l"] = $params[">4-<=14_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>4-<=14_tahun_p"] = $params[">4-<=14_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>14-<=24_tahun_l"] = $params[">14-<=24_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>14-<=24_tahun_p"] = $params[">14-<=24_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>24-<=44_tahun_l"] = $params[">24-<=44_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>24-<=44_tahun_p"] = $params[">24-<=44_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>44-<=64_tahun_l"] = $params[">44-<=64_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>44-<=64_tahun_p"] = $params[">44-<=64_tahun_p"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>64_tahun_l"] = $params[">64_tahun_l"];
			$params["jumlah_pasien_kasus_menurut_golongan_umur_dan_sex_>64_tahun_p"] = $params[">64_tahun_p"];
			
			unset($params["0-<=6_hari_l"]);
			unset($params["0-<=6_hari_p"]);
			unset($params[">6-<=28_hari_l"]);
			unset($params[">6-<=28_hari_p"]);
			unset($params[">28_hari-<=1_tahun_l"]);
			unset($params[">28_hari-<=1_tahun_p"]);
			unset($params[">1-<=4_tahun_l"]);
			unset($params[">1-<=4_tahun_p"]);
			unset($params[">4-<=14_tahun_l"]);
			unset($params[">4-<=14_tahun_p"]);
			unset($params[">14-<=24_tahun_l"]);
			unset($params[">14-<=24_tahun_p"]);
			unset($params[">24-<=44_tahun_l"]);
			unset($params[">24-<=44_tahun_p"]);
			unset($params[">44-<=64_tahun_l"]);
			unset($params[">44-<=64_tahun_p"]);
			unset($params[">64_tahun_l"]);
			unset($params[">64_tahun_p"]);

			unset($params["object_id"]);
	        unset($params["id"]);
	        unset($params["tanggal_kirim"]);
	        unset($params["kirim"]);
			unset($params["response"]);

			$method = "POST";
			$id = "";
			$data = [
				"data" => [$params]
			];
			if(!empty($row["id"])) {
				unset($params["tahun"]);
				$method = "PATCH";
				$id = "/".$row["id"];
				$data = [
					"data" => $params
				];
			}
	       
	        /* kirim data  */
	        $result = $this->doSendRequest([
				"url" => $this->config['url'],
				"action" => "rlempatbsebab".$id,
				"method" => $method,
				"data" => $data
			]);

			$row = [
				"object_id" => $row["object_id"]
			];
	
			if($this->httpcode == 201 || $this->httpcode == 200) {
				if($result->status) {
					if($this->httpcode == 201) $row["id"] = $result->data[0]->id;
					$row["tanggal_kirim"] = new \Laminas\Db\Sql\Expression('NOW()');
					$row["response"] = new \Laminas\Db\Sql\Expression('NULL');
					$row["kirim"] = 0;
					$terkirim++;
					$return["status"] = 200;
					$return["success"] = true;
					$return["detail"] = "Data berhasil terkirim";
				}
			} else {
				$result = (array) $result;
				$row["response"] = !empty($result["message"]) ? $result["message"] : null;
				$gagal++;
				$return["detail"] = $row["response"];
			}

			try {
				$this->service->simpanData($row);
			} catch(\Exception $e) {
				return [
					"status" => 412,
					"success" => false,
					"detail" => $e->getMessage()
				];
			}
		}
		if(!empty($queries["id"])) {
			$this->response->setStatusCode($return["status"]);
			return $return;
		};
	    
	    return [
			"status" => 200,
			"success" => true,
			"terkirim" => $terkirim,
			"gagal" => $gagal
	    ];
	}

	/**
     * ambil data rl
     *
     * @param  mixed $tahun
     * @return mixed
     */
    public function ambilDataAction() {
		$queries = (array) $this->request->getQuery();
		$tahun = date("Y");
		if(!empty($queries["tahun"])) $tahun = $queries["tahun"];

		try {
			$this->service->ambilData($tahun);
		} catch(\Exception $e) {
			return [
				"status" => 412,
				"success" => false,
				"detail" => $e->getMessage()
			];
		}
		return [
			"status" => 200,
			"success" => true,
			"detail" => "Pengambilan data RL telah dilakukan"
		];
	}

	/**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
		$_id = "";
		try {
			$find = $this->service->load([
				"object_id" => $id
			]);
			if(count($find) > 0) {
				$_id = $find[0]["id"];
			} else {
				$find = $this->service->load([
					"id" => $id
				]);
				if(count($find) > 0) {
					$id = $find[0]["object_id"];
					$_id = $find[0]["id"];
				} else {
					$_id = $id;
					$id = "0";
				}
			}
		} catch(\Exception $e) {
			return [
				"status" => 412,
				"success" => false,
				"detail" => $e->getMessage()
			];
		}

		/* kirim data  */
		$result = $this->doSendRequest([
			"url" => $this->config['url'],
			"action" => "rlempatbsebab/".$_id,
			"method" => "DELETE"
		]);

		if($this->httpcode == 200) {
			if($result->status) {
				try {
					$this->service->hapus([
						"object_id" => $id
					]);
				} catch(\Exception $e) {
					return [
						"status" => 412,
						"success" => false,
						"detail" => $e->getMessage()
					];
				}
				$this->response->setStatusCode(200);
				return [
					"status" => 200,
					"success" => true,
					"detail" => "Data telah dihapus"
				];
			}
		}

		$result = (array) $result;
		$message = !empty($result["message"]) ? $result["message"] : "Data gagal dihapus";
		$this->response->setStatusCode(422);
		return [
			"status" => 422,
			"success" => false,
			"detail" => $message
		];
    }
}
