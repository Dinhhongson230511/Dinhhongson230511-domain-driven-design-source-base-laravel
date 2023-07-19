<?php

namespace Project\Application\Admin\Providers;

use Project\Application\Admin\Services\Interfaces\UserAdminExampleServiceInterface;
use Project\Application\Admin\Services\UserAdminExampleService;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserAdminExampleServiceInterface::class, UserAdminExampleService::class);
    }
}
