<?php

namespace MvLabsDriversLicenseValidation\Exception;

class ValidationErrorException extends \ErrorException
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
