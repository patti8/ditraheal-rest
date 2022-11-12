<?php
namespace MedicalRecord\V1\Rest\PenilaianFisik;
use DBService\SystemArrayObject;

class PenilaianFisikEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "DESKRIPSI"=>1
		, "TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
    ];
}
