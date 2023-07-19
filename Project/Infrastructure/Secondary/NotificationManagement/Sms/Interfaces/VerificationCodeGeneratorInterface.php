<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces;

interface VerificationCodeGeneratorInterface
{
    /**
     * Get Verification Code
     *
     * @param string $mobileNumber
     * @return string
     */
    public function getVerificationCode(string $mobileNumber) : string ;

    /**
     * Used to get a verification code
     *
     * @param $mobileNumber
     * @param $timeOut
     * @return mixed
     */
    public static function generateVerificationCode(string $mobileNumber,int $timeOut) : int ;
}
