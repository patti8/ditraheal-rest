<?php
namespace PPI\V1\Rest\IDO;
use DBService\SystemArrayObject;

class IDOEntity extends SystemArrayObject
{
    protected $fields = array(
        "ID"=>1,
        "KUNJUNGAN"=>1,
        "ID_OPERASI"=>1,
        "RUANGAN"=>1,
        "NORM"=>1,
        "TANGGAL_OPERASI"=>1,
        "USIA"=>1,
        "SUHU"=>1,
        "JENIS_OPERASI"=>1,
        "ASA_SCORE"=>1,
        "KLP"=>1,
        "OPERASI"=>1,
        "STATUS_IDO"=>1,
        "ANTIBIOTIK"=>1,
        "HASIL_KULTUR"=>1,
        "OLEH"=>1,
        "TANGGAL"=>1,
        "STATUS"=>1
    );
}
