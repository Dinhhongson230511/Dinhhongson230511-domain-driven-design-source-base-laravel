<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Traits;

use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Factory\EmailFactory;

trait HasFactory
{
    public static function factory(...$parameters): EmailFactory
    {
        return new EmailFactory();
    }
}
