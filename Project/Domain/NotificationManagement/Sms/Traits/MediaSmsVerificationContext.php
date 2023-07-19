<?php

namespace Project\Domain\NotificationManagement\Sms\Traits;

trait MediaSmsVerificationContext
{
    /**
     * Get verification form context for media sms
     *
     * @param $mobileNumber
     * @param $messageTitle
     * @param $messageBody
     *
     * @return string
     */
    protected static function getFormContext( string $mobileNumber, string $messageTitle, string $messageBody) : string
    {
        return json_encode([
            'mobilenumber' => $mobileNumber,
            'smstitle' => $messageTitle,
            'smstext' => $messageBody
        ]);
    }
}
