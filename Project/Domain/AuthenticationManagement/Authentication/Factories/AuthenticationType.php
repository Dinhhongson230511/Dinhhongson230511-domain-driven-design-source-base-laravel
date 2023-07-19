<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Factories;

use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\ClientAuthenticateInterface;

class AuthenticationType
{
    /**
     *  Get the type of authentication being done (User/Admin)
     *
     * @param string $clientType
     * @param $clientAuth
     * @return ClientAuthenticateInterface
     */
    public static function instantiate(string $clientType, $clientAuth) : ClientAuthenticateInterface
    {
        $client = 'Project\\Domain\\AuthenticationManagement\\Authentication\\Services\\Authenticate'.$clientType;
        return new $client($clientAuth);
    }
}
