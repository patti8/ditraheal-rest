<?php
namespace PPI\V1\Rest\CuciTangan;
use DBService\SystemArrayObject;

class CuciTanganEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"PROFESI"=>1
        ,"TANGGAL_BUAT"=>1
        ,"TANGGAL"=>1
        ,"STATUS"=>1
    );
}
