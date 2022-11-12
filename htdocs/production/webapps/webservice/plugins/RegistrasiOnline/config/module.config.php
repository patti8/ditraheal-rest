<?php
return [
    'service_manager' => [
        'factories' => [
            \RegistrasiOnline\V1\Rest\Pasien\PasienResource::class => \RegistrasiOnline\V1\Rest\Pasien\PasienResourceFactory::class,
            \RegistrasiOnline\V1\Rest\Reservasi\ReservasiResource::class => \RegistrasiOnline\V1\Rest\Reservasi\ReservasiResourceFactory::class,
            \RegistrasiOnline\V1\Rest\Ruangan\RuanganResource::class => \RegistrasiOnline\V1\Rest\Ruangan\RuanganResourceFactory::class,
            \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarResource::class => \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarResourceFactory::class,
            \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienResource::class => \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienResourceFactory::class,
            \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanResource::class => \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanResourceFactory::class,
            \RegistrasiOnline\V1\Rest\Istansi\IstansiResource::class => \RegistrasiOnline\V1\Rest\Istansi\IstansiResourceFactory::class,
            \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsResource::class => \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsResourceFactory::class,
            \RegistrasiOnline\V1\Rest\Token\TokenResource::class => \RegistrasiOnline\V1\Rest\Token\TokenResourceFactory::class,
            \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianResource::class => \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianResourceFactory::class,
            \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianResource::class => \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianResourceFactory::class,
            \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianResource::class => \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'registrasi-online.rest.pasien' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/pasien[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.reservasi' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/reservasi[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Reservasi\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.ruangan' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/ruangan[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Ruangan\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.cara-bayar' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/carabayar[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\CaraBayar\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.jenis-pasien' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/jenispasien[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\JenisPasien\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.pengaturan' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/pengaturan[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Pengaturan\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.istansi' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/istansi[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Istansi\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.ref-poli-bpjs' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/refpolibpjs[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\RefPoliBpjs\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.token' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/token[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\Token\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.pos-antrian' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/posantrian[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\PosAntrian\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.panggil-antrian' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/panggilantrian[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\PanggilAntrian\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rest.loket-antrian' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/loketantrian[/:id]',
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rest\\LoketAntrian\\Controller',
                    ],
                ],
            ],
            'registrasi-online.rpc.plugins' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/registrasionline/plugins[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'registrasi-online.rest.pasien',
            1 => 'registrasi-online.rest.reservasi',
            2 => 'registrasi-online.rest.ruangan',
            3 => 'registrasi-online.rest.cara-bayar',
            4 => 'registrasi-online.rest.jenis-pasien',
            5 => 'registrasi-online.rest.pengaturan',
            6 => 'registrasi-online.rest.istansi',
            7 => 'registrasi-online.rest.ref-poli-bpjs',
            8 => 'registrasi-online.rest.token',
            9 => 'registrasi-online.rest.pos-antrian',
            10 => 'registrasi-online.rest.panggil-antrian',
            11 => 'registrasi-online.rest.loket-antrian',
            12 => 'registrasi-online.rpc.plugins',
        ],
    ],
    'api-tools-rest' => [
        'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Pasien\PasienResource::class,
            'route_name' => 'registrasi-online.rest.pasien',
            'route_identifier_name' => 'ID',
            'collection_name' => 'pasien',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'NORM',
                1 => 'TANGGAL_LAHIR',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Pasien\PasienEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Pasien\PasienCollection::class,
            'service_name' => 'Pasien',
        ],
        'RegistrasiOnline\\V1\\Rest\\Reservasi\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Reservasi\ReservasiResource::class,
            'route_name' => 'registrasi-online.rest.reservasi',
            'route_identifier_name' => 'id',
            'collection_name' => 'reservasi',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'NORM',
                1 => 'TANGGAL',
                2 => 'STATUS',
                3 => 'start',
                4 => 'limit',
                5 => 'NOMOR',
                6 => 'JENIS',
                7 => 'QUERY',
                8 => 'POS_ANTRIAN',
                9 => 'POLI_BPJS',
                10 => 'POLI_EKSEKUTIF',
                11 => 'VIEW_DISPLAY_ANTRIAN',
                12 => 'TANGGALKUNJUNGAN',
                13 => 'FILTER_CB',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Reservasi\ReservasiEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Reservasi\ReservasiCollection::class,
            'service_name' => 'Reservasi',
        ],
        'RegistrasiOnline\\V1\\Rest\\Ruangan\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Ruangan\RuanganResource::class,
            'route_name' => 'registrasi-online.rest.ruangan',
            'route_identifier_name' => 'id',
            'collection_name' => 'ruangan',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'STATUS',
                1 => 'start',
                2 => 'limit',
                3 => 'DEFAULT',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Ruangan\RuanganEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Ruangan\RuanganCollection::class,
            'service_name' => 'Ruangan',
        ],
        'RegistrasiOnline\\V1\\Rest\\CaraBayar\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarResource::class,
            'route_name' => 'registrasi-online.rest.cara-bayar',
            'route_identifier_name' => 'id',
            'collection_name' => 'cara_bayar',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'STATUS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarCollection::class,
            'service_name' => 'CaraBayar',
        ],
        'RegistrasiOnline\\V1\\Rest\\JenisPasien\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienResource::class,
            'route_name' => 'registrasi-online.rest.jenis-pasien',
            'route_identifier_name' => 'id',
            'collection_name' => 'jenis_pasien',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'start',
                1 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienCollection::class,
            'service_name' => 'JenisPasien',
        ],
        'RegistrasiOnline\\V1\\Rest\\Pengaturan\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanResource::class,
            'route_name' => 'registrasi-online.rest.pengaturan',
            'route_identifier_name' => 'id',
            'collection_name' => 'pengaturan',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'STATUS',
                1 => 'start',
                2 => 'limit',
                3 => 'POS_ANTRIAN',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanCollection::class,
            'service_name' => 'Pengaturan',
        ],
        'RegistrasiOnline\\V1\\Rest\\Istansi\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Istansi\IstansiResource::class,
            'route_name' => 'registrasi-online.rest.istansi',
            'route_identifier_name' => 'id',
            'collection_name' => 'istansi',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Istansi\IstansiEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Istansi\IstansiCollection::class,
            'service_name' => 'Istansi',
        ],
        'RegistrasiOnline\\V1\\Rest\\RefPoliBpjs\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsResource::class,
            'route_name' => 'registrasi-online.rest.ref-poli-bpjs',
            'route_identifier_name' => 'id',
            'collection_name' => 'ref_poli_bpjs',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'KDPOLI',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsCollection::class,
            'service_name' => 'RefPoliBpjs',
        ],
        'RegistrasiOnline\\V1\\Rest\\Token\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\Token\TokenResource::class,
            'route_name' => 'registrasi-online.rest.token',
            'route_identifier_name' => 'id',
            'collection_name' => 'token',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\Token\TokenEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\Token\TokenCollection::class,
            'service_name' => 'Token',
        ],
        'RegistrasiOnline\\V1\\Rest\\PosAntrian\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianResource::class,
            'route_name' => 'registrasi-online.rest.pos-antrian',
            'route_identifier_name' => 'id',
            'collection_name' => 'pos_antrian',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'STATUS',
                1 => 'start',
                2 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianCollection::class,
            'service_name' => 'PosAntrian',
        ],
        'RegistrasiOnline\\V1\\Rest\\PanggilAntrian\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianResource::class,
            'route_name' => 'registrasi-online.rest.panggil-antrian',
            'route_identifier_name' => 'id',
            'collection_name' => 'panggil_antrian',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'POS',
                1 => 'LOKET',
                2 => 'TANGGAL',
                3 => 'start',
                4 => 'limit',
                5 => 'STATUS',
                6 => 'KOLOM',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianCollection::class,
            'service_name' => 'PanggilAntrian',
        ],
        'RegistrasiOnline\\V1\\Rest\\LoketAntrian\\Controller' => [
            'listener' => \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianResource::class,
            'route_name' => 'registrasi-online.rest.loket-antrian',
            'route_identifier_name' => 'id',
            'collection_name' => 'loket_antrian',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'start',
                1 => 'limit',
                2 => 'STATUS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianEntity::class,
            'collection_class' => \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianCollection::class,
            'service_name' => 'LoketAntrian',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\Reservasi\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\Ruangan\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\CaraBayar\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\JenisPasien\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\Pengaturan\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\Istansi\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\RefPoliBpjs\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\Token\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\PosAntrian\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\PanggilAntrian\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rest\\LoketAntrian\\Controller' => 'Json',
            'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Reservasi\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Ruangan\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\CaraBayar\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\JenisPasien\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Pengaturan\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Istansi\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\RefPoliBpjs\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Token\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\PosAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\PanggilAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\LoketAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'RegistrasiOnline\\V1\\Rest\\Pasien\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Reservasi\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Ruangan\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\CaraBayar\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\JenisPasien\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Pengaturan\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Istansi\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\RefPoliBpjs\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\Token\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\PosAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\PanggilAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rest\\LoketAntrian\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
            'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller' => [
                0 => 'application/vnd.registrasi-online.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \RegistrasiOnline\V1\Rest\Pasien\PasienEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'registrasi-online.rest.pasien',
                'route_identifier_name' => 'ID',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Pasien\PasienCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'registrasi-online.rest.pasien',
                'route_identifier_name' => 'ID',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\Reservasi\ReservasiEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.reservasi',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Reservasi\ReservasiCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.reservasi',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\Ruangan\RuanganEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.ruangan',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Ruangan\RuanganCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.ruangan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.cara-bayar',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\CaraBayar\CaraBayarCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.cara-bayar',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'registrasi-online.rest.jenis-pasien',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\JenisPasien\JenisPasienCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'registrasi-online.rest.jenis-pasien',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.pengaturan',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Pengaturan\PengaturanCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.pengaturan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\Istansi\IstansiEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.istansi',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Istansi\IstansiCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.istansi',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.ref-poli-bpjs',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\RefPoliBpjs\RefPoliBpjsCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.ref-poli-bpjs',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\Token\TokenEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.token',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\Token\TokenCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.token',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.pos-antrian',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\PosAntrian\PosAntrianCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.pos-antrian',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.panggil-antrian',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \RegistrasiOnline\V1\Rest\PanggilAntrian\PanggilAntrianCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.panggil-antrian',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.loket-antrian',
                'route_identifier_name' => 'id',
                'hydrator' => 'Laminas\\Hydrator\\ArraySerializableHydrator',
            ],
            \RegistrasiOnline\V1\Rest\LoketAntrian\LoketAntrianCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'registrasi-online.rest.loket-antrian',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [],
    ],
    'controllers' => [
        'factories' => [
            'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller' => \RegistrasiOnline\V1\Rpc\Plugins\PluginsControllerFactory::class,
        ],
    ],
    'api-tools-rpc' => [
        'RegistrasiOnline\\V1\\Rpc\\Plugins\\Controller' => [
            'service_name' => 'Plugins',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'registrasi-online.rpc.plugins',
        ],
    ],
];
