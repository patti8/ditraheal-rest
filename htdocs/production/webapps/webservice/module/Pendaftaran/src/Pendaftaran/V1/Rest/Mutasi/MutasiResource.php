<?php

namespace Pendaftaran\V1\Rest\Mutasi;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class MutasiResource extends Resource
{
    protected $title = "Kunjungan";

    public function __construct()
    {
        parent::__construct();
        $this->service = new MutasiService();
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
        if ($this->isAllowPrivilage('110302')) {
            $data->OLEH = $this->user;
            // check if mutasi is exists
            $finds = $this->service->load([
                "KUNJUNGAN" => $data->KUNJUNGAN,
                "TUJUAN" => $data->TUJUAN,
                "RESERVASI" => $data->RESERVASI,
                "STATUS" => 1
            ]);

            if(count($finds) > 0) {
                return [
                    "success" => true,
                    "data" => $finds
                ];
            }
            return parent::create($data);
        } else return new ApiProblem(405, 'Anda tidak memiliki akses layanan mutasi');
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
        parent::fetchAll($params);
        $order = ["mutasi.TANGGAL ASC"];
        if (isset($params->sort)) {
            $orders = json_decode($params->sort);
            if (is_array($orders)) {
            } else {
                $orders->direction = strtoupper($orders->direction);
                $orders->property = $orders->property == "TANGGAL" ? "mutasi.TANGGAL" : $orders->property;
                $order = [$orders->property . " " . ($orders->direction == "ASC" || $orders->direction == "DESC" ? $orders->direction : "")];
            }
            unset($params->sort);
        }
        $this->service->setUser($this->user);
        $total = $this->service->getRowCount($params);
        $data = [];
        if ($total > 0) $data = $this->service->load($params, ['*'], $order);

        return [
            /*"success" => $total > 0 ? true : false,
            "total" => $total,
            "data" => $data*/
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
        $status = isset($data->STATUS) ? $data->STATUS : 1;
        if ($status != 0) {
        // untuk update
        } else {
        if ($this->isAllowPrivilage('11080102')) {
            $data->OLEH = $this->user;
            return parent::update($id, $data);
        } else return new ApiProblem(405, 'Anda tidak memiliki akses pembatalan mutasi');
        }
    }
}
