<?php
namespace Mutu\V1\Rest\DataIndikator;
use DBService\SystemArrayObject;
class DataIndikatorEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID' => 1
		,'INDIKATOR' => 1
		,'RUANGAN' => 1
		,'TANGGAL' => 1
		,'NUM' => 1
		,'DENUM' => 1
		,'OLEH' => 1
		,'DATA_DASAR' => 1
		,'STATUS' => 1 
	);
}

