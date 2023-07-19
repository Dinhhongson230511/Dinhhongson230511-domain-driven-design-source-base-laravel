<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Traits;

use Project\Domain\UserManagement\User\Models\User;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User as UserDAO;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\OauthAccessToken;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserAuthRelationshipTrait
{
    /**
     * Anti-pattern but might work in this case
     *
     * @return User
     */
    public function getDomainEntity()
    {
        if (!$this->user) {
            $this->user = $this->user()->first()->toDomainEntity();
        }
        return $this->user;
    }

    /**
     * @deprecated
     *
     * @return BelongsTo | UserDAO
     */
    public function user()
    {
        return $this->belongsTo(UserDAO::class);
    }

    /**
     * Get user's oauth access token
     *
     * @return HasMany
     */
    public function oauthAccessToken()
    {
        return $this->hasMany(OauthAccessToken::class, 'user_id');
    }
}
