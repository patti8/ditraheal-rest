<?php
return [
    'service_manager' => [
        'factories' => [
            \Mutu\V1\Rest\Indikator\IndikatorResource::class => \Mutu\V1\Rest\Indikator\IndikatorResourceFactory::class,
            \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorResource::class => \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorResourceFactory::class,
            \Mutu\V1\Rest\DataIndikator\DataIndikatorResource::class => \Mutu\V1\Rest\DataIndikator\DataIndikatorResourceFactory::class,
            \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorResource::class => \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorResourceFactory::class,
            \Mutu\V1\Rest\Analisa\AnalisaResource::class => \Mutu\V1\Rest\Analisa\AnalisaResourceFactory::class,
            \Mutu\V1\Rest\PDSA\PDSAResource::class => \Mutu\V1\Rest\PDSA\PDSAResourceFactory::class,
            \Mutu\V1\Rest\Laporan\LaporanResource::class => \Mutu\V1\Rest\Laporan\LaporanResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'mutu.rest.indikator' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\Indikator\\Controller',
                    ],
                ],
            ],
            'mutu.rest.ruangan-indikator' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/ruangan[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\RuanganIndikator\\Controller',
                    ],
                ],
            ],
            'mutu.rest.data-indikator' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/data[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\DataIndikator\\Controller',
                    ],
                ],
            ],
            'mutu.rest.data-dasar-indikator' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/datadasar[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\DataDasarIndikator\\Controller',
                    ],
                ],
            ],
            'mutu.rest.analisa' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/analisa[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\Analisa\\Controller',
                    ],
                ],
            ],
            'mutu.rest.pdsa' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/pdsa[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\PDSA\\Controller',
                    ],
                ],
            ],
            'mutu.rest.laporan' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/mutu/indikator/laporan[/:id]',
                    'defaults' => [
                        'controller' => 'Mutu\\V1\\Rest\\Laporan\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'mutu.rest.indikator',
            1 => 'mutu.rest.ruangan-indikator',
            2 => 'mutu.rest.data-indikator',
            3 => 'mutu.rest.data-dasar-indikator',
            4 => 'mutu.rest.analisa',
            5 => 'mutu.rest.pdsa',
            6 => 'mutu.rest.laporan',
        ],
    ],
    'api-tools-rest' => [
        'Mutu\\V1\\Rest\\Indikator\\Controller' => [
            'listener' => \Mutu\V1\Rest\Indikator\IndikatorResource::class,
            'route_name' => 'mutu.rest.indikator',
            'route_identifier_name' => 'id',
            'collection_name' => 'indikator',
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
                0 => 'ID',
                1 => 'KODE',
                2 => 'STATUS',
                3 => 'start',
                4 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\Indikator\IndikatorEntity::class,
            'collection_class' => \Mutu\V1\Rest\Indikator\IndikatorCollection::class,
            'service_name' => 'Indikator',
        ],
        'Mutu\\V1\\Rest\\RuanganIndikator\\Controller' => [
            'listener' => \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorResource::class,
            'route_name' => 'mutu.rest.ruangan-indikator',
            'route_identifier_name' => 'id',
            'collection_name' => 'ruangan_indikator',
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
                0 => 'RUANGAN',
                1 => 'INDIKATOR',
                2 => 'STATUS',
                3 => 'start',
                4 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorEntity::class,
            'collection_class' => \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorCollection::class,
            'service_name' => 'RuanganIndikator',
        ],
        'Mutu\\V1\\Rest\\DataIndikator\\Controller' => [
            'listener' => \Mutu\V1\Rest\DataIndikator\DataIndikatorResource::class,
            'route_name' => 'mutu.rest.data-indikator',
            'route_identifier_name' => 'id',
            'collection_name' => 'data_indikator',
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
                0 => 'INDIKATOR',
                1 => 'RUANGAN',
                2 => 'STATUS',
                3 => 'TANGGAL',
                4 => 'sortperiode',
                5 => 'GRAFIK',
                6 => 'TANGGAL_AWAL',
                7 => 'TANGGAL_AKHIR',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\DataIndikator\DataIndikatorEntity::class,
            'collection_class' => \Mutu\V1\Rest\DataIndikator\DataIndikatorCollection::class,
            'service_name' => 'DataIndikator',
        ],
        'Mutu\\V1\\Rest\\DataDasarIndikator\\Controller' => [
            'listener' => \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorResource::class,
            'route_name' => 'mutu.rest.data-dasar-indikator',
            'route_identifier_name' => 'id',
            'collection_name' => 'data_dasar_indikator',
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
                0 => 'DATA_INDIKATOR',
                1 => 'NOREG',
                2 => 'NORM',
                3 => 'STATUS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorEntity::class,
            'collection_class' => \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorCollection::class,
            'service_name' => 'DataDasarIndikator',
        ],
        'Mutu\\V1\\Rest\\Analisa\\Controller' => [
            'listener' => \Mutu\V1\Rest\Analisa\AnalisaResource::class,
            'route_name' => 'mutu.rest.analisa',
            'route_identifier_name' => 'id',
            'collection_name' => 'analisa',
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
                0 => 'INDIKATOR',
                1 => 'RUANGAN',
                2 => 'TANGGAL_AWAL',
                3 => 'TANGGAL_AKHIR',
                4 => 'STATUS',
                5 => 'sortperiode',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\Analisa\AnalisaEntity::class,
            'collection_class' => \Mutu\V1\Rest\Analisa\AnalisaCollection::class,
            'service_name' => 'Analisa',
        ],
        'Mutu\\V1\\Rest\\PDSA\\Controller' => [
            'listener' => \Mutu\V1\Rest\PDSA\PDSAResource::class,
            'route_name' => 'mutu.rest.pdsa',
            'route_identifier_name' => 'id',
            'collection_name' => 'pdsa',
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
                1 => 'ANALISA',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\PDSA\PDSAEntity::class,
            'collection_class' => \Mutu\V1\Rest\PDSA\PDSACollection::class,
            'service_name' => 'PDSA',
        ],
        'Mutu\\V1\\Rest\\Laporan\\Controller' => [
            'listener' => \Mutu\V1\Rest\Laporan\LaporanResource::class,
            'route_name' => 'mutu.rest.laporan',
            'route_identifier_name' => 'id',
            'collection_name' => 'laporan',
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
                0 => 'TANGGAL_AWAL',
                1 => 'TANGGAL_AKHIR',
                2 => 'INDIKATOR',
                3 => 'RUANGAN',
                4 => 'STATUS',
                5 => 'sortperiode',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Mutu\V1\Rest\Laporan\LaporanEntity::class,
            'collection_class' => \Mutu\V1\Rest\Laporan\LaporanCollection::class,
            'service_name' => 'Laporan',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'Mutu\\V1\\Rest\\Indikator\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\RuanganIndikator\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\DataIndikator\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\DataDasarIndikator\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\Analisa\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\PDSA\\Controller' => 'Json',
            'Mutu\\V1\\Rest\\Laporan\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Mutu\\V1\\Rest\\Indikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\RuanganIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\DataIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\DataDasarIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\Analisa\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\PDSA\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\Laporan\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Mutu\\V1\\Rest\\Indikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\RuanganIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\DataIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\DataDasarIndikator\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\Analisa\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\PDSA\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
            'Mutu\\V1\\Rest\\Laporan\\Controller' => [
                0 => 'application/vnd.mutu.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \Mutu\V1\Rest\Indikator\IndikatorEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.indikator',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\Indikator\IndikatorCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.indikator',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorEntity::class => [
                'entity_identifier_name' => 'RUANGAN',
                'route_name' => 'mutu.rest.ruangan-indikator',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\RuanganIndikator\RuanganIndikatorCollection::class => [
                'entity_identifier_name' => 'RUANGAN',
                'route_name' => 'mutu.rest.ruangan-indikator',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\DataIndikator\DataIndikatorEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.data-indikator',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\DataIndikator\DataIndikatorCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.data-indikator',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.data-dasar-indikator',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\DataDasarIndikator\DataDasarIndikatorCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.data-dasar-indikator',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\Analisa\AnalisaEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.analisa',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\Analisa\AnalisaCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.analisa',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\PDSA\PDSAEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.pdsa',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\PDSA\PDSACollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.pdsa',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
            \Mutu\V1\Rest\Laporan\LaporanEntity::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.laporan',
                'route_identifier_name' => 'id',
                'hydrator' => \Laminas\Hydrator\ArraySerializable::class,
            ],
            \Mutu\V1\Rest\Laporan\LaporanCollection::class => [
                'entity_identifier_name' => 'ID',
                'route_name' => 'mutu.rest.laporan',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ],
        ],
    ],
];
