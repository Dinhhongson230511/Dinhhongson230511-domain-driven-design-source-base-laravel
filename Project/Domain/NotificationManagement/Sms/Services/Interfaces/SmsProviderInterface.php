<?php

namespace Project\Domain\NotificationManagement\Sms\Services\Interfaces;

interface SmsProviderInterface
{
    /**
     * Send verification code request
     *
     * @return array
     */
    public function sendVerificationCode() : array ;

    /**
     * Api to verify code
     *
     * @param int $verificationCode
     * @return bool
     */
    public function verifyCode( int $verificationCode ) : bool ;

    /**
     * Get sanitized mobile number
     *
     * @return mixed
     */
    public function getMobileNumber() :string ;

    /**
     * Send verification code via sms
     *
     * @param int $verificationCode
     * @param string $serviceProvider
     * @return mixed
     */
    public function sendVerificationCodeViaSms(int $verificationCode, string $serviceProvider ) : array ;

    /**
     *  Verify the code sent to user
     *
     * @param int $verificationCode
     * @return mixed
     */
    public function verified(int $verificationCode) : bool;

    /**
     * Send sms notification
     *
     * @param array $params
     * @return array
     */
    public function sendSmsNotification(string $message, ?string $title = null) : array;
}
