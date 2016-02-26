<?php

namespace MvLabsDriversLicenseValidation\Service;

use MvLabsDriversLicenseValidation\Response\Response;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

class PortaleAutomobilistaValidationService implements ValidationServiceInterface
{
    /**
     * @var array $config Portale dell'automobilista configuration parameters
     */
    private $config;

    /**
     * @var Client $client Zend client used to make http requests
     */
    private $client;

    public function __construct(array $config, Client $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * validate the user driver's license using the API from
     * www.ilportaledellautomobilista.it
     *
     * The parameter $data needs to contain the following keys
     * - license
     * - taxCode
     * - name
     * - surname
     * - birthDate
     * - birthCountry
     * - birthProvince (required only if birthCountry === 'it')
     * - birthTown (required only if birthCountry === 'it')
     *
     * @inheritdoc
     */
    public function validateDriversLicense(array $data)
    {
        $request = $this->createRequest($data);

        $response = $this->client->send($request);

        return $this->parseResponse($response);
    }

    /**
     * @param array $data
     * @return Request
     */
    private function createRequest(array $data)
    {
        $request = new Request();
        $request->setUri($this->config['url']);
        $request->setMethod('POST');

        // temporary measure for the passage from one license name filed to two
        if (!isset($data['driverLicenseSurname'])) {
            list($data['driverLicenseName'], $data['driverLicenseSurname']) =
                $this->splitDriverLicenseName($data['driverLicenseName']);
        }

        $postParameters = new Parameters([
            'patente' => $data['driverLicense'],
            'cf' => $data['taxCode'],
            'nome' => $data['driverLicenseName'],
            'cognome' => $data['driverLicenseSurname'],
            'data_di_nascita' => date_create($data['birthDate']['date'])->format('Y-m-d'),
            'origine_nascita' => $data['birthCountry'] === 'it' ? 'I' : 'E',
            'provincia_nascita' => $data['birthCountry'] === 'it' ? $data['birthProvince'] : '',
            'comune_nascita' => $data['birthCountry'] === 'it' ? $data['birthTown'] : '',
            'stato_nascita' => $data['birthCountryMCTC'],
        ]);
        $request->setPost($postParameters);

        return $request;
    }

    /**
     * @param Response $response
     * @return Response
     */
    private function parseResponse($response)
    {
        $body = $response->getBody();

        // small hack to avoid strange characters at the beginning of the string
        $cleanedBody = substr($body, strpos($body, "{"));

        $parsedResponse = json_decode($cleanedBody);

        if ($parsedResponse->err) {
            // the gateway is not responding correctly to us
            // so we respond with the appropriate message
            return new Response(false, -1, "Gateway not reachable");
        }

        if ($this->isLicenseValid($parsedResponse)) {
            return new Response(true, $parsedResponse->codiceMessaggio, $parsedResponse->descrizioneMessaggio);
        } else {
            return new Response(false, $parsedResponse->codiceErrore, $parsedResponse->descrizioneErrore);
        }
    }

    /**
     * @param \StdClass $responseMessage
     * @return bool
     */
    private function isLicenseValid(\StdClass $responseMessage)
    {
        return $responseMessage->codiceErrore === "None";
    }

    private function splitDriverLicenseName($driverLicenseName)
    {
        $spacePosition = strpos($driverLicenseName, " ");

        if ($spacePosition !== false) {
            $name = substr($driverLicenseName, 0, $spacePosition);
            $surname = substr($driverLicenseName, $spacePosition + 1);
        } else {
            $name = $driverLicenseName;
            $surname = "";
        }

        return [$name, $surname];
    }
}
