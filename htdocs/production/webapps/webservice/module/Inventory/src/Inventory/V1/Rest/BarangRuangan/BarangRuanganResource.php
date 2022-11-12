<?php
namespace Inventory\V1\Rest\BarangRuangan;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use Inventory\V1\Rest\Barang\BarangService;

class BarangRuanganResource extends Resource
{
	public function __construct() {
		parent::__construct();
		$this->service = new BarangRuanganService();
	}	
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		$this->service->simpan($data);
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
		#$params = is_array($params) ? $params : (array) $params;
		#$params[] = new \Laminas\Db\Sql\Predicate\Expression("STOK > 0");
		if(isset($params->BARANG_RS)){
            unset($params->BARANG_RS);
            unset($params->RUANGAN);
            $masterbarang = new BarangService();
            $total = $masterbarang->getRowCount($params);
            $params[] = new \Laminas\Db\Sql\Predicate\Expression("STATUS = 1");
            $row = $masterbarang->load($params, array('*'));
            $data = [];
            foreach($row as &$entity) {
                $rows = array();
                $rows['BARANG'] = $entity['ID'];
                $rows['REFERENSI']['BARANG'] = $entity;
                $rows['ID'] = $entity['ID'];
                $rows['RUANGAN'] = "0";
                $params2 = array();
                $params2['BARANG'] = $entity['ID'];
                $params2['STATUS'] = 1;
                $rec = $this->service->load($params2, array('*', "STOKTERSEDIA" => new \Laminas\Db\Sql\Expression('FLOOR(SUM(barang_ruangan.STOK))')));
                $rows['STOK'] = $rec[0]['STOKTERSEDIA'];
                //if($rows['STOK'] > 0){
                    array_push($data,$rows);
                //}
            }
        } else {
            $total = $this->service->getRowCount($params);
            $data = $this->service->load($params, array('*'));
        }
		return array(
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data
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
		$this->service->simpan($data);
        //return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
