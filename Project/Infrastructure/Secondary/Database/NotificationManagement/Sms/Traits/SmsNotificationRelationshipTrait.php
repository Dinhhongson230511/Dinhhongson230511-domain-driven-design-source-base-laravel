<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Sms\Traits;

use Project\Infrastructure\Secondary\Database\NotificationManagement\AutoCheckSetting\ModelDao\NotificationAutoCheckSetting;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\Notification;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait SmsNotificationRelationshipTrait
{
    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class );
    }

    /**
     * @return BelongsTo
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'key', 'key');
    }

    public function notificationAutoCheckSetting()
    {
        return $this->belongsTo(NotificationAutoCheckSetting::class, 'notification_id', 'notification_id');
    }
}
