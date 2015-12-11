<?php

namespace MvLabsDriversLicenseValidation\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Client;

class PortaleAutomobilistaValidationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['drivers-license-validation']['portale-automobilista'];
        $client = new Client();
        $client->setOptions([
            'timeout' => 30
        ]);

        return new PortaleAutomobilistaValidationService($config, $client);
    }
}
