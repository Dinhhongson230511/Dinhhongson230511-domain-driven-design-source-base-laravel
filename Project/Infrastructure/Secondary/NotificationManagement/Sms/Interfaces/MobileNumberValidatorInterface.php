<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces;

interface MobileNumberValidatorInterface
{
    /**
     * Validate the mobile number based on the environment
     *
     * @param string $mobileNumber
     * @return bool
     */
    public static function validate(string $mobileNumber) : bool ;

    /**
     * Check if the number is valid for verification
     *
     * @param string $mobileNumber
     * @return mixed
     */
    public static function validateForVerification(string $mobileNumber) : bool;

    /**
     * Does verification
     *
     * @param string $mobileNumber
     * @param int $verificationCode
     * @return bool
     */
    public static function verified(string $mobileNumber, int $verificationCode) : bool;

    /**
     * Trim Country code
     *
     * @param string $mobileNumber
     * @param string $countryCode
     * @return bool|string
     */
    public static function trimCountryCode(string $mobileNumber, string $countryCode) : string ;
}
