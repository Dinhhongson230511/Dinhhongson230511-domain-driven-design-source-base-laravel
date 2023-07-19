<?php

namespace Project\Domain\NotificationManagement\Email\Enums;

use Project\UserInterface\Enums\IntEnum;

final class EmailStatus extends IntEnum
{
    const Processing =  1;
    const Success =  2;
    const Fail = 3;
}
