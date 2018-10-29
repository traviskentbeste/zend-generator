<?php

namespace {{moduleCamelized}};

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;

class Module
{

    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig()
    {

        return include __DIR__ . '/../config/module.config.php';

    }

    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners.
     */
    public function onBootstrap(MvcEvent $event)
    {

        // get Event Manager
        //$eventManager = $event->getApplication()->getEventManager();

        // get Shared Event Manager
        //$sharedEventManager = $eventManager->getSharedManager();

    }

    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {

        //print "onDispatch\n";

    }

}
