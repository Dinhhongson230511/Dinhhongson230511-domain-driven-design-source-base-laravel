<?php

namespace Project\Application\User\Services;

use Project\Application\User\Services\Interfaces\SmsServiceInterface;
use Project\Application\User\Traits\SmsServiceFormatter;
use Project\Domain\NotificationManagement\Sms\Services\SmsDomainService;
use Project\Domain\UserManagement\User\Services\UserDomainService;
use Project\UserInterface\Helpers\Utility;

class SmsService implements SmsServiceInterface
{
    use SmsServiceFormatter;

    private SmsDomainService $sms;

    private UserDomainService $userDomainService;

    public function __construct(SmsDomainService $sms, UserDomainService $userDomainService)
    {
        $this->sms = $sms;
        $this->userDomainService = $userDomainService;
    }

    /**
     * Send verification code for user
     *
     * @param array $params
     * @return array
     */
    public function sendVerificationCodeToUser ( array $params ): array
    {
        $result = $this->sms->sendVerificationCodeToUser(self::formatDataForSendingVerificationCode($params));
        //check user is new or not
        $userAuthQueryBuilder = $this->userDomainService->retrieveUserAuthByAuthId(Utility::encode($params['mobileNumber']));
        $result['data']['isNewUser'] = !($userAuthQueryBuilder->count() >= 1);
        $result['data']['ip_address'] = request()->ip(); // get ip address from server side instead of client side

        return $result;
    }

    /**
     * Verify Code
     *
     * @param array $params
     * @return array|string
     */
    public function verifyCode ( array $params ) : array
    {
        return $this->sms->verifyCode(self::formatDataForCodeVerification($params));
    }

}
