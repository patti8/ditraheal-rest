<?php
namespace Mutu\V1\Rest\Analisa;
use DBService\SystemArrayObject;
class AnalisaEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID" => 1,
		"INDIKATOR" => 1,
		"RUANGAN" => 1,
		"TANGGAL_AWAL" => 1,
		"TANGGAL_AKHIR" => 1,
		"ANALISA" => 1,
		"OLEH" => 1,
		"STATUS" => 1
	);
}
