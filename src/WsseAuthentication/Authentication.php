<?php

namespace MvLabs\DriversLicenseValidation\WsseAuthentication;

class Authentication
{
    /**
     * @var SoapVar $username
     */
    private $username;

    /**
     * @var SoapVar $password
     */
    private $password;

    /**
     * @param SoapVar $username
     * @param SoapVar $password
     */
    public function __construct(SoapVar $username, SoapVar $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
