<?php
namespace RegistrasiOnline\V1\Rest\Pengaturan;
use DBService\SystemArrayObject;

class PengaturanEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "LIMIT_DAFTAR"=>1
		, "DURASI"=>1
		, "MULAI"=>1
		, "POS_ANTRIAN"=>1
		, "STATUS"=>1
	);
}