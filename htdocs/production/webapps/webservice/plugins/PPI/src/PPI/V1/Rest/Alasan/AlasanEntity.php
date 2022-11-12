<?php
namespace PPI\V1\Rest\Alasan;
use DBService\SystemArrayObject;
class AlasanEntity extends SystemArrayObject
{
	protected $fields = array(
        "ID"=>1
        ,"DESKRIPSI"=>1
        ,"STATUS_ETC"=>1
        ,"STATUS"=>1
    );
}
