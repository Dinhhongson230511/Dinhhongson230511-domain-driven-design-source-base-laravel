<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Factory;


use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAuthFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAuth::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        ];
    }
}
