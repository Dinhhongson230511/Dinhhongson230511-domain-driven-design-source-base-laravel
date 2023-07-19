<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Factories;

use Project\Domain\AuthenticationManagement\Authentication\Model\MobileAuthentication;
use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\AuthServiceInterface;

class AuthType
{
    /**
     * Instantiate user type
     *
     * @param string $authId
     * @param string $authType
     * @return AuthServiceInterface
     */
    public static function instantiate( string $authType, string $authId = '', ?string $lpQueryStr = null, ?string $oneSignalPlayerId = null, ?string $appVersion = null) : AuthServiceInterface
    {
        $authenticateService = 'Project\\Domain\\AuthenticationManagement\\Authentication\\Model\\'.$authType.'Authentication';
        return $authType == 'Mobile' ? new $authenticateService($authId, $lpQueryStr, $oneSignalPlayerId, $appVersion) :  new $authenticateService($authId);
    }
}
