<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao;

use Project\Infrastructure\Secondary\Database\UserManagement\User\Factory\UserAuthFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Traits\UserAuthUtilityTrait;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Traits\UserAuthRelationshipTrait;

class UserAuth extends Authenticatable
{
    use UserAuthRelationshipTrait, HasApiTokens, UserAuthUtilityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'auth_id',
        'auth_type',
        'access_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    protected $user = null;

    /**
     * Get a new factory instance for the model.
     *
     * @param  mixed  $parameters
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public static function factory(...$parameters)
    {
        return (new UserAuthFactory())
            ->count(is_numeric($parameters[0] ?? null) ? $parameters[0] : null)
            ->state(is_array($parameters[0] ?? null) ? $parameters[0] : ($parameters[1] ?? []));
    }
}
