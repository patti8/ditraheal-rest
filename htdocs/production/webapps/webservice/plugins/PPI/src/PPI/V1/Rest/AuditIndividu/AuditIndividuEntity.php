<?php
namespace PPI\V1\Rest\AuditIndividu;
use DBService\SystemArrayObject;

class AuditIndividuEntity extends SystemArrayObject
{
	protected $fields = array(
        "ID"=>1
        ,"PROFESI"=>1
        ,"GROUP"=>1
        ,"TANGGAL_BUAT"=>1
        ,"PEGAWAI"=>1
        ,"TANGGAL"=>1
        ,"OLEH"=>1
        ,"STATUS"=>1
    );
}
