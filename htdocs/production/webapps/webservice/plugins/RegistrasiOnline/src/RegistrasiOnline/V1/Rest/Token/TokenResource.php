<?php
namespace RegistrasiOnline\V1\Rest\Token;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use DBService\Resource;
use Aplikasi\Token;
use \DateTime;
use \DateTimeZone;

class TokenResource extends Resource
{
    public function __construct() {
		parent::__construct();
		$this->authType = self::AUTH_TYPE_NOT_SECURE;
	}
	/**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
		/*
		$timestamp = $this->getTimestamp();
		$record['token'] = $this->generateToken($data, $timestamp);
		$metadata = array("message" => "Ok","code" => 200);
		$result = array(
			"response" => $record,
			"metadata" => $metadata
		);
		return $result;
		*/
		$metadata = array("message" => "Ok","code" => 200);
		$result = array(
			"response" => '',
			"metadata" => $metadata
		);
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
        //return new ApiProblem(405, 'The GET method has not been defined for collections');
        return new ApiProblem(405, 'Method Not Found');
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
	
	public function getTimestamp() {
		$dt = new DateTime(null, new DateTimeZone("UTC"));
		return $dt->getTimestamp();
	}
	
	public function generateToken($data, $xtimestamp) {
		$key = $data->X_ID."&".$xtimestamp;					
		return base64_encode($key."&".hash_hmac("sha256", utf8_encode($key), utf8_encode($data->X_PASS), true));
	}
}
