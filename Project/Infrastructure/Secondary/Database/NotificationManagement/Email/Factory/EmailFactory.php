<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Factory;

use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao\NotificationEmailMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    protected $model = NotificationEmailMessage::class;

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
