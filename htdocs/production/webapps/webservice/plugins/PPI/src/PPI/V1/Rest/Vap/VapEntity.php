<?php
namespace PPI\V1\Rest\Vap;
use DBService\SystemArrayObject;

class VapEntity extends SystemArrayObject
{
	protected $fields = array(
		'ID' => 1, 
		'NOPEN' => 1,
		'KUNJUNGAN' => 1,
		'NORM' => 1,
		'RUANGAN' => 1,
		'USIA' => 1,
		'ANTIBIOTIK' => 1,
		'TANGGAL_PASANG' => 1,
		'TANGGAL_LEPAS' => 1,
		'LAMA_HARI' => 1,
		'INFEKSI_LAIN' => 1,
		'HASIL_KULTUR' => 1,
		'LABORATORIUM' => 1,
		'PEMAKAIAN_ALAT' => 1,
		'STATUS_HAIS' => 1,
		'TANGGAL_PAKAI_ANTIBIOTIK' => 1,
		'TANGGAL_BERHENTI_ANTIBIOTIK' => 1,
		'JENIS_PEMERIKSAAN_KULTUR' => 1,
		'TANGGAL_KEJADIAN' => 1,
		'OLEH' => 1,
		'TANGGAL' => 1,
		'STATUS' => 1,
	);
}
