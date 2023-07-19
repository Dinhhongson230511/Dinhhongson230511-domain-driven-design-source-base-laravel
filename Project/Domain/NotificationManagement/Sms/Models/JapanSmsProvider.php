<?php

namespace Project\Domain\NotificationManagement\Sms\Models;

use Project\Domain\Base\Country\Enums\Countries;
use Project\Domain\NotificationManagement\Sms\Factories\SmsServiceProvider;
use Project\Domain\NotificationManagement\Sms\Services\Interfaces\SmsProviderInterface;
use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\MobileNumberValidatorInterface;
use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\VerificationCodeGeneratorInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class JapanSmsProvider implements SmsProviderInterface
{
    /*
     * Mobile Number
     */
    private $mobileNumber;

    /*
     * @var MobileNumberValidatorInterface
     */
    private MobileNumberValidatorInterface $validator;

    /*
     * Service Provider
     */
    private $smsServiceProvider;

    /**
     * @var VerificationCodeGeneratorInterface
     */
    private $generator;

    /**
     * AuthenticationService constructor.
     * @param string $smsServiceProvider
     * @param Countries $country
     * @param string $mobileNumber
     * @throws BindingResolutionException
     */
    public function __construct (string $smsServiceProvider, Countries $country, string $mobileNumber)
    {
        $this->validator = app()->make(MobileNumberValidatorInterface::class);
        $this->generator = app()->make(VerificationCodeGeneratorInterface::class);
        $this->mobileNumber = $this->validator->trimCountryCode($mobileNumber, $country->value['country_code']);
        $this->smsServiceProvider = $smsServiceProvider;
    }

    /**
     * Get sanitized mobile number
     *
     * @return mixed
     */
    public function getMobileNumber() : string
    {
        return $this->mobileNumber;
    }

    /**
     * Get auth type
     *
     * @return mixed
     */
    public function getSmsServiceProvider() : string
    {
        return $this->smsServiceProvider;
    }

    /**
     * Send verification code request
     *
     * @return array
     */
    public function sendVerificationCode() : array
    {
        // Send verification code via suitable service provider
        return self::sendVerificationCodeViaSms( $this->generator->getVerificationCode( $this->getMobileNumber() ), $this->getSmsServiceProvider() );
    }

    /**
     * Send verification code via sms
     *
     * @param int $verificationCode
     * @param string $serviceProvider
     * @return mixed
     */
    public function sendVerificationCodeViaSms( int $verificationCode, string $serviceProvider ) : array
    {
        // Instantiate the sms service provider of your choice
        $smsServiceProvider = SmsServiceProvider::instantiate($serviceProvider, $this->mobileNumber);

        // Get formatted verification context(form data) based on the service provider and send sms via service provider
        return $smsServiceProvider->sendSms(
            $smsServiceProvider->getFormattedVerificationCode( $verificationCode )
        )->handleApiResponse(request()->all());
    }

    /**
     *  Verify the code sent to user
     *
     * @param int $verificationCode
     * @return mixed
     */
    public function verified(int $verificationCode ) : bool
    {
        return $this->validator->validateForVerification($this->mobileNumber) && $this->validator->verified($this->mobileNumber, $verificationCode) ;
    }

    /**
     * Api to verify code
     *
     * @param int $verificationCode
     * @return bool
     */
    public function verifyCode( int $verificationCode ) : bool
    {
        // If the code is verified
        return self::verified($verificationCode);
    }

    /**
     * Send sms notification
     *
     * @param array $params
     * @return array
     */
    public function sendSmsNotification(string $message, ?string $title = null) : array
    {
        // Instantiate the sms service provider of your choice
        $smsServiceProvider = SmsServiceProvider::instantiate(self::getSmsServiceProvider(), $this->mobileNumber);

        if (!isset($title)) $title = config('constants.sms_title_media');

        // Get formatted form data based on the service provider and send sms via service provider
        return $smsServiceProvider->sendSms(
            $smsServiceProvider->formatDataToSend($message, $title)
        )->handleApiResponse();
    }

}
