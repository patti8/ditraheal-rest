<?php
namespace Mutu\V1\Rest\Laporan;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use DBService\RPCResource;

class LaporanResource extends Resource
{
    public function __construct(){
        parent::__construct();
        $this->service = new Service();
        $this->rpcresouce = new RPCResource();
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
            "detail" => "Gagal menyimpan Laporan & Balasan"
        );
        
        $result["data"] = null;
        $data->OLEH = $this->user;
        
        $success = $this->service->simpan($data, true);
        if($success) {
            $result["status"] = 200;
            $result["success"] = true;
            $result["data"] = $success[0];
            $result["detail"] = "Berhasil menyimpan Laporan & Balasan";
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
        $code = base64_decode($id);
        $explode = explode('-', $code);
        $tipe = "application/pdf";
        $ext = "pdf";
        $finds = $this->service->load(array("ID" => $explode[0], "SHOW_FILE" => true ));
        if(count($finds) > 0) {
            $file = $finds[0]["FILES"];
            $tipe = $finds[0]["TYPE"];
        }
        
        return $this->rpcresouce->downloadDocument($file, $tipe, $ext, md5($id), false);
        //return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        if(isset($params["sortperiode"])){
            if(($params['sortperiode'] == "true")&&(isset($params["TANGGAL_AWAL"]))&&(isset($params["TANGGAL_AKHIR"]))){
                $params[] = new \Zend\Db\Sql\Predicate\Expression("TANGGAL_AWAL >= '".$params["TANGGAL_AWAL"]."'");
                $params[] = new \Zend\Db\Sql\Predicate\Expression("TANGGAL_AKHIR  <= '".$params["TANGGAL_AKHIR"]."'");
                unset($params["TANGGAL_AKHIR"]);
                unset($params["TANGGAL_AWAL"]);
            }
            unset($params["sortperiode"]);
        }
        $data = $this->service->load($params);
        $total = $this->service->getRowCount($params);
        $result =  array(
            "status" => $total > 0 ? 200 : 404,
            "success" => $total > 0 ? true : false,
            "total" => $total,
            "data" => count($data) ? $data : null,
            "detail" => count($data) > 0 ? "Laporan & Balasan ditemukan" : "Laporan & Balasan tidak ditemukan"
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
            "detail" => "Gagal merubah Laporan & Balasan"
        );
        
        $data->ID = $id;
        $data->OLEH =$this->user;
        
        $params = array("ID" => $id);
        
        $records = $this->service->load($params);
        $canUpdate = count($records) > 0;
        
        if($canUpdate) {
            $success = $this->service->simpan($data);
            if($success) {
                $result["status"] = 200;
                $result["success"] = true;
                $result["data"] = $success[0];
                $result["detail"] = "Berhasil merubah Laporan & Balasan";
            }
        }
        
        if(!$result["success"]) return new ApiProblem($result["status"], $result["detail"], null, null, array("success" => false));
        
        return $result;
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
