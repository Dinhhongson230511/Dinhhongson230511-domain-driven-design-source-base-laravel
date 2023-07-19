<?php

namespace Project\Application\Web\Providers;

use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Repository\UserRepository;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Application
        $this->app->bind(UserCouponApplicationServiceInterface::class, UserCouponApplicationService::class);

        // Repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
