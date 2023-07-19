<?php

namespace Project\Domain\NotificationManagement\Email\Models;

use Project\Domain\Base\BaseDomainModel;
use Project\Domain\NotificationManagement\Email\Enums\EmailStatus;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\UserManagement\User\Models\User;
use Carbon\Carbon;

class NotificationEmailMessage extends BaseDomainModel
{
    private int $userId;
    private string $key;
    private string $title;
    private string $content;
    private int $status;
    private ?int $notificationId;
    private bool $isSmsSent;
    private ?Carbon $readAt;

    private ?User $user;
    private ?Notification $notification;

    public function __construct(
        int $userId,
        string $key,
        string $title,
        string $content,
        int $status = EmailStatus::Processing,
        ?int $notificationId = null,
        bool $isSmsSent = false,
        ?Carbon $readAt = null,
        Carbon $createdAt = null
    ) {
        $this->setUserId($userId);
        $this->setKey($key);
        $this->setTitle($title);
        $this->setContent($content);
        $this->setStatus($status);
        $this->setNotificationId($notificationId);
        $this->setIsSmsSent($isSmsSent);
        $this->setReadAt($readAt);
        $createdAt ? $this->setCreatedAt($createdAt) : $this->setCreatedAt(Carbon::now());
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
     * @return NotificationEmailMessage
     */
    public function setUserId(int $userId): NotificationEmailMessage
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
     * @return NotificationEmailMessage
     */
    public function setNotificationId(?int $notificationId): NotificationEmailMessage
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
     * @return NotificationEmailMessage
     */
    public function setKey(string $key): NotificationEmailMessage
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
     * @return NotificationEmailMessage
     */
    public function setTitle(string $title): NotificationEmailMessage
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
     * @return NotificationEmailMessage
     */
    public function setContent(string $content): NotificationEmailMessage
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
     * @return NotificationEmailMessage
     */
    public function setStatus(int $status): NotificationEmailMessage
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSmsSent(): bool
    {
        return $this->isSmsSent;
    }

    /**
     * @param bool $isSmsSent
     * @return NotificationEmailMessage
     */
    public function setIsSmsSent(bool $isSmsSent): NotificationEmailMessage
    {
        $this->isSmsSent = $isSmsSent;

        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getReadAt(): ?Carbon
    {
        return $this->readAt;
    }

    /**
     * @param Carbon|null $readAt
     * @return NotificationEmailMessage
     */
    public function setReadAt(?Carbon $readAt): NotificationEmailMessage
    {
        $this->readAt = $readAt;

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
     * @return NotificationEmailMessage
     */
    public function setUser(?User $user): NotificationEmailMessage
    {
        if (!in_array($user->getEmail(), config('whitelist_in_dev')['email']) && env('APP_ENV') != 'production') {
            throw new \Exception($user->getEmail() . 'email is not verified!!');
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Notification|null
     */
    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    /**
     * @param Notification|null $notification
     * @return NotificationEmailMessage
     */
    public function setNotification(?Notification $notification): NotificationEmailMessage
    {
        $this->notification = $notification;

        return $this;
    }
}
