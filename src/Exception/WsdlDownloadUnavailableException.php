<?php

namespace MvLabs\DriversLicensevalidation\Exception;

class WsdlDownloadUnavailableException extends \RuntimeException
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
