<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Repository;


use Project\Domain\NotificationManagement\Notification\Enums\NotificationType;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\UserNotificationExclusion as ModelDAO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Project\Domain\NotificationManagement\Notification\Models\UserNotificationExclusion;

class UserNotificationExclusionRepository extends EloquentBaseRepository
{

    /**
     * @param ModelDAO $modelDao
     */
    public function __construct(ModelDAO $modelDao)
    {
        parent::__construct($modelDao);
    }

    /**
     * @param int $id
     * @return UserNotificationExclusion|null
     */
    public function getById(int $id): ?UserNotificationExclusion
    {
        $modelDao = $this->createQuery()->where('id', $id)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param string $key
     * @param int $user
     * @return Builder|Model|object|null
     */
    public function getByKeyAndUser(string $key, int $user)
    {
        $modelDao = $this->createQuery()
            ->where('notification_key', $key)
            ->where('user_id', $user)
            ->first();

        return $modelDao ? $modelDao : null;
    }

    /**
     * @param string $key
     * @param int $user
     * @return UserNotificationExclusion|null
     */
    public function getNotiExclusionByKeyAndUser(string $key, int $user): ?UserNotificationExclusion
    {
        $modelDao = $this->createQuery()
            ->where('notification_key', $key)
            ->where('user_id', $user)
            ->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param UserNotificationExclusion $UserNotificationExclusion
     * @return UserNotificationExclusion
     */
    public function save(UserNotificationExclusion $notificationExclusion): UserNotificationExclusion
    {
        return $this->createModelDAO($notificationExclusion->getId())
            ->saveData($notificationExclusion);
    }
}
