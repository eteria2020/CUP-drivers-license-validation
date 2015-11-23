<?php

namespace MvLabs\DriversLicenseValidation\Service;

use Mvlabs\DriversLicenseValidation\WsseAuthentication\Authentication;
use Mvlabs\DriversLicenseValidation\Exception\WsdlDownloadUnavailableException;
use Mvlabs\DriversLicenseValidation\Exception\ValidationErrorException;

use SoapVar;
use SoapHeader;
use SoapClient;

class PortaleAutomobilistaValidationService implements ValidationServiceInterface
{
    /**
     * @var array $config
     */
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * validate the user driver's license using the public API from
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
        $inputData = $this->createInputData($data);

        try {
            $wsdl = $this->createSoapClient();
        } catch (WsdlDownloadUnavailableException $e) {
            throw new ValidationErrorException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function createInputData(array $data)
    {
        $inputData = [
            'login' => [],
            'patente' => [
                'numeroPatente' => strtoupper($data['license'])
            ],
            'titolare' => [
                'codiceFiscale' => strtoupper($data['taxCode']),
                'nome' => strtoupper($data['name']),
                'cognome' => strtoupper($data['surname']),
                'dataNascita' => $data['birthDate'],
                'origineNascita' => strtoupper($data['birthCountry'])
            ],
            'pdf' => false
        ];

        // check if born in Italy or abroad
        if (strtoupper($data['birthCountry'] === 'it')) {
            $inputData['titolare']['luogoNascitaItaliano'] = [
                'siglaProvincia' => strtoupper($data['birthProvince']),
                'descrizioneComune' => strtoupper($data['birthTown'])
            ];
        } else {
            $inputData['titolare']['siglaStatoEsteroNascita'] = strtoupper($data['birthCountry']);
        }

        return $inputData;
    }

    /**
     * @return SoapClient
     * @throws WsdlDownloadUnavailableException
     */
    private function createSoapClient()
    {
        $wsseNamespace = $this->config['wsse-namespace'];

        $soapUser = new SoapVar($this->config['username'], XSD_STRING, null, $wsseNamespace, null, $wsseNamespace);
        $soapPassword = new SoapVar($this->config['password'], XSD_STRING, null, $wsseNamespace, null, $wsseNamespace);

        $wsseAuthentication = new Authentication($soapUser, $soapPassword);

        $soapWsseAuthentication = new SoapVar($wsseAuthentication, SOAP_ENC_OBJECT, null, $wsseNamespace, 'UsernameToken', $wsseNamespace);

        $wsseToken = new Token($soapWsseAuthentication);

        $soapWsseToken = new SoapVar($wsseToken, SOAP_ENC_OBJECT, null, $wsseNamespace, 'UsernameToken', $wsseNamespace);

        $soapHeader = new SoapVar($soapWsseToken, SOAP_ENC_OBJECT, null, $wsseNamespace, 'Security', $wsseNamespace);

        $soapWsseHeader = new SoapHeader($wsseNamespace, 'Security', $soapHeader, true);

        try {
            $wsdl = new SoapClient($this->config['url'], [
                'connection_timeout' => $this->config['connection_timeout']
            ]);
        } catch (\Exception $e) {
            throw new WsdlDownloadUnavailableException($e->getMessage());
        }

        $wsdl->__setSoapHeaders([$soapWsseHeader]);

        return $wsdl;
    }
}
