<?php


namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Traits;


use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Factory\UserNotificationExclusionFactory;

trait HasUserNotificationExclusionFactory
{
    public static function factory(...$parameters): UserNotificationExclusionFactory
    {
        return new UserNotificationExclusionFactory();
    }
}
