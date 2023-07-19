<?php

namespace Project\Domain\NotificationManagement\Notification\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static Email()
 * @method static static Sms()
 * @method static static Push()
 * @method static static SecondPush()
 */
final class NotificationType extends StringEnum
{
    const Email = 'email';
    const Sms = 'sms';
    const Push = 'push';
    const SecondPush = 'second_push';
    
    /**
     * @return array
     */
    public static function notificationTypes(): array
    {
        return [
            self::Email,
            self::Sms,
            self::Push,
            self::SecondPush
        ];
    }

}
