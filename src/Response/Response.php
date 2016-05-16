<?php

namespace MvLabsDriversLicenseValidation\Response;

class Response
{
    /**
     * @var bool $valid driver's license valid or not
     */
    private $valid;

    /**
     * @var string|string[] $code code of the outcome, array if more than one code is present
     * @see documentation of Motorizzazione Civile
     */
    private $code;

    /**
     * @var string|string[] $message description of the outcome, array if more than one is present
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
        return $this->valid && $this->message == 'PATENTE VALIDA';
    }

    /**
     * @return string code of the outcome
     */
    public function code()
    {
        if (is_array($this->code)) {
            return implode('|', $this->code);
        }

        return $this->code;
    }

    /**
     * @return string message of the outcome
     */
    public function message()
    {
        if (is_array($this->message)) {
            return implode('|', $this->message);
        }

        return $this->message;
    }
}
