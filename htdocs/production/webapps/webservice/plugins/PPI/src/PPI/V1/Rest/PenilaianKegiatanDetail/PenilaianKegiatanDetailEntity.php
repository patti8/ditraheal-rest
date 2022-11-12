<?php
namespace PPI\V1\Rest\PenilaianKegiatanDetail;
use DBService\SystemArrayObject;
class PenilaianKegiatanDetailEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"PENILAIAN"=>1
        ,"KEGIATAN"=>1
        ,'CHECK'=>1
        ,'ALASAN'=>1
        ,'KET'=>1
        ,'RTL'=>1
        ,'STATUS'=>1
    );
}
