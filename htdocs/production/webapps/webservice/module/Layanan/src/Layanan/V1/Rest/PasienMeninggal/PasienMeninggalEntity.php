<?php
namespace Layanan\V1\Rest\PasienMeninggal;
use DBService\SystemArrayObject;

class PasienMeninggalEntity extends SystemArrayObject
{
	protected $fields = [
		'ID' => 1
		, 'KUNJUNGAN' => 1
		, 'NOMOR' => 1
		, 'TANGGAL' => 1
		, 'DIAGNOSA' => 1
		, 'DOKTER' => 1
		, 'OLEH' => 1
		, 'STATUS' => 1
	];
}
