<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Traits;

use Carbon\Carbon;
use Project\UserInterface\Helpers\Utility;

trait UserAuthUtilityTrait
{
    /**
     *  Get redirect authentication trait
     */
    public function getAuthenticationLink()
    {
        return env('WEB_APP_LOGIN_URL') . "?authId=$this->auth_id&authType=$this->auth_type";
    }

    /**
     * Method to override the passport user retrieval
     *
     * @param $authId
     * @return mixed
     */
    public function findForPassport($authId)
    {
        return self::where('auth_id', $authId)->first();
    }

    /**
     * Method to override the passport password verification
     *
     * @param $password
     * @return mixed
     */
    public function validateForPassportPasswordGrant($password)
    {
        // We dont have password field
        return true;
    }

    /**
     *  Get the url used for auto login
     */
    public function getAutoLoginUrl()
    {
        return url('auto-login?login_code=' . Utility::encode($this->auth_id . '_' . Carbon::now() . config('constants.auto_login_code')));
    }
}
