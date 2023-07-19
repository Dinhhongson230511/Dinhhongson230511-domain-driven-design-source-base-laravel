<?php

namespace Project\Domain\UserManagement\User\Enums;

use Project\UserInterface\Enums\StringEnum;

final class UserFilter extends StringEnum
{
    // Add more filters here
    const Id = 'id';
    const Name = 'name';
    const Email = 'email';
    const Gender = 'gender';
    const Status = 'status';
    const MobileNumber = 'mobile_number';
    const CreatedAt = 'created_at';
}
