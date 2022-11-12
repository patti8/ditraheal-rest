<?php
return [
    'controllers' => [
        'factories' => [
            'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller' => \Kemkes\IHS\V1\Rpc\Organization\OrganizationControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller' => \Kemkes\IHS\V1\Rpc\Location\LocationControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller' => \Kemkes\IHS\V1\Rpc\Practitioner\PractitionerControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller' => \Kemkes\IHS\V1\Rpc\Patient\PatientControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller' => \Kemkes\IHS\V1\Rpc\Encounter\EncounterControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller' => \Kemkes\IHS\V1\Rpc\Condition\ConditionControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller' => \Kemkes\IHS\V1\Rpc\Observation\ObservationControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller' => \Kemkes\IHS\V1\Rpc\Procedure\ProcedureControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller' => \Kemkes\IHS\V1\Rpc\Composition\CompositionControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller' => \Kemkes\IHS\V1\Rpc\Medication\MedicationControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller' => \Kemkes\IHS\V1\Rpc\MedicationRequest\MedicationRequestControllerFactory::class,
            'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller' => \Kemkes\IHS\V1\Rpc\MedicationDispanse\MedicationDispanseControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'kemkes-ihs.rpc.organization' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/organization[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.location' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/location[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.practitioner' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/practitioner[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.patient' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/patient[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.encounter' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/encounter[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.condition' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/condition[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.observation' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/observation[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.procedure' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/procedure[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.composition' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/composition[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.medication' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/medication[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.medication-request' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/medication/request[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller',
                    ],
                ],
            ],
            'kemkes-ihs.rpc.medication-dispanse' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/kemkes/ihs/medication/dispanse[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z]*$',
                        'id' => '[a-zA-Z0-9-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'kemkes-ihs.rpc.organization',
            1 => 'kemkes-ihs.rpc.location',
            2 => 'kemkes-ihs.rpc.practitioner',
            3 => 'kemkes-ihs.rpc.patient',
            4 => 'kemkes-ihs.rpc.encounter',
            5 => 'kemkes-ihs.rpc.condition',
            6 => 'kemkes-ihs.rpc.observation',
            7 => 'kemkes-ihs.rpc.procedure',
            8 => 'kemkes-ihs.rpc.composition',
            9 => 'kemkes-ihs.rpc.medication',
            10 => 'kemkes-ihs.rpc.medication-request',
            11 => 'kemkes-ihs.rpc.medication-dispanse',
        ],
    ],
    'api-tools-rpc' => [
        'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller' => [
            'service_name' => 'Organization',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.organization',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller' => [
            'service_name' => 'Location',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'kemkes-ihs.rpc.location',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller' => [
            'service_name' => 'Practitioner',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'kemkes-ihs.rpc.practitioner',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller' => [
            'service_name' => 'Patient',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.patient',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller' => [
            'service_name' => 'Encounter',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.encounter',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller' => [
            'service_name' => 'Condition',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'kemkes-ihs.rpc.condition',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller' => [
            'service_name' => 'Observation',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'kemkes-ihs.rpc.observation',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller' => [
            'service_name' => 'Procedure',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'kemkes-ihs.rpc.procedure',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller' => [
            'service_name' => 'Composition',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.composition',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller' => [
            'service_name' => 'Medication',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.medication',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller' => [
            'service_name' => 'MedicationRequest',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.medication-request',
        ],
        'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller' => [
            'service_name' => 'MedicationDispanse',
            'http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'route_name' => 'kemkes-ihs.rpc.medication-dispanse',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller' => 'Json',
            'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'Kemkes\\IHS\\V1\\Rpc\\Organization\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Location\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Practitioner\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Patient\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Encounter\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Condition\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Observation\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Procedure\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Composition\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\Medication\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\MedicationRequest\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
            'Kemkes\\IHS\\V1\\Rpc\\MedicationDispanse\\Controller' => [
                0 => 'application/vnd.kemkes-ihs.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [],
    ],
    'api-tools-rest' => [],
    'api-tools-hal' => [
        'metadata_map' => [],
    ],
];
