<?php

namespace Project\Application\User\Services\Interfaces;

interface SmsServiceInterface
{
    /**
     * Send verification code for user
     *
     * @param array $params
     * @return array
     */
    public function sendVerificationCodeToUser(array $params) : array ;

    /**
     * Verify Code
     *
     * @param array $params
     * @return array
     */
    public function verifyCode(array $params) : array ;
}
