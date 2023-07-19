<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Interfaces;

use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Illuminate\Database\Eloquent\Builder;

interface EloquentUserAuthInterface
{
    /**
     * Retrieve user auth
     *
     * @param string $authId
     * @param string $authColumn
     * @return Builder
     */
    public function retrieveUserAuthViaAuthIdQueryBuilder(string $authId, string $authColumn = 'auth_id'): Builder;

    /**
     * Used to create new auth user
     *
     * @param array $authData
     * @return UserAuth
     */
    public function createNewUserAuth(array $authData): UserAuth;

    /**
     * get by user id
     *
     * @param int $userId
     * @return UserAuth
     */
    public function getByUserId(int $userId): UserAuth;

    /**
     * Log when user login
     *
     * @param UserAuth $userAuth
     * @return mixed
     */
    public function logUserLogin(UserAuth $userAuth);

    /**
     * Get base user data ( common for all type of users )
     *
     * @param UserAuth $userAuth
     * @return array
     */
    public function getUserDataAfterAuthentication(UserAuth $userAuth): array;

    /**
     * @param int $userId
     * @return void
     */
    public function deleteUserAuthGetByUserId(int $userId):void;
}
