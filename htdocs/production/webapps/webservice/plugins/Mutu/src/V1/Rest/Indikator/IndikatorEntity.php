<?php
namespace Mutu\V1\Rest\Indikator;
use DBService\SystemArrayObject;

class IndikatorEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID" => 1
		, "KODE" => 1
		, "NAMA" => 1
		, "DEFINISI_OPERASIONAL" => 1
		, "NUMERATOR" => 1
		, "DENUMERATOR" => 1
		, "PENGUKURAN" => 1
		, "TARGET" => 1
		, "STATUS" => 1
	);
}
