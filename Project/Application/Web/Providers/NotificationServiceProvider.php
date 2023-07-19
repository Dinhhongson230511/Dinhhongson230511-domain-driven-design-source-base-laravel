<?php

namespace Project\Application\Web\Providers;

use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Repository\EloquentNotificationRepository;
use Illuminate\Support\ServiceProvider;
use Project\Domain\NotificationManagement\Email\Interfaces\NotificationEmailMessageRepositoryInterface;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Repository\EloquentNotificationEmailMessageRepository;


class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Repository
        $this->app->bind(NotificationRepositoryInterface::class, EloquentNotificationRepository::class);
        $this->app->bind(NotificationEmailMessageRepositoryInterface::class , EloquentNotificationEmailMessageRepository::class);

    }
}
