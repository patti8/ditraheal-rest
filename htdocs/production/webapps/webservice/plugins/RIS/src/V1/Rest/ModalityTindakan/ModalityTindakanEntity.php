<?php
namespace RIS\V1\Rest\ModalityTindakan;

use DBService\SystemArrayObject;

class ModalityTindakanEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
        , "TINDAKAN"=>1
        , "MODALITY"=>1
        , "STATUS"=>1
	];
}
