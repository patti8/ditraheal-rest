<?php
namespace RegistrasiOnline\V1\Rest\PanggilAntrian;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class PanggilAntrianResource extends Resource
{
    public function __construct() {
		parent::__construct();
		$this->service = new PanggilAntrianService();
	}/**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if(isset($data->STATUS_LOKET)){
            $success = $this->service->statusloket($data);
            return $success;
        } else {
            $result = array(
                "status" => 422,
                "success" => false,
                "detail" => "Gagal "
            );
            
            $result["data"] = null;	
            
            $success = $this->service->simpan($data, true);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil";
            }
            
            if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false)); 
            
            return $result;
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
        parent::fetchAll($params);
        $order = array("LOKET ASC");
		$data = null;
		if (isset($params->sort)) {
			$orders = json_decode($params->sort);
			if(is_array($orders)) {
			} else {
				$orders->direction = strtoupper($orders->direction);
				$order = array($orders->property." ".($orders->direction == "ASC" || $orders->direction == "DESC" ? $orders->direction : ""));
			}
			unset($params->sort);
		}
		$params = is_array($params) ? $params : (array) $params;
		$params2 = $params;
		
		$total = $this->service->getRowCount($params);
		if($total > 0) $data = $this->service->load($params, array('*'), $order);
		
		return array(
			"status" => $total > 0 ? 200 : 404,
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data,
			"detail" => $total > 0 ? "Antrian ditemukan" : "Antrian tidak ditemukan"
        );
        
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
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
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
