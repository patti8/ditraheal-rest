<?php
namespace MedicalRecord\V1\Rest\JadwalKontrol;

use DBService\SystemArrayObject;

class JadwalKontrolEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "NOMOR"=>1
		, "NOMOR_REFERENSI"=>1
		, "NOMOR_ANTRIAN"=>1
		, "NOMOR_BOOKING"=>1
		, "TUJUAN"=>1
		, "DOKTER"=>1
		, "DESKRIPSI"=>1
		, "RUANGAN"=>1
        , "TANGGAL"=>1
        , "JAM"=>1
        , "DIBUAT_TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
    ];
}