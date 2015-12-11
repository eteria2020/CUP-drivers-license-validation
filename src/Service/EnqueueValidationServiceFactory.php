<?php

namespace MvLabsDriversLicenseValidation\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EnqueueValidationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $redisBackend = $config['zf2resque']['redisBackend'];
        $redisDatabase = $config['zf2resque']['redisDatabase'];

        return new EnqueueValidationService($redisBackend, $redisDatabase);
    }
}
