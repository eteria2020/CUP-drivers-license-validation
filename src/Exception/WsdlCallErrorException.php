<?php

namespace MvLabs\DriversLicensevalidation\Exception;

class WsdlCallErrorException extends \RuntimeException
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
