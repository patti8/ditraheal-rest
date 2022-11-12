<?php
namespace Plugins\V2\Rest\RequestReport;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use Aplikasi\RequestReport;

class RequestReportResource extends Resource
{
	private $requestReport;

    public function setServiceManager($serviceManager) {
        parent::setServiceManager($serviceManager);
        $this->requestReport = new RequestReport($serviceManager->get('Config'));
	}

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $data->USER_ID = $this->user;
        $data->USER_NAMA = $this->dataAkses->NAME;
        $data->USER_NIP = $this->dataAkses->NIP;
        return $this->requestReport->create($data);
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
        if(count($params) != 2) return new ApiProblem(412, 'Parameter request tidak valid');
        $params["STATUS"] = 2;
        $rss = $this->requestReport->getRequestServiceStorage();
        $data = null;
        $total = $rss->getRowCount($params);
		if($total > 0) $data = $rss->load($params, ['*'], ["TTD_TANGGAL DESC"]);

        if($data) {
            foreach($data as &$d) {
                unset($d["KEY"]);
                unset($d["REQUEST_DATA"]);
                unset($d["KEY"]);
                unset($d["DOCUMENT_DIRECTORY_ID"]);
                unset($d["REF_ID"]);            
            }
        }
		
		return [
			"status" => $total > 0 ? 200 : 404,
			"success" => $total > 0 ? true : false,
			"total" => $total,
			"data" => $data,
			"detail" => "Request Report ".($total > 0 ? " ditemukan" : " tidak ditemukan")
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
