<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Traits;

use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User;

trait NotificationRelationshipTrait
{
    /**
     * Get the user to which this notification belongs to
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class );
    }

    /**
     * Get the owning notifiable model.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}
