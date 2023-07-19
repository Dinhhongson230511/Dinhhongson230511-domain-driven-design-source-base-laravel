<?php

namespace Project\Application\User\Traits;

use Project\Domain\AuthenticationManagement\Authentication\Enums\UserAuthType;
use Project\Domain\Base\Country\Enums\Countries;

trait SmsServiceFormatter
{
    /**
     * Format data for code verification
     *
     * @param array $params
     * @return array
     */
    protected function formatDataForCodeVerification(array $params) : array
    {
        return array_merge($params, [
           'authType' => UserAuthType::Mobile
        ]);
    }

    /**
     * Get formatted data for sending verification code
     *
     * @param array $params
     * @return array
     */
    protected function formatDataForSendingVerificationCode(array $params) : array
    {
        return array_merge($params, [
           'country' => Countries::{$params['country']}()
        ]);
    }
}
