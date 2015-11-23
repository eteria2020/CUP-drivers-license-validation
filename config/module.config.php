<?php

namespace MvLabs\DriversLicenseValidation;

return [
    'service_manager' => [
        'factories' => [
            'MvLabs\DriversLicenseValidation\PortaleAutomobilista' => 'MvLabs\DriversLicenseValidation\PortaleAutomobilistaServiceFactory'
        ],
        'alias' => [
            'MvLabs\DriversLicenseValidation\Validation' => 'MvLabs\DriversLicenseValidation\PortaleAutomobilista'
        ]
    ]
];
