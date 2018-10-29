

// route
'{{ nameDashed }}' => [
    'type' => Segment::class,
    'options' => [
        'route' => '/oauth2/{{ nameDashed }}[/:action[/:id]]',
        'constraints' => [
            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            'id' => '[a-zA-Z0-9_-]*',
        ],
        'defaults' => [
            'controller' => Controller\{{ nameCamelized }}Controller::class,
                'action' => 'index',
            ],
    ],
],


// this goes in 'controllers' => 'factories'
Controller\{{ nameCamelized }}Controller::class => Controller\Factory\{{ nameCamelized }}ControllerFactory::class,


// this goes in 'service_manager' => 'factories'
Service\{{ nameCamelized }}Manager::class => Service\Factory\{{ nameCamelized }}ManagerFactory::class,


