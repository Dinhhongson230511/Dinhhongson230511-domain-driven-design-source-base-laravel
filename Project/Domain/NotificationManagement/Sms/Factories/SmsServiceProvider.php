<?php

namespace Project\Domain\NotificationManagement\Sms\Factories;

use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\SmsServiceProviderInterface;

class SmsServiceProvider
{
    /**
     * Instantiate sms service provider
     *
     * @param string $providerType
     * @param string $mobileNumber
     * @return SmsServiceProviderInterface
     */
    public static function instantiate( string $providerType, string $mobileNumber) : SmsServiceProviderInterface
    {
        $smsServiceProvider = 'Project\\Port\\Secondary\\NotificationManagement\\Sms\\Repository\\'.$providerType.'Sms';
        return new $smsServiceProvider($mobileNumber);
    }

}
