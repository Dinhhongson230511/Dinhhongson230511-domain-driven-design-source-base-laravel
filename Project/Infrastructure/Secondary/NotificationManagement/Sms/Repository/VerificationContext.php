<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Repository;

use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\VerificationContextInterface;

class VerificationContext implements VerificationContextInterface
{
    /**
     *  Used to get a verification context
     *
     * @param string $mobileNumber
     * @param int $verificationCode
     * @return mixed
     */
    public static function getVerificationContext( string $mobileNumber, int $verificationCode ) : string
    {
        return __(config('constants.verification_code_context'), [
            'auth_code' => $verificationCode,
            'expiry_minutes' => config('constants.verification_code_timeout')
        ]);
    }
}
