<?php
namespace PPI\V1\Rest\Kegiatan;
use DBService\SystemArrayObject;

class KegiatanEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"DESKRIPSI"=>1
        ,"STATUS"=>1
    );
}
