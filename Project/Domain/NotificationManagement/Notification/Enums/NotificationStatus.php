<?php

namespace Project\Domain\NotificationManagement\Notification\Enums;

use Project\UserInterface\Enums\IntEnum;

/**
 * @method static static Inactive()
 * @method static static Active()
 */
final class NotificationStatus extends IntEnum
{
    const Inactive =  0;
    const Active =  1;
}
