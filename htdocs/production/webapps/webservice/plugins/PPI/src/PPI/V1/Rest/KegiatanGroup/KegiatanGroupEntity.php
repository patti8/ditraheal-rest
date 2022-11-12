<?php
namespace PPI\V1\Rest\KegiatanGroup;
use DBService\SystemArrayObject;
class KegiatanGroupEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"KEGIATAN"=>1
        ,"GROUP"=>1
        ,"KELOMPOK"=>1
        ,'STATUS'=>1
    );
}
