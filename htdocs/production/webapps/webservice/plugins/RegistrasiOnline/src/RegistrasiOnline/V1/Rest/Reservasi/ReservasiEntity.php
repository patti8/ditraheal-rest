<?php
namespace RegistrasiOnline\V1\Rest\Reservasi;
use DBService\SystemArrayObject;

class ReservasiEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'TANGGALKUNJUNGAN'=>1, 'TANGGAL_REF'=>1, 'NORM'=>1, 'NIK'=>1, 'NAMA'=>1
		, 'TEMPAT_LAHIR'=>1, 'TANGGAL_LAHIR'=>1, 'POLI'=>1, 'POLI_BPJS'=>1, 'DOKTER'=>1, 'CARABAYAR'=>1, 'NO_KARTU_BPJS'=>1, 'CONTACT'=>1
		, 'TGL_DAFTAR'=>1, 'NO'=>1, 'JAM'=>1, 'POS_ANTRIAN'=>1, 'JENIS'=>1, 'NO_REF_BPJS'=>1, 'JENIS_REF_BPJS'=>1, 'JENIS_REQUEST_BPJS'=>1
		, 'POLI_EKSEKUTIF_BPJS'=>1, 'JENIS_APLIKASI'=>1, 'STATUS'=>1, 'LOKET_RESPON'=>1);
}