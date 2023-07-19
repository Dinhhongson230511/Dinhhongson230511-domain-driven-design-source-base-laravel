<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Factory;

use Project\Domain\UserManagement\User\Models\User;
use Project\Domain\UserManagement\User\Interfaces\UserFactoryInterface;

class UserFactory implements UserFactoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data = []): User
    {
        return new User($data);
    }
}
