<?php

namespace {{moduleCamelized}};

use PHPUnit\Framework\MockObject\Invokable;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [

    'router' => [
        'routes' => [

        ]
    ],

    'controllers' => [
        'factories' => [

        ],
    ],

    'service_manager' => [
        'factories' => [

        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],

];