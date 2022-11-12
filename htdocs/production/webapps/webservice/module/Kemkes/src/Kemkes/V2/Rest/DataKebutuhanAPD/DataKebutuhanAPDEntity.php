<?php
namespace Kemkes\V2\Rest\DataKebutuhanAPD;

use DBService\SystemArrayObject;

class DataKebutuhanAPDEntity extends SystemArrayObject
{
	protected $fields = [
		"id_kebutuhan" => 1
        , "jumlah_eksisting" => 1
        , "jumlah" => 1
        , 'jumlah_diterima' => 1
        , 'baru' => 1
        , 'tgl_kirim' => 1
        , 'kirim' => 1
        , 'response' => 1
	];
}
