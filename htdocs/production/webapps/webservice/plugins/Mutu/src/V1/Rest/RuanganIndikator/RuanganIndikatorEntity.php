<?php
namespace Mutu\V1\Rest\RuanganIndikator;
use DBService\SystemArrayObject;

class RuanganIndikatorEntity extends SystemArrayObject
{
	protected $fields = array('RUANGAN' => 1, 'INDIKATOR' => 1, 'STATUS' => 1);
}
