<?php

namespace Project\Domain\NotificationManagement\Sms\Models;

use Project\Domain\Base\BaseDomainModel;
use Project\Domain\NotificationManagement\Sms\Enums\SmsStatus;
use Project\Domain\UserManagement\User\Models\User;

class NotificationSmsMessage extends BaseDomainModel
{
    private int $userId;
    private string $key;
    private string $title;
    private string $content;
    private int $status;
    private ?int $notificationId;

    private ?User $user;

    public function __construct(
        int $userId,
        string $key,
        string $title,
        string $content,
        int $status = SmsStatus::Processing,
        ?int $notificationId = null
    ) {
        $this->setUserId($userId);
        $this->setKey($key);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setStatus($status);
        $this->setNotificationId($notificationId);
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
     * @return NotificationSmsMessage
     */
    public function setUserId(int $userId): NotificationSmsMessage
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    /**
     * @param int|null $notificationId
     * @return NotificationSmsMessage
     */
    public function setNotificationId(?int $notificationId): NotificationSmsMessage
    {
        $this->notificationId = $notificationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return NotificationSmsMessage
     */
    public function setKey(string $key): NotificationSmsMessage
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return NotificationSmsMessage
     */
    public function setTitle(string $title): NotificationSmsMessage
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return NotificationSmsMessage
     */
    public function setContent(string $content): NotificationSmsMessage
    {
        $this->content = $content;

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
     * @return NotificationSmsMessage
     */
    public function setStatus(int $status): NotificationSmsMessage
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return NotificationSmsMessage
     */
    public function setUser(?User $user): NotificationSmsMessage
    {
        $this->user = $user;

        return $this;
    }
}
