<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Model;

use Project\Domain\AuthenticationManagement\Authentication\Enums\UserAuthType;
use Illuminate\Contracts\Container\BindingResolutionException;

class LineAuthentication extends Authentication
{

    /**
     * AuthenticationService constructor.
     *
     * @param string $authId
     * @param string $authType
     * @throws BindingResolutionException
     */
    public function __construct (string $authId, string $authType = UserAuthType::Line)
    {
        parent::__construct($authId, $authType);
    }

}
