<?php

namespace MvLabs\DriversLicenseValidation\WsseAuthentication;

class Token
{
    /**
     * @var SoapVar $usernameToken
     */
    private $usernameToken;

    /**
     * @param SoapVar $usernameToken
     */
    public function __construct(SoapVar $usernameToken)
    {
        $this->usernameToken = $usernameToken;
    }
}
