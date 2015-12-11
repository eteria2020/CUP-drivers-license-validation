<?php

namespace MvLabsDriversLicenseValidation\Job;

use MvLabsDriversLicenseValidation\Service\ValidationServiceInterface;

use Zend\EventManager\EventManagerInterface;
use Zf2Resque\Service\ResqueJob;

class ValidationJob
{
    /**
     * @var ResqueJob $job
     */
    public $job;

    /**
     * @var array $args array of arguments
     */
    public $args;

    /**
     * @var string $queue The name of the queue that this job belongs to.
     */
    public $queue;

    /**
     * @var ValidationServiceInterface $validationService
     */
    private $validationService;

    /**
     * @var EventManagerInterface $events
     */
    private $events;

    /**
     * @var int number of times the job should be retried
     */
    public $retryLimit = 3;

    public function __construct(
        ValidationServiceInterface $validationService,
        EventManagerInterface $events
    ) {
        $this->validationService = $validationService;
        $this->events = $events;

        $this->events->setIdentifiers(__CLASS__);
    }

    public function perform()
    {
        $response = $this->validationService->validateDriversLicense($this->args);

        $data = [
            'args' => $this->args,
            'response' => $response
        ];

        if ($response->valid()) {
            $this->events->trigger('validDriversLicense', $this, $data);
        } else {
            $this->events->trigger('unvalidDriversLicense', $this, $data);
        }
    }
}
