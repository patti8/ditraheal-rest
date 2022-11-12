<?php
namespace General\V1\Rest\DokterRuangan;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class DokterRuanganResource extends Resource
{
    protected $title = "Dokter Ruangan";

	public function __construct() {
		parent::__construct();
		$this->service = new DokterRuanganService();
	}
	
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if(isset($data->ALL)) {
            if($data->ALL) {
                if(isset($data->RUANGAN)) return $this->service->tambahSemuaDokter($data->RUANGAN);
            }
        }
        
		$result = null;
		
        if(is_array($data->DOKTER)) {
            foreach($data->DOKTER as $dr) {
                 $cek = $this->service->load([
                    'RUANGAN' => $data->RUANGAN,
                    'DOKTER' => $dr
                ]);
                
                if(count($cek) > 0){
                    $record = $cek[0];
                    $result = $this->service->simpanData([
                        'ID' =>$record['ID'],
                        'STATUS' => 1
                    ], false);
                } else {
                    $result = $this->service->simpanData([
                        'RUANGAN' => $data->RUANGAN,
                        'DOKTER' => $dr
                    ], true);
                }
            }
             
            return [
				'data' => $result,
                'success' => $result ? true : false
            ];
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
        $order = ["ID ASC"];
		$data = null;
		if (isset($params->sort)) {
			$orders = json_decode($params->sort);
			if(is_array($orders)) {
			} else {
				$orders->direction = strtoupper($orders->direction);
				$order = [$orders->property." ".($orders->direction == "ASC" || $orders->direction == "DESC" ? $orders->direction : "")];
			}
			unset($params->sort);
		}

		$params->limit = 5000;
		$total = $this->service->getRowCount($params);
		if($total > 0) $data = $this->service->load($params, ['*'], $order);
		 
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
