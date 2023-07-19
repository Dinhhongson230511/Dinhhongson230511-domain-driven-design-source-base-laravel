<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static User()
 * @method static static Admin()
 */

final class ClientType extends StringEnum
{
    const User = 'User';
    const Admin = 'Admin';
}
