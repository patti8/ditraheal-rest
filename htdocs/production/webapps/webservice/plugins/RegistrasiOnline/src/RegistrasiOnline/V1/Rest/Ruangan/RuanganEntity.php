<?php
namespace RegistrasiOnline\V1\Rest\Ruangan;
use DBService\SystemArrayObject;

class RuanganEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'DESKRIPSI'=>1, 'ANTRIAN'=>1, 'STATUS'=>1);
}
