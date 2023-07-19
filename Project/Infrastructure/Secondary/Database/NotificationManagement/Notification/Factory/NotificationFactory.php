<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Factory;

use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }
}
