<?php

namespace MvLabsDriversLicenseValidation;

use Resque_Event;
use Resque\Plugins\Retry;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $retryPlugin = new Retry();

        Resque_Event::listen('beforePerform', [$retryPlugin, 'beforePerform']);
        Resque_Event::listen('afterPerform', [$retryPlugin, 'afterPerform']);
        Resque_Event::listen('onFailure', [$retryPlugin, 'onFailure']);

        $application = $e->getApplication();

        Resque_Event::listen('beforeFork', function () use ($application) {
            $serviceManager = $application->getServiceManager();
            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

            $entityManager->getConnection()->close();
        });
        Resque_Event::listen('beforePerform', function () use ($application) {
            $serviceManager = $application->getServiceManager();
            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

            $entityManager->getConnection()->connect();
        });
    }
}
