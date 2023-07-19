<?php


namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Factory;


use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\UserNotificationExclusion;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNotificationExclusionFactory extends Factory
{
    protected $model = UserNotificationExclusion::class;
    public function definition(): array
    {
        return [];
    }
}
