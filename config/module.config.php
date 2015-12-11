<?php

namespace MvLabsDriversLicenseValidation;

return [
    'service_manager' => [
        'factories' => [
            'MvLabsDriversLicenseValidation\PortaleAutomobilista' => 'MvLabsDriversLicenseValidation\Service\PortaleAutomobilistaValidationServiceFactory',
            'MvLabsDriversLicenseValidation\EnqueueValidation' => 'MvLabsDriversLicenseValidation\Service\EnqueueValidationServiceFactory',
            'MvLabsDriversLicenseValidation\Job\ValidationJob' => 'MvLabsDriversLicenseValidation\Job\ValidationJobFactory'
        ],
        'aliases' => [
            'MvLabsDriversLicenseValidation\Validation' => 'MvLabsDriversLicenseValidation\PortaleAutomobilista'
        ]
    ]
];
