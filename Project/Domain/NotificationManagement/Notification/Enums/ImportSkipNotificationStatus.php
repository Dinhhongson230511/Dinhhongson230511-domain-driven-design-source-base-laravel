<?php


namespace Project\Domain\NotificationManagement\Notification\Enums;


use MyCLabs\Enum\Enum;

final class ImportSkipNotificationStatus extends Enum
{
    const SkipNotification = "TRUE";
    const SendNotification = "FALSE";
}
