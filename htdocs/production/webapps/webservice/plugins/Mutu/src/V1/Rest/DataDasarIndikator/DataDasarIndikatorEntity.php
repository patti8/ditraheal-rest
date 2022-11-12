<?php
namespace Mutu\V1\Rest\DataDasarIndikator;
use DBService\SystemArrayObject;

class DataDasarIndikatorEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID' => 1, 
		'DATA_INDIKATOR' => 1,
		'NOREG' => 1,
		'NORM' => 1,
		'NUM' => 1,
		'DENUM' => 1,
		'NILAI' => 1,
		'TANGGAL' => 1,
		'OLEH' => 1,
		'STATUS' => 1
	);
}
