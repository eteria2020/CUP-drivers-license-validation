<?php

namespace MvLabsDriversLicenseValidation\Service;

interface ValidationServiceInterface
{
    /**
     * @param array $data array containing the data for the validation; the keys
     *              could change for any specific implementation
     * @return \MvLabsDriversLicenseValidation\Response\Response
     */
    public function validateDriversLicense(array $data);
}
