<?php
namespace MedicalRecord\V1\Rest\PemeriksaanDada;

use DBService\SystemArrayObject;

class PemeriksaanDadaEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1     
		,"KUNJUNGAN"=>1     
		,"PENDAFTARAN"=>1     
		,"ADA_KELAINAN"=>1     
		,"DESKRIPSI"=>1     
		,"TANGGAL"=>1     
		,"OLEH"=>1
		,"STATUS"=>1  
	];
}
