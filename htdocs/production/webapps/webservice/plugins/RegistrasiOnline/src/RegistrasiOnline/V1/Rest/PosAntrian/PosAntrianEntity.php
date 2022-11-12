<?php
namespace RegistrasiOnline\V1\Rest\PosAntrian;
use DBService\SystemArrayObject;

class PosAntrianEntity extends SystemArrayObject
{
	protected $fields = array('NOMOR'=>1, 'DESKRIPSI'=>1, 'STATUS'=>1);
}