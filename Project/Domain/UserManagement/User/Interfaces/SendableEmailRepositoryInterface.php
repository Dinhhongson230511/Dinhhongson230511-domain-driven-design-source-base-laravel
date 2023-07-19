<?php

namespace Project\Domain\UserManagement\User\Interfaces;

interface SendableEmailRepositoryInterface
{
    /**
     * @param $email
     * @return bool
     */
    public function verifyEmail($email) :bool;
}
