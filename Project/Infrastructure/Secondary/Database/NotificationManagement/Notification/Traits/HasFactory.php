<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Traits;

use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Factory\NotificationFactory;

trait HasFactory
{
    public static function factory(...$parameters): NotificationFactory
    {
        return new NotificationFactory();
    }
}
