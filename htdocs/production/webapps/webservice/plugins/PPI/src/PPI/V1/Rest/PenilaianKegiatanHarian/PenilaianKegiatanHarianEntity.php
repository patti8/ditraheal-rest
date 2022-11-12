<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarian;
use DBService\SystemArrayObject;
class PenilaianKegiatanHarianEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"RUANGAN"=>1
        ,'GROUP'=>1
        ,'BULAN'=>1
        ,'TAHUN'=>1
        ,'USER'=>1
        ,'TANGGAL'=>1
        ,'STATUS'=>1
    );
}
