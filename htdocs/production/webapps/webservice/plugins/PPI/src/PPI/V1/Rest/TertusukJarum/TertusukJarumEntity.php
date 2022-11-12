<?php
namespace PPI\V1\Rest\TertusukJarum;
use DBService\SystemArrayObject;

class TertusukJarumEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1,
        "RUANGAN"=>1,
        "NAMA_TERLAPOR"=>1,
        "TANGGAL_KEJADIAN"=>1,
        "KATEGORI"=>1,
        "HASIL_LAB"=>1,
        "KETERANGAN"=>1,
        "OLEH"=>1,
        "STATUS"=>1
    );
}
