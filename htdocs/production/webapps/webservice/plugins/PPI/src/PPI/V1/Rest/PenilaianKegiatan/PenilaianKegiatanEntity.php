<?php
namespace PPI\V1\Rest\PenilaianKegiatan;
use DBService\SystemArrayObject;
class PenilaianKegiatanEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"TANGGAL_BUAT"=>1
        ,"RUANGAN"=>1
        ,'GROUP'=>1
        ,'USER'=>1
        ,'TANGGAL'=>1
        ,'STATUS'=>1
    );
}
