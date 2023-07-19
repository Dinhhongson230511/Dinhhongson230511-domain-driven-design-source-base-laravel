<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static Facebook()
 * @method static static Line()
 * @method static static Mobile()
 */
final class UserAuthType extends StringEnum
{
    const Facebook = 'Facebook';
    const Line = 'Line';
    const Mobile = 'Mobile';
}
