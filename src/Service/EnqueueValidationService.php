<?php

namespace MvLabsDriversLicenseValidation\Service;

use Resque;

class EnqueueValidationService
{
    private $redisBackend;

    private $redisDatabase;

    public function __construct($redisBackend, $redisDatabase)
    {
        $this->redisBackend = $redisBackend;
        $this->redisDatabase = $redisDatabase;
    }

    /**
     * enqueue the request of validating the drivers license of a customer
     *
     * @param array $data
     */
    public function validateDriversLicense($data)
    {
        Resque::setBackend($this->redisBackend, $this->redisDatabase);
        Resque::enqueue('dlv', 'MvLabsDriversLicenseValidation\Job\ValidationJob', $data);
    }
}
