<?php
namespace Mutu\V1\Rest\Laporan;
use DBService\SystemArrayObject;
class LaporanEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID' => 1,
		'INDIKATOR' => 1, 
		'RUANGAN' => 1,
		'TANGGAL_AWAL' => 1,
		'TANGGAL_AKHIR' => 1,
		'JUDUL' => 1,
		'KETERANGAN' => 1,
		'FILES' => 1,
		'TYPE' => 1,
		'OLEH' => 1,
		'TANGGAL' => 1,
		'STATUS' => 1
	);
}