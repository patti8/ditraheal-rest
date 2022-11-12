<?php
namespace PPI\V1\Rest\TindakLanjut;
use DBService\SystemArrayObject;
class TindakLanjutEntity extends SystemArrayObject
{
	protected $fields = array(
        "ID"=>1
        ,"GROUP"=>1
        ,"BULAN"=>1
        ,"TAHUN"=>1
        ,"PLAN"=>1
        ,"DO"=>1
        ,"CHECK"=>1
        ,"ACTION"=>1
        ,"TANGGAL"=>1
        ,"OLEH"=>1
        ,"STATUS"=>1
    );
}
