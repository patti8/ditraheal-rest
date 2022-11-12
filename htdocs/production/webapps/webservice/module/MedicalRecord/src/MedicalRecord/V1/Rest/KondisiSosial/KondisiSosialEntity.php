<?php
namespace MedicalRecord\V1\Rest\KondisiSosial;

use DBService\SystemArrayObject;

class KondisiSosialEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "MARAH"=>1
		, "CEMAS"=>1
		, "TAKUT"=>1
		, "BUNUH_DIRI"=>1
		, "LAINNYA"=>1
		, "MASALAH_PERILAKU"=>1
		, "TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
	];
}