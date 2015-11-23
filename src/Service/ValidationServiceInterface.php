<?php

namespace MvLabs\DriversLicenseValidation;

interface ValidationServiceInterface
{
    /**
     * @param array $data array containing the data for the validation; the keys
     *              could change for any specific implementation
     * @return bool
     */
    public function validateDriversLicense(array $data);
}
