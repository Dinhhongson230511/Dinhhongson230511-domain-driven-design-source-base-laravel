<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces;

interface VerificationContextInterface
{
    /**
     *  Used to get a verification context
     *
     * @param string $mobileNumber
     * @param int $verificationCode
     * @return mixed
     */
    public static function getVerificationContext(string $mobileNumber, int $verificationCode) : string ;
}
