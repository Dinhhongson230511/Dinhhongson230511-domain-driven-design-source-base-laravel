<?php

namespace Project\Domain\NotificationManagement\Notification\Models;

use Project\Domain\Base\BaseDomainModel;
use Carbon\Carbon;

class UserNotificationExclusion extends BaseDomainModel
{
    private int $userId;
    private string $notificationKey;
    private int $status;
    private string $type;
    private Carbon $updatedAt;
    private Carbon $createdAt;

    public function __construct(
        int $userId,
        string $notificationKey,
        string $status,
        string $type,
    ) {
        $this->setUserId($userId);
        $this->setKey($notificationKey);
        $this->setStatus($status);
        $this->setType($type);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return UserNotificationExclusion
     */
    public function setUserId(int $userId): UserNotificationExclusion
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->notificationKey;
    }

    /**
     * @param string $notificationKey
     * @return UserNotificationExclusion
     */
    public function setKey(string $notificationKey): UserNotificationExclusion
    {
        $this->notificationKey = $notificationKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return UserNotificationExclusion
     */
    public function setType(string $type): UserNotificationExclusion
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return UserNotificationExclusion
     */
    public function setStatus(int $status): UserNotificationExclusion
    {
        $this->status = $status;

        return $this;
    }
}
