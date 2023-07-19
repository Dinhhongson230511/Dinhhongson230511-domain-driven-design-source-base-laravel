<?php

namespace Project\Domain\NotificationManagement\Sms\Enums;

use Project\UserInterface\Enums\IntEnum;

final class SmsStatus extends IntEnum
{
    const Processing =  1;
    const Success =  2;
    const Fail = 3;
}
