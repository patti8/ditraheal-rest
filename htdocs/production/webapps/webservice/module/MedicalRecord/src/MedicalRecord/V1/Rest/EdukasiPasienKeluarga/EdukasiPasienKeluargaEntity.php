<?php
namespace MedicalRecord\V1\Rest\EdukasiPasienKeluarga;

use DBService\SystemArrayObject;

class EdukasiPasienKeluargaEntity extends SystemArrayObject
{
	protected $fields = [
		"ID"=>1
		, "KUNJUNGAN"=>1
		, "KESEDIAAN"=>1
		, "HAMBATAN"=>1
		, "HAMBATAN_PENDENGARAN"=>1
		, "HAMBATAN_PENGLIHATAN"=>1
		, "HAMBATAN_KOGNITIF"=>1
		, "HAMBATAN_FISIK"=>1
		, "HAMBATAN_BUDAYA"=>1
		, "HAMBATAN_EMOSI"=>1
		, "HAMBATAN_BAHASA"=>1
		, "HAMBATAN_LAINNYA"=>1		
		, "PENERJEMAH"=>1
		, "BAHASA"=>1
		, "EDUKASI_DIAGNOSA"=>1
		, "EDUKASI_PENYAKIT"=>1
		, "EDUKASI_REHAB_MEDIK"=>1
		, "EDUKASI_HKP"=>1
		, "EDUKASI_OBAT"=>1
		, "EDUKASI_NYERI"=>1
		, "EDUKASI_NUTRISI"=>1
		, "EDUKASI_PENGGUNAAN_ALAT"=>1
		, "TANGGAL"=>1
		, "OLEH"=>1
		, "STATUS"=>1
    ];
}