<?php
namespace RegistrasiOnline\V1\Rest\Pasien;
use DBService\SystemArrayObject;

class PasienEntity extends SystemArrayObject
{
	protected $fields = array('NORM'=>0, 'NAMA'=>1, 'PANGGILAN'=>2, 'GELAR_DEPAN'=>3, 'GELAR_BELAKANG'=>4
		, 'TEMPAT_LAHIR'=>5, 'TANGGAL_LAHIR'=>6, 'JENIS_KELAMIN'=>7, 'ALAMAT'=>8, 'RT'=>9, 'RW'=>10
		, 'KODEPOS'=>11, 'WILAYAH'=>12, 'AGAMA'=>13, 'PENDIDIKAN'=>14, 'PEKERJAAN'=>15, 'STATUS_PERKAWINAN'=>16
		, 'GOLONGAN_DARAH'=>17, 'KEWARGANEGARAAN'=>18, 'TANGGAL'=>19, 'OLEH'=>1, 'STATUS'=>20);
}