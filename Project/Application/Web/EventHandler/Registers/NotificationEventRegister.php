<?php

namespace Project\Application\User\EventHandler\Registers;


use Project\Application\User\EventHandler\Listeners\Notification\SendPushNotification;
use Project\Domain\NotificationManagement\Email\Events\SendEmailToUser;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
class NotificationEventRegister extends EventServiceProvider
{
    /**
     *
     * @var array
     */
    protected $listen = [
        SendEmailToUser::class => [
            SendPushNotification::class
        ],
    ];
}
