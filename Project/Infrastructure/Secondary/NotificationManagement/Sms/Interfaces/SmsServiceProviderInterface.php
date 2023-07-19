<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces;

interface SmsServiceProviderInterface
{
    /**
     * Send sms via service provider
     *
     * @param string $formParams
     * @return mixed
     */
    public function sendSms( string $formParams ) : SmsServiceProviderInterface;

    /**
     * Handle Api response
     *
     * @param array $data
     * @return mixed
     */
    public function handleApiResponse( array $data = [] ) : array ;

    /**
     * Get formatted verification code
     *
     * @param int $verificationCode
     * @return mixed
     */
    public function getFormattedVerificationCode( int $verificationCode ) : string ;

    /**
     * Get formatted data to send sms notification
     *
     * @param string $message
     * @param string $title
     * @return string
     */
    public function formatDataToSend(string $message, string $title) : string ;
}
