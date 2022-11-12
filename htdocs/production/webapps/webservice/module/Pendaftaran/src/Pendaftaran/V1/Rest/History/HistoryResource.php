<?php
namespace Pendaftaran\V1\Rest\History;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use Pendaftaran\V1\Rest\Pendaftaran\PendaftaranService;
use Pendaftaran\V1\Rest\Kunjungan\KunjunganService;

class HistoryResource extends Resource
{
	private $types = array();
	private $orders = array();
	
	public function __construct() {
        parent::__construct();
        $this->authType = self::AUTH_TYPE_SIGNATURE_OR_LOGIN;
		$this->types['pendaftaran'] = new PendaftaranService();
		$this->types['kunjungan'] = new KunjunganService();
		
		$this->orders['pendaftaran'] = array('TANGGAL DESC');
		$this->orders['kunjungan'] = array('MASUK DESC');
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
		$params = is_array($params) ? $params : (array) $params;
		$type = isset($params['TYPE']) ? $params['TYPE'] : null;		
		if($type) {
			unset($params['TYPE']);
			$select = $this->types[$type];
			if($select) {
				$total = $select->getRowCount($params);
				$data = $select->load($params, array('*'), $this->orders[$type]);	
				
				return array(
					"success" => $total > 0 ? true : false,
					"total" => $total,
					"data" => $data
				);
			}
		}
		
		return array(
			"success" => false
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
