<?php
return [
    'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller' => [
        'description' => 'List Data Pasien',
        'collection' => [
            'GET' => [
                'description' => '{
	"NORM":1,
	"TANGGAL_LAHIR":"2000-01-01"
}',
                'response' => '{
  "success": true,
  "total": 1,
  "data": [
    {
      "NORM": "1",
      "NAMA": "ASM",
      "PANGGILAN": "LIA",
      "GELAR_DEPAN": null,
      "GELAR_BELAKANG": "se",
      "TEMPAT_LAHIR": "SENGKANG",
      "TANGGAL_LAHIR": "2000-01-01 00:00:00",
      "JENIS_KELAMIN": "1",
      "ALAMAT": "JL ",
      "RT": "003",
      "RW": "001",
      "KODEPOS": null,
      "WILAYAH": "7371141006",
      "AGAMA": "1",
      "PENDIDIKAN": "5",
      "PEKERJAAN": "2",
      "STATUS_PERKAWINAN": "2",
      "GOLONGAN_DARAH": "13",
      "KEWARGANEGARAAN": "71",
      "SUKU": "0",
      "TANGGAL": "2015-11-02 13:17:04",
      "OLEH": "188",
      "STATUS": "1"
    }
  ]
}',
            ],
        ],
    ],
];
