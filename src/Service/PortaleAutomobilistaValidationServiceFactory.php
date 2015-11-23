<?php

namespace MvLabs\DriversLicenseValidation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PortaleAutomobilistavalidationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['drivers-license-validation']['portale-automobilista'];

        return new PortaleAutomobilistaValidationService($config);
    }
}
