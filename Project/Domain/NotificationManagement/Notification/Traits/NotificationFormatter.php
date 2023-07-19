<?php

namespace Project\Domain\NotificationManagement\Notification\Traits;

use Project\Domain\NotificationManagement\Notification\Enums\NotificationStatus;
use Project\Domain\UserManagement\User\Models\User;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;
use Carbon\Carbon;

trait NotificationFormatter
{
    /**
     * Get formatted data to be stored to database
     *
     * @param User $user
     * @return array
     */
    protected function getFormattedEmailNotification(User $user) : array
    {
        return [
            'user_id' => $user->getId(),
            'key' => $this->notification->key,
            'status' => !isEmpty($this->notification->interval) ? NotificationStatus::Scheduled : NotificationStatus::Success,
            'sent_at' => !isEmpty($this->notification->interval) ? null : Carbon::now(),
            'interval' => $this->notification->interval ?? 0
        ];
    }

    /**
     * Get formatted data to be stored to database
     *
     * @param User $user
     * @return array
     */
    protected function getFormattedSmsNotification(User $user) : array
    {
        return [
            'user_id' => $user->getId(),
            'key' => $this->notification->key,
            'status' => !isEmpty($this->notification->interval) ? NotificationStatus::Scheduled : NotificationStatus::Success,
            'sent_at' => !isEmpty($this->notification->interval) ? null : Carbon::now(),
            'interval' => $this->notification->interval ?? 0
        ];
    }

    /**
     * Get formatted data to be stored to database as notification log
     *
     * @param User $user
     * @param string $type
     * @param BaseModel $notification
     * @param string $via
     * @return array
     */
    protected function getFormattedNotificationLog( User $user, string $type, BaseModel $notification, string $via = 'email') : array
    {
        return [
            'user_id' => $user->getId(),
            'key' => $this->notification->key,
            'notification_id' => $notification->id,
            '$notification_type' => $type,
            'send_via' => $via,
            'message' => $this->notification->message,
            'send_at' => !isEmpty($this->notification->interval) ? Carbon::now()->addMinutes($this->notification->interval) : Carbon::now(),
            'status' => !isEmpty($this->notification->interval) ? NotificationStatus::Scheduled : NotificationStatus::Success,
        ];
    }

    /**
     * Get formatted data for target email notification to be mark as read
     *
     * @return array
     */
    protected function getFormatterDataForEmailNotificationToBeMarkedAsRead() : array
    {
        $now = Carbon::now();

        return [
            'read_at' => $now,
            'resend_via' => '',
            'updated_at' => $now,
            'status' => NotificationStatus::Read
        ];
    }
}
