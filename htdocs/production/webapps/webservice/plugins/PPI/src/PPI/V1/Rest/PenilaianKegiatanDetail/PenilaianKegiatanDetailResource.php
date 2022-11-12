<?php
namespace PPI\V1\Rest\PenilaianKegiatanDetail;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class PenilaianKegiatanDetailResource extends Resource
{
    public function __construct() {
        parent::__construct();
        $this->service = new Service();
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $result = array(
            "status" => 422,
            "success" => false,
            "detail" => "Gagal menyimpan penilaian kegiatan detail"
        );
        
        $result["data"] = null;
        $data->OLEH = $this->user;
        
        $success = $this->service->simpan($data, true);
        if($success) {
            $result["status"] = 200;
            $result["success"] = true;
            $result["data"] = $success[0];
            $result["detail"] = "Berhasil menyimpan penilaian kegiatan detail";
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result;
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
    public function fetchAll($params = [])
    {
        $data = $this->service->load($params);
        $total = $this->service->getRowCount($params);
        $result =  array(
            "status" => $total > 0 ? 200 : 404,
            "success" => $total > 0 ? true : false,
            "total" => $total,
            "data" => count($data) ? $data : null,
            "detail" => count($data) > 0 ? "penilaian kegiatan detail ditemukan" : "Penilaian kegiatan detail tidak ditemukan"
        );
        
        return $result;
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
        $result = array(
            "status" => 422,
            "success" => false,
            "data" => null,
            "detail" => "Gagal merubah penilaian kegiatan detail"
        );
        
        $data->ID = $id;
        $data->OLEH = $this->user;
        
        $params = array("ID" => $id);
        
        $records = $this->service->load($params);
        $canUpdate = count($records) > 0;
        
        if($canUpdate) {
            $success = $this->service->simpan($data);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil merubah data penilaian kegiatan detail";
            }
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result;
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
