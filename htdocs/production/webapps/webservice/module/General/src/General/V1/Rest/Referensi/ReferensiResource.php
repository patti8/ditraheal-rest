<?php
namespace General\V1\Rest\Referensi;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class ReferensiResource extends Resource
{
	public function __construct($services) {
		parent::__construct();
        $this->authType = self::AUTH_TYPE_LOGIN_OR_TOKEN;
		$this->service = new ReferensiService();
	}
	
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		unset($data->id);
        // cari deskripsi jika sudah ada
        $founds = $this->service->load([
            "JENIS" => $data->JENIS,
            "DESKRIPSI" => trim($data->DESKRIPSI),
            "STATUS" => 1
        ]);
        if(count($founds) == 0) {
		    $this->service->simpan($data);
        }
        //return new ApiProblem(405, 'The POST method has not been defined');
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
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
		if(isset($params['BAR_RUANGAN'])){
			$data =  $this->service->execute("CALL informasi.infoRuangKamarTidur('".$params['BAR_RUANGAN']."')");
            if(count($data) > 0){
                return array(
                    "status" => 200,
                    "success" => true,
                    "total" => 1,
                    "data" => $data,
                    "detail" => "Info Tempat Tidur ditemukan"
                );
            } else {
                return array(
                    "status" => 404,
                    "success" => false,
                    "total" => 0,
                    "data" => "",
                    "detail" => "Info Tempat Tidur tidak ditemukan"
                );
            }
            
        } else {
			$total = $this->service->getRowCount($params);		
			$data = $this->service->load($params);	
			
			return array(
				"success" => $total > 0 ? true : false,
				"total" => $total,
				"data" => $data
			);
		}
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
        $ids = explode('-', $id);
        $data->JENIS = $ids[0];        
        $data->ID = $ids[1];
        if(isset($data->DESKRIPSI)) $data->DESKRIPSI = trim($data->DESKRIPSI);
        
        unset($data->id);
        $this->service->simpan($data);
    }
}
