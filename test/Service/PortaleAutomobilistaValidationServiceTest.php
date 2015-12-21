<?php

namespace MvLabsDriversLicenseValidation\Service;

use Zend\Http\Client;

class PortaleAutomobilistaValidationServiceTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Client();
    }

    public function testValidateDriversLicenseCorrect()
    {
        $config = [
            'url' => 'http://license.sharengo.it:8080/check_dl.php'
        ];
        $this->validationService = new PortaleAutomobilistaValidationService($config, $this->client);

        $data = [
            'driverLicense' => 'TV5162660G',
            'taxCode' => 'PRNMRC83S14C957V',
            'driverLicenseName' => 'Marco',
            'driverLicenseSurname' => 'Perone',
            'birthDate' => ['date' => '1983-11-14'],
            'birthCountry' => 'it',
            'birthProvince' => 'TV',
            'birthTown' => 'Conegliano'
        ];

        $this->assertTrue($this->validationService->validateDriversLicense($data)->valid());
    }

    public function testValidateDriversLicenseFails()
    {
        $config = [
            'url' => 'http://license.sharengo.it:8080/check_dl.php'
        ];
        $this->validationService = new PortaleAutomobilistaValidationService($config, $this->client);

        $data = [
            'driverLicense' => '',
            'taxCode' => 'PRNMRC83S14C957V',
            'driverLicenseName' => 'Marco',
            'driverLicenseSurname' => 'Perone',
            'birthDate' => ['date' => '1983-11-14'],
            'birthCountry' => 'it',
            'birthProvince' => 'TV',
            'birthTown' => 'Conegliano'
        ];

        $this->assertFalse($this->validationService->validateDriversLicense($data)->valid());
    }
}
