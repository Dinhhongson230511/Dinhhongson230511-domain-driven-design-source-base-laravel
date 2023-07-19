<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static Passport()
 */
final class OauthType extends StringEnum
{
    const Passport = 'passport';
}
