<?php
return [
    'controllers' => [
        'factories' => [
            'RIS\\V1\\Rpc\\Order\\Controller' => \RIS\V1\Rpc\Order\OrderControllerFactory::class,
            'RIS\\V1\\Rpc\\Viewer\\Controller' => \RIS\V1\Rpc\Viewer\ViewerControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'ris.rpc.order' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ris/order[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'RIS\\V1\\Rpc\\Order\\Controller',
                    ],
                ],
            ],
            'ris.rpc.viewer' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ris/viewer[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'RIS\\V1\\Rpc\\Viewer\\Controller',
                    ],
                ],
            ],
            'ris.rest.modality' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ris/modality[/:modality_id]',
                    'defaults' => [
                        'controller' => 'RIS\\V1\\Rest\\Modality\\Controller',
                    ],
                ],
            ],
            'ris.rest.modality-tindakan' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ris/modality-tindakan[/:modality_tindakan_id]',
                    'defaults' => [
                        'controller' => 'RIS\\V1\\Rest\\ModalityTindakan\\Controller',
                    ],
                ],
            ],
            'ris.rest.logs' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ris/logs[/:logs_id]',
                    'defaults' => [
                        'controller' => 'RIS\\V1\\Rest\\Logs\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'ris.rpc.order',
            1 => 'ris.rpc.viewer',
            2 => 'ris.rest.modality',
            3 => 'ris.rest.modality-tindakan',
            4 => 'ris.rest.logs',
        ],
    ],
    'api-tools-rpc' => [
        'RIS\\V1\\Rpc\\Order\\Controller' => [
            'service_name' => 'Order',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'route_name' => 'ris.rpc.order',
        ],
        'RIS\\V1\\Rpc\\Viewer\\Controller' => [
            'service_name' => 'Viewer',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'ris.rpc.viewer',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'RIS\\V1\\Rpc\\Order\\Controller' => 'Json',
            'RIS\\V1\\Rpc\\Viewer\\Controller' => 'Json',
            'RIS\\V1\\Rest\\Modality\\Controller' => 'Json',
            'RIS\\V1\\Rest\\ModalityTindakan\\Controller' => 'Json',
            'RIS\\V1\\Rest\\Logs\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'RIS\\V1\\Rpc\\Order\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'RIS\\V1\\Rpc\\Viewer\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'RIS\\V1\\Rest\\Modality\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RIS\\V1\\Rest\\ModalityTindakan\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'RIS\\V1\\Rest\\Logs\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'RIS\\V1\\Rpc\\Order\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
            ],
            'RIS\\V1\\Rpc\\Viewer\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
            ],
            'RIS\\V1\\Rest\\Modality\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
            ],
            'RIS\\V1\\Rest\\ModalityTindakan\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
            ],
            'RIS\\V1\\Rest\\Logs\\Controller' => [
                0 => 'application/vnd.ris.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \RIS\V1\Rest\Modality\ModalityResource::class => \RIS\V1\Rest\Modality\ModalityResourceFactory::class,
            \RIS\V1\Rest\ModalityTindakan\ModalityTindakanResource::class => \RIS\V1\Rest\ModalityTindakan\ModalityTindakanResourceFactory::class,
            \RIS\V1\Rest\Logs\LogsResource::class => \RIS\V1\Rest\Logs\LogsResourceFactory::class,
        ],
    ],
    'api-tools-rest' => [
        'RIS\\V1\\Rest\\Modality\\Controller' => [
            'listener' => \RIS\V1\Rest\Modality\ModalityResource::class,
            'route_name' => 'ris.rest.modality',
            'route_identifier_name' => 'modality_id',
            'collection_name' => 'modality',
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
                0 => 'NAMA',
                1 => 'start',
                2 => 'limit',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RIS\V1\Rest\Modality\ModalityEntity::class,
            'collection_class' => \RIS\V1\Rest\Modality\ModalityCollection::class,
            'service_name' => 'Modality',
        ],
        'RIS\\V1\\Rest\\ModalityTindakan\\Controller' => [
            'listener' => \RIS\V1\Rest\ModalityTindakan\ModalityTindakanResource::class,
            'route_name' => 'ris.rest.modality-tindakan',
            'route_identifier_name' => 'modality_tindakan_id',
            'collection_name' => 'modality_tindakan',
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
                2 => 'QUERY',
                3 => 'JENIS',
                4 => 'STATUS',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RIS\V1\Rest\ModalityTindakan\ModalityTindakanEntity::class,
            'collection_class' => \RIS\V1\Rest\ModalityTindakan\ModalityTindakanCollection::class,
            'service_name' => 'ModalityTindakan',
        ],
        'RIS\\V1\\Rest\\Logs\\Controller' => [
            'listener' => \RIS\V1\Rest\Logs\LogsResource::class,
            'route_name' => 'ris.rest.logs',
            'route_identifier_name' => 'logs_id',
            'collection_name' => 'logs',
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
                0 => 'TANGGAL',
                1 => 'start',
                2 => 'limit',
                3 => 'TINDAKAN_MEDIS',
                4 => 'JENIS',
                5 => 'STATUS',
                6 => 'KIRIM',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \RIS\V1\Rest\Logs\LogsEntity::class,
            'collection_class' => \RIS\V1\Rest\Logs\LogsCollection::class,
            'service_name' => 'Logs',
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \RIS\V1\Rest\Modality\ModalityEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.modality',
                'route_identifier_name' => 'modality_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \RIS\V1\Rest\Modality\ModalityCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.modality',
                'route_identifier_name' => 'modality_id',
                'is_collection' => true,
            ],
            \RIS\V1\Rest\ModalityTindakan\ModalityTindakanEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.modality-tindakan',
                'route_identifier_name' => 'modality_tindakan_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \RIS\V1\Rest\ModalityTindakan\ModalityTindakanCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.modality-tindakan',
                'route_identifier_name' => 'modality_tindakan_id',
                'is_collection' => true,
            ],
            \RIS\V1\Rest\Logs\LogsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.logs',
                'route_identifier_name' => 'logs_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \RIS\V1\Rest\Logs\LogsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'ris.rest.logs',
                'route_identifier_name' => 'logs_id',
                'is_collection' => true,
            ],
        ],
    ],
];
