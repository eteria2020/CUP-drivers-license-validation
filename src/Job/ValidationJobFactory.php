<?php

namespace MvLabsDriversLicenseValidation\Job;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidationJobFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $validationService = $serviceLocator->get('MvLabsDriversLicenseValidation\Validation');
        $events = $serviceLocator->get('EventManager');

        return new ValidationJob($validationService, $events);
    }
}
