<?php

namespace Project\Application\User\Providers;

use Project\Application\User\Services\Interfaces\NotificationServiceInterface as NotificationServiceInterfaceProvider;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Application
        $this->app->bind(NotificationServiceInterfaceProvider::class, \Project\Application\User\Services\NotificationService::class);
    }
}
