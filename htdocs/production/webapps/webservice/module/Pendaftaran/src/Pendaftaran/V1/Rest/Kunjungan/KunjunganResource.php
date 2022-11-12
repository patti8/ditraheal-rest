<?php
namespace Pendaftaran\V1\Rest\Kunjungan;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use Layanan\V1\Rest\TindakanMedis\TindakanMedisService;
use Layanan\V1\Rest\Farmasi\FarmasiService;
use Layanan\V1\Rest\OrderResep\OrderResepService;
use Pendaftaran\V1\Rest\TujuanPasien\TujuanPasienService;

class KunjunganResource extends Resource
{	
	private $ruangan;
	protected $title = "Kunjungan";
	
	public function __construct() {
		parent::__construct();
		$this->service = new KunjunganService();
		$this->service->setPrivilage(true);
		$this->tindakanMedis = new TindakanMedisService();
		$this->farmasi = new FarmasiService();
		$this->orderfarmasi = new OrderResepService(true, [
		    'Ruangan' => false,
		    'Referensi' => false,
		    'Dokter' => false,
		    'OrderDetil' => false,
		    'Kunjungan' => true
		]);
		$this->tujuan = new TujuanPasienService(false);
	}
	
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$data->DITERIMA_OLEH = $this->user;
		$ref = isset($data->REF) ? "= '".$data->REF."'" : "IS NULL";
		
		if(isset($data->REF)){
			if(substr($data->REF, 0, 2) == 10){
				if(!$this->isAllowPrivilage('110102')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan konsul');	
				
			} else if(substr($data->REF, 0, 2) == 11){
				if(!$this->isAllowPrivilage('110103')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan mutasi');	

			} else if(substr($data->REF, 0, 2) == 12){
				if(!$this->isAllowPrivilage('110104')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order laboratorium');	

			} else if(substr($data->REF, 0, 2) == 13){
				if(!$this->isAllowPrivilage('110105')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order radiologi');	

			} else if(substr($data->REF, 0, 2) == 14){
				if(!$this->isAllowPrivilage('110106')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan order resep');	
			}
		} else {
			if(!$this->isAllowPrivilage('110101')) return new ApiProblem(405, 'Anda tidak memiliki akses penerimaan pendaftaran kunjungan');				
		}
		
		$find = $this->service->load(['NOPEN' => $data->NOPEN, 'RUANGAN' => $data->RUANGAN, new \Laminas\Db\Sql\Predicate\Expression("REF ".$ref)]);
		if(count($find) > 0) {
			$tujuans = $this->tujuan->load([
				"NOPEN" => $data->NOPEN
			]);
			if(count($tujuans) == 1) {
				if($tujuans[0]["STATUS"] == 1) {
					$this->tujuan->simpan([
						"NOPEN" => $data->NOPEN,
						"STATUS" => 2
					]);
				}
			}
			return new ApiProblem(405, 'penerimaan pendaftaran kunjungan ini sudah diterima');	
		}
		
        $result = parent::create($data);
		return $result;
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
		$result = parent::fetch($id);
		return $result;
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
		parent::fetchAll($params);
		$this->service->setUser($this->user);
		$this->service->setUserAkses($this->dataAkses);		
		$total = $this->service->getRowCount($params);
		$data = [];
		if($total > 0) {
			$data = $this->service->load($params, ['*'], ['MASUK DESC']);
			
			foreach($data as &$entity) {
				if(isset($params['JENIS_KUNJUNGAN'])) {
					if($params['JENIS_KUNJUNGAN'] == 11) {
						$orderfarmasi = $this->orderfarmasi->load(['NOMOR' => $entity['REF']]);
						if(count($orderfarmasi) > 0) $entity['REFERENSI']['ASAL'] = $orderfarmasi[0];
					}
				}
			}
		}
		
		return [
			/*"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data,*/
			"status" => $total > 0 ? 200 : 404,
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data,
			"detail" => $this->title.($total > 0 ? " ditemukan" : " tidak ditemukan")
		];
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {		
		if(isset($data->STATUS)) {
			$notify = false;
            $notifyMsg = "";
			if($data->STATUS == 0) {				
				if(!$this->isAllowPrivilage('110802')) {
                    $notify = true;
                    $notifyMsg = 'Anda tidak memiliki akses untuk melakukan pembatalan';
                } 
                
    			if(!$notify) {
    			    $rows = $this->tindakanMedis->load(['KUNJUNGAN' => $id, 'STATUS' => 1]);
    			    if(count($rows) > 0) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan tindakan';
    			    }
    			}
    				
    			if(!$notify) {				
    			    $rows = $this->farmasi->load(['KUNJUNGAN' => $id, 'STATUS' => 2]);
    				if(count($rows) > 0) {
    				    $notify = true;
    				    $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan farmasi';
    				}
    			}
    				
    			if(!$notify) {
    			    if($this->service->getMutasi()->kunjunganSudahDimutasikan($id)) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan mutasi';
    			    }
    			}
    				
    			if(!$notify) {
    			    if($this->service->getKonsul()->kunjunganSudahDikonsulkan($id)) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan konsul';
    			    }
    			}
    				
    			if(!$notify) {
    			    if($this->service->getOrderLab()->kunjunganSudahDiOrderLabkan($id)) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan order laboratorium';
    			    }
    			}
    				
    			if(!$notify) {
    			    if($this->service->getOrderRad()->kunjunganSudahDiOrderRadkan($id)) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan order radiologi';
    			    }
    			}
    			
    			if(!$notify) {
    			    if($this->service->getEResep()->kunjunganSudahDiOrderResepkan($id)) {
    			        $notify = true;
    			        $notifyMsg = 'Anda tidak dapat melakukan pembatalan kunjungan ini karena kunjungan ini sudah memiliki layanan resep';
    			    }
    			}
			} else if($data->STATUS == 1) {
                if(!$notify) {
                    $kjgn = $this->service->load(["NOMOR" => $id, "STATUS" => 0]);
                    if(count($kjgn) > 0) {
                        $rows = $this->service->getPendaftaran()->load(['NOMOR' => $kjgn[0]["NOPEN"], 'STATUS' => 0]);
                        if(count($rows) > 0) {
                            $notify = true;
                            $notifyMsg = 'Anda tidak dapat mengaktifkan kunjungan ini karena pendaftarannya tidak aktif';
                        }
                    }
                }
            } else if($data->STATUS == 2) {
                if(!$notify) {
                    $result = $this->service->adaPetugasMedisYgTidakTerisi($id);                    
                    if($result) {
						$notify = true;
						$notifyMsg = 'Anda tidak dapat memfinalkan kunjungan ini, karena masih ada petugas medis yang belum terisi.';					
                    }
                }                
            }

			if($notify) {
    		    return new ApiProblem(405, $notifyMsg);
    		}
		}

		if(isset($data->FINAL_HASIL)) {
			if($data->FINAL_HASIL == 0) {
				if(!$this->isAllowPrivilage('110902')) {
					return new ApiProblem(405, 'Anda tidak memiliki akses untuk melakukan pembatalan final hasil');
				}
			} else {
				if(!$this->isAllowPrivilage('110901')) {
					return new ApiProblem(405, 'Anda tidak memiliki akses untuk melakukan final hasil');
				}
			}
			
			$data->FINAL_HASIL_OLEH = $this->user;
			$data->FINAL_HASIL_TANGGAL = new \Laminas\Db\Sql\Expression('NOW()');
		}

		$result = parent::update($id, $data);
		return $result;
    }
}
