<?php
namespace PPI\V1\Rest\KegiatanAlasan;
use DBService\SystemArrayObject;
class KegiatanAlasanEntity extends SystemArrayObject
{
	protected $fields = array(
        "ID"=>1
        ,"KEGIATAN"=>1
        ,"ALASAN"=>1
        ,"STATUS"=>1
    );
}
