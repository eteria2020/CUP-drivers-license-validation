<?php

namespace MvLabsDriversLicenseValidation\Response;

class Response
{
    /**
     * @var bool $valid driver's license valid or not
     */
    private $valid;

    /**
     * @var string $code code of the outcome
     * @see documentation of Motorizzazione Civile
     */
    private $code;

    /**
     * @var string $message description of the outcome
     */
    private $message;

    public function __construct($valid, $code, $message)
    {
        $this->valid = $valid;
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @return bool whether the validation was valid or not
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * @return string cod of the outcome
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return string message of the outcome
     */
    public function message()
    {
        return $this->message;
    }
}
