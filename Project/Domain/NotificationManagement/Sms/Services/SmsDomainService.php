<?php

namespace Project\Domain\NotificationManagement\Sms\Services;

use Project\Domain\Base\Country\Enums\Countries;
use Project\Domain\NotificationManagement\Sms\Factories\SmsServiceType;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User;
use Project\UserInterface\Helpers\Utility;
use Project\UserInterface\ResponseCodes\ApiCodes;
use Illuminate\Http\Response;

class SmsDomainService
{
    /**
     * Send verification code for user
     *
     * @param array $params
     * @return array
     */
    public function sendVerificationCodeToUser(array $params): array
    {
        $mobileNumber = $params['mobileNumber'];

        // Instantiate service provider based on the country and send verification code
        return SmsServiceType::instantiate($params['country'], $mobileNumber)
            ->sendVerificationCode();
    }

    /**
     * Send sms notification
     *
     * @param array $params
     * @return array
     */
    public function sendSmsNotification(string $mobileNumber, string $message, ?Countries $country = null): array
    {
        if (!in_array($mobileNumber, config('whitelist_in_dev')['sms']) && env('APP_ENV') != 'production') {
            throw new \Exception($mobileNumber . ' sms is not verified!!');
        }

        $country = $country ?? Countries::Japan();
        return SmsServiceType::instantiate($country, $mobileNumber)
            ->sendSmsNotification($message);
    }

    /**
     * Verify Code
     *
     * @param array $params
     * @return array
     */
    public function verifyCode(array $params): array
    {
        // Instantiate service provider based on the country and verify code
        $mobileNumber = request()->get('mobileNumber');
        $smsService = SmsServiceType::instantiate(Countries::{$params['country']}(), $mobileNumber);

        // Verify user code
        if ($smsService->verifyCode($params['verificationCode'])) {

            // //if user's mobile number was changed, store new auth id
            $authId = Utility::encode($smsService->getMobileNumber());

            $user = User::where('mobile_number', $mobileNumber)->first();

            $userAuth = $user?->userAuth()->first();
            if ($userAuth && $userAuth->auth_id != $authId) {
                $userAuth->auth_id = $authId;
                $userAuth->save();
            }

            $whiteMode = 0;
            $userMetaData = $user?->userMetaData()?->first();
            if ($userMetaData && $userMetaData->one_signal_player_id) {
                $whiteMode = 1;
            }

            // Return success response
            return Utility::getStructuredResponse(Response::HTTP_OK, __('api_auth.mobile_number_successfully_verified', [
                'number' => $mobileNumber,
            ]), [
                'redirectUrl' => env('WEB_APP_LOGIN_URL')
                    . "?authId=" . $authId
                    . "&authType=" . $params['authType']
                    . "&mobile_number=" . $mobileNumber
                    . "&isNewUser=" . ($user ? 'false' : 'true')
                    . "&white=" . $whiteMode,
            ]);
        }


        // If not verified then return an error
        return Utility::getStructuredResponse(ApiCodes::INVALID_VERIFICATION_CODE, __('api_auth.not_a_valid_verification_code'));
    }
}
