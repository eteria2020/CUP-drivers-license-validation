<?php

namespace MvLabsDriversLicenseValidation\Job;

use MvLabsDriversLicenseValidation\Response\Response;

use Zend\EventManager\EventManager;

class ValidationJobTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->validationService = \Mockery::mock('MvLabsDriversLicenseValidation\Service\ValidationServiceInterface');
        $this->events = \Mockery::mock('Zend\EventManager\EventManagerInterface');

        $this->events->shouldReceive('setIdentifiers');

        $this->validationJob = new ValidationJob($this->validationService, $this->events);
        $this->validationJob->args = [];
    }

    public function testPerformValid()
    {
        $response = new Response(true, '', '');

        $this->validationService->shouldReceive('validateDriversLicense')->andReturn($response);
        $this->events->shouldReceive('trigger')->with('validDriversLicense', \Mockery::any(), \Mockery::any());

        $this->validationJob->perform();
    }

    public function testPerformInvalid()
    {
        $response = new Response(false, '', '');

        $this->validationService->shouldReceive('validateDriversLicense')->andReturn($response);
        $this->events->shouldReceive('trigger')->with('unvalidDriversLicense', \Mockery::any(), \Mockery::any());

        $this->validationJob->perform();
    }
}
