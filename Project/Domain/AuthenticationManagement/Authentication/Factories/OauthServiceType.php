<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Factories;

use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\OauthServiceProviderInterface;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;

class OauthServiceType
{
    /**
     * Instantiate oauth service provider
     *
     * @param string $oauthType
     * @param UserAuth $userAuth
     * @return mixed
     */
    public static function instantiateOauthServiceProvider ( string $oauthType, UserAuth $userAuth ) : OauthServiceProviderInterface
    {
        $oauthServiceProvider = 'Project\\Domain\\AuthenticationManagement\\Authentication\\Services\\'.ucfirst($oauthType).'OauthServiceProvider';
        return new $oauthServiceProvider($userAuth);
    }
}
