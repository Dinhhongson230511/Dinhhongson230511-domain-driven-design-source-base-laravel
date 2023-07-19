<?php

namespace Project\Domain\NotificationManagement\Email\Events;

use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\UserManagement\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmailToUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     *
     * @var User
     */
    private User $user;

    /**
     * @var Notification
     */
    private Notification $notification;

    /**
     * UpgradePlan constructor.
     * @param User $user
     * @param Notification $notification
     */
    public function __construct(User $user, Notification $notification)
    {
        $this->user = $user;
        $this->setNotification($notification);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification(Notification $notification): void
    {
        $this->notification = $notification;
    }

}
