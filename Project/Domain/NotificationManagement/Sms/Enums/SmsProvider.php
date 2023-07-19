<?php

namespace Project\Domain\NotificationManagement\Sms\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static Media()
 * @method static static Twilio()
 */
final class SmsProvider extends StringEnum
{
    const Media =  'Media';
    const Twilio =  'Twilio';
}
