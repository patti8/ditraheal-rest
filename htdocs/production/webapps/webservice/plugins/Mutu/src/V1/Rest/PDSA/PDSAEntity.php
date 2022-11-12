<?php
namespace Mutu\V1\Rest\PDSA;
use DBService\SystemArrayObject;
class PDSAEntity extends SystemArrayObject
{
	protected $fields = array(
		"ID" => 1,
		"ANALISA" => 1,
		"PLAN" => 1,
		"DO" => 1,
		"STUDY" => 1,
		"ACTION" => 1,
		"OLEH" => 1,
		"STATUS" => 1
	);
}
