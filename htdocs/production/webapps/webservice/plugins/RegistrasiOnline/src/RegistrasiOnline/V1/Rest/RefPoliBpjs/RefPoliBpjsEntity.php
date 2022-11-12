<?php
namespace RegistrasiOnline\V1\Rest\RefPoliBpjs;
use DBService\SystemArrayObject;

class RefPoliBpjsEntity extends SystemArrayObject
{
	protected $fields = array('KDPOLI'=>1, 'NMPOLI'=>1, 'ANTRIAN'=>1, 'STATUS'=>1);
}
