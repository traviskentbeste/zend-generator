<?php

namespace {{ moduleCamelized }}\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use {{ moduleCamelized }}\Controller\{{ nameCamelized }}Controller;
use {{ moduleCamelized }}\Service\{{ nameCamelized }}Manager;

/**
 * This is the factory for {{ name }}Controller. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class {{ nameCamelized }}ControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return {{ name }}Controller|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        ${{ nameCamelizedLcFirst }}Manager = $container->get({{ nameCamelized }}Manager::class);

        return new {{ nameCamelized }}Controller($entityManager, ${{ nameCamelizedLcFirst }}Manager);

    }

}

