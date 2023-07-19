<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces;

use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;

interface OauthServiceProviderInterface
{
    /**
     *  Used to generate access token for oauth client
     * @param UserAuth $userAuth
     * @return mixed
     */
    public function generateAccessToken(UserAuth $userAuth);
}
