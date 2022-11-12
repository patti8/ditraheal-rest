<?php
namespace Layanan\V1\Rest\HasilLabMikroskopikDetail;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class HasilLabMikroskopikDetailResource extends Resource
{
    protected $title = "Hasil Mikroskopik Detil";

    public function __construct() {
        parent::__construct();
        $this->service = new Service();
        $this->service->setPrivilage(true);
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
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
        $order = array("ID ASC");
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
        if (isset($params["NOT"])) {
            $nots = (array) json_decode($params["NOT"]);
            foreach($nots as $key => $val) {
                $params[] = new \Laminas\Db\Sql\Predicate\Expression("(NOT hasil_lab_mkroskopik_detail.".$key." = ".$val.")");
            }
            unset($params["NOT"]);
        }
        $this->service->setUser($this->user);
        $total = $this->service->getRowCount($params);
        if($total > 0) $data = $this->service->load($params, array('*'), $order);
        
        return [
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
        $result = parent::update($id, $data);
		return $result;
    }
}
