<?php


namespace Project\Domain\UserManagement\User\Enums;

use BenSampo\Enum\Enum;

final class ValidationMessages extends Enum
{
    // TODO update correct japanese word
    const Other = [
        'other' => ['Other']
    ];
    const UserNotFound = [
        'user_not_found' => ['User not found']
    ];
}
