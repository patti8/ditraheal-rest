<?php
namespace PPI\V1\Rest\PenilaianKegiatanHarianDetail;
use DBService\SystemArrayObject;
class PenilaianKegiatanHarianDetailEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1
        ,"PK_HARIAN"=>1
        ,'KEGIATAN'=>1
        ,'MINGGU'=>1
        ,'HARIAN'=>1
        ,'CHECK'=>1
        ,'TANGGAL'=>1
        ,'STATUS'=>1
    );
}
