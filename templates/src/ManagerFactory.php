<?php

namespace {{ moduleCamelized }}\Service\Factory;

use Interop\Container\ContainerInterface;

use {{ moduleCamelized }}\Service\{{ nameCamelized }}Manager;

/**
 * This is the factory class for {{ nameCamelized }}Manager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class {{ nameCamelized }}ManagerFactory
{

    /**
     * This method creates the UserManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new {{ nameCamelized }}Manager($entityManager);

    }

}
