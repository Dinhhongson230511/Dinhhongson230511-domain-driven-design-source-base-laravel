<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao\NotificationEmailMessage;

trait UserRelationshipTrait
{
    /**
     * @return HasMany
     */
    public function notificationEmailMessage(): HasMany
    {
        return $this->hasMany(NotificationEmailMessage::class);
    }

}
