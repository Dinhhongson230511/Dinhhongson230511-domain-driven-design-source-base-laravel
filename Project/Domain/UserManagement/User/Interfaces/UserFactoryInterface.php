<?php

namespace Project\Domain\UserManagement\User\Interfaces;

use Project\Domain\UserManagement\User\Models\User;

interface UserFactoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data = []): User;
}
