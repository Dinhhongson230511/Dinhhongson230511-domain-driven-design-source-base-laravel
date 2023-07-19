<?php

namespace Project\Application\User\Services\Interfaces;

use Project\Domain\NotificationManagement\Notification\Models\Notification;

interface NotificationServiceInterface
{
    /**
     * @param string $id
     * @return array
     */
    public function markEmailNotificationAsRead(string $id): array;
}
