<?php
namespace RIS\V1\Rest\Logs;

use DBService\SystemArrayObject;

class LogsEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
        , "TINDAKAN_MEDIS"=>1
        , "JENIS"=>1
        , "TGL_REQUEST"=>1
        , "REQUEST"=>1
        , "TGL_RESPONSE"=>1
        , "RESPONSE"=>1
        , "KIRIM"=>1
        , "STATUS"=>1
	];
}
