<?php

namespace Project\Domain\UserManagement\User\Enums;

use Project\UserInterface\Enums\IntEnum;

/**
 * @method static static IncompleteUser()
 * @method static static AwaitingUser()
 * @method static static ApprovedUser()
 * @method static static DeactivatedUser()
 * @method static static CancelledUser()
 */
final class UserStatus extends IntEnum
{
    const IncompleteUser = 1;
    const AwaitingUser = 2;
    const ApprovedUser = 4;
    const DeactivatedUser = 5;
    const CancelledUser = 6;
}
