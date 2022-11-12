<?php
namespace RegistrasiOnline\V1\Rest\JenisPasien;
use DBService\SystemArrayObject;

class JenisPasienEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'DESKRIPSI'=>1, 'STATUS'=>1);
}