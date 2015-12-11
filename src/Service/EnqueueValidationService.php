<?php

namespace MvLabsDriversLicenseValidation\Service;

use Resque;

class EnqueueValidationService
{
    /**
     * enqueue the request of validating the drivers license of a customer
     *
     * @param array $data
     */
    public function validateDriversLicense($data)
    {
        Resque::enqueue('dlv', 'MvLabsDriversLicenseValidation\Job\ValidationJob', $data);
    }
}
