<?php
namespace RegistrasiOnline\V1\Rest\PanggilAntrian;

use DBService\SystemArrayObject;

class PanggilAntrianEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1
		, "LOKET"=>1
		, "NOMOR"=>1
		, "POS"=>1
		, "CARA_BAYAR"=>1
		, "TANGGAL"=>1
		, "STATUS"=>1
	);
}