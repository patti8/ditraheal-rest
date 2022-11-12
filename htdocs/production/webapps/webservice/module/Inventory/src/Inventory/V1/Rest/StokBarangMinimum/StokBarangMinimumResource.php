<?php
namespace Inventory\V1\Rest\StokBarangMinimum;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;

class StokBarangMinimumResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new StokBarangMinimumService();
	}
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
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
		
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
		$total = $this->service->execute("select
											a.BARANG,
											b.NAMA,
											FLOOR(sum(a.STOK)) STOKTERSEDIA,
											b.STOK STOKSISA
										from
											inventory.barang_ruangan a
										left join
											inventory.barang b on b.ID = a.BARANG
										group by
											a.BARANG");
		$data = $this->service->load($params, array('*', "STOKTERSEDIA" => new \Laminas\Db\Sql\Expression('FLOOR(SUM(barang_ruangan.STOK))')));	
		
		if($params['QUERY']){
			$to = count($data);
		}else{
			$to = count($total);
		}
		
		return array(
			"success" => $to > 0 ? true : false,
			"total" => $to,
			"data" => $data
		);
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
